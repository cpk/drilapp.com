<?php

class SyncService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }


  public function sync($data, $uid){
    try{
        $this->conn->beginTransaction();
        $time = $this->time();
        $mappingArray = $this->syncBooks($time, $data, $uid);
        $mappingArray = $this->syncLectures($time, $data, $mappingArray);
        $lectureIds = $this->syncWords($time, $data, $mappingArray);
        $lectureIds = $this->syncDeletedRows($data, $lectureIds);
        $this->updateCountOfWordsInLectures($lectureIds);
        $this->conn->commit();
        echo 'succes';
    }catch(MysqlException $e){
      $this->conn->rollback();
      $logger = Logger::getLogger('api');
      $logger->error('Sync for user [uid=$uid] failed');
    }
  }


  private function updateCountOfWordsInLectures($lecuteIdList){
    $sql = "";
    foreach ($lecuteIdList as $lectureId) {
        $sql =  "UPDATE dril_book_has_lecture " .
                 "SET `no_of_words`= (SELECT count(*) FROM dril_lecture_has_word WHERE dril_lecture_id = $lectureId) ".
                 "WHERE id = $lectureId;";
        $this->conn->simpleQuery($sql);         
    }
  }

  private function syncDeletedRows($data, $lectureIds){

    $tables = array( "word" => "dril_lecture_has_word", "lecture" => "dril_book_has_lecture", "book" => "dril_book" );
    $sql = "";
    foreach ($data->deletedList as $row) {
        $sql .= "DELETE FROM ".$tables[$row->tableName]." WHERE id=".intval($row->sid).";";
        if($row->tableName == "word"){
          $res = $this->conn->simpleQuery('SELECT dril_lecture_id FROM dril_lecture_has_word WHERE id = '. $row->sid);
          if(!in_array($res[0]['dril_lecture_id'], $lectureIds )){
            $lectureIds[] = $res[0]['dril_lecture_id'];
          }
        }
    }
    if($sql != ""){
      $this->conn->simpleQuery($sql);
    }
    // list of lectures ID which  in which should be updated counts of words.
    return $lectureIds;
  }


// ===================================================
  private function syncWords($time, $data, $mappingArray){
    $lectureIds = array();
    foreach ($data->wordList as $word) {
        if($word->sid == null){
          $lid = $this->createWord($time, $word, $mappingArray );
          if(!in_array($lid, $lectureIds)){
            $lectureIds[] = $lid;
          }
        }else{
          $this->updateWord($time, $word);
        }
    }
    // for updating couts
    return $lectureIds;
  }


  private function createWord($time, $word, $mappingArray){

    $lectureId = isset($mappingArray[$word->lectureId]) ? $mappingArray[$word->lectureId] : $word->lectureId;
         
   $sql = "INSERT INTO `dril_lecture_has_word` ".
          " (`question`, `answer`, `dril_lecture_id`, `last_rating`, `viewed`, `avg_rating`, `is_activated`, `changed`, `created`) ".
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? )";
        $this->conn->insert($sql,  array(
            $word->question, 
            $word->answer, 
            $lectureId,
            $word->lastRating, 
            $word->hits, 
            $word->avgRating, 
            $word->active,
            $time['createTime'],
            $time['createTime']
        ));
     return $lectureId;   
  }

  private function updateWord($time, $word){
     $sql = "UPDATE `dril_lecture_has_word` ".
            "SET `question`=?, `answer`=?, last_rating=?, viewed=?, avg_rating=?, is_activated=?, changed=? ".
            "WHERE id = ?";
        $this->conn->update($sql,  array(
            $word->question, 
            $word->answer, 
            $word->lastRating, 
            $word->hits, 
            $word->avgRating, 
            $word->active, 
            $time['syncTime'],
            $word->sid
        ));
  }

// ===================================================

  private function syncLectures($time, $data, $mappingArray){
    $ids = array();
    foreach ($data->lectureList  as $lecture) {
        if($lecture->sid == null){
          $ids[$lecture->id] = $this->createLecture($time, $lecture, $mappingArray);
        }else{
          updateLecture($time, $lecture);
        }
    }
    return $ids;
  }


private function updateLecture($time, $lecture){
  $sql = "UPDATE `dril_book_has_lecture` SET `name` = ?, `changed` = '".$time['syncTime']."' WHERE `id`=? ";
  $this->conn->update($sql,  array($lecture->lectureName, $lecture->sid) );
}



private function createLecture($time, $lecture, $mappingArray){
    $sql = "INSERT INTO `dril_book_has_lecture` (`name`,`dril_book_id`,`changed`,`created`) ".
    "VALUES (?, ?, '".$time['createTime']."', '".$time['createTime']."')";

    $bookId = isset($mappingArray[$lecture->bookId]) ? $mappingArray[$lecture->bookId] : $lecture->bookId;

    $this->conn->insert($sql,  array($lecture->lectureName, $bookId) );
    return $this->conn->getInsertId();
}


// ===================================================
  private function syncBooks($now, $data, $uid){
     $mappingArray = array();
     foreach ($data->bookList as $book) {
       if($book->sid == null){
          $mappingArray[$book->id] = $this->createBook($now, $book, $uid);
       }else{
          $this->updateBook($now, $book);
       }
     }
     return $mappingArray;
  }

  

  private function updateBook($now, $book ){
        $sql = 
          "UPDATE `dril_book` SET ".
            "`name` = ?, ".
            "`question_lang_id` = ?, ".
            "`answer_lang_id` = ?, ". 
            "`level_id` = ?, ".
            "`is_shared` = ?, ".
            "`changed` = '".$time['syncTime']."' ".
          "WHERE id = ? LIMIT 1";
        $this->conn->update($sql,  array(
            $book->bookName, 
            $book->questionLang, 
            $book->answerLang, 
            $book->level, 
            $book->shared, 
            $book->sid,
        ));
    }


  private function createBook($time, $book, $uid){
      $sql = 
        "INSERT INTO `dril_book` ( ".
          "`name`, ".
          "`question_lang_id`, ".
          "`answer_lang_id`, ".
          "`level_id`, ".
          "`is_shared`, ".
          "`user_id`, ".
          "`changed`, ".
          "`created`) ".
        "VALUES (?,?,?,?,?,?, '".$time['createTime']."', '".$time['createTime']."')";
      $this->conn->insert($sql,  array(
          $book->bookName, 
          $book->questionLang, 
          $book->answerLang, 
          $book->level, 
          $book->shared, $uid
        ));
      return $this->conn->getInsertId();
    }


   private function time(){
      $now = $this->conn->simpleQuery("select NOW() as syncTime, DATE_SUB(NOW(), INTERVAL 1 SECOND) as createTime ");
      return $now[0];
   } 


}

?>