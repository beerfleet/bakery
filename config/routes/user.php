<?php

use broodjes2\TeLaet\Controllers\UserController;

$contr = new UserController($em, $app);

/* registration */
$app->get('/register', function() use ($contr) {
  $contr->register();  
})->name('user_register');

$app->post('/register', function() use ($contr) {
  $contr->processRegistration();
})->name('user_register_process');

$app->get('/verify/:token', function($token) use ($contr){
  $contr->verifyRegistration($token);
});

/* logon */
$app->get('/logon', function() use ($contr){
  $contr->logonPage();
})->name('logon');

$app->post('/logon', function() use ($contr){
  $contr->verifyCredentials();
})->name('logon_verify');

$app->get('/logoff', function() use ($contr){
  $contr->logoffProcess();
})->name('user_logoff');


/* password reset */
$app->get('/password/reset', function() use ($contr) {
  $contr->passwordResetRequest();  
})->name('password_reset_request');

$app->post('/password/reset', function() use ($contr) {
  $contr->passwordResetProcess();
})->name('password_reset_process');

$app->get('/reset/:token', function($token) use ($contr) {
 $contr->processResetToken($token);
})->name('reset_token_verify');

$app->post('/password/store', function() use ($contr) {  
 $contr->processNewPassword();
})->name('password_reset');