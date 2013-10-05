<?php
session_start(); 
ini_set("display_errors", 1);

require_once "../admin/config.php";
require_once "../admin/inc/fnc.main.php";
require_once "../admin/page/fnc.page.php";
require_once "fnc.php";
require_once "messageSource.php";

function __autoload($class) {
  require_once '../admin/inc/class.'.$class.'.php';
}




try{
    
    $lang = $_GET['lang'];
    $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $auth = new Authenticate($conn);
    if(!$auth->isLogined()){
      throw new AuthException(getMessage("errLogined"));
    }
    $userService = new UserService($conn);

    
    switch ($_GET['act']) {
    	
    	// odstranenie slovicka	
    	case 1:
    		if(!$userService->hasUserPermission($_GET['id'])){
		    	throw new AuthException(getMessage("errPerm"));
		    }
		    $userService->removeWord($_GET['id']);
		    $data = array( "err" => 0 );
    		break;

    	// editacia slovicka
    	case 2:
    		if(!$userService->hasUserPermission($_GET['id'])){
				throw new AuthException(getMessage("errPerm"));
			}
			$userService->updateWord($_GET['id'], $_GET['question'], $_GET['answer']);
			$data = array( "err" => 0 );
    		break;

    	// pridanie
    	case 3:
    		if(!$userService->isUserOwner($_GET['importId'])){
				throw new AuthException(getMessage("errPerm"));
			}
			$id = $userService->createWord($_GET['importId'], $_GET['question'], $_GET['answer']);
			$data = array( "err" => 0, "id" => $id );
    		break;
    	default:
    		throw new Exception("Invalid operation.");
    		break;
    
    }
    
}catch(AuthException $ex){
    $data = array( "err" => 1, "msg" => $ex->getMessage() );
}catch(MysqlException $ex){
    $data = array( "err" => 1, "msg" => getMessage("errDatabase") );
}catch(Exception $ex){
    $data = array( "err" => 1, "msg" => $ex->getMessage() );
}


echo json_encode( $data );

?>
