<?php

namespace broodjes2\TeLaet\Service\Bread;

use broodjes2\TeLaet\Service\Validation\PriceableValidation;
use broodjes2\TeLaet\Entities\Constants\Entities;
use broodjes2\TeLaet\Entities\Bread;

/**
 * BreadService
 *
 * @author jan van biervliet
 */
class BreadService {
  
  private $em;
  
  public function __construct($em) {
    $this->em = $em;
  }
  
  public function addBread($app) {
    $validated = $this->validateBread($app);
    if (true === $validated) {      
      $em = $this->em;
      $bread = new Bread();
      $name = ucwords($app->request->post('name'));            
      $bread->setName($name);
      $bread->setPrice($app->request->post('price') * 100);
      $em->persist($bread);
      $em->flush();
    }
  }
  
  public function validateBread($app) {
    $val = new PriceableValidation($app, $this->em, Entities::BREAD);
    if ($val->validate()) {
      return true;
    }
    return $val->getErrors();
  }
}
