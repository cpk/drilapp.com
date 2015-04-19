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

	public function getCategories(){
		$categories = $this->getCategoryLevel(null);
        for($i = 0; $i < count($categories); $i++){
        	$categories[$i]['subCategories'] = $this->getCategoryLevel($categories[$i]['id']);
        }
        return $categories;
	}


	private function getItems($table){
		$lang = getLang();
		return $this->conn->select(
            "SELECT id_$table as id, name_$lang as name ".($table == "lang" ? ", `code` " : "").
            "FROM `$table` ".
            "ORDER BY id_$table"
        );
	}

	

	private function getCategoryLevel($id){
		$lang = getLang();
		return $this->conn->select(
            "SELECT id , name_$lang as name ".
            "FROM `dril_category` ".
            "WHERE parent_id ".($id == null ? " IS NULL " : " = ".$id." ").
            "ORDER BY ordering"
        );
	}

}


?>