<?php

class SyncService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }


  public function sync($data, $uid){
        $now = $this->now();
        $mappingArray = $this->syncBooks($now, $data, $uid);
        $mappingArray = $this->syncLectures($now, $data, $mappingArray);
        
  }


  private function syncWords($now, $data, $lectureIds){
    foreach ($data->wordList as $word) {
        if($word['sid'] == null){

        }else{

        }
    }
  }

  private function updateWord($now, $word){
     $sql = "UPDATE `dril_lecture_has_word` ".
            "SET `question`=?, `answer`=?,  last_rating=?, viewed=?, avg_rating=?, active=?, changed='$now' ".
            "WHERE id = ?";
        $this->conn->insert($sql,  array(
            $word['question'], 
            $word['answer'], 
            $word['lastRating'], 
            $word['hits'], 
            $word['avgRating'], 
            $word['is_activated'], 
            $word['hits'],
            $word['sid']
        ));
  }

  public function createLecture($now, $lecture, $bookIds){
    $sql = "INSERT INTO `dril_book_has_lecture` (`name`,`dril_book_id`,`changed`,`created`) ".
    "VALUES (?, ?, '$now', '$now')";
    $bookId = isset($bookIds[$lecture['bookId']]) ? $bookIds[$lecture['bookId']] : $lecture['bookId'];
    $this->conn->insert($sql,  array($lecture['lectureName'], $bookId) );
    return $this->getLectureById($this->conn->getInsertId());
}

  private function syncLectures($now, $data, $bookIds){
    foreach ($data->lectureList  as $lecture) {
        if($lecture['sid'] == null){
          $mappingArray[$lecture['id']] = $this->createLecture($now, $lecture, $bookIds);
        }else{
          updateLecture($now, $lecture);
        }
    }
    return $mappingArray;
  }


public function updateLecture($now, $lecture){
  $sql =  "UPDATE `dril_book_has_lecture` SET `name` = ?, `changed` = '$now' WHERE `id`=? ";
  $this->conn->update($sql,  array($lecture['lectureName'], $$lecture['sid']) );
}



public function createLecture($now, $lecture, $bookIds){
    $sql = "INSERT INTO `dril_book_has_lecture` (`name`,`dril_book_id`,`changed`,`created`) ".
    "VALUES (?, ?, '$now', '$now')";
    $bookId = isset($bookIds[$lecture['bookId']]) ? $bookIds[$lecture['bookId']] : $lecture['bookId'];
    $this->conn->insert($sql,  array($lecture['lectureName'], $bookId) );
    return $this->getLectureById($this->conn->getInsertId());
}



  private function syncBooks($now, $data, $uid){
     $bookIds = array();
     foreach ($data->bookList as $book) {
       if($book['sid'] == null){
          $bookIds[$book['id']] = $this->createBook($now, $book, $uid);
       }else{
          $this->updateBook($now, $book);
       }
     }
     return $bookIds;
  }

  

  public function updateBook($now, $book ){
        $sql = 
          "UPDATE `dril_book` SET ".
            "`name` = ?, ".
            "`question_lang_id` = ?, ".
            "`answer_lang_id` = ?, ". 
            "`level_id` = ?, ".
            "`is_shared` = ?, ".
            "`changed` = '$now' ".
          "WHERE id = ? LIMIT 1";
        $this->conn->update($sql,  array(
            $book['bookName'], 
            $book['questionLang'], 
            $book['answerLang'], 
            $book['level'], 
            $book['shared'], 
            $book['sid'],
        ));
    }


  public function createBook($now, $book, $uid){
      $sql = 
        "INSERT INTO `dril_book` ( ".
          "`name`, ".
          "`question_lang_id`, ".
          "`answer_lang_id`, ".
          "`level_id`, ".
          "`is_shared`, ".
          "`user_id`, ".
          "'$now', ".
          "'$now') ".
        "VALUES (?,?,?,?,?,?, NOW(), NOW())";
      $this->conn->insert($sql,  array(
          $book['bookName'], $book['questionLang'], $book['answerLang'], 
          $book['level'], $book['shared'], $uid
        ));
      $bookId = $this->conn->getInsertId();
    }


   public function now(){
      $now = $conn->simpleQuery("select CURRENT_TIMESTAMP as timestamp;");
      return $now[0]['timestamp'];
   } 


}

?>