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

$app->get('/admin/bread/edit/:id', function($id) use ($contr) {
  $contr->editBread($id);
})->name('admin_bread_edit');

$app->post('/admin/bread/edit/process', function() use ($contr) {
  $contr->editBreadProcess();
})->name('admin_bread_edit_process');


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

$app->get('/admin/topping/edit/:id', function($id) use ($contr) {
  $contr->editTopping($id);
})->name('admin_topping_edit');

$app->post('/admin/topping/edit/process', function() use ($contr) {
  $contr->editToppingProcess();
})->name('admin_topping_edit_process');


/* users */
$app->get('/admin/users', function() use ($contr){
  $contr->listAllUsers();
})->name('admin_user_list');

$app->get('/admin/user/edit/:id', function($id) use ($contr){
  $contr->editUser($id);
})->name('admin_user_edit');

$app->post('/admin/user/edit', function() use ($contr){
  $contr->editUserProcess();
})->name('admin_user_edit_process');

