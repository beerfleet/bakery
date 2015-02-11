<?php

namespace broodjes2\TeLaet\Service\Order;

use broodjes2\TeLaet\Exceptions\ElementNotFoundException;
use broodjes2\TeLaet\Exceptions\AlreadyAddedException;
use broodjes2\TeLaet\Service\Service;
use broodjes2\TeLaet\Service\Bread\BreadService;
use broodjes2\TeLaet\Entities\Constants\Entities;
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
      throw new ElementNotFoundException($id);
    }
    $this->addBread($bread);
    return $bread;
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
  
  public function removeOrderlineWithKey($key) {
    if (!isset($_SESSION['basket'][$key])) {
      throw new ElementNotFoundException($key);
    }
    unset($_SESSION['basket'][$key]);
  }
  
  public function addToppingToBread($line_key, $id) {
    /* @var $line OrderLine */
    if (!isset($_SESSION['basket'][$line_key])) {
      throw new ElementNotFoundException("order line $line_key");
    }
    $br_srv = new BreadService($this->getEntityManager());
    $topping = $br_srv->findTopping($id);
    if (!isset($topping)) {
      throw new ElementNotFoundException("Topping with $id");
    }
    $line = $_SESSION['basket'][$line_key];
    if (in_array($topping, $line->getToppings()->toArray())) {
      throw new AlreadyAddedException($topping->getName() . " already added.");
    }
    $line->addTopping($topping);
    return $topping;
  }
  
  public function removeToppingFromBread($ol_key, $t_key) {
    if (!isset($_SESSION['basket'][$ol_key])) {
      throw new ElementNotFoundException("order line $ol_key");
    }
    $line = $_SESSION['basket'][$ol_key];
    $toppings = $line->getToppings();
    if (!isset($toppings[$t_key])) {
      throw new ElementNotFoundException("topping index $t_key");
    }
    
    /* @var $line OrderLine */
    unset($toppings[$t_key]);
  }

}
