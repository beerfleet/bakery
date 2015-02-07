<?php

namespace broodjes2\TeLaet\Entities;

/**
 * Topping entity
 *
 * @author jan van biervliet
 */
class Topping {

  private $id;
  private $name;
  private $price;
  private $image;

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

  function getImage() {
    return $this->image;
  }

  function setImage($image) {
    $this->image = $image;
  }

}
