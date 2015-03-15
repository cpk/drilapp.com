<?php

class BookService extends BaseService
{
	private $tagService;
  private $lectureService;

	public function __construct(&$conn, &$tagService, &$lectureService)
    {
       parent::__construct($conn);
       $this->tagService = $tagService;
       $this->lectureService = $lectureService;
    }



    public function create( $book ){
        $this->validate( $book );
        $this->conn->simpleQuery('START TRANSACTION;');  
        if($this->isBookNameUniqe($book->name, $book->user_id)){
            $sql = 
              "INSERT INTO `dril_book` (`name`, `question_lang_id`, `answer_lang_id`, `level_id`, `user_id`) ".
              "VALUES (?, ?, ?, ?, ? )";
            $this->conn->insert($sql,  array(
                $book->name, $book->question_lang_id, $book->answer_lang_id, $book->level_id, $book->user_id
              ));
            $bookId = $this->conn->getInsertId();
            $this->tagService->createTags($book->tags, $bookId);
            $this->conn->simpleQuery('COMMIT;');
            return $bookId;
        }
        $this->conn->simpleQuery('ROLLBACK;');
        throw new InvalidArgumentException("The book with given name already exists.", 1);
    }


    public function update( $book ){
        $this->validate( $book );
        if($this->isBookNameUniqe($book->name, $book->user_id, $book->id)){
            $sql = 
              "UPDATE `dril_book` SET ".
                "`name` = ?, ".
                "`question_lang_id` = ?, ".
                "`answer_lang_id` = ?, ". 
                "`level_id` = ?, ".
                "`user_id` = ?, ".
                "`changed` = CURRENT_TIMESTAMP ".
              "WHERE id = ? LIMIT 1";
            $this->conn->update($sql,  array(
                $book->name, 
                $book->question_lang_id, 
                $book->answer_lang_id, 
                $book->level_id, 
                $book->user_id, 
                $book->id
            ));
            $this->tagService->createTags($book->tags, $book->id);
        }
        throw new InvalidArgumentException("The book with given name already exists.", 1);
    }


    public function delete( $id ){
      $book = $this->getBookById( $id );
      if($book != null){
        $this->tagService->deleteAllBookTags( $id );
        $this->lectureService->deleteAllBookLectures( $id );
        $this->conn->delete("DELETE FROM `dril_book` WHERE id = ? LIMIT 1;", array( $id ));
        return true;
      }
      return false;
    }


    public function getBookById( $id ){
      $lang = getLang();
      $result = $this->conn->select("SELECT * FROM dril_view_$lang book WHERE `book`.id = ? LIMIT 1", 
            array($id)
        );

        if(count($result) == 1){
            return $result[0];
        }
        return null;
    }


    public function getFetchedBookById( $id ){
      $book = $this->getBookById($id);
      if($book != null){
        $book['tags'] = $this->tagService->getAllBookTags($id);
        $book['lectures'] = $this->lectureService->getAllBookLectures($id);
      }
      return $book;
    }

    public function getFatchedBooks( $params ){
       $lang = getLang();
       $whereClause = $this->where();
       $count = $this->conn->select("SELECT count(*) FROM dril_view_".$lang." book ".$whereClause);
       $orderClause = $this->orderBy();
       $result["books"] = $this->conn->select("SELECT * FROM dril_view_$lang book $whereClause  $orderClause ".$this->getLimit());
       $result["count"] = $count[0]["count(*)"];
       return $result;
    }


    
    public function isBookNameUniqe( $name, $userId, $bookId = null){
       $sql =  "SELECT count(*) as book_count FROM  `dril_book` ".
               "WHERE name = ? AND user_id = ? ";
        if($bookId != null){
            $sql .= " AND id <> ".$bookId;
        }

        $result =  $this->conn->select( $sql, array( $name, $userId ));
        return $result[0]["book_count"] == 0;
    }

    public function where(){
        $langAnswer = "langAnswer";
        $langQuestion = "langQuestion";
        $level = "level";
        $category = "category";
        $queryStr = "query";
        $where = array();
      
        // SOURCE LANGUAGE
        if(isset($_GET[$langAnswer]) && intval($_GET[$langAnswer]) > 0){
          $where[] = "(`book`.`answer_lang_id` = ". $_GET[$langAnswer] ." OR ".
                     " `book`.`question_lang_id` = ". $_GET[$langAnswer] .") ";
        }

        // TARGET LANGUAGE
        if(isset($_GET[$langQuestion]) && intval($_GET[$langQuestion]) > 0){
          $where[] = "(`book`.`answer_lang_id` = ". $_GET[$langQuestion] ." OR ".
                     " `book`.`question_lang_id` = ". $_GET[$langQuestion] .") ";
        }

        // LEVEL
        if(isset($_GET[$level]) && intval($_GET[$level]) > 0){
          $where[] = " (`book`.`level_id` = ".$_GET[$level].") ";
        }

        // CATEGORY
        if(isset($_GET[$category]) && intval($_GET[$category]) > 0){
          $where[] = " (`book`.`dril_category_id` = ".$_GET[$category].") ";
        }
        
        if(isset($_GET[$queryStr])){
            $q = $this->conn->clean($_GET[$queryStr]);
            $where[] = "( `book`.`name` LIKE '%".$q."%' OR `book`.`login` LIKE '%".$q."%' )";
        }

        return (count($where) > 0 ? " WHERE " : "").implode(" AND ", $where);
    }

    private function getLimit(){
      $peerPage =  !isset($_GET["peerPage"]) ? 15 : intval($_GET["peerPage"]);
      $currentPage = isset($_GET["currentPage"]) ? intval($_GET["currentPage"]) : 1;
      $currentPage = $currentPage < 1 ? 1 : $currentPage;
      $offset =  $peerPage * ($currentPage - 1);
      
      if($peerPage == 0 || $peerPage > 100){
        $peerPage = 15;
        $offset = 0;
      }
      return " LIMIT $peerPage OFFSET ".$offset;
    }

    public function orderBy(){
      if(!isset($_GET['orderBy'])){
        return "";
      }
      $orderType = "DESC";
      if(isset($_GET["orderType"]) && intval($_GET["orderType"]) == 0){
        $orderType = "ASC";
      }
      switch ($_GET['orderBy']){
        case "name" :
          return " ORDER BY book.`name` $orderType ";
        case "lang":
          return " ORDER BY book.".($orderType == "DESC" ? '`question_lang_id`' : '`answer_lang_id`')." $orderType ";
        case "level":
          return " ORDER BY book.`level_id` $orderType ";
        case "date":
          return " ORDER BY book.`created_timestamp` $orderType ";
        default :
          return "";
        }
    }

    private function validate(&$book){
      if(strlen(trim($book->name)) == 0){
        throw new  InvalidArgumentException("The Book name can not be empty", 1); 
      }
      if(intval($book->question_lang_id) == 0 ||  
         intval($book->answer_lang_id) == 0){
        throw new  InvalidArgumentException("Languages are required", 1);  
      }
      if(intval($book->user_id) == 0){
        throw new InvalidArgumentException("The Book has not assigned any user", 1);
      }
      if(intval($book->level_id) == 0){
        throw new InvalidArgumentException("The Level of the Book is required", 1);
      }
    }

}



?>