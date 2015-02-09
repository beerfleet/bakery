<?php

use broodjes2\TeLaet\Controllers\OrderController;

$contr = new OrderController($em, $app);

$app->get('/order/start', function() use ($contr){
  $contr->orderStart();
})->name('order_start');