<?php

namespace broodjes2\TeLaet\Controllers;

use Slim\Slim;
use broodjes2\TeLaet\Controllers\Controller;

/**
 * HomepageController
 *
 * @author jan van biervliet
 */
class HomepageController extends Controller {
  
  public function __construct($em, $app) {
    parent::__construct($em, $app);
  }
  
  public function homepage() {
    /* @var $app Slim */
    $app = $this->getApp();
    $app->render('homepage.html.twig', array('globals' => $this->getGlobals()));
    
  }
  
  public function notFound() {
   $app = $this->getApp();
   $app->render('Error\error_404.html.twig', array());
  }

}
