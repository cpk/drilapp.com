<?php

class BookService extends BaseService
{
	private $tagService;
  private $lectureService;

	public function __construct(&$conn, &$lectureService  = null, &$wordService  = null, &$tagService = null)
    {
       parent::__construct($conn);

       if($tagService == null){
         $this->tagService = new TagService($conn);
       }else{
        $this->tagService = $tagService;
       }

       if($wordService == null){
        $this->wordService = new WordService($conn);
       }else{
        $this->wordService = $wordService;
       }

       if($lectureService == null){
        $this->lectureService = new LectureService($conn, $this->wordService);
       }else{
        $this->lectureService = $lectureService;
       }

    }

    private function getAllUserWhoForked($bookId){
      $sql = "SELECT u.id_user as id, u.login ".
             "FROM `user` u ".
             "LEFT JOIN dril_book b ON b.user_id=u.id_user ".
             "WHERE b.forked_book_id = ? GROUP BY u.id_user";
      return $this->conn->select($sql, array( $bookId ));
    }


    public function create( $book ){
        $this->validate( $book );
        $this->conn->simpleQuery('START TRANSACTION;');
        if($this->isBookNameUniqe($book->name, $book->user_id)){
            $sql =
              "INSERT INTO `dril_book` ( ".
                "`name`, ".
                "`question_lang_id`, ".
                "`answer_lang_id`, ".
                "`level_id`, ".
                "`dril_category_id`, ".
                "`is_shared`, ".
                "`user_id`, ".
                "`description`, ".
                "`changed`, ".
                "`created`) ".
              "VALUES (?,?,?,?,?,?,?,?, NOW(), NOW())";
            $this->conn->insert($sql,  array(
                $book->name, $book->question_lang_id, $book->answer_lang_id,
                $book->level_id, $book->category_id,$book->isShared,
                $book->user_id, $book->description
              ));
            $bookId = $this->conn->getInsertId();
            if(isset($book->tags)){
              $this->tagService->createTags($book->tags, $bookId, $book->user_id);
            }
            $this->conn->simpleQuery('COMMIT;');
            return $bookId;
        }
        $this->conn->simpleQuery('ROLLBACK;');
        throw new InvalidArgumentException("The book with given name already exists.", 1);
    }


    public function update( $book ){
        $this->validate( $book );
        $sql =
          "UPDATE `dril_book` SET ".
            "`name` = ?, ".
            "`question_lang_id` = ?, ".
            "`answer_lang_id` = ?, ".
            "`level_id` = ?, ".
            "`user_id` = ?, ".
            "`is_shared` = ?, ".
            "`dril_category_id` = ?, ".
            "`description` = ?, ".
            "`changed` = CURRENT_TIMESTAMP ".
          "WHERE id = ? LIMIT 1";
        $this->conn->update($sql,  array(
            $book->name,
            $book->question_lang_id,
            $book->answer_lang_id,
            $book->level_id,
            $book->user_id,
            $book->is_shared,
            $book->dril_category_id,
            $book->description,
            $book->id
        ));
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

      $sql = "SELECT `book`.*, `category`.name_$lang as category_name, count(`bisf`.`dril_book_id`) as favorited, fb.`name` as `forked_name`, fu.`login` as `forked_login` ".
             "FROM dril_view_$lang book ".
             "LEFT JOIN dril_category category ON `category`.`id` = `book`.`dril_category_id` ".
             "LEFT JOIN `dril_book_is_favorited` bisf ON `bisf`.`dril_book_id` = `book`.`id` ".
						 // "LEFT JOIN `dril_view_$lang` fb ON `fb`.`id` = `book`.`forked_book_id` ".
						 "LEFT JOIN `dril_book` fb ON `fb`.`id` = `book`.`forked_book_id` ".
						 "LEFT JOIN `user` fu ON `fu`.`id_user` = `fb`.`user_id` ".
             "WHERE `book`.id = ? ".
             "GROUP BY `book`.id ".
             "LIMIT 1";

      $result = $this->conn->select($sql , array($id));
      if(count($result) == 1){
          return $result[0];
      }
      return null;
    }


    public function forkBook($id, $uid){
			if($this->bookIsAlreadyForked($id, $uid)){
				throw new RestException(400, getMessage("errForkExists"));
			}
      $query =
	      "INSERT INTO `dril_book`( `name`, `description`, `question_lang_id`, `answer_lang_id`, `level_id`, `downloaded`, `user_id`, `created`,  `dril_category_id`, `forked_book_id`) ( ".
	        "  SELECT `name`, `description`, `question_lang_id`, `answer_lang_id`, `level_id`, `downloaded`, $uid, NOW(), `dril_category_id`, `id` ".
	        "  FROM `dril_book` ".
	        "  WHERE is_shared = 1 AND id = ".intval($id). " ".
	        "  LIMIT 1 ".
	      ")";
      $this->conn->simpleQuery($query);
      $bookId = $this->conn->getInsertId();
			if(!isset($bookId) || intval($bookId) == 0){
				throw new RestException(400, getMessage("errFork"));
			}
      $lectures = $this->lectureService->getAllBookLectures($id, $uid);
      $this->forkLectures($bookId, $lectures, $uid);
      return array('bookId' => $bookId );
    }


