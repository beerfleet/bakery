<?php

use Doctrine\Common\ClassLoader;

include 'bootstrap.php';

$classloader = new ClassLoader("broodjes2", "src");
$classloader->register();

try {
  /* routes */  
  include 'config/routes/user.php';
  include 'config/routes/main.php';
  include 'config/routes/admin.php';
  
  $app->run();
    
} catch (Exception $ex) {
  $app->render('problem.html.twig', array('problem' => $ex->getMessage()));
}