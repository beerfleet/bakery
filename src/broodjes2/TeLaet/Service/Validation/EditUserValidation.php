<?php

namespace broodjes2\TeLaet\Service\Validation;

use Valitron\Validator;
use broodjes2\TeLaet\Entities\Constants\Entities;

use broodjes2\TeLaet\Service\Validation\Validation;

/**
 * Description of EditUserValidation
 *
 * @author jan van biervliet
 */
class EditUserValidation extends Validation {
  
  public function __construct($app, $em) {
    
    Validator::addRule('unique_email', function($field, $value, array $params) use ($em, $app) {
      $id= $app->request->post('id');
      $email = $app->request->post('email');
      $repo = $em->getRepository(Entities::USER);
      return $repo->isEmailTaken($id, $email);
    }, 'already exists');
    
    parent::__construct($app, $em);
  }

  public function addRules() {
    $val = $this->getVal();           
    $val->rule('required', 'firstname')->message('First name is required');
    $val->rule('required', 'surname');    
    $val->rule('required', 'address');
    $val->rule('unique_email', 'email')->message("Email is already taken.");
  }

//put your code here
}
