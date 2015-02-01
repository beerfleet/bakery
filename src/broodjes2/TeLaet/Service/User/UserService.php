<?php

namespace broodjes2\TeLaet\Service\User;

use Doctrine\ORM\EntityManager;
use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\Repository;
use broodjes2\TeLaet\Service\Validation\RegistrationValidation;

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
  
  public function processRegistration() {
    
  }
 
}
