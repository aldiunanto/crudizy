<?php

session_start();
require_once('connection.php');

$param = explode('.', $_POST['_action'] ?: $_GET['_action']);
require_once(dirname(__DIR__) . '/app/controllers/' . $param[0] . '.php');

$classPath	= 'app\controllers\\' . $param[0];
$controller = new $classPath($conn);

print $controller->{$param[1]}();