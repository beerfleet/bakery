<?php

namespace broodjes2\TeLaet\Service\Order;

use broodjes2\TeLaet\Service\Service;
use broodjes2\TeLaet\Service\Bread\BreadService;
use broodjes2\TeLaet\Entities\Constants\Entities;
use broodjes2\TeLaet\Exceptions\ElementNotFoundException;
use broodjes2\TeLaet\Entities\OrderLine;
use broodjes2\TeLaet\Entities\Bread;

/**
 * Description of OrderService
 *
 * @author jan van biervliet
 */
class OrderService extends Service {

  public function __construct($em) {
    parent::__construct($em);
  }
  
  public function fetchBreadsOrdered() {
    $repo = $this->getRepo(Entities::BREAD);
    return $repo->findBy(array(), array('name' => 'ASC'));
  }
  
  public function fetchOrderStartData() {
    $data = array();
    $data['breads'] = $this->fetchBreadsOrdered();
    $data['basket'] = $this->getBasket();
    return $data;
  }
  
  /* basket control */
  public function createBasket() {
    if (!isset($_SESSION['basket'])) {
      $_SESSION['basket'] = array();
    }
  }
  
  public function destroyBasket() {
    if (isset($_SESSION['basket'])) {
      unset($_SESSION['basket']);
    }
  }
  
  public function getBasket() {
    if (!isset($_SESSION['basket'])) {
      return array();
    }
    return $_SESSION['basket'];
  }
  
  public function addBread($bread) {    
    $line = new OrderLine();
    $line->setBread($bread);
        
    $_SESSION['basket'][] = $line;
  }
  
  public function addBreadToBasket($id) {
    $repo = $this->getRepo(Entities::BREAD);
    $bread = $repo->find($id);
    if (!isset($bread)) {
      throw new ElementNotFoundException();
    }
    $this->addBread($bread);
    return $_SESSION['basket'];
  }
  

  public function getOrderLineByKey($key) {
    return $_SESSION['basket'][$key];    
  }
  
  public function getAddToppingsData($key) {
    $br_srv = new BreadService($this->getEntityManager());
    $toppings = $br_srv->fetchAllToppings();
    $line = $this->getOrderLineByKey($key);
    $data['order_line'] = $line;
    $data['toppings'] = $toppings;
    $data['basket'] = $this->getBasket();
    return $data;
  }

}
