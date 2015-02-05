<?php

require_once './vendor/autoload.php';
$config = require_once './config/config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Slim\Slim;
use broodjes2\TeLaet\Entities\Constants\Entities;
use Slim\Extras\Views\Twig;

// ---------------->  YAML
$namespaces = $config['yaml']['namespaces'];
$driver = new SimplifiedYamlDriver($namespaces);
$driver->setGlobalBasename('global'); // global.orm.yml
// ---------------->  Doctrine
$isDevMode = true;
$dbParams = $config['doctrine'];

// ----------------> YAML
$yml_config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/doctrine"), $isDevMode);
$entityManager = EntityManager::create($dbParams, $yml_config);
$em = $entityManager;

// ---------------> Slim
$view = new Twig();
$view->twigOptions = $config['slim_twig'];
$view->twigExtensions = $config['slim_ext'];


$app = new Slim(array('view' => $view));
$app->config($config['slim']);

// TWIG GLOBALS
/* @var $env Twig_Environment */

function queryUserByUserName($em, $username) {
  $repo = $em->getRepository(Entities::USER);
  $user = $repo->findBy(array('username' => $username));
  return $user;
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
function getActiveUser($em) {
  if (isset($_SESSION['user']) && $_SESSION['user'] != null) {
    $user = queryUserByUserName($em, $_SESSION['user']);
    return isset($user[0]) ? $user[0] : null;
  }
  return null;
}

$activeUser = getActiveUser($em);
$view->set('app', $app);
$view->set('user', $activeUser);
$view->set('session', $_SESSION);
$view->set('path', $_SERVER['REQUEST_URI']); //full path
$view->set('root', 'http://' . $_SERVER['HTTP_HOST']);
