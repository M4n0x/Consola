<?php

require 'KERNEL/autoload.php';

autoloader::init();
Session::init();
//user object creation

$router = new Router();
$router->RouteRequest();
