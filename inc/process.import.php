<?php
	session_start(); 
	ini_set("display_errors", 1);

	require_once "../admin/config.php";
	require_once "../admin/inc/fnc.main.php";
	require_once "../admin/page/fnc.page.php";
	require_once "messageSource.php";
	require_once "../admin/inc/class.Database.php";
	require_once "../admin/inc/class.MysqlException.php";
	require_once '../admin/inc/PHPExcel/IOFactory.php';
	require_once '../admin/inc/class.Excel.php';

   
	
	try{
	    $lang = $_POST['lang'];
	    $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);
	 }catch(MysqlException $ex){
    	$_SESSION['error'] = getMessage("errDatabase");
    	if (!empty($_SERVER['HTTP_REFERER'])){
    		header("Location: ".$_SERVER['HTTP_REFERER']);
    		exit();
    	}
    	exit($_SESSION['error']);
	}

	try{
		validate();
	}catch(Exception $e){
		$_SESSION['error'] = $e->getMessage();
		header("Location: " . linker(26, 1, $lang));
		exit();
	}

	try {
		$fileLocation = str_replace("admin", "data", $config['root_dir'])."/import/" . $_FILES["xlsFile"]["name"];
		//die($fileLocation);
		move_uploaded_file($_FILES["xlsFile"]["tmp_name"], $fileLocation);

	    $inputFileType = PHPExcel_IOFactory::identify($fileLocation);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($fileLocation );
	} catch(Exception $e) {
	    die('Error loading file "'.pathinfo($_FILES['xlsFile']['tmp_name'], PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	$token =  getToken($conn);

	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();
	$rows = array();
	$_POST['share'] = ($_POST['share'] == "on" ? 1 : 0);
	for ($row = 0; $row <= $highestRow; $row++){ 
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
	    	//  Insert row data array into your database of choice here
	    	
	    if(!empty($rowData[0][0]) && !empty($rowData[0][1])){
	    	$rows[] = "('".c($rowData[0][0], $conn )."','".c($rowData[0][1], $conn )."',".$token.",". $_POST['share'].")";	
	    }
	}
	
	
	$count = count($rows);

	if($count == 0){
	  	$_SESSION['error'] = getMessage("errData");
		header("Location: " . linker(26, 1, $lang));
		exit();
	}



	$uid = $_SESSION['id'];
	$q = "INSERT INTO `import_book` (`lang`,`lang_a`, `level`, `name`, `descr`,`import_id`, `shared`,`id_user`) VALUES (?,?,?,?,?,?,?,?)";
	$conn->insert($q, array($_POST['lang2'], $_POST['lang_a'], $_POST['level'], $_POST['name'], $_POST['descr'],  $token,  $_POST['share'], $uid));
	
	$bookId = $conn->getInsertId();

	$q = "INSERT INTO `import_word` (`question`, `answer`, `token`, `share`) VALUES ".implode(",", $rows);
	$conn->simpleQuery($q);

	$q = "INSERT INTO `import_log` (`user_id`, `count`, `user_agent`, `ip`, `file_name`, `lang`) ".
		 " VALUES (".$uid.", ".$count.", '".$_SERVER['HTTP_USER_AGENT']."', '".$_SERVER['REMOTE_ADDR']."', '".$_FILES['xlsFile']['name']."', '".$lang."')";
	$conn->simpleQuery($q);

	
	$_SESSION['importStatus'] = 1;
	header("Location: " . linker(16, 1, $lang).'?book='.$bookId);
	exit();
function c($s, $conn){
	return $conn->clean(addslashes(trim($s)));
}

function validate(){
	if(empty($_FILES["xlsFile"]) || ($_FILES['xlsFile']['error'] != 0)) {
		throw new Exception("Vyberte XLS súbor.");
	}
	//Check if the file is JPEG image and it's size is less than 350Kb
	$filename = basename($_FILES['xlsFile']['name']);
	$ext = substr($filename, strrpos($filename, '.') + 1);

	$mimes = array( 
		    	"application/vnd.ms-excel",
		    	"application/msexcel",
		    	"application/x-msexcel",
		    	"application/x-ms-excel",
		    	"application/x-excel",
		    	"application/x-dos_ms_excel",
		    	"application/xls",
		    	"application/x-xls"
		);
	print_r($_FILES["xlsFile"]);
	
	if (($ext != "xls" && $ext != "xlsx") || !in_array($_FILES["xlsFile"]["type"], $mimes) ) {
		throw new Exception(getMessage("errNoXlsFile"));
	}
	
	if(empty($_POST['name']) || strlen($_POST['name']) < 8){
		throw new Exception(getMessage("errBookName"));
	}

	if(!isset($_SESSION['id'])){
		throw new Exception(getMessage("errLogined"));
	}

	if(empty($_POST['lang2']) || empty($_POST['lang_a'])){
		throw new Exception(getMessage("errNoLang"));
	}
}


function getToken($conn){
    $uniqe = false;
    while(! $uniqe){
        $token = mt_rand(1000000, 9999999);
        if(isUnique($conn, 'import_word', 'token', $token));
            $uniqe = true;
    }
    return $token;
}