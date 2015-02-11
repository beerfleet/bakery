<?php

namespace broodjes2\TeLaet\Entities;

/**
 * Order contains one or more breads with or without spread(s) / topping(s)
 *
 * @author jan van biervliet
 */
class Order {

  private $id;
  private $order_date;
  private $user;
  private $order_lines;
  private $picked_up;

  function getId() {
    return $this->id;
  }

  function getOrder_date() {
    return $this->order_date;
  }

  function getUser() {
    return $this->user;
  }

  function getOrder_lines() {
    return $this->order_lines;
  }

  function setId($id) {
    $this->id = $id;
  }

  function setOrder_date() {
    $this->order_date = new \DateTime();
  }

  function setUser($user) {
    $this->user = $user;
  }

  function setOrder_lines($order_lines) {
    $this->order_lines = $order_lines;
  }  
  
  function getPicked_up() {
    return $this->picked_up;
  }

  function setPicked_up($picked_up) {
    $this->picked_up = $picked_up;
  }

}
