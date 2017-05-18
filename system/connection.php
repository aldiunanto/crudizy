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
	static $select = '*';
	static $where;
	static $join;

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
	public function select($who = ''){
		if($who != '') return self::$select = $who;
		else return self::$select;
	}
	public function where($field, $val = ''){
		$w = '';
		if(is_array($field)){
			foreach ($field as $key => $val){
				$w .= $key . '="' . $val . '" AND ';
			}
		}else{
			$w = $field . '="' . $val . '" AND ';
		}
		
		return self::$where .= substr($w, 0, (strlen($w) - 5));
	}
	public function join($table, $on, $inner){
		$i  = ' ' . strtoupper($inner) . ' JOIN ';
		$i .= $table . ' ON(' . $on . ') ';
		
		return self::$join .= $i;
	}
	public function get($tbl){
		return self::$_db->query('SELECT ' . self::$select . ' FROM ' . $tbl . self::$join . (self::$where == '' ? null : ' WHERE ' . self::$where));
	}
	public function save($tbl, $set){
		$o = '';
		foreach($set as $k => $v){
			$o .= $k . '="' . $v . '",';
		}

		$q = self::$_db->prepare('UPDATE ' . $tbl . ' SET ' . substr($o, 0, (strlen($o) - 1)) . (self::$where == '' ? null : ' WHERE ' . self::$where));
		return $q->execute();
	}
	public function delete($tbl){
		$q = self::$_db->prepare('DELETE FROM ' . $tbl . (self::$where == '' ? null : ' WHERE ' . self::$where));
		return $q->execute();
	}

}