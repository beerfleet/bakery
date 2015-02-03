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
  
  public function breadCount() {
    $em = $this->em;
    $repo = $em->getRepository(Entities::BREAD);
    $breads = $repo->findAll();
    return count($breads);
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
      
      $bread_count = $this->breadCount();      
      $json_bread = array('id' => $bread->getId(), 'name' => $bread->getName(), 'price' => $bread->getPrice(), 'count' => $bread_count);
      $app->response->body(json_encode($json_bread));
    } else {
      $encode = array('error' => 1, 'messages' => $validated);
      $app->response->body(json_encode($encode));
    }
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
  
  public function addTopping($app) {
    $validated = $this->validateTopping($app);
    if (true === $validated) {
      $em = $this->em;
      $topping = new Topping();
      $name = ucwords($app->request->post('name'));            
      $topping->setName($name);
      $topping->setPrice($app->request->post('price') * 100);
      $em->persist($topping);
      $em->flush();
      
      $topping_count = $this->toppingCount();      
      $json_topping = array('id' => $topping->getId(), 'name' => $topping->getName(), 'price' => $topping->getPrice(), 'count' => $topping_count);
      $app->response->body(json_encode($json_topping));
    } else {
      $encode = array('error' => 1, 'messages' => $validated);
      $app->response->body(json_encode($encode));
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
  
  public function toppingCount() {
    $em = $this->em;
    $repo = $em->getRepository(Entities::TOPPING);
    $toppings= $repo->findAll();
    return count($toppings);
  }
}
