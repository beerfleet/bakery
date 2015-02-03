<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Service\Admin\AdminService;
use broodjes2\TeLaet\Service\Bread\BreadService;
use Slim\Slim;

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
    if ($this->isUserAdmin()) {
      $app = $this->getApp();
      $app->render('Admin\main_admin.html.twig', array('globals' => $this->getGlobals()));
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  /* breads */

  public function addBreadPage() {
    $app = $this->getApp();
    if ($this->isUserAdmin()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $breads = $bread_srv->fetchAllBreads();
      $this->getApp()->render('Admin/breads.html.twig', array('globals' => $this->getGlobals(), 'breads' => $breads));
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function addBreadProcess() {
    $bread_srv = new BreadService($this->getEntityManager());
    $errors = $bread_srv->addBread($this->getApp());
    $app = $this->getApp();
    if (false === $errors) {
      $app->flash('info', 'Bread added');
    } else {
      $app->flash('errors', $errors);
    }
    $app->redirectTo('admin_manage_breads');
  }

  /* users */

  public function listAllUsers() {
    $app = $this->getApp();
    $app->render('Admin/user_list.html.twig', array('globals' => $this->getGlobals()));
  }

  //users
}
