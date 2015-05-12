<?php

class ContactService extends BaseService
{

	public function __construct(&$conn){
		parent::__construct($conn);
	}


	
	public function saveReport($data, $user){
		if($user != null){
			$sql =  "INSERT INTO `dril_report`(`id_user`, `message`, `user_agent`, `ip`) ".
            		" VALUES (?, ?, ?, ?)";
            $params = array($user['id'], $data->message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'] );
        }else{
        	$sql =  "INSERT INTO `dril_report`(`name`, `email`, `message`, `user_agent`, `ip`) ".
            		" VALUES (?, ?, ?, ?, ?)";
            $params = array($data->name, $data->email, $data->message, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'] );
        }
		$this->conn->insert($sql, $params );
		$this->sendEmail($data, $user);
	}


	public function sendEmail($data, $user){
		global $config;
        $logger = Logger::getLogger('email');
        $mail = PHPMailer::createInstance();
        $mail->AddAddress($config["mailUsername"]);
        $mail->Subject = "Dril report";
        $mail->MsgHTML($this->getMessage($data, $user));
        if(!$mail->Send()) {
            $logger->error("Email error [".$mail->ErrorInfo."] [ip=" .$_SERVER['SERVER_ADDR']."]");
        }
    }
	

	private function getMessage($data, $user){
		return 
		"From ".($user != null ? "registrated" : "").
		" user: ".($user != null ? $user['firstName']." ".$user['lastName'] : $data->name)."<br>".
		"Email: ". ($user != null ? $user['email'] : $data->email)."<br>".
		"Message: ".$data->message;
	}

}


?>