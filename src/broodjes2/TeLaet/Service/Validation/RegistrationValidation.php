<?php

namespace broodjes2\TeLaet\Service\Validation;

use Valitron\Validator;
use broodjes2\TeLaet\Entities\Constants\Entities;

class RegistrationValidation extends Validation {

  public function __construct($app, $em) {
    // custom rule unique email
    Validator::addRule('unique_email', function($field, $value, array $params) use ($em, $app) {
      $email = $app->request->post('email');
      $repo = $em->getRepository(Entities::USER);
      $result = $repo->findBy(array('email' => $email));
      return count($result) < 1;
    }, 'already exists');
    
    // custom rule unique username
    Validator::addRule('unique_username', function($field, $value, array $params) use ($em, $app) {
      $username = $app->request->post('username');
      $repo = $em->getRepository(Entities::USER);
      $result = $repo->findBy(array('username' => $username));
      return count($result) < 1;      
    }, 'already exists');
    
    parent::__construct($app, $em);    
  }
  
  public function addRules() {
    $val = $this->getVal();
    $val->rule('required', 'username');
    $val->rule('unique_username', 'username');
    $val->rule('email', 'email');
    $val->rule('unique_email', 'email');    
    $val->rule('required', 'email');
    $val->rule('required', 'password');    
    $val->rule('required', 'firstname')->message('First name is required');
    $val->rule('required', 'surname');
    $val->rule('equals', 'password', 'password_bis')->message('Passwords do not match.');    
    $val->rule('required', 'address');
  }
}
