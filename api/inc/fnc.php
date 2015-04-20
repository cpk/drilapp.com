<?php
  $lang = "en";
  function setLang($locale = false){
    global $lang;
    $avaiableLang = array("sk", "en");
    if($locale){
      if(in_array($locale, $avaiableLang)){
        $lang = $locale;
      }
    }else{
      if(isset($_GET['locale']) && in_array($_GET['locale'], $avaiableLang)){
        $lang = $_GET['locale'];
      }
    }
  }
 	setLang();
  
  function getLang(){
    global $lang;
    return $lang;
  }
 	function sendEmail($toEmail, $subject, $body){
        $mail = new PHPMailer();
        $mail->From = "info@drilapp.com";
        $mail->FromName = "Android Dril";
        $mail->AddAddress( $toEmail ); 
        $mail->WordWrap = 120; 
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        if($_SERVER['REMOTE_ADDR']  != "127.0.0.1"){
            $mail->Send();
        }
    }

    if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
      $arh = array();
      $rx_http = '/\AHTTP_/';
      foreach($_SERVER as $key => $val) {
        if( preg_match($rx_http, $key) ) {
          $arh_key = preg_replace($rx_http, '', $key);
          $rx_matches = array();
          // do some nasty string manipulations to restore the original letter case
          // this should work in most cases
          $rx_matches = explode('_', $arh_key);
          if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
            foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
            $arh_key = implode('-', $rx_matches);
          }
          $arh[$arh_key] = $val;
        }
      }
      return( $arh );
    }
  }


   function checkBookPermision($book, $uid){
      if(isset($book) && $book != null){
          if($book['is_shared'] == 0 && !isset($uid) || $book['is_shared'] == 0 && $book['user_id'] != $uid){
              throw new RestException(401, 'User has not permission to book [id='.$book['id'].']');
          }
      }else{
        throw new RestException(404, 'The book was not found');
      }
    }

    function getConf($conn, $type = "dril"){
      $sql = "SELECT `key`, `val` FROM `config`";
      switch($type){
        case "basic":
          $sql .= " WHERE `key` LIKE 'c_%'";
        break; 
        case "dril":
          $sql .= " WHERE `key` LIKE 'dril_%'";
        break; 
        case "shop":
          $sql .= " WHERE `key` LIKE 's_%'";
        break;
        case "full":
        default:  
      }
      $array = $conn->select($sql);
      $result = array();
      for($i = 0; $i < count($array); $i++){
          $result[ $array[$i]["key"]] = htmlspecialchars($array[$i]["val"]) ; 
      }
      return $result;
  }

  function isEmail($email){
    return (preg_match ("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/i" ,$email) == 1);
  }

  
   
?>