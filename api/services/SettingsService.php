<?php

class SettingsService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }

    public function getUserSettings($uid){
        $sql = "SELECT 	s.`dril_stategy` as drilStrategy, s.`locale_id`, l.`code`, `target_lang_id`".
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


    public function createUserSettings($uid, $data = null){
        
        if($data != null){
            $sql = "INSERT INTO `dril_settings` (`user_id`,`locale_id`,`target_lang_id`) VALUES (?,?,?)";
            $this->conn->insert( $sql, array(  $uid, $data->locale_id, $data->target_lang_id ) );
        }else{
            $sql = "INSERT INTO `dril_settings` (`user_id`) VALUES (?)";
            $this->conn->insert( $sql, array(  $uid ) );
        }
        return $this->getUserSettings($uid);
    }

}

?>