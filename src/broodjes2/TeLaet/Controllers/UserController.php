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
  
  private $user_srv;
  
  public function __construct($em, $app) {
    parent::__construct($em, $app);
    $this->user_srv = new UserService($em);
  }
  
  public function register() {    
    $em = $this->getEntityManager();
    $app = $this->getApp();    
    $postcodes = $this->user_srv->fetchPostcodes();
    $app->render('User\register.html.twig', array('globals' => $this->getGlobals(), 'postcodes' => $postcodes));
  }  
  
  public function processRegistration() {
    $app = $this->getApp();
    $srv = $this->user_srv;
    $validated = $srv->validateRegistration($this->getApp());
    if (true === $validated) {
      $srv->processRegistration();
      $app->flash('Please confirm your registration by clicking the link on the email we sent you.');
      $app->redirectTo('main_page');
    } else {
      $errors = $validated;
      $app->flash('errors', $errors);
      $app->redirectTo('user_register');
    }
  }
  
}
