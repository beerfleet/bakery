<?php

namespace broodjes2\TeLaet\Service\Bread;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Service;
use broodjes2\TeLaet\Service\Validation\BreadValidation;
use broodjes2\TeLaet\Service\Validation\ToppingValidation;
use broodjes2\TeLaet\Entities\Constants\Entities;
use broodjes2\TeLaet\Entities\Bread;
use broodjes2\TeLaet\Entities\Topping;
use Doctrine\ORM\EntityManager;

/**
 * BreadService
 *
 * @author jan van biervliet
 */
class BreadService extends Service {

  public function __construct($em) {
    parent::__construct($em);
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

  public function fetchAllBreads() {
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::BREAD);
    $breads = $repo->findAll();
    return $breads;
  }

  public function addBread($app) {
    $bread_val = new BreadValidation($app, $this->getEntityManager());
    if ($bread_val->validate()) {
      $bread = new Bread();
      $bread->setName($app->request->post('name'));
      $bread->setPrice($app->request->post('price') * 100);
      $this->store($bread);
      return false;
    } else {
      return $bread_val->getErrors();
    }
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

  public function removeBreadById($id) {
    /* @var $em EntityManager */
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::BREAD);
    $bread = $repo->find($id);
    if ($bread != null) {
      $em->remove($bread);
      $em->flush();
      return $bread;
    } else {
      return null;
    }
  }

}
