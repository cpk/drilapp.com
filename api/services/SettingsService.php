<?php

class SettingsService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }

    public function getUserSettings($uid){
        $sql = "SELECT 	s.`dril_stategy` as drilStrategy, s.`locale_id`, l.`code` ".
               "FROM `dril_settings` s ".
               "INNER JOIN `lang` l ON l.`id_lang` = s.`locale_id` ".
               "WHERE `user_id`=?";

        $data = $this->conn->select( $sql , array(intval($uid))); 
        if(count($data) > 0){
            return $data[0];
        }
        return null;       
    }

    public function getOrCreateUserSettings($uid){
    	$settings = $this->getUserSettings($uid);
    	if($settings == null){
    		return $this->createUserSettings($uid);
    	}
    	return $settings;
    }


    public function createUserSettings($uid, $locale = null){
        $params =  array(  $uid );
        if($locale != null){
            $sql = "INSERT INTO `dril_settings` (`user_id`,`locale_id`) VALUES (?, ?)";
            $params[] = $locale;
        }else{
            $sql = "INSERT INTO `dril_settings` (`user_id`) VALUES (?)";
        }
        $this->conn->insert( $sql, $params );
        return $this->getUserSettings($uid);
    }

}

?>