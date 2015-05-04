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
		$lang = getLang();
		return $this->conn->select(
            "SELECT c.id, c2.name_$lang as catName, c.name_$lang as name ".
			"FROM `dril_category` c ".
			"INNER JOIN  `dril_category` c2 ON c2.id = c.parent_id ".
			"WHERE c.parent_id IS NOT NULL ".
			"ORDER BY c.ordering "
        );
	}

	public function getTreeCategories(){
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


	public function logError($data){
		return $this->conn->insert(
            "INSERT INTO `dril_error`(`user_agent`, `cause`, `error_message`, `error_url`, `stack_trace`, `user_id`, `version`) ".
            " VALUES (?, ?, ?, ? ,? ,?, ?)",
            array(
            	$_SERVER['HTTP_USER_AGENT'], 
            	$data->cause, 
            	$data->errorMessage, 
            	$data->errorUrl, 
            	$data->stackTrace,
            	$data->userId,
            	$data->version,
            	)
            );	
	}

}


?>