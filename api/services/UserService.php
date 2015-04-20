<?php

class UserService extends BaseService
{

	public function conn(&$__construct){
       parent::__construct($conn);
    }


    public function getUserById($uid, $full = false){
        
        $sql = "SELECT ".($full ? "* " : " `id_user` as id, `login`, `email`, `givenname` as `firstName`, `surname` as `lastName`, `pass`, `salt` " ).
               "FROM `user` ".
               "WHERE `id_user`=? ".($full ? "" : "AND `active`=1 AND `blocked`=0") ;

        $data = $this->conn->select( $sql , array(intval($uid))); 
        if(count($data) > 0){
            return $data[0];
        }
        return null;       
    }

    public function getUserByLogin( $login ){
        
        $sql = "SELECT `id_user` as id, `login`, `email`, `active`, `givenname` as `firstName`, `surname` as `lastName`, `pass`, `salt` ".
               "FROM `user` ".
               "WHERE `login`=? LIMIT 1";

        $data = $this->conn->select( $sql , array($login) ); 
        if(count($data) > 0){
            return $data[0];
        }
        return null;       
    }
   
    public function create($user){
        $this->validate($user);
        $salt = StringUtils::getRandomString(6);
        $token = StringUtils::getRandomString(); 
        $sql = "INSERT INTO `user` (`id_user_type`, `login`, `pass`, `salt`, `active`, `blocked`, ".
                            "`reg_time`, `email`, `givenname`, `surname`, `token`, `token_created`) ".
                            " VALUES (?,?,?,?,?,?,?,?,?,?,?, NOW())";
        $this->conn->insert( $sql , 
                array(  1, 
                        $user->login, 
                        hash_hmac('sha256', $user->password, $salt), 
                        $salt, 
                        0, 
                        0,
                        time(), 
                        $user->email, 
                        $user->firstName,
                        $user->lastName,
                        $token) 
                );
        $uid = $this->conn->getInsertId();
       return $this->getUserById( $uid , true );
    }


    public function rateWord($uid, $word){
         $sql = "UPDATE `dril_lecture_has_word` ".
                "SET `viewed`=`viewed`+1,`last_viewd`=NOW(), `is_activated`=?, ".
                "`avg_rating`= (`avg_rating` * `viewed` + ?) / (`viewed`+1) ".
                "WHERE `id`=? LIMIT 1";
        $data = $this->conn->update( $sql , array(!$word->isLearned, $word->lastRating, $word->id) ); 
        $this->updateDrilSession($uid, $word);
    }

    public function getDrilSession($uid){
        $sql = "SELECT id FROM `dril_session` ".
               "WHERE `user_id`= ? AND `date`= CURDATE() LIMIT 1";
        $data = $this->conn->select( $sql , array($uid) ); 
        if(count($data) > 0){
            return $data[0]['id'];
        }
        $this->createDrilSession($uid);
        return $this->getDrilSession($uid);
    }

    public function createDrilSession($uid){
        $sql = "INSERT INTO `dril_session` (`user_id`, `date`)  VALUES (?, CURDATE()) ";
        $data = $this->conn->insert( $sql , array($uid) ); 
    }


    public function updateDrilSession($uid, $word){
        $sessionId = $this->getDrilSession($uid);
        $sql =  "UPDATE `dril_session` ".
                "SET `hits`= `hits` +1 ".($word->isLearned ? ", `learned_cards`=1+`learned_cards` " : "").
                "WHERE `id`=$sessionId LIMIT 1";
        $data = $this->conn->UPDATE( $sql  ); 
    }

    public function isValueUniqe($field, $value, $uid = null){
         $sql = "SELECT count(*) ".
               "FROM `user` ".
               "WHERE `$field`=? ".($uid != null ? "AND `user_id`<>".$uid : "");

        $data = $this->conn->select( $sql , array($value) ); 
        return $data[0]["count(*)"] == 0;   
    }


    public function sendRegistrationEmail($user){
        $logger = Logger::getLogger('email');
        $mail = PHPMailer::createInstance();
        $model = array(
            "head" => getMessage("emailReg_head"),
            "description" => getMessage("emailReg_descr"),
            "activate" => getMessage("emailReg_activate"),
            "activationUrl" => "http://" . $_SERVER['SERVER_NAME']."?token=".$user['token'],
            "copyUrl" =>  getMessage("emailReg_ccopyUrl")
        );

        $message = PHPMailer::getTemplate('registration.html', $model);
        $mail->AddAddress($user['email']);
        $mail->Subject = getMessage("emailReg_head");
        $mail->MsgHTML($message);

        if(!$mail->Send()) {
            $logger->error("Snding registratin email to User [id=" .$user['id_user']."] failed. Error [".$mail->ErrorInfo."] [ip=" .$_SERVER['SERVER_ADDR']."]");
        }else{
            $logger->info("Registration email sent successfully to User [id=" .$user['id_user']."]. [ip=" .$_SERVER['SERVER_ADDR']."]");
        }
    }


    private function validate($user){
        if(!isset($user->login) || strlen($user->login) < 4 || strlen($user->login) > 20){
            throw new InvalidArgumentException(getMessage("errUserLoginLength"));
        }else if(!$this->isValueUniqe("login", $user->login)){
            throw new InvalidArgumentException(getMessage("errUserLoginUniqe", $user->login));
        }elseif(!isEmail($user->email)){
            throw new InvalidArgumentException(getMessage("errUserEmailInvalid"));
        }elseif(strlen($user->email) > 45){
            throw new InvalidArgumentException(getMessage("errUserLoginLength"));
        }else if(!$this->isValueUniqe("email", $user->email)){
            throw new InvalidArgumentException(getMessage("errUserEmailUniqe", $user->email));
        }else if(strlen($user->firstName) == 0 || strlen($user->firstName) > 30){
            throw new InvalidArgumentException(getMessage("errUserFirstNameLength"));
        }else if(strlen($user->lastName) == 0 || strlen($user->lastName) > 30){
            throw new InvalidArgumentException(getMessage("errUserLastNameLength"));
        }else if(!isset($user->password) || strlen($user->password) < 6){
            throw new InvalidArgumentException(getMessage("errUserPasswordLength"));
        }else if(!isset($user->password2) || $user->password != $user->password2){
            throw new InvalidArgumentException(getMessage("errUserPasswordMatch"));
        }else if(!isset($user->locale)){
            throw new InvalidArgumentException(getMessage("errUserLocaleEmpty"));
        }

    }

}



?>