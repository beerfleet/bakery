<?php

namespace broodjes2\TeLaet\Service\Validation;

use Valitron\Validator;
use broodjes2\TeLaet\Service\Validation\Validation;

/**
 * Description of EditUserValidation
 *
 * @author jan van biervliet
 */
class EditUserValidation extends Validation {

  public function addRules() {
    $val = $this->getVal();           
    $val->rule('required', 'firstname')->message('First name is required');
    $val->rule('required', 'surname');    
    $val->rule('required', 'address');
  }

//put your code here
}
