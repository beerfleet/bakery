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
$app->get('/admin/toppings', function() use ($contr){
  $contr->addToppingsPage();
})->name('admin_manage_toppings');

$app->post('/admin/topping/add', function() use ($contr){
  $contr->addToppingProcess();
})->name('admin_topping_add');

$app->get('/admin/topping/remove/:id', function($id) use ($contr) {
  $contr->removeTopping($id);
})->name('admin_topping_remove');

/* users */
$app->get('/admin/users', function() use ($contr){
  $contr->listAllUsers();
})->name('admin_user_list');