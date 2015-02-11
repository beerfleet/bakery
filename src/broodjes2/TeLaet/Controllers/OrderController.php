<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Exceptions\ElementNotFoundException;
use broodjes2\TeLaet\Exceptions\AlreadyAddedException;
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
    $data = $srv->fetchOrderStartData();
    $breads = $data['breads'];
    $basket = $data['basket'];

    //var_dump($data['basket']);

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
      $bread = $srv->addBreadToBasket($id);
      $this->redirectTo('order_start', 'info', $bread->getName() . " added.");
    } catch (ElementNotFoundException $e) {
      $app = $this->getApp();
      $app->flash('error', $e->getMessage());
      $app->redirectTo('order_start');
    }
  }

  public function emptyBasket() {
    $srv = $this->ord_srv;
    $srv->destroyBasket();
    $this->redirectTo('order_start', 'info', 'Basket was emptied.');
  }

  public function addToppingPage($key) {
    $srv = $this->ord_srv;
    $data = $srv->getAddToppingsData($key);
    //var_dump($data['order_line']->getToppings());
    $this->render('Order/toppings.html.twig', array('order_line' => $data['order_line'], 'toppings' => $data['toppings'], 'basket' => $data['basket'], 'order_line_key' => $key));
  }

  public function removeBread($key) {
    $app = $this->getApp();
    try {
      $srv = $this->ord_srv;
      $srv->removeOrderlineWithKey($key);
      $app->redirectTo('order_start');
    } catch (ElementNotFoundException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('order_start');
    }
  }

  /**
   * Add topping to selected bread in session 'basket'
   * @param type $order_line_key identifies the order line in session
   * @param type $id The is of the bread to be added to the selected order
   */
  public function addToppingToBread($order_line_key, $id) {
    $app = $this->getApp();
    try {
      $srv = $this->ord_srv;
      $topping = $srv->addToppingToBread($order_line_key, $id);
      $app->flash('info', $topping->getName() . " added.");
      $app->redirectTo('order_add_topping_page', array('key' => $order_line_key));
    } catch (ElementNotFoundException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('order_start');
    } catch (AlreadyAddedException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('order_add_topping_page', array('key' => $order_line_key));
    }
  }

  /**
   * Removes topping from given order
   * @param type $ol_key Order line key for 'basket' session array
   * @param type $t_key Topping key for topping in orderline $ol_key
   */
  public function removeTopping($ol_key, $t_key) {
    $app = $this->getApp();
    try {
      $srv = $this->ord_srv;
      $srv->removeToppingFromBread($ol_key, $t_key);
      $app->redirectTo('order_add_topping_page', array('key' => $ol_key));
    } catch (ElementNotFoundException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('order_start');
    }
  }

}
