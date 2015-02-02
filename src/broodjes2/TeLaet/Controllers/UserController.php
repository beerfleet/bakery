<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\User\UserService;
use Slim\Slim;
use broodjes2\TeLaet\Entities\User;

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
  
  /* registration */
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
  
  /* logon */
  public function logonPage() {
    $globals = $this->getGlobals();
    $app = $this->getApp();
    $app->render('User\logon.html.twig', array('globals' => $globals));
  }
  
  public function verifyCredentials() {
    $srv = $this->user_srv;
    $app = $this->getApp();
    /* @var $user User */
    $user = $srv->validateCredentials($app);
    if (isset($user) && $user->isEnabled()) {
      $this->setUserLoggedOn($user->getUsername());
      $app->redirectTo('main_page');
    } else {
      $app->flash('error', 'Access denied.');
      $app->redirectTo('logon');
    }
  }
  
  public function logoffProcess() {
    $this->logoff();
    $this->getApp()->redirectTo('main_page');
  }
  
  /* password reset */
  public function passwordResetRequest() {
    $app = $this->getApp();
    $app->render('User\password_reset_request.html.twig', array());
  }
  
  public function passwordResetProcess() {
    $app = $this->getApp();        
    $srv = $this->user_srv;
    $srv->mailResetRequest($app);
    $app->flash('info', 'An email has been sent if a valid email address was provided.');
    $app->redirectTo('main_page');
  }
  
  
}
