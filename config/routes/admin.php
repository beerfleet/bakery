<?php

use Slim\Slim;
use broodjes2\TeLaet\Controllers\AdminController;

$contr = new AdminController($em, $app);

/* @var $app Slim */
$app->get('/admin', function() use ($contr) {
  $contr->adminPage();
})->name('admin_main_page');

$app->post('/bread/add', function() use ($contr) {
  $contr->ajax_add_bread();
})->name('admin_add_bread');
