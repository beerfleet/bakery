<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Admin\AdminService;
use broodjes2\TeLaet\Service\Bread\BreadService;

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
  
}
