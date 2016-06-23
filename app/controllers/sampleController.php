<?php namespace app\controllers;

class sampleController {

	private $db;

	public function __construct($conn){
		$this->db = $conn;
	}

}