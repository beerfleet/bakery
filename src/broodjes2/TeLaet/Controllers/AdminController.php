<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Admin\AdminService;
use broodjes2\TeLaet\Service\Bread\BreadService;
use Slim\Slim;

/**
 * AdminController
 *
 * @author jan van biervliet
 */
class AdminController extends Controller {
  
  private $admin_srv;
  
  public function __construct($em, $app) {
    parent::__construct($em, $app);
    $this->admin_srv = new AdminService($em);
  }
  
  public function adminPage() {
    $app = $this->getApp();
    $srv = $this->admin_srv;
    $vars = $srv->provideAllAdminData();
    $app->render('Admin\main_admin.html.twig', array('globals' => $this->getGlobals(), 'vars' => $vars));
  }
  
  public function ajax_add_bread() {    
    $bread_srv = new BreadService($this->getEntityManager());
    $bread_srv->addBread($this->getApp());
  }
  
  public function ajax_get_bread($name) {
    /* @var $app Slim */
    $app = $this->getApp();
    if ($app->request()->isAjax()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $bread = $bread_srv->findBreadByName($name);
      if (null != $bread) {
        $app->response()->setBody(json_encode($bread));
      } 
      
    } else {
      $app->redirectTo('error_404');
    }
  }
  
  public function ajax_add_topping() {    
    $bread_srv = new BreadService($this->getEntityManager());
    $bread_srv->addTopping($this->getApp());
  }
    
  public function ajax_get_topping($name) {
    /* @var $app Slim */
    $app = $this->getApp();
    if ($app->request()->isAjax()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $topping = $bread_srv->findToppingByName($name);
      if (null != $topping) {
        $app->response()->setBody(json_encode($topping));
      } 
      
    } else {
      $app->redirectTo('error_404');
    }
  }
}
