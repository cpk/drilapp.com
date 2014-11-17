<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderService
 *
 * @author Peto
 */
class UserService {
    
    private $conn;
    
    private $countOfItems = 0;

    private $peerPage = 20;
           
   
   public function __construct($conn) {
       
        if(!$conn instanceof Database){
            throw new Exception("Vyskytol sa problém s databázou.");
        }
        
        $this->conn = $conn;
    }
    
    
    
    public function getUserBooks($uid, $pageNumber){
        $_GET['id_user'] = intval($uid);
        $data =  $this->conn->select( "SELECT * FROM book_view bv ".
                                      $this->where().
                                      $this->orderBy().
                                      "LIMIT ".$this->getOffset($pageNumber).",  ".$this->peerPage);
        return xss($data);
    }

    private function getOffset($pageNumber){
        return ($pageNumber == 1 ? 0 :  ($pageNumber * $this->peerPage) - $this->peerPage);
    }


    
    public function getById($id){        
       $data =  $this->conn->select( "SELECT b.name as book_name, b.id_user, b.author, b.level, b.descr, b.descr, b.import_id, b.create, le.name, u.login , b.lang AS lang, b.lang_a AS lang_a, ".
                                      "lang_answer.name_sk AS lang_answer, lang_question.name_sk AS lang_question, ".
                                      "(SELECT count(w._id) FROM import_word w WHERE w.token=b.import_id ) as count ".
                                      "FROM import_book b ".
                                        "JOIN lang lang_question ON lang_question.id_lang=b.lang ".
                                        "JOIN lang lang_answer ON lang_answer.id_lang=b.lang_a ".
                                        "JOIN level le ON le.id_level=b.level ".
                                        "LEFT JOIN user u ON u.id_user=b.id_user ".
                                      "WHERE b.level = le.id_level AND b._id=? ".
                                      "LIMIT 1", array($id));
       return xss($data);
    }
    
    
    public function getInsertId(){
        return $this->conn->getInsertId();
    }
    

    public function getCount(){
        if($this->countOfItems == null){
            $count =  $this->conn->select("SELECT count(*) FROM book_view bv ".$this->where());
            $this->countOfItems = $count[0]["count(*)"];
        }
        return (int)$this->countOfItems;
    }
 

    public function getPeerPage(){
        return $this->peerPage;
    }

    public function orderBy(){
        if(!isset($_GET['order'])) $_GET['order'] = 0;
        switch ($_GET['order']){
            case 0 :
                return ' ORDER BY bv.`_id` DESC ';
            case 1 :
                return ' ORDER BY bv.`_id` ASC ';
            case 2 :
                return ' ORDER BY bv.`author` DESC ';
            case 3 :
                return ' ORDER BY bv.`author` ASC ';
            case 4 :
                return ' ORDER BY bv.`downloads` DESC ';
             default : 
                return ' ORDER BY bv.`_id` DESC ';
        }
        
    }
    
    public function where(){
        $_GET['lang_q'] = (isset($_GET['lang_q']) ? intval($_GET['lang_q']) : 0);
        $_GET['lang_a'] = (isset($_GET['lang_a']) ? intval($_GET['lang_a']) : 0);
        $_GET['level'] = (isset($_GET['level']) ? intval($_GET['level']) : 0);
        
        if(isset($_GET['query'])){
            $_GET['query'] = $this->conn->clean($_GET['query']);
        }

        $where = array();
        if(isset($_GET['id_user'])) 
            $where[] =  " bv.`id_user`=".$_GET['id_user']." "; 
        if(isset($_GET['lang_q']) && $_GET['lang_q'] != 0) 
            $where[] =  " (bv.`lang` ='".$_GET['lang_q']."' OR bv.`lang_a` ='".$_GET['lang_q']."' )"; 
        if(isset($_GET['lang_a']) && $_GET['lang_a'] != 0) 
            $where[] =  " (bv.`lang_a` ='".($_GET['lang_a'])."' OR bv.`lang` ='".($_GET['lang_a'])."') "; 
        if(isset($_GET['level']) && $_GET['level'] != 0) 
            $where[] =  " bv.`level` ='".($_GET['level'])."' "; 
        if(isset($_GET['query']) && strlen($_GET['query']) > 0) 
            $where[] =  " (bv.`author` LIKE '%".$_GET['query']."%' OR bv.`name` LIKE '%".$_GET['query']."%')"; 
         return (count($where) > 0 ? " WHERE " : "").implode(" AND ", $where);
    }

    public function findBooksAndAssign($uid){
        $user = $this->getUserById($uid);
        if(sizeof($user) == 1){
            $q = "update import_book set id_user=".$uid." where id_user is NULL ".
                                     " AND (TRIM(author)='".$user[0]['login']."' OR email='".$user[0]['email']."')";
            $this->conn->simpleQuery($q);
        }
    }

    public function getUserById($uid){
        $data =  $this->conn->select("SELECT * from `user` WHERE `id_user`=? and `active`=1 LIMIT 1", array(intval($uid))); 
        return xss($data);       
    }


    public function validate($user){
        if(strlen(trim($user["login"])) < 2){
            throw new InvalidArgumentException(getMessage("errLoginLen"));
        }elseif(loginExists($this->conn, $user["login"])){
            throw new InvalidArgumentException(getMessage("errLoginUniqe", $user["login"]));
        }elseif(!isEmail($user["email"])){
            throw new InvalidArgumentException(getMessage("errEmailInvalid"));
        }elseif(checkUserEmail($this->conn, $user["email"])){
            throw new InvalidArgumentException(getMessage("errEmailUniqe", $user["email"]));
        }elseif(strlen($user["pass"]) < 4){
            throw new InvalidArgumentException(getMessage("errPassLen"));
        }elseif(!preg_match("/^\w*$/", $user['login'])){
            throw new InvalidArgumentException(getMessage("errLoginChars"));
        }
    }

