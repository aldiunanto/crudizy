<?php

$config = require_once(dirname(__DIR__) . '/app/configs/connection.php');
$dsn	= 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'];

try{
	$conn = new PDO($dsn, $config['user'], $config['password']);
}catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
}