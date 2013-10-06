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

if(!isset($_POST['lang'])){
  $lang = "sk";  
}else{
  $lang = $_POST['lang'];
}

$_POST =  array_map("trim", $_POST);
$_POST =  array_map("htmlspecialchars", $_POST);

try{
  $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);
  if(isset($_POST["action"])){
    if($_POST["action"] == "registration"){
      try{
        $userService = new UserService($conn);
        $userService->validate($_POST);
        $userService->createUser($_POST);
        $uid = $userService->getInsertId();
        if(isset($uid)){
          $userService->findBooksAndAssign($uid);
          $auth = new Authenticate($conn);
          $auth->login($_POST['login'] ,$_POST['pass'], false , $_POST['token']);
          $_SESSION['status'] = true;
        }else{
          $_SESSION['status'] = "Some error occurred, try it again later.";
        }
      }catch(InvalidArgumentException $e){
        $_SESSION['status'] = $e->getMessage();
      }
      
    }
    
    if($_POST["action"] == "login"){
      $auth = new Authenticate($conn);
      try{
        if(isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on'){
          $_POST['rememberMe'] = true;
        }else{
          $_POST['rememberMe'] = false;
        }
        $auth->login($_POST['login'] ,$_POST['pass'], $_POST['rememberMe'], $_POST['token']);
      }catch(AuthException $e){
        $_SESSION['status'] = $e->getMessage();
      }
      
    }
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}catch(MysqlException $ex){
   die("<h2>".getMessage("errDatabase")."</h2>");
}catch(Exception $ex){

}

?>
