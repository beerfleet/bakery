<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\User\UserService;
use Slim\Slim;

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
    /* @var $app Slim */
    $app = $this->getApp();    
    $postcodes = $this->user_srv->fetchPostcodes();
    $app->render('User\register.html.twig', array('globals' => $this->getGlobals(), 'postcodes' => $postcodes));
  }  
  
  public function processRegistration() {
    /* @var $app Slim */
    $app = $this->getApp();
    $srv = $this->user_srv;
    $validated = $srv->validateRegistration($app);
    if (true === $validated) {
      $srv->processRegistration($app->request()->post());
      $app->flash('info', 'Please confirm your registration by clicking the link on the email we sent you.');
      $app->redirectTo('main_page');
    } else {
      $errors = $validated;
      $app->flash('errors', $errors);
      $app->redirectTo('user_register');
    }
  }
  
  public function verifyRegistration($token) {
    $app = $this->getApp();
    $srv = $this->user_srv;
    $user = $srv->processToken($token);
    if (isset($user)) {
      $app->flash('info', 'Verfication ok. You may now log on.');
      $app->redirectTo('logon');
    } else {
      $app->redirectTo('error_404');
    }
  }
  
}
