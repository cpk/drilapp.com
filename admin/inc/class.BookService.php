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
        
   
   public function __construct($conn) {
       
        if(!$conn instanceof Database){
            throw new Exception("Vyskytol sa problém s databázou.");
        }
        
        $this->conn = $conn;
    }
    
    
    public function getBooks($pageNumber, $peerPage){
        
        $offset = ($pageNumber == 1 ? 0 :  ($pageNumber * $peerPage) - $peerPage);
        return  $this->conn->select( "SELECT * FROM book_view bv ".
                                      $this->where().
                                      $this->orderBy().
                                      "LIMIT ".$offset.",  ".$peerPage);
    }
    
    public function getById($id){        
       return  $this->conn->select( "SELECT b.name as book_name, b.author, b.descr, b.import_id, b.create, la.name_sk, le.name,
                                        (SELECT count(w._id) FROM import_word w WHERE w.token=b.import_id ) as count
                                      FROM import_book b, lang la, level le
                                      WHERE b.lang = la.id_lang AND b.level = le.id_level AND b._id=?
                                      LIMIT 1", array($id));
    }
    
    public function getBooksWords($importId){        
       return  $this->conn->select( "SELECT * FROM `import_word` WHERE `token`=?", array($importId));
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
       
        $where = array();
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
