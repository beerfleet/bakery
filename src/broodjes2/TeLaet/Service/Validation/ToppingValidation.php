<?php

namespace broodjes2\TeLaet\Service\Validation;

use broodjes2\TeLaet\Service\Validation\Validation;
use broodjes2\TeLaet\Entities\Constants\Entities;
use Valitron\Validator;

/**
 * PriceableValidation Validates a priceable item (has name and price attributes)
 *
 * @author jan van biervliet
 */
class ToppingValidation extends Validation {

  public function __construct($app, $em) {
    
    Validator::addRule('unique_name', function($field, $value, array $params) use ($em, $app) {
      $name = $app->request->post('name');
      $repo = $em->getRepository(Entities::TOPPING);
      $result = $repo->findBy(array('name' => $name));
      return count($result) < 1;
    }, 'already exists');
    
    parent::__construct($app, $em);
  }

  public function addRules() {
    /* @var $val Validator */
    $val = $this->getVal();
    $val->rule('required', 'name')->message('The name field cannot be empty');
    $val->rule('unique_name', 'name')->message('The topping already exists');    
    $val->rule('required', 'price')->message('Price is required');
    $val->rule('numeric', 'price')->message('Price must be numeric');
    $val->rule('min', 'price', 0.10)->message('Prioe must be at least 10 cents.');
    
  }

}
