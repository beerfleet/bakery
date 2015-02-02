<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Admin\AdminService;

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
  
}
