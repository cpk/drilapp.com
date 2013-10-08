<?php 
    session_start(); 
    
    require_once "../admin/config.php";
    require_once "../admin/inc/fnc.main.php";
    require_once "../admin/page/fnc.page.php";
    require_once "../inc/fnc.php";
    require_once "../inc/messageSource.php";
    require_once "../admin/inc/PHPExcel.php";
/*
    function __autoload($class) {
            require_once '../admin/inc/class.'.$class.'.php';
    }
  
*/
// Or, using an anonymous function as of PHP 5.3.0
    spl_autoload_register(function ($class) {
        require_once '../admin/inc/class.'.$class.'.php';;
    });

    try{
        $bookId = intval($_GET['id']);
        $lang = (!isset($_GET['lang']) ? "sk" : $_GET['lang']);
        $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);
        $auth = new Authenticate($conn);
        
      $userService = new UserService($conn);
      $book = $userService->getById($bookId);

      if(count($book) == 0){
       	header("Location: /error/page.php?p=404");
      }

      if(!$auth->isLogined() || $book[0]['id_user'] != $_SESSION['id']){
        die("You have no perrmision for access this book");
      }
      $bookPrezenter = new BookPrezenter($conn);
      $words = $bookPrezenter->getBooksWords($book[0]['import_id']);
      $count = count($words);
	    switch ($_GET['t']) {
	    	case 'print':	
	    			include 'export/print.php';
	    		break;
	    	case 'pdf':
            include 'export/pdf.php';
          break;
        case 'xls':
            include 'export/xls.php';
          break;
        case 'csv':
            include 'export/csv.php';
          break;
	    	default:
	    		throw new Exception("Error Processing Request", 1);
	    		break;
	    }

    }catch(MysqlException $e){
        echo '<!DOCTYPE HTML><html><head><meta charset="utf-8" /></head><body>'.
             'Vyskytol sa problém s databázou, opráciu skúste zopakovať</body></html>';
    }catch(Exception $e){
      die('Invalid operation: ' . $_GET['t']);
    } 
?>
