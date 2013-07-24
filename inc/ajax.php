<?php
session_start(); 
ini_set("display_errors", 0);

require_once "../admin/config.php";
require_once "../admin/inc/fnc.main.php";
require_once "../admin/page/fnc.page.php";
require_once "../admin/inc/class.Database.php";
require_once "../admin/inc/class.MysqlException.php";
require_once "../admin/inc/mailer/class.phpmailer.php";


function getToken($conn){
    $uniqe = false;
    while(! $uniqe){
        $token = mt_rand(1000000, 9999999);
        if(isUnique($conn, 'import_word', 'token', $token));
            $uniqe = true;
    }
    return $token;
}

$str["sk"]["err_email"] = "Neplatná emailvá adresa.";
$str["en"]["err_email"] = "Invalid email address, fix it please.";
$str["sk"]["err_db"] = "Vyskytol sa problém s databázou, operáciu skúste zopakovať.";
$str["en"]["err_db"] = "Database error occurred, try it again.";
$str["sk"]["err_captcha"] = "Chybne opísaný text obrázka.";
$str["en"]["err_captcha"] = "Invalid captcha.";
$str["sk"]["err_length"] = "Autor môže mať max. 50 znakov";
$str["en"]["err_length"] = "Author is too long. Can be only 50 char. length.";
$str["sk"]["err_bookname"] = "Chybne vyplnený názov učebnice ";
$str["en"]["err_bookname"] = "Name of textbook is wrong, correct it please.";
$str["en"]["subject"] = "Your import ID";
$str["sk"]["subject"] = "Váš identifikátor";
$str["sk"]["body"] = "Váš vygenerovaný identifikátor je: ";
$str["en"]["body"] = "Your import ID is: ";
$str["sk"]["success"] = "Váš identifikátor, <strong>platný 24 hodín</strong>, je úspešne vygenerovaný: <strong>";
$str["en"]["success"] = "Your import ID, <strong>valid only 24 hours</strong>, is successfully created: <strong>";
$str["sk"]["success_share"] = "Váš zdielaný identifikátor je úspešne vygenerovaný: <strong>";
$str["en"]["success_share"] = "Your shared import ID, is successfully created: <strong>";
$str["sk"]["err_data"] = "Chybne vyplnené slovíčka, skontrolujte si ich.";
$str["en"]["err_data"] = "Check your word please and correct them.";


try{
    if($_POST['act'] == 1){
    $lang = $_POST['lang'];
    $conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);
    
    if(isset($_POST['email']) && strlen($_POST['email']) > 5 && !isEmail($_POST['email'])){
        echo json_encode( array( "err" => 1, "msg" => $str[$lang]["err_email"]) );
        exit;
    } 
    
    if($_POST['share'] == 1 && strlen($_POST['author']) > 50){
        echo json_encode( array( "err" => 1, "msg" => $str[$lang]["err_length"]) );
        exit;
    } 
    
    if($_POST['share'] == 1 && (strlen($_POST['name']) > 250 || strlen($_POST['name']) <= 0)) {
        echo json_encode( array( "err" => 1, "msg" => $str[$lang]["err_bookname"]) );
        exit;
    } 

    if (!isset($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
        echo json_encode( array( "err" => 1, "msg" => $str[$lang]["err_captcha"]) );
        exit;
    } 

      $token =  getToken($conn);
      $count = (count($_POST['data']) - 1);
      $rows = array();
      for($i = 0; $i < $count; $i++){
          if(strlen(trim($_POST['data'][$i]["question"])) != 0 &&
             strlen(trim($_POST['data'][$i]["answer"])) != 0 ){
              $rows[] = "('".addslashes(trim($_POST['data'][$i]["question"]))."','".addslashes(trim($_POST['data'][$i]["answer"]))."',".$token.",".$_POST['share'].")";
              
          }
      }
      
      if(count($rows) == 0){
          throw new Exception($str[$lang]["err_data"]);
      }
      
      if($_POST['share'] == 1){ 
        $q = "INSERT INTO `import_book` (`lang`,`lang_a`, `level`, `name`, `descr`,`author`,`import_id`,`email`) VALUES (?,?,?,?,?,?,?,?)";
        $conn->insert($q, array($_POST['lang2'], $_POST['lang_a'], $_POST['level'], $_POST['name'], $_POST['descr'], $_POST['author'], $token, $_POST['email']));
      }
      $q = "INSERT INTO `import_word` (`question`, `answer`, `token`, `share`) VALUES ".implode(",", $rows);
      $conn->simpleQuery($q);
      }
      
      if(isset($_POST['email']) && strlen($_POST['email']) > 5  ){
            $mail = new PHPMailer();
            $mail->From = "info@drilapp.com";
            $mail->FromName = "Android Dril";
            $mail->AddAddress( $_POST['email'] ); 
            $mail->WordWrap = 120; 
            $mail->IsHTML(false);
            $mail->Subject = $str[$lang]["subject"];
            $mail->Body    = $str[$lang]["body"].$token;
            $mail->Send();
      
          
      }
      if($_POST['share'] == 1){ 
          $data = array( "err" => 0, "msg" => $str[$lang]["success_share"].$token.'<strong>' );
      }else{
          $data = array( "err" => 0, "msg" => $str[$lang]["success"].$token.'<strong>' );
      }
      
    


}catch(MysqlException $ex){
    $data = array( "err" => 1, "msg" => $str[$lang]["err_db"] );
}catch(Exception $ex){
    $data = array( "err" => 1, "msg" => $ex->getMessage() );
}


echo json_encode( $data );

?>
