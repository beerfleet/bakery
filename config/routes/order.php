<?php

use broodjes2\TeLaet\Controllers\OrderController;

$contr = new OrderController($em, $app);

$app->get('/order/start', function() use ($contr){
  $contr->orderStart();
})->name('order_start');

$app->get('/order/add/bread/:id', function($id) use ($contr) {
  $contr->addBread($id);
})->name('order_add_bread');

$app->get('/order/empty/basket', function() use ($contr) {
  $contr->emptyBasket();
})->name('order_empty_basket');

$app->get('/order/add/topping/:key', function($key) use ($contr) {
  $contr->addToppingPage($key);
})->name('order_add_topping_page');