<?php

namespace broodjes2\TeLaet\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use broodjes2\TeLaet\Entities\Topping;

/**
 * Bread entity
 *
 * @author jan van biervliet
 */
class Bread {

  private $id;
  private $name;
  private $price;
  
  private $toppings;
  
  function __construct() {
    $this->topings = new ArrayCollection();
  }

    function getId() {
    return $this->id;
  }

  function getName() {
    return $this->name;
  }

  function getPrice() {
    return $this->price;
  }

  function setId($id) {
    $this->id = $id;
  }

  function setName($name) {
    $this->name = $name;
  }

  function setPrice($price) {
    $this->price = $price;
  }
  
  function addTopping(Topping $topping) {
    $this->toppings[] = $topping;
  }

}
