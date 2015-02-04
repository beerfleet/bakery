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
use Doctrine\ORM\EntityRepository;

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
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::BREAD);
    $bread = $repo->findBy(array('name' => $name));
    return count($bread) == 0 ? null : $bread[0];
  }

  public function findBread($id) {
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::BREAD);
    $bread = $repo->find($id);
    return $bread;
  }
  
  public function findTopping($id) {
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::TOPPING);
    $topping = $repo->find($id);
    return $topping;
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
    /* @var $repo EntityRepository */    
    $breads = $repo->findBy(array(), array('name' => 'ASC'));
    return $breads;
  }

  public function addBread($app) {
    $bread_val = new BreadValidation($app, $this->getEntityManager());
    if ($bread_val->validate()) {
      $bread = new Bread();
      $name_capped = ucwords($app->request->post('name'));
      $bread->setName($name_capped);
      $bread->setPrice($app->request->post('price') * 100);
      $this->store($bread);
      return false;
    } else {
      return $bread_val->getErrors();
    }
  }

  public function editBread($app) {
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::BREAD);
    $val = new BreadValidation($app, $em);
    if ($val->validate()) {
      /* @var $bread Bread */
      $bread = $repo->find($app->request->post('id'));
      $name = $app->request->post('name');
      $price= $app->request->post('price');
      $bread->setName( ucwords($name));
      $bread->setPrice($price * 100);
      $em->merge($bread);
      $em->flush();
      return false;
    } else {
      return $val->getErrors();
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

  public function fetchAllToppings() {
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::TOPPING);
    $toppings = $repo->findBy(array(), array('name' => 'ASC'));
    return $toppings;
  }

  public function addTopping($app) {
    $topping_val = new ToppingValidation($app, $this->getEntityManager());
    if ($topping_val->validate()) {
      $topping = new Topping();
      $name_capped = ucwords($app->request->post('name'));
      $topping->setName($name_capped);
      $topping->setPrice($app->request->post('price') * 100);
      $this->store($topping);
      return false;
    } else {
      return $topping_val->getErrors();
    }
  }

  public function removeToppingById($id) {
    /* @var $em EntityManager */
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::TOPPING);
    $toppping = $repo->find($id);
    if ($toppping != null) {
      $em->remove($toppping);
      $em->flush();
      return $toppping;
    } else {
      return null;
    }
  }
  
  public function editTopping($app) {
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::TOPPING);
    $val = new ToppingValidation($app, $em);
    if ($val->validate()) {
      /* @var $bread Bread */
      $topping = $repo->find($app->request->post('id'));
      $name = $app->request->post('name');
      $price= $app->request->post('price');
      $topping->setName( ucwords($name));
      $topping->setPrice($price * 100);
      $em->merge($topping);
      $em->flush();
      return false;
    } else {
      return $val->getErrors();
    }
  }

}
