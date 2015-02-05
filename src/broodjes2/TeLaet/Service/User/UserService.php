<?php

namespace broodjes2\TeLaet\Service\User;

use Doctrine\ORM\EntityManager;
use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\Repository;
use broodjes2\TeLaet\Service\Validation\RegistrationValidation;
use broodjes2\TeLaet\Service\Validation\PasswordValidation;
use broodjes2\TeLaet\Entities\User;

use Slim\Slim;

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
    /* @var $em EntityManager */
    $em = $this->em;
    $repo = $em->getRepository(Entities::POSTCODE);        
    $postcode = $repo->findOneBy(array('id' => $post['postcode']));
    
    $user = new User();    
    
    $user->setUsername($post['username']);
    $user->setEmail($post['email']);
    $user->setEnabled(0);
    $password = password_hash($post['password'], CRYPT_BLOWFISH);
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
    $message = 
        "Dear Mr " . $user->getSurname() . ".\n" .
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
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    /* @var $user User */
    $user = $repo->findByUsername($app->request->post('username'));
    $password = $app->request->post('password');
    $hash = $user->getPassword();
    if (isset($user) && password_verify($password, $hash)) {
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
    $message = "Dear Mr " . $user->getSurname() . ".\n" .
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
  
  /* @var $app Slim */
  public function processPassword($app) {
    $em = $this->em;
    /* @var $val PasswordValidation */
    $val = new PasswordValidation($app, $em);
    if ($val->validate()) {
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

}
