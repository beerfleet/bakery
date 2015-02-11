<?php

use broodjes2\TeLaet\Controllers\OrderController;

$contr = new OrderController($em, $app);

$app->get('/order/start', function() use ($contr){
  $contr->orderStart();
})->name('order_start');

$app->get('/order/add/bread/:id', function($id) use ($contr) {
  $contr->addBread($id);
})->name('order_add_bread');

$app->get('/order/remove/bread/:key', function($key) use ($contr){
  $contr->removeBread($key);
})->name('order_remove_bread');

$app->get('/order/empty/basket', function() use ($contr) {
  $contr->emptyBasket();
})->name('order_empty_basket');

$app->get('/order/add/topping/:key', function($key) use ($contr) {
  $contr->addToppingPage($key);
})->name('order_add_topping_page');

$app->get('/order/add/topping/:order_line_key/:id', function($order_line_key, $id) use ($contr) {
  $contr->addToppingToBread($order_line_key, $id);
})->name('order_add_topping');

$app->get('/order/remove/topping/:ol_key/:t_key', function($ol_key, $t_key) use ($contr) {
  $contr->removeTopping($ol_key, $t_key);
})->name('order_remove_topping');