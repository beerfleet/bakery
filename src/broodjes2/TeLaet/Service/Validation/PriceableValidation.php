<?php

namespace broodjes2\TeLaet\Service\Validation;

use broodjes2\TeLaet\Service\Validation\Validation;
use Valitron\Validator;

/**
 * PriceableValidation Validates a priceable item (has name and price attributes)
 *
 * @author jan van biervliet
 */
class PriceableValidation extends Validation {

  public function __construct($app, $em, $repo) {
    
    Validator::addRule('unique_name', function($field, $value, array $params) use ($em, $app, $repo) {
      $name = $app->request->post('name');
      $repo = $em->getRepository($repo);
      $result = $repo->findBy(array('name' => $name));
      return count($result) < 1;
    }, 'already exists');
    
    parent::__construct($app, $em);
  }

  public function addRules() {
    /* @var $val Validator */
    $val = $this->getVal();
    $val->rule('required', 'name')->message('The name field cannot be empty.');
    $val->rule('unique_name', 'name');    
    $val->rule('numeric', 'price');
    $val->rule('min', 'price', 0.10);
    
  }

}
