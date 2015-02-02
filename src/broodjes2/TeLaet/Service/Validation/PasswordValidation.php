<?php

namespace broodjes2\TeLaet\Service\Validation;

use broodjes2\TeLaet\Service\Validation\Validation;
use Valitron\Validator;

/**
 * PasswordValidation
 *
 * @author jan van biervliet
 */
class PasswordValidation extends Validation {

  public function __construct($app, $em) {
    parent::__construct($app, $em);
  }
  
  public function addRules() {    
    /* @var $val Validator */
    $val = $this->getVal();
    $val->rule('lengthBetween', 'password', '3', '12');
    $val->rule('required', 'password');
    $val->rule('equals', 'password', 'password_bis')->message('Passwords do not match.');
  }

}
