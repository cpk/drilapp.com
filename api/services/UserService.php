<?php

class UserService extends BaseService
{

	public function conn(&$__construct){
       parent::__construct($conn);
    }


    public function getUserById($uid, $full = false){
        $cols = " u.`id_user` as id, u.`login`, u.`email`, u.`givenname` as `firstName`, u.`active`, u.`blocked`, ".
                " u.`locale_id` as localeId, u.`target_locale_id` as targetLocaleId, u.word_limit as wordLimit, ".
                " u.`surname` as `lastName`, u.`pass`, u.`salt`, l.`code` as localeCode, l2.`code` as targetLocaleCode ";

        $sql = "SELECT ".($full ? "u.*, l.`code` as localeCode, l2.`code` as targetLocaleCode  " : $cols ).
               "FROM `user` u ".
               "INNER JOIN `lang` l ON l.`id_lang` = u.`locale_id` ".
               "LEFT JOIN `lang` l2 ON l2.`id_lang` = u.`target_locale_id` ".
               "WHERE `id_user`=? ".($full ? "" : "AND `active`=1 AND `blocked`=0") ;

        $data = $this->conn->select( $sql , array(intval($uid)));
        if(count($data) > 0){
            return $data[0];
        }
        return null;
    }

    public function getUserByLogin( $login ){

         $cols = " u.`id_user` as id, u.`login`, u.`email`, u.`givenname` as `firstName`, u.`active`, u.`blocked`, ".
                " u.`locale_id` as localeId, u.`target_locale_id` as targetLocaleId, u.word_limit as wordLimit, ".
                " u.`surname` as `lastName`, u.`pass`, u.`salt`, l.`code` as localeCode, l2.`code` as targetLocaleCode ";

        $sql = "SELECT $cols ".
               "FROM `user` u ".
               "INNER JOIN `lang` l ON l.`id_lang` = u.`locale_id` ".
               "LEFT JOIN `lang` l2 ON l2.`id_lang` = u.`target_locale_id` ".
               "WHERE u.`active`=1 AND (u.`login`=? OR u.`email`=?) LIMIT 1";

        $data = $this->conn->select( $sql , array($login, $login) );
        if(count($data) > 0){
            return $data[0];
        }
        return null;
    }

    public function getUserByToken( $token , $tokenValidity = null){
        if($tokenValidity == null){
            $date = new DateTime();
            $date->sub(new DateInterval('P2D'));
            $tokenValidity = $date->format('Y-m-d H:i:s');
        }

        $sql = "SELECT * ".
               "FROM `user` ".
               "WHERE `token` IS NOT NULL AND `token`=? AND `token_created` > '$tokenValidity' LIMIT 1";

        $data = $this->conn->select( $sql , array($token) );
        if(count($data) > 0){
            return $data[0];
        }
        return null;
    }

