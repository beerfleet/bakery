<?php

namespace broodjes2\TeLaet\Controllers;

use Slim\Slim;
use Slim\Route;
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

  /* routes */
  public function simplifiedRoutes($routes) {
    $simple = array();
    foreach ($routes as $route) {
      /* @var $route Route */
      $simple[$route->getName()] = array('pattern' => $route->getPattern(), 'methods' => $route->getHttpMethods());      
    }
    return $simple;
  }

  public function showRoutes() {
    $app = $this->getApp();
    $routes = $app->router->getNamedRoutes();
    $simple = $this->simplifiedRoutes($routes);
    $app->render('Test\routes.html.twig', array('globals' => $this->getGlobals(), 'routes' => $simple));
  }

}
