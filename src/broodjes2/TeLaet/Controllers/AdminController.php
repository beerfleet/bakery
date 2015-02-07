<?php

namespace broodjes2\TeLaet\Controllers;

use broodjes2\TeLaet\Controllers\Controller;
use broodjes2\TeLaet\Entities\Constants\Files;
use broodjes2\TeLaet\Service\Admin\AdminService;
use broodjes2\TeLaet\Service\Bread\BreadService;
use broodjes2\TeLaet\Service\User\UserService;
use broodjes2\TeLaet\Exceptions\AccessDeniedException;
use broodjes2\TeLaet\Exceptions\ImageException;
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
      $app->render('Admin\main_admin.html.twig');
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
      $this->getApp()->render('Admin/breads.html.twig', array('breads' => $breads));
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
      $app->flash('info', $app->request->post('name') . ' added');
    } else {
      $app->flash('errors', $errors);
    }
    $app->redirectTo('admin_manage_breads');
  }

  public function removeBread($id) {
    $app = $this->getApp();
    if ($this->isUserAdmin()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $bread = $bread_srv->removeBreadById($id);
      if (isset($bread)) {
        $app->flash('info', 'Removed ' . $bread->getName());
        $app->redirectTo('admin_manage_breads');
      } else {
        $app->flash('error', 'Invalid operation.');
        $app->redirectTo('admin_manage_breads');
      }
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function editBread($id) {
    $app = $this->getApp();
    if ($this->isUserAdmin()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $bread = $bread_srv->findBread($id);
      $admin_srv = new AdminService($this->getEntityManager());
      $files = $admin_srv->getFilesInDir(Files::BREAD_IMG_DIR);
      if (isset($bread)) {
        $app->render('Admin/edit_bread.html.twig', array('bread' => $bread, 'files' => $files));
      } else {
        $app->flash('error', 'Invalid operation.');
        $app->redirectTo('admin_manage_breads');
      }
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function editBreadProcess() {
    $app = $this->getApp();
    $bread_srv = new BreadService($this->getEntityManager());
    $errors = $bread_srv->editBread($app);
    if (false === $errors) {
      $app->flash('info', 'Bread updated');
      $app->redirectTo('admin_manage_breads');
    } else {
      $app->flash('errors', $errors);
      /* @var $app Slim */
      $app->redirectTo('admin_bread_edit', array('id' => $app->request->post('id')));
    }
  }

  // breads

  /* toppings */
  public function addToppingsPage() {
    $app = $this->getApp();
    if ($this->isUserAdmin()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $toppings = $bread_srv->fetchAllToppings();
      $this->getApp()->render('Admin/toppings.html.twig', array('toppings' => $toppings));
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function addToppingProcess() {
    $bread_srv = new BreadService($this->getEntityManager());
    $errors = $bread_srv->addTopping($this->getApp());
    $app = $this->getApp();
    if (false === $errors) {
      $app->flash('info', $app->request->post('name') . ' added');
    } else {
      $app->flash('errors', $errors);
    }
    $app->redirectTo('admin_manage_toppings');
  }

  public function removeTopping($id) {
    $app = $this->getApp();
    if ($this->isUserAdmin()) {
      $topping_srv = new BreadService($this->getEntityManager());
      $topping = $topping_srv->removeToppingById($id);
      if (isset($topping)) {
        $app->flash('info', 'Removed ' . $topping->getName());
        $app->redirectTo('admin_manage_breads');
      } else {
        $app->flash('error', 'Invalid operation.');
        $app->redirectTo('admin_manage_breads');
      }
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function editTopping($id) {
    $app = $this->getApp();
    if ($this->isUserAdmin()) {
      $bread_srv = new BreadService($this->getEntityManager());
      $topping = $bread_srv->findTopping($id);
      $admin_srv = new AdminService($this->getEntityManager());
      $files = $admin_srv->getFilesInDir(Files::TOPPING_IMG_DIR);
      if (isset($topping)) {
        $app->render('Admin/edit_topping.html.twig', array('topping' => $topping, 'files' => $files));
      } else {
        $app->flash('error', 'Invalid operation.');
        $app->redirectTo('admin_manage_toppings');
      }
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function editToppingProcess() {
    $app = $this->getApp();
    $bread_srv = new BreadService($this->getEntityManager());
    $errors = $bread_srv->editTopping($app);
    if (false === $errors) {
      $app->flash('info', 'Topping updated');
      $app->redirectTo('admin_manage_toppings');
    } else {
      $app->flash('errors', $errors);
      /* @var $app Slim */
      $app->redirectTo('admin_topping_edit', array('id' => $app->request->post('id')));
    }
  }

  // toppings

  /* users */
  public function listAllUsers() {
    $app = $this->getApp();
    $srv = new UserService($this->getEntityManager());
    $users = $srv->fetchAllUsers();
    $app->render('Admin/user_list.html.twig', array('users' => $users));
  }

  public function editUser($id) {
    if ($this->isUserAdmin()) {
      $app = $this->getApp();
      $srv = new UserService($this->getEntityManager());
      $user = $srv->fetchUserById($id);
      if (isset($user)) {
        $app->render('Admin/edit_user.html.twig', array('user' => $user, 'postcodes' => $srv->fetchPostcodes()));
      } else {
        $app->flash('error', 'Invalid operation.');
        $app->redirectTo('admin_user_list');
      }
    } else {
      $app->flash('error', 'Unauthorized action');
      $app->redirectTo('main_page');
    }
  }

  public function editUserProcess() {
    $app = $this->getApp();
    $srv = new UserService($this->getEntityManager());
    $errors = $srv->processEditUser($app);
    if (null === $errors) {
      $app->flash('info', $app->request->post('username') . ' is updated.');
      $app->redirectTo('admin_user_list');
    } else {
      $app->flash('errors', $errors);
      $app->redirectTo('admin_user_edit', array('id' => $app->request->post('id')));
    }
  }

  //users


  /* images */
  public function manageImages() {
    $app = $this->getApp();
    try {
      $srv = new AdminService($this->getEntityManager());
      $breads = $srv->getBreadImageFileInfos();
      $toppings = $srv->getToppingImageFileInfos();
      $app->render('Admin/images.html.twig', array('breads' => $breads, 'toppings' => $toppings, 'maxsize' => Files::MAX_SIZE));
    } catch (AccessDeniedException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('main_page');
    }
  }

  public function uploadBreadImg() {
    $app = $this->getApp();
    try {
      $srv = new AdminService($this->getEntityManager());
      $srv->uploadBreadImage('image');
      $app->flash('info', 'Image succesfully uploaded');
      $app->redirectTo('admin_images_manage');
    } catch (ImageException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('admin_images_manage');
    }
  }

  public function uploadToppingsImg() {
    $app = $this->getApp();
    try {
      $srv = new AdminService($this->getEntityManager());
      $srv->uploadToppingImage('image');
      $app->flash('info', 'Image succesfully uploaded');
      $app->redirectTo('admin_images_manage');
    } catch (ImageException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('admin_images_manage');
    }
  }

  public function deleteBreadImg($filename) {
    $app = $this->getApp();
    try {
      $srv = new AdminService($this->getEntityManager());
      $srv->deleteBreadImage($filename);
      $app->flash('info', $filename . ' removed');
      $app->redirectTo('admin_images_manage');
    } catch (ImageException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('admin_images_manage');
    }
  }
  
  public function deleteToppingImg($filename) {
    $app = $this->getApp();
    try {
      $srv = new AdminService($this->getEntityManager());
      $srv->deleteToppingImage($filename);
      $app->flash('info', $filename . ' removed');
      $app->redirectTo('admin_images_manage');
    } catch (ImageException $e) {
      $app->flash('error', $e->getMessage());
      $app->redirectTo('admin_images_manage');
    }
  }
  
  

  //images
}
