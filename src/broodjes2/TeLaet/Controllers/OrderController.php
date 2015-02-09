<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Order\OrderService;

/**
 * OrderController 
 *
 * @author jan van biervliet
 */
class OrderController extends Controller {
  
  private $ord_srv;

  public function __construct($em, $app) {
    parent::__construct($em, $app);
    $this->ord_srv = new OrderService($em);
  }
  
  public function orderStart() {
    /* @var $srv OrderService */
    $srv = $this->ord_srv;
    $breads = $srv->fetchBreadsOrdered();
    $this->getApp()->render('Order/order_page.html.twig', array('breads' => $breads));
  }

}
