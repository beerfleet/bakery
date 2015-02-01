<?php

use broodjes2\TeLaet\Controllers\UserController;

$contr = new UserController($em, $app);

$app->get('/register', function() use ($contr) {
  $contr->register();
  
})->name('user_register');
$app->post('/register', function() use ($contr) {
  $contr->register_process();
})->name('user_register_process');



$app->get('/logon', function() use ($contr){
  echo "TODO";
})->name('logon');

/* password */
$app->get('/password/reset', function() use ($contr) {
  //$contr->passwordResetRequest();  
})->name('password_reset_request');

$app->post('/password/reset', function() use ($contr) {
  //$contr->passwordResetProcess();
})->name('password_reset_process');

$app->get('/verify/:token', function($token) use ($contr) {
 //$contr->processToken($token);
})->name('reset_token_verify');

$app->get('/enable/:token', function($token) use ($contr) {
  //$contr->processLogonToken($token);  
})->name('logon_token_verify');

$app->post('/password/store', function() use ($contr) {  
 // $contr->processNewPassword();
})->name('verify_new_password');