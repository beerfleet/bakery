<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\User\UserService;

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
    $em = $this->getEntityManager();
    $app = $this->getApp();
    $user_srv = new UserService($em);
    $postcodes = $user_srv->fetchPostcodes();
    $app->render('User\register.html.twig', array('globals' => $this->getGlobals(), 'postcodes' => $postcodes));
  }
  
}
