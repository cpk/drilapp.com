<?php

class CommonService extends BaseService
{

	public function __construct(&$conn){
		parent::__construct($conn);
	}


	/**
	* Get all languages
	*/
	public function getAllLanguages(){
		return $this->getItems("lang");
	}


	/**
	* Get all lang
	*/
	public function getAllLevels(){
		return $this->getItems("level");
	}


	private function getItems($table){
		$lang = getLang();
		return $this->conn->select(
            "SELECT id_$table as id, name_$lang as name ".
            "FROM `$table` ".
            "ORDER BY id_$table"
        );
	}

}


?>