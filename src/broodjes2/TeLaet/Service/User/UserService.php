<?php

namespace broodjes2\TeLaet\Service\User;

use Doctrine\ORM\EntityManager;
use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\Repository;
use broodjes2\TeLaet\Service\Validation\RegistrationValidation;
use broodjes2\TeLaet\Entities\User;

use Slim\Slim;

/**
 * UserService
 *
 * @author jan van biervliet
 */
class UserService {

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
    $subject = 'First Time Logon TOken';
    $message = 
        "Dear Mr " . $user->getSurname() . ".\n" .
      "Click http://" . $_SERVER['HTTP_HOST'] . "/verify/" . $user->getPasswordToken();
    $header = "From: noreply@janvanbiervliet.com";
    $this->mailUser($user, $subject, $message, $header);
  }
  
  public function mailUser(User $user, $subject, $message, $header) {
    mail($user->getEmail(), $subject, $message, $header);
  }
  
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
 
}
