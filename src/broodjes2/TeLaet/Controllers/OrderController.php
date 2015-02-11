<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Order\OrderService;
use broodjes2\TeLaet\Exceptions\ElementNotFoundException;

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
    $data = $srv->fetchOrderStartData();
    $breads = $data['breads'];
    $basket = $data['basket'];
    var_dump($basket);
    $this->getApp()->render('Order/order_page.html.twig', array('breads' => $breads, 'basket' => $basket));
  }

  /**
   * Add bread to order basket
   * @param integer $id
   */
  public function addBread($id) {
    try {
      /* @var $srv OrderService */
      $srv = $this->ord_srv;
      $basket = $srv->addBreadToBasket($id);
      
      var_dump($basket);     
      $this->redirectTo('order_start', 'info', 'Bread added.');
    } catch (ElementNotFoundException $e) {
      $this->redirectTo('order_start', null, 'error', $e->getMessage());
    }
  }
  
  public function emptyBasket() {
    $srv = $this->ord_srv;
    $srv->destroyBasket();
    $this->redirectTo('order_start', 'info', 'Basket wxas emptied.');
  }
  
  public function addToppingPage($key) {
    $srv = $this->ord_srv;
    $data = $srv->getAddToppingsData($key);
    var_dump($data['order_line']);
    $this->render('Order/toppings.html.twig', array('order_line' => $data['order_line'], 'toppings' => $data['toppings'], 'basket' => $data['basket']));
  }

}
