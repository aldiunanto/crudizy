<?php

$config = require_once(dirname(__DIR__) . '/app/configs/connection.php');
$dsn	= 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'];

try{
	$conn = new PDO($dsn, $config['user'], $config['password']);
}catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
}

$DB = new DB($conn);
class DB{

	static $_db;

	public function __construct($conn){
		self::$_db = $conn;
	}
	public function store($tbl, $args){
		$mark = ''; $vals = [];
		foreach($args as $k => $v){
			$mark .= '?,';
			$vals[] = $v;
		}

		$q = self::$_db->prepare('INSERT INTO '. $tbl . '(' . implode(',', array_keys($args)) . ') VALUES(' . substr($mark, 0, (strlen($mark) - 1)) . ')');
		return $q->execute($vals);
	}

}