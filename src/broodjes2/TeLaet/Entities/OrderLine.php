<?php

namespace broodjes2\TeLaet\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use broodjes2\TeLaet\Entities\Topping;

/**
 * Description of OrderLine
 *
 * @author jan van biervliet
 */
class OrderLine {

  private $id;
  private $order;
  private $bread;
  private $toppings;
  
  public function __construct() {
    $this->toppings = new ArrayCollection();
  }

  function getId() {
    return $this->id;
  }

  function getOrder() {
    return $this->order;
  }

  function getBread() {
    return $this->bread;
  }

  function getToppings() {
    return $this->toppings;
  }

  function setId($id) {
    $this->id = $id;
  }

  function setOrder($order) {
    $this->order = $order;
  }

  function setBread($bread) {
    $this->bread = $bread;
  }

  function setToppings($toppings) {
    $this->toppings = $toppings;
  }
  
  function addTopping(Topping $topping) {
    $this->toppings[] = $topping;
  }

}
