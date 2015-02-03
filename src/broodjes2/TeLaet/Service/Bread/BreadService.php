<?php

namespace broodjes2\TeLaet\Service\Bread;

use broodjes2\TeLaet\Service\Validation\PriceableValidation;
use broodjes2\TeLaet\Service\Validation\ToppingValidation;
use broodjes2\TeLaet\Entities\Constants\Entities;
use broodjes2\TeLaet\Entities\Bread;
use broodjes2\TeLaet\Entities\Topping;

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

  public function findBreadByName($name) {
    $em = $this->em;
    $repo = $em->getRepository(Entities::BREAD);
    $bread = $repo->findBy(array('name' => $name));
    return count($bread) == 0 ? null : $bread[0];
  }

  public function validateBread($app) {
    $val = new PriceableValidation($app, $this->em, Entities::BREAD);
    if ($val->validate()) {
      return true;
    }
    return $val->getErrors();
  }

  public function findToppingByName($name) {
    $em = $this->em;
    $repo = $em->getRepository(Entities::TOPPING);
    $topping = $repo->findBy(array('name' => $name));
    return count($topping) == 0 ? null : $topping[0];
  }

  public function validateTopping($app) {
    $val = new ToppingValidation($app, $this->em, Entities::TOPPING);
    if ($val->validate()) {
      return true;
    }
    return $val->getErrors();
  }
  
  public function fetchAllBreads() {
    $em = $this->em;
    $repo = $em->getRepository(Entities::BREAD);
    $breads = $repo->findAll();
    return $breads;
  }    

}
