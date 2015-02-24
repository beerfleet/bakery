<?php

namespace broodjes2\TeLaet\Service\User;

use broodjes2\TeLaet\Service\BCrypt;
use broodjes2\TeLaet\Service\Admin\AdminService;
use Doctrine\ORM\EntityManager;
use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\Repository;
use broodjes2\TeLaet\Service\Validation\RegistrationValidation;
use broodjes2\TeLaet\Service\Validation\PasswordValidation;
use broodjes2\TeLaet\Service\Validation\EditUserValidation;
use broodjes2\TeLaet\Entities\User;
use Slim\Slim;
use broodjes2\TeLaet\Exceptions\AccessDeniedException;

/**
 * UserService
 *
 * @author jan van biervliet
 */
class UserService {
  /* @var $em EntityManager */

  private $em;

  function __construct($em) {
    $this->em = $em;
  }

  public function fetchPostcodes() {
    /* @var $em EntityManager */
    $em = $this->em;
    /* @var $repo Repository */
    $repo = $em->getRepository(Entities::POSTCODE);
    $postcodes = $repo->findAllOrderPostcodes();
    return $postcodes;
  }

  public function validateRegistration($app) {
    $val = new RegistrationValidation($app, $this->em);
    if ($val->validate()) {
      return true;
    }
    return $val->getErrors();
  }

  public function processRegistration($post) {
    $user = $this->storeUser($post);
    $this->mailLogonToken($user);
  }

  /**
   * Stores user to DB. 
   * It is assumed that the input has been validated
   * @param array() $post
   */
  public function storeUser($post) {
    $crypt = new BCrypt();
    /* @var $em EntityManager */
    $em = $this->em;
    $repo = $em->getRepository(Entities::POSTCODE);
    $postcode = $repo->findOneBy(array('id' => $post['postcode']));

    $user = new User();

    $user->setUsername($post['username']);
    $user->setEmail($post['email']);
    $user->setEnabled(0);
    $password = $crypt->password_hash($post['password'], CRYPT_BLOWFISH);
    $user->setPassword($password);
    $user->setFirstName($post['firstname']);
    $user->setSurname($post['surname']);
    $user->setPostcode($postcode);
    $user->setAddress($post['address']);
    $user->setPasswordToken();

    $em->persist($user);
    $em->flush();

    return $user;
  }

  public function mailLogonToken(User $user) {
    $subject = 'First Time Logon Token';
    $message = "Dear Mr " . $user->getSurname() . ".\n" .
        "Click http://" . $_SERVER['HTTP_HOST'] . "/verify/" . $user->getPasswordToken();
    $header = "From: noreply@janvanbiervliet.com";
    $this->mailUser($user, $subject, $message, $header);
  }

  public function mailUser(User $user, $subject, $message, $header) {
    mail($user->getEmail(), $subject, $message, $header);
  }

  /**
   * To be used only for registration, because the user is then enabled
   * @param type $token
   * @return type
   */
  public function processToken($token) {
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    /* @var $user User */
    $user = $repo->findUserByToken($token);
    if (isset($user)) {
      $user->resetPasswordToken();
      $user->setEnabled(1);
      $em->merge($user);
      $em->flush();
      return $user;
    }
    return null;
  }

  public function validateCredentials($app) {
    $crypt = new BCrypt();
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    /* @var $user User */
    $user = $repo->findByUsername($app->request->post('username'));
    if (!isset($user)){
      return;
    }
    $password = $app->request->post('password');
    $hash = $user->getPassword();
    if (isset($user) && $crypt->password_verify($password, $hash)) {
      return $user;
    }
    return null;
  }

  /* password reset */
  public function mailResetRequest($app) {
    $email = $app->request->post('email');
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    $user = $repo->findOneBy(array('email' => $email));
    if (isset($user)) {
      /* @var $user User */
      $user->setResetToken();
      $em->merge($user);
      $em->flush();
      $this->mailResetToken($user);
    }
  }

  public function mailResetToken(User $user) {
    $subject = 'Reset Token Requested';
    $message = "Dear Mr / Mrs " . $user->getSurname() . ".\n" .
        "Click http://" . $_SERVER['HTTP_HOST'] . "/reset/" . $user->getResetToken();
    $header = "From: noreply@janvanbiervliet.com";
    $this->mailUser($user, $subject, $message, $header);
  }

  public function verifyToken($token) {
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    /* @var $user User */
    $user = $repo->findUserByResetToken($token);
    if (isset($user)) {
      $user->resetResetToken();
      $em->merge($user);
      $em->flush();
      return $user;
    }
    return null;
  }

  /**
   * set new user password if validated
   * @param Slim $app
   * @return boolean|array() false or array of error messages
   */
  public function processPassword($app) {
    $em = $this->em;
    /* @var $val PasswordValidation */
    $val = new PasswordValidation($app, $em);
    if ($val->validate()) {
      /* @var $app Slim */
      $user_id = $app->request->post('user_id');
      $repo = $em->getRepository(Entities::USER);
      $password = $app->request->post('password');
      $user = $repo->find($user_id);
      $hash = password_hash($password, CRYPT_BLOWFISH);
      $user->setPassword($hash);
      $em->merge($user);
      $em->flush();
      return true;
    }
    return $val->getErrors();
  }

  public function fetchAllUsers() {
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    $users = $repo->findAll();
    return $users;
  }
  
  public function fetchAllUsersSecure() {
    $admin_srv = new AdminService($this->em);
    if (!$admin_srv->isUserAdmin()) {
      throw new AccessDeniedException();
    }
    return $this->fetchAllUsers();
  }

  public function fetchUserById($id) {
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    $user = $repo->find($id);
    return $user;
  }
  
  public function updateUser($app) {
    $id = $app->request->post('id');
    $firstname = $app->request->post('firstname');
    $surname = $app->request->post('surname');
    $address = $app->request->post('address');
    $postcode_id = $app->request->post('postcode');
    $em = $this->em;
    $repo = $em->getRepository(Entities::POSTCODE);
    $postcode = $repo->findOneBy(array('id' => $postcode_id));
    $user = $this->fetchUserById($id);
    $user->setFirstName($firstname);
    $user->setSurname($surname);
    $user->setAddress($address);            
    $user->setPostcode($postcode);
    $em->merge($user);
    $em->flush();
  }
  
  public function processEditUser($app) {
    $val = new EditUserValidation($app, $this->em);
    if ($val->validate()) {
      $this->updateUser($app);
      return null;
    } else {
      return $val->getErrors();
    }
  }

}
