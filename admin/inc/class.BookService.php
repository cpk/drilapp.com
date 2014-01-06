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
class BookService {
    
    private $conn;
    
    private $countOfItems = null;
    
    private $totalPrice = null;
    
    private $totalSalePrice = null;

    private $isLoggined = false;


        
   
   public function __construct($conn) {
       
        if(!$conn instanceof Database){
            throw new Exception("Vyskytol sa problém s databázou.");
        }
        
        $this->conn = $conn;
        $this->isLoggined = isset($_SESSION['id']);
    }
    
    
    public function getBooks($pageNumber, $peerPage){
        $offset = ($pageNumber == 1 ? 0 :  ($pageNumber * $peerPage) - $peerPage);
        $data =  $this->conn->select( "SELECT bv.* ".($this->isLoggined ? ", f.id_book as favorite " : "")."FROM book_view bv ".
                                      $this->appendFavoriteBooks().
                                      $this->where().
                                      $this->orderBy().
                                      "LIMIT ".$offset.",  ".$peerPage);

        return xss($data);
    }

    private function appendFavoriteBooks(){
        if($this->isLoggined){
            return ' LEFT OUTER JOIN `user_has_favorite`  f on f.`id_book`=`bv`.`_id` and f.`id_user`='.intval($_SESSION['id']).' ';
        }
        return '';
        
    }

    public function getUserFavoriteBooks($uid, $pageNumber, $peerPage){
        $offset = ($pageNumber == 1 ? 0 :  ($pageNumber * $peerPage) - $peerPage);
        $data =  $this->conn->select( "SELECT * FROM book_view bv ".
                                      "INNER JOIN user_has_favorite f ON f.id_book=bv.`_id` AND f.`id_user`=? ".
                                      $this->where().
                                      $this->orderBy().
                                      "LIMIT ".$offset.",  ".$peerPage, array($uid));

        return xss($data);
    }

    
    public function getById($id){        
       $data =  $this->conn->select( "SELECT b.name as book_name, b._id, b.author, b.descr, b.import_id, b.create, u.login,  b.id_user, le.name, b.lang AS lang, b.lang_a AS lang_a, ".
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
    
    public function getBooksWords($importId){        
       $data = $this->conn->select( "SELECT * FROM `import_word` WHERE `token`=? order by _id ", array($importId));
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

    public function getCountOfFavoriteUsersBook($uid){
        if($this->countOfItems == null){
            $count =  $this->conn->select("SELECT count(*) FROM user_has_favorite f where id_user=$uid");
            $this->countOfItems = $count[0]["count(*)"];
        }
        return (int)$this->countOfItems;
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
        $where[] =  " bv.`shared`=1 "; 
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


   
    
}

?>
