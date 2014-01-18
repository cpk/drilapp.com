<?php
header('Content-type: application/json');
	
  define("ACTION_CHECK", 1);
  define("ACTION_UPDATE", 2);
  define("LANG_EN", 1);
  define("LANG_DE", 2); 

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
    if(!isAuthorized($conn)){
      sendUnauthorizedResponse();
    }
    

    if(isset($_GET['importId'])){
    // DRILAPP.COM ------------------------------------------------------------
       $book  = $conn->select("SELECT `name` FROM `import_book` WHERE `import_id`=? LIMIT 1", array( intval($_GET['importId']) ));
       $words  = $conn->select("SELECT `question` as a,`answer` as q FROM `import_word` WHERE `token`=?", array( intval($_GET['importId']) ));
	     $conn->update("UPDATE `import_book` SET `downloads`= `downloads`+1 WHERE `import_id`=? LIMIT 1", array( intval($_GET['importId'])));
       echo json_encode(array('words'=> $words, "name" => $book[0]["name"] )) ;

    }else{
      $action = intval($_GET['act']);      
      $lang = intval($_GET['lang']);
      $version = intval($_GET['ver']);      
    // UPDATING ------------------------------------------------------------
      $books = $conn->select("SELECT * FROM `book` WHERE `version`>? AND `lang`=? AND enabled=1", array( $version, $lang ));        

      if(ACTION_CHECK == $action){
        echo json_encode(array('count' => count($books) )) ;
        exit;
      }

      for($i = 0; $i < count($books); $i++){
        $JSONarray[$i] = array( 
          "name" =>  $books[$i]['name'], 
          "version" =>  $books[$i]['version'],
          "lang_question" =>  $books[$i]['lang_question'],
          "lang_answer" =>  $books[$i]['lang_answer'],
          "sync" =>  $books[$i]['sync']
        );
        

        $lectures  = $conn->select("SELECT * FROM `lecture` WHERE `book_id`=?", array( $books[$i]['_id'] )); 
        // words
        for($j = 0; $j < count($lectures); $j++){
          $words  = $conn->select("SELECT `question` as q ,`answer` as a FROM `word` WHERE `lecture_id`=?", array( $lectures[$j]['_id'] ));

          $JSONarray[$i]["lectures"][$j] = array( 
                        "lecture_name" =>  $lectures[$j]['lecture_name'],
                        "words" =>  $words
                      );
          
        }
      }        
    
    echo json_encode(array('books'=>$JSONarray)) ;
    }
  }catch(MysqlException $ex){
    header('HTTP/1.0 400 Bad Request');
    exit();
  }
  


function isAuthorized($conn){
  $headers = apache_request_headers();
  if(isset($headers['Authorization'])){
    $authToken = $conn->select("select val from config where `key`='dril_auth' LIMIT 1");
    return $headers['Authorization'] == $authToken[0]["val"];
  } 
  return false;
}


function sendUnauthorizedResponse(){
  header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  die();
}