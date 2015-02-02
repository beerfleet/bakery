<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;

/**
 * AdminController
 *
 * @author jan van biervliet
 */
class AdminController extends Controller {
  
  public function __construct($em, $app) {
    parent::__construct($em, $app);
  }
  
  public function adminPage() {
    $app = $this->getApp();
    $app->render('Admin\main_admin.html.twig', array('globals' => $this->getGlobals()));
  }
  
}
