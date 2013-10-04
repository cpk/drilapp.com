<?php
ini_set("display_errors", 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__).'/admin/logs/php_errors.txt');
	
        
require_once "admin/config.php";
//   include_once  BASE_DIR."/inc/functions.php";
function __autoload($class) {
      require_once 'admin/inc/class.'.$class.'.php';
}
  

  
  
try{

  $JSONarray = array( );

  $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);

  $conn->delete("DELETE FROM `import_word` WHERE `share`=0 AND `create`<?", array( date("Y-m-d H:i:s", strtotime('-1 day', time())) ));
 

}catch(MysqlException $ex){
  exit();
}
  
