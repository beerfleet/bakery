<?php

use Slim\Slim;
use broodjes2\TeLaet\Controllers\AdminController;

$contr = new AdminController($em, $app);

/* @var $app Slim */
$app->get('/admin', function() use ($contr) {
  $contr->adminPage();
})->name('admin_main_page');


/* breads */
$app->get('/admin/breads', function() use ($contr){
  $contr->addBreadPage();
})->name('admin_manage_breads');

$app->post('/admin/bread/add', function() use ($contr){
  $contr->addBreadProcess();
})->name('admin_bread_add');

$app->get('/admin/bread/remove/:id', function($id) use ($contr) {
  $contr->removeBread($id);
})->name('admin_bread_remove');


/* toppings */


/* users */
$app->get('/admin/users', function() use ($contr){
  $contr->listAllUsers();
})->name('admin_user_list');