    private function forkLectures($toBookId, $lectures, $uid){
      $statsSevice = new StatisticService($this->conn);
      foreach ($lectures as $lecture) {
          $lecture = (object) $lecture ;
          $lectureWords = $this->wordService->getAllWordByLectureId( $lecture->id, null);
          $lecture->dril_book_id = $toBookId;
          $lecture = $this->lectureService->create($lecture);
          $lecture['dril_lecture_id'] = $lecture['id'];
          $this->wordService->createWords($lectureWords, $lecture, $statsSevice->getUserStatistics($uid));
      }
    }

		public function bookIsAlreadyForked($bookId, $uid){
			$res = $this->conn->select(
				"SELECT count(*) FROM `dril_book` WHERE `forked_book_id`=? AND `user_id`= ?",
				array($bookId, $uid));
			return $res[0]["count(*)"] > 0;
		}

    public function getFetchedBookById( $id ){
      $book = $this->getBookById($id);
      if($book != null){
        $book['forkedByUsers'] = $this->getAllUserWhoForked( $id );
        $book['tags'] = $this->tagService->getAllBookTags($id);
        $book['lectures'] = $this->lectureService->getAllBookLectures($id);
      }
      return $book;
    }

    public function getFetchedLectureId( $bookId, $lectureId, $uid ){
      $book = $this->getBookById($bookId);
      if($book != null){
        $book['tags'] = $this->tagService->getAllBookTags($bookId);
        $book['lecture'] = $this->lectureService->getLectureById($lectureId);
        $book['lecture']['words'] = $this->wordService->getAllWordByLectureId($lectureId, $uid );
        if($uid != null){
            return $this->setLanguages($book);
        }
      }
      return $book;
    }

    private function setLanguages($book){
      $count = count($book['lecture']['words']);
      for($i =0; $i < $count; $i++){
        $book['lecture']['words'][$i]['langQuestion'] = $book['question_lang_code'];
        $book['lecture']['words'][$i]['langAnswer'] = $book['answer_lang_code'];
      }
      return $book;
    }

    public function getFatchedBooks( ){
       $lang = getLang();
       $whereClause = $this->where();
       $count = $this->conn->select("SELECT count(*) FROM dril_view_".$lang." book ".$whereClause);
       $orderClause = $this->orderBy();
				$sql = "SELECT book.*, fb.`name` as `forked_name`, fb.`login` as `forked_login` ".
							 " FROM dril_view_$lang book ".
							 " LEFT JOIN `dril_view_$lang` fb ON `fb`.`id` = `book`.`forked_book_id` ".
							 " $whereClause  $orderClause ".$this->getLimit();

       $result["books"] = $this->conn->select($sql);
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


        if(isset($_GET['sharedOnly'])){
          $where[] = "`book`.`is_shared` = 1 ";
        }

        if(isset($_GET['userId']) && intval($_GET['userId']) > 0){
          $where[] = "`book`.`user_id` = ". $_GET['userId'];
        }

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
      $book->name = trim($book->name);
      if(strlen($book->name) < 8){
        throw new InvalidArgumentException(getMessage("errBookShortName"), 1);
      }
      if(strlen($book->name) > 200){
        throw new InvalidArgumentException(getMessage("errBookLongName"), 1);
      }
      $bookId = isset($book->id) ? $book->id : null;
      if(!$this->isBookNameUniqe($book->name, $book->user_id, $bookId )){
        throw new InvalidArgumentException(getMessage("errBookUniqeName", $book->name), 1);
      }
      if(intval($book->question_lang_id) == 0 || intval($book->answer_lang_id) == 0){
        throw new InvalidArgumentException(getMessage("errBookLangs"), 1);
      }
      if(intval($book->level_id) == 0){
        throw new InvalidArgumentException(getMessage("errBookLevel"), 1);
      }
       if(isset($book->description) && strlen($book->description) > 250){
        throw new InvalidArgumentException(getMessage("errBookDescr"), 1);
      }
      if(intval($book->user_id) == 0){
        throw new InvalidArgumentException(getMessage("errUnexpected"), 1);
      }

    }

}



?>
