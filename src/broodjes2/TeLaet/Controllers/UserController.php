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
    $app->render('User\register.html.twig', array('postcodes' => $postcodes));
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
      $app->flashNow('errors', $errors);
      $app->render('User\register.html.twig', array('postcodes' => $this->user_srv->fetchPostcodes()));
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
    $app = $this->getApp();
    $app->render('User\logon.html.twig');
  }

  public function verifyCredentials() {
    $srv = $this->user_srv;
    $app = $this->getApp();
    /* @var $user User */
    $user = $srv->validateCredentials($app);
    if (isset($user) && $user->isEnabled()) {
      $this->setUserLoggedOn($user->getUsername());
      if ($user->isAdmin()) {
        $app->redirectTo('admin_main_page');
      } else {
        $app->redirectTo('main_page');
      }
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
    $app->render('User\password_reset_request.html.twig');
  }

  public function passwordResetProcess() {
    $app = $this->getApp();
    $srv = $this->user_srv;
    $srv->mailResetRequest($app);
    $app->flash('info', 'An email has been sent if a valid email address was provided.');
    $app->redirectTo('main_page');
  }

  public function processResetToken($token) {
    $srv = $this->user_srv;
    $user = $srv->verifyToken($token);
    $app = $this->getApp();
    if (isset($user)) {
      $app->render('User\password_reset.html.twig', array('user_id' => $user->getId()));
    } else {
      $app->redirectTo('error_404');
    }
  }

  public function processNewPassword() {
    $srv = $this->user_srv;
    $app = $this->getApp();
    $processed = $srv->processPassword($app);
    if (true === $processed) {
      $app->flash('info', 'Your password has been changed');
      $app->redirectTo('main_page');
    } else {
      $user_id = $app->request->post('user_id');
      $app->render('User\password_reset.html.twig', array('user_id' => $user_id, 'errors' => $processed));
    }
  }

}
