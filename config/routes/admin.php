<?php

use Slim\Slim;
use broodjes2\TeLaet\Controllers\AdminController;

$contr = new AdminController($em, $app);

/* @var $app Slim */
$app->get('/admin', function() use ($contr) {
  $contr->adminPage();
})->name('admin_main_page');


/* breads */
$app->post('/bread/add', function() use ($contr) {
  $contr->ajax_add_bread();
})->name('ajax_admin_add_bread');

$app->get('/bread/ajaxGet/:name', function($name) use ($contr) {  
  $contr->ajax_get_bread($name);
})->name('ajax_get_bread');

/* toppings */
$app->post('/topping/add', function() use ($contr) {
  $contr->ajax_add_topping();
})->name('ajax_admin_add_topping');

$app->get('/topping/ajaxGet/:name', function($name) use ($contr) {  
  $contr->ajax_get_topping($name);
})->name('ajax_get_topping');