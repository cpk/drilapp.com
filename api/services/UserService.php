<?php

class UserService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }


    public function getUserById($uid){
        
        $sql = "SELECT `id_user` as id, `login`, `email`, `givenname` as `firstName`, `surname` as `lastName`, `pass`, `salt` ".
               "FROM `user` ".
               "WHERE `id_user`=? AND `active`=1 AND `blocked`=0";

        $data = $this->conn->select( $sql , array(intval($uid))); 
        if(count($data) > 0){
            return $data[0];
        }
        return null;       
    }

    public function getUserByLogin( $login ){
        
        $sql = "SELECT `id_user` as id, `login`, `email`, `givenname` as `firstName`, `surname` as `lastName`, `pass`, `salt` ".
               "FROM `user` ".
               "WHERE `login`=? AND `active`=1 LIMIT 1";

        $data = $this->conn->select( $sql , array($login) ); 
        if(count($data) > 0){
            return $data[0];
        }
        return null;       
    }
   
    public function create($user){
        $this->validate($user);
        $salt = "zex*mur"; 
        $sql = "INSERT INTO `user` (`id_user_type`, `login`, `pass`, `salt`, `active`, ".
                            "`blocked`, `reg_time`, `email`, `givenname`, `surname`) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $this->conn->insert( $sql , 
                array(  1, 
                        $user->email, 
                        hash_hmac('sha256', $user->password, $salt), 
                        $salt, 
                        1, 
                        0,
                        time(), 
                        $user->email, 
                        $user->givenname,
                        $user->surname) 
                );
        
       return $this->conn-getInsertId();
    }


    public function rateWord($uid, $word){
         $sql = "UPDATE `dril_lecture_has_word` ".
                "SET `viewed`=`viewed`+1, `last_viewd`=NOW(), `is_activated`=?  ")
                "WHERE `id`=? LIMIT 1";
        $data = $this->conn->UPDATE( $sql , array(!$word->isLearned, $word->id) ); 
    }

    public function getDrilSession($uid){
        $sql = "SELECT * FROM `dril_session` ".
               "WHERE `user_id`= ? AND `date`= CURDATE() LIMIT 1";
        $data = $this->conn->select( $sql , array($uid) ); 
        if(count($data) > 0){
            return $data[0];
        }
        $this->createDrilSession($uid);
        return $this->getDrilSession($uid);
    }

    public function createDrilSession($uid){
        $sql = "INSERT INTO `dril_session` (`user_id`, `date`)  VALUES (?, CURDATE()) ";
        $data = $this->conn->insert( $sql , array($uid) ); 
    }


    public function updateDrilSession($uid, $word){
        $session = $this->getDrilSession($uid);
        $sql =  "UPDATE `dril_session` ".
                "SET `hits`= `hits` +1 ".($word->isLearned ? ", `learned_cards`=1+`learned_cards` ")
                "WHERE `id`=? LIMIT 1";
        $data = $this->conn->UPDATE( $sql , array($session['id']) ); 
    }

    private function validate($user){
        if(loginExists($this->conn, $user->email)){
            throw new InvalidArgumentException(getMessage("errLoginUniqe", $user->email));
        }elseif(!isEmail($user->email)){
            throw new InvalidArgumentException(getMessage("errEmailInvalid"));
        }elseif(checkUserEmail($this->conn, $user->email)){
            throw new InvalidArgumentException(getMessage("errEmailUniqe", $user->email));
        }elseif(strlen($user->password) < 6){
            throw new InvalidArgumentException(getMessage("errPassLen"));
        }
    }

}



?>