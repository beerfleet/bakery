<?php

namespace broodjes2\TeLaet\Controllers;

use Doctrine\ORM\EntityManager;
use Slim\Slim;
use broodjes2\TeLaet\Entities\Constants\Entities;

/**
 * Controller abstract controller
 *
 * @author jan van biervliet
 */
abstract class Controller {
  
  /* @var $em EntityManager() */
  private $em;

  /* @var $app  Slim */
  private $app;

  /**
   * 
   * @param EntityManager $em entity manager
   * @param Slim $app Slim instance
   */
  function __construct($em, $app) {
    $this->em = $em;
    $this->app = $app;    
  }
  
  public function getEntityManager() {
    return $this->em;
  }

  public function getApp() {
    return $this->app;
  }
  
  /**
   * Gets Slim $app, current user and session vars for use in Twig 
   * @return 1 dimensional array of global vars
   */
  public function getGlobals() {
    $globals = array(
      'app' => $this->app,  // http://docs.slimframework.com/
      'user' => $this->getActiveUser(), // User
      'session' => $this->getSession(), // session var array
      'path' => $_SERVER['REQUEST_URI'], // current path,      
      'root' => 'http://' . $_SERVER['HTTP_HOST']
    );
    return $globals;
  }
  public function getSession() {
    return $_SESSION;
  
  }
  
  private function queryUserByUserName($username) {    
    $em = $this->getEntityManager();
    $repo = $em->getRepository(Entities::USER);
    $user = $repo->findBy(array('username' => $username));
    return $user;
  }

  public function getActiveUser() {
    if (isset($_SESSION['user']) && $_SESSION['user'] != null ) {
      $user = $this->queryUserByUserName($_SESSION['user']);
      return isset($user[0]) ? $user[0] : null;
    }
    return null;
  }
  
  public function isUserAnonymous() {
    return !$this->isLoggedIn();
  }
  
  public function isUserLoggedIn() {
    return $this->getUser() != null;
  }

  public function isUserAdmin() {
    if (isset($_SESSION['user'])) {
      $user = $this->getActiveUser();
      return $user->isAdmin() == 1 ? true : false;
    }
    return false;
  }
  
  public function setUserLoggedOn($username) {
    $_SESSION['user'] = $username;
  }
  
  public function logoff() {
    unset($_SESSION['user']);
  }
  
}