    public function create($user){
        $this->validate($user);
        $salt = StringUtils::getRandomString(5);
        $token = StringUtils::getRandomString();
        $sql = "INSERT INTO `user` (`id_user_type`, `login`, `pass`, `salt`, `active`, `blocked`, ".
                            "`reg_time`, `email`, `givenname`, `surname`, `token`, `token_created`, ".
                            " `locale_id`, `target_locale_id` ) ".
                            " VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),?,?)";
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
                        $token,
                        $user->localeId,
                        $user->targetLocaleId)
                );
        $uid = $this->conn->getInsertId();
       return $this->getUserById( $uid , true );
    }

    public function update($user){
        $this->baseValidation($user);
        $sql = "UPDATE `user` SET givenname = ?, surname = ?, `locale_id`= ? , `target_locale_id`= ?,  changed = NOW() ".
               "WHERE id_user = ? ";
       $this->conn->update( $sql ,
            array(  $user->firstName,
                    $user->lastName,
                    $user->localeId,
                    $user->targetLocaleId,
                    $user->id
                )
        );
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

		public function generateForgottenPasswordToken($login){
				if(!isset($login) || strlen($login) < 5){
					throw new RestException(400, getMessage("errUserNotFound"));
				}
				$user = $this->getUserByLogin( $login );
				if($user == null){
					throw new RestException(400, getMessage("errUserNotFound"));
				}
				$user['token'] = StringUtils::getRandomString();
				$this->setUserToken( $user['id'], $user['token'] );
				$this->sendForgottenPassEmail($user);
		}

		private function setUserToken($uid, $token){
			$this->conn->update("UPDATE `user` SET `token`= ?, `token_created`=NOW() WHERE `id_user` =? ",
					array($token, $uid)
			);
		}

		public function resetUserPassword($data){
			$this->passValidate($data);
			$user = $this->getUserByToken($data->token);
			if($user == null){
				throw new RestException(400, getMessage("errTokenNotFound"));
			}
			$salt = StringUtils::getRandomString(5);
			$this->conn->update("UPDATE `user` SET `token` = NULL , `pass`=?, `salt`=? WHERE `id_user`=?",
				array(hash_hmac('sha256', $data->password, $salt), $salt, $user['id_user'])
			);
		}

		public function sendRegistrationEmail($user){
        $logger = Logger::getLogger('email');
        $mail = PHPMailer::createInstance();
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
            $url = 'http://localhost:9000/#/login#'.$user['token'];
        }else{
            $url = 'http://web.drilapp.com/login#'.$user['token'];
        }
        $model = array(
            "head" => getMessage("emailReg_head"),
            "description" => getMessage("emailReg_descr"),
            "activate" => getMessage("emailReg_activate"),
            "activationUrl" => $url,
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



		public function sendForgottenPassEmail($user){
        $logger = Logger::getLogger('email');
        $mail = PHPMailer::createInstance();
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
            $url = 'http://localhost:9000/forgottenPass#'.$user['token'];
        }else{
            $url = 'http://web.drilapp.com/forgottenPass#'.$user['token'];
        }
        $model = array(
            "head" => getMessage("emailPass_head"),
            "description" => getMessage("emailPass_descr"),
            "activate" => getMessage("emailPass_activate"),
            "activationUrl" => $url,
            "copyUrl" =>  getMessage("emailPass_ccopyUrl")
        );

        $message = PHPMailer::getTemplate('registration.html', $model);
        $mail->AddAddress($user['email']);
        $mail->Subject = getMessage("emailPass_head");
        $mail->MsgHTML($message);

        if(!$mail->Send()) {
            $logger->error("Snding forgotten password email to User [id=" .$user['id']."] failed. Error [".$mail->ErrorInfo."] [ip=" .$_SERVER['SERVER_ADDR']."]");
        }else{
            $logger->info("Forgotten password email sent successfully to User [id=" .$user['id']."]. [ip=" .$_SERVER['SERVER_ADDR']."]");
        }
    }

    public function activateAccount($token){
        $user = $this->getUserByToken($token);
        if($user != null && $user['active'] == 0){
            $sql = "UPDATE `user` ".
                "SET `active`=1,`changed`=NOW() ".
                "WHERE `id_user`=? LIMIT 1";
            $this->conn->update( $sql , array( $user['id_user'] ) );
            return array('success' => true, "message" => getMessage("activated"));
        }
        $logger = Logger::getLogger('api');
        $logger->warn("User [id=" .$user['id_user']."] tried to activated expired [token=$token]. [ip=" .$_SERVER['SERVER_ADDR']."]");
        return array('success' => true);
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
        }
				$this->passValidate($user);
        $this->baseValidation($user);
    }

		private function passValidate($user){
			if(!isset($user->password) || strlen($user->password) < 6){
					throw new InvalidArgumentException(getMessage("errUserPasswordLength"));
			}else if(!isset($user->password2) || $user->password != $user->password2){
					throw new InvalidArgumentException(getMessage("errUserPasswordMatch"));
			}
		}
    private function baseValidation($user){
        if(!isset($user->firstName) || strlen($user->firstName) == 0 || strlen($user->firstName) > 30){
            throw new InvalidArgumentException(getMessage("errUserFirstNameLength"));
        }else if(!isset($user->lastName) || strlen($user->lastName) == 0 || strlen($user->lastName) > 30){
            throw new InvalidArgumentException(getMessage("errUserLastNameLength"));
        }else if(!isset($user->localeId)){
            throw new InvalidArgumentException(getMessage("errUserLocaleEmpty"));
        }
    }

}



?>