    public function validateBook($book){
        if(strlen(trim($book["name"])) < 5){
            throw new InvalidArgumentException(getMessage("errBookName"));
        }elseif(intval($book["lang_q"]) == 0 || intval($book["lang_a"]) == 0){
            throw new InvalidArgumentException(getMessage("errNoLang"));
        }elseif(intval($book["level"]) == 0){
            throw new InvalidArgumentException(getMessage("errData"));
        }
    }


    public function createUser($user){
        $salt = createSalt();
        $this->conn->insert("INSERT INTO `user` (`id_user_type`, `login`, `pass`, `salt`, `active`, `blocked`, `reg_time`, `email`, `givenname`, `surname`) VALUES (?,?,?,?,?,?,?,?,?,?)", 
                array(  1, 
                        $user['login'], 
                        hash_hmac('sha256', $user['pass'], $salt), 
                        $salt, 
                        1, 
                        0,
                        time(), 
                        $user['email'], 
                        $user['givenname'],
                        $user['surname']) 
                );
        
        $body = getMessage("regEmailBody", $user['login'], $user['pass']);
        $this->sendEmail($user['email'], getMessage("regEmailSubject"), $body);
    }

    public function updateUserInfo($user){
        $this->conn->insert("UPDATE `user` set `givenname`=?, `surname`=? WHERE id_user=? LIMIT 1", 
                array(  $user['givenname'],
                        $user['surname'],
                        intval($_SESSION['id'])
                        ) 
                );
    }

    private function sendEmail($toEmail, $subject, $body){
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

    public function changeUserPass($data){
        if($data['newPass'] != $data['newPassConfirm']){
            new Exception(getMessage("errUserPassMatch"));
        }
        $user = $this->getUserById($_SESSION['id']);
        if(count($user) != 1){
            throw new Exception("Error Processing Request");    
        }
        if(hash_hmac( 'sha256', $data["oldPass"] , $user[0]['salt']) != $user[0]['pass']){
            throw new Exception(getMessage("errUserOldPassMatch"));
        }
        if(strlen($data["newPass"]) < 5){
            throw new InvalidArgumentException(getMessage("errPassLen"));
        }
        $newHash = hash_hmac( 'sha256', $data["newPass"] , $user[0]['salt']);
        $this->conn->update("UPDATE user set pass=? WHERE id_user=? LIMIT 1", array($newHash, intval($_SESSION["id"]) ));
    }
    

    public function hasUserPermission($wordId){
        $result = $this->conn->simpleQuery(
            "select count(*) from import_book b ".
            "LEFT JOIN import_word w on w.token=b.import_id ".
            "WHERE w._id=".intval($wordId)." AND b.id_user=".intval($_SESSION["id"])
            );

        return ($result[0]["count(*)"] == 1);
    }

    public function isUserOwner($importId){
        $result = $this->conn->simpleQuery(
            "SELECT count(*) from import_book b WHERE b.import_id=".intval($importId)." || b._id=".intval($importId)." AND b.id_user=".intval($_SESSION["id"])
            );
        return ($result[0]["count(*)"] == 1);
    }

    public function removeWord($wordId){
        $this->conn->simpleQuery("DELETE from import_word WHERE _id=".intval($wordId)." LIMIT 1");
    }

    public function updateWord($wordId, $question, $answer){
        $this->conn->update("UPDATE import_word SET question=?, answer=? WHERE _id=? LIMIT 1", array($question, $answer, $wordId));
    }

    public function updateBook($name, $lang, $lang_a, $level, $descr, $id){
        $this->conn->update("UPDATE import_book SET name=?, lang=?, lang_a=?, level=?, descr=? WHERE _id=? LIMIT 1", 
                    array($name, $lang, $lang_a, $level, $descr, $id));
    }

    public function updateBookSharing($newState, $bookId){
        $this->conn->update("UPDATE import_book SET shared=? WHERE _id=? LIMIT 1", array($newState, $bookId));
    }


    public function hasUserBookInFavorite($uid, $bid){
        $result = $this->conn->simpleQuery("SELECT count(*) FROM user_has_favorite WHERE id_book=$bid AND id_user=$uid");
        return ($result[0]["count(*)"] == 1);
    }

    public function addBookToFavorite($uid, $bid){
        if(!$this->hasUserBookInFavorite($uid, $bid)){
            $this->conn->insert("INSERT INTO user_has_favorite (`id_book`,`id_user`) VALUES (?,?)", array($bid, $uid));       
        }
    }

    public function removeBookFromFavorite($uid, $bid){
        $this->conn->delete("DELETE FROM user_has_favorite WHERE id_book=? AND id_user=? LIMIT 1", array($bid, $uid));       
        
    }


    public function createWord($importId, $question, $answer){
        $this->conn->insert("INSERT INTO `import_word` (`question`, `answer`, `lecture_id`, `token`, `share`) VALUES (?,?,?,?,?)", 
            array(  $this->conn->clean($question), 
                    $this->conn->clean($answer), 
                    0, 
                    $importId, 
                    1) 
            );
        return $this->getInsertId();
    }
 
    public function removeBook($importId){
        $this->conn->delete("DELETE FROM import_word WHERE token =".intval($importId));
        $this->conn->delete("DELETE FROM import_book WHERE import_id = ".intval($importId)." LIMIT 1;");
    }
}

?>
