<?php

class BaseService
{
	protected $conn;

	public function __construct(&$conn){
		$this->conn = $conn;
	}
}

?>