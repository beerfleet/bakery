<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;

/**
 * Description of UserController
 *
 * @author jan van biervliet
 */
class UserController extends Controller {
  
  public function __construct($em, $app) {
    parent::__construct($em, $app);
  }
  
  public function register() {
    $app = $this->getApp();
    $app->render('User\register.html.twig', array('globals' => $this->getGlobals()));
  }
  
}
