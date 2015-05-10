<?php

class WordService extends BaseService
{

	public function __construct(&$conn){
    parent::__construct($conn);
  }

    public function create( $word ){
        $this->validate( $word );
        $sql = "INSERT INTO `dril_lecture_has_word` (`question`, `answer`, `dril_lecture_id`) ".
               "VALUES (?, ?, ? )";
        $this->conn->insert($sql,  array(
            $word->question, $word->answer, $word->dril_lecture_id
        ));
        $id = $this->conn->getInsertId();    
        $this->updateCountOfWordsByLectureId( $word->dril_lecture_id );
        return $this->getWordById( $id );
    }


    public function activateWord($data, $id){
        if($data->activate){
          $this->updateWordActivity($id, 1);
          $sql = "SELECT w.`id`, w.`question`, w.`answer`, w.`last_rating` as lastRating, w.`viewed`,".
               "  false as isLearned, ".
               "  UNIX_TIMESTAMP(w.`last_viewd`) as lastViewed, ".
               "  UNIX_TIMESTAMP(w.`changed`) as `changed_timestamp`,".
               "  question_lang.code as langQuestion, answer_lang.code as langAnswer ".
               "FROM `dril_lecture_has_word` w".
               "  INNER JOIN dril_book_has_lecture lhw ON lhw.id = w.dril_lecture_id ".
               "  INNER JOIN dril_book b ON b.id = lhw.dril_book_id ".
               "  INNER JOIN lang question_lang ON question_lang.id_lang = b.question_lang_id ".
               "  INNER JOIN lang answer_lang ON answer_lang.id_lang = b.answer_lang_id ".
               "WHERE w.id= ? ";
        $result = $this->conn->select( $sql, array($id) );
        if(count($result) > 0){
          return $result[0];
        }
      }else{
        $this->updateWordActivity($id, 0);
      }
      return null;
    }


    private function updateWordActivity( $id, $status ){
        $sql = "UPDATE `dril_lecture_has_word` ".
               "SET `is_activated`=? ".
               "WHERE id = ? ";
        $this->conn->update($sql,  array( $status, $id ));
    }


    public function update( $word ){

        $this->validate( $word );
        $sql = "UPDATE `dril_lecture_has_word` ".
               "SET `question`=?, `answer`=?  ".
               "WHERE id = ?";
        $this->conn->insert($sql,  array(
            $word->question, $word->answer, $word->id
        ));
        return $word;
    }


    public function getAllWordByLectureId( $id, $uid ){
      $sql = "SELECT `id`, `question`, `answer` ".( $uid == null ? '' : ", `is_activated` as isActivated " ).
             "FROM `dril_lecture_has_word` ".
             "WHERE dril_lecture_id = ? ".
             "ORDER BY id";
      $result =  $this->conn->select( $sql, array($id) );
      if($uid != null){
        for( $i = 0; $i < count($result); $i++) {
          $result[$i]['isActivated'] = (bool) $result[$i]['isActivated'];
        }
      }
      return $result;
    }

    public function getWordById( $id ){
     $sql = "SELECT w.`id`, w.`question`, w.`answer`, w.`last_rating` as lastRating, w.`viewed`,".
             "  false as isLearned, ".
             "  UNIX_TIMESTAMP(w.`last_viewd`) as lastViewed, ".
             "  UNIX_TIMESTAMP(w.`changed`) as `changed_timestamp`,".
             "  question_lang.code as langQuestion, answer_lang.code as langAnswer ".
             "FROM `dril_lecture_has_word` w".
             "  INNER JOIN dril_book_has_lecture lhw ON lhw.id = w.dril_lecture_id ".
             "  INNER JOIN dril_book b ON b.id = lhw.dril_book_id ".
             "  INNER JOIN lang question_lang ON question_lang.id_lang = b.question_lang_id ".
             "  INNER JOIN lang answer_lang ON answer_lang.id_lang = b.answer_lang_id ".
             "WHERE w.id = ? ";
      $word = $this->conn->select( $sql, array($id) ); 
      if(count($word) == 1){
        return $word[0];
      }
      return null;
    }

    public function getAllUserActivatedWords( $userId ){
     $sql = "SELECT w.`id`, w.`question`, w.`answer`, w.`last_rating` as lastRating, w.`viewed`,".
             "  false as isLearned, ".
             "  UNIX_TIMESTAMP(w.`last_viewd`) as lastViewed, ".
             "  UNIX_TIMESTAMP(w.`changed`) as `changed_timestamp`,".
             "  question_lang.code as langQuestion, answer_lang.code as langAnswer ".
             "FROM `dril_lecture_has_word` w".
             "  INNER JOIN dril_book_has_lecture lhw ON lhw.id = w.dril_lecture_id ".
             "  INNER JOIN dril_book b ON b.id = lhw.dril_book_id ".
             "  INNER JOIN lang question_lang ON question_lang.id_lang = b.question_lang_id ".
             "  INNER JOIN lang answer_lang ON answer_lang.id_lang = b.answer_lang_id ".
             "WHERE w.is_activated = true AND b.user_id = ? ".
             "LIMIT 300";
      return $this->conn->select( $sql, array($userId) ); 
    }


    public function getRandomWords( $limit = 15 ){
     $sql = "SELECT w.`id`, w.`question`, w.`answer`, w.`last_rating` as lastRating, w.`viewed`,".
             "  false as isLearned, ".
             "  UNIX_TIMESTAMP(w.`last_viewd`) as lastViewed, ".
             "  UNIX_TIMESTAMP(w.`changed`) as `changed_timestamp`,".
             "  question_lang.code as langQuestion, answer_lang.code as langAnswer ".
             "FROM `dril_lecture_has_word` w".
             "  INNER JOIN dril_book_has_lecture lhw ON lhw.id = w.dril_lecture_id ".
             "  INNER JOIN dril_book b ON b.id = lhw.dril_book_id ".
             "  INNER JOIN lang question_lang ON question_lang.id_lang = b.question_lang_id ".
             "  INNER JOIN lang answer_lang ON answer_lang.id_lang = b.answer_lang_id ".
             "WHERE b.is_shared = true AND (question_lang.id_lang = 1 OR answer_lang.id_lang = 1)".
             "ORDER BY RAND() LIMIT $limit ";
      return $this->conn->select( $sql ); 
    }

    private function validate($word){
      if(strlen(trim($word->answer)) == 0 || strlen(trim($word->question)) == 0){
        throw new  InvalidArgumentException("The Question and the answer is required", 1); 
      }
    
    }


    public function getBookByWordId( $wordId ){
      $sql =  "SELECT b.*, bhl.`no_of_words` as no_of_words, bhl.`id` as dril_lecture_id ".
              "FROM `dril_lecture_has_word` w ".
              "INNER JOIN dril_book_has_lecture bhl ON bhl.id = w.`dril_lecture_id` ".
              "INNER JOIN dril_book b ON b.id = bhl.`dril_book_id` ".
              "WHERE w.id = ? ";
      $result =  $this->conn->select( $sql, array($wordId) ); 
       if(count($result) == 1){
          return $result[0];
      }
      return null;
    }


    public function createWords($words, $lecture, $userStats){
        $count = count($words);
        $newLectureCount = $count + intval($lecture['no_of_words']);
        $logger = Logger::getLogger('api');

        if($newLectureCount > LECTURE_WORD_LIMIT){
          $logger->warn("User [uid=".$lecture['user_id']."] tried to import $count " . 
                        " into [lid=" . $lecture['dril_lecture_id']. "] current count: ".$lecture['no_of_words'] );
          throw new InvalidArgumentException( getMessage("errLecutreWordLimit", LECTURE_WORD_LIMIT) );
        }
        $totalWords = $userStats['wordCount'] + $count;
        if( $userStats['wordLimit'] != UNLIMITED && $totalWords > $userStats['wordLimit'] ){
          $logger->warn("User [uid=".$lecture['user_id']."] word limit exceeded. The user tried to import $count " . 
                        "into [lid=" . $lecture['dril_lecture_id']."]" );
          throw new InvalidArgumentException( getMessage("errWordLimit", $userStats['wordCount'], $userStats['wordLimit']) );
        }
        $sqlRows = array();
        for( $i = 0; $i < $count; $i++ ){
          $sqlRows[] = "(
                        '".$this->conn->clean(StringUtils::xssClean($words[$i]['question']))."', 
                        '".$this->conn->clean(StringUtils::xssClean($words[$i]['answer']))."',
                        ".$lecture['dril_lecture_id'].",".
                        "NOW() ".
                      ")";
        } 
        $sql = "INSERT INTO `dril_lecture_has_word` (`question`, `answer`, `dril_lecture_id`, `created`) VALUES ".
                implode(",", $sqlRows);
        $this->conn->insert($sql);
        $this->updateCountOfWordsByLectureId($lecture['dril_lecture_id']);
    }



    public function getBookByLectureId( $lectureId ){
      $sql =  "SELECT b.*, bhl.`no_of_words` as no_of_words, bhl.`id` as dril_lecture_id ".
              "FROM `dril_book` b ".
              "INNER JOIN dril_book_has_lecture bhl ON bhl.dril_book_id = b.`id` ".
              "WHERE bhl.id = ? LIMIT 1";
      $result =  $this->conn->select( $sql, array($lectureId) ); 
       if(count($result) == 1){
          return $result[0];
      }
      return null;
    }

    public function delete( $id ){
      $lectureId = $this->getLecturIdByWordId( $id );
      $this->conn->delete("DELETE FROM `dril_lecture_has_word` WHERE id = ?;", array( $id ));
      if($lectureId == null){
        $logger = Logger::getLogger('api');
        $logger->error("Culdnt not update count of words.[wid=$id][ip=" .$_SERVER['REMOTE_ADDR']."]", $e);
      }else{
        $this->updateCountOfWordsByLectureId( $lectureId  );
      }
    }

    private function getLecturIdByWordId( $wordId ){
      $result = $this->conn->select( "SELECT dril_lecture_id as id FROM dril_lecture_has_word WHERE id = ? LIMIT 1", array( $wordId ));
      if($result != null){
        return $result[0]['id'];
      }
      return null;
    }


    private function updateCountOfWordsByLectureId( $lectureId ){
      $sql = "UPDATE `dril_book_has_lecture` " .
             "SET `no_of_words`= (SELECT count(*) FROM dril_lecture_has_word WHERE dril_lecture_id = $lectureId) ".
             "WHERE id = $lectureId";
      $this->conn->select( $sql );    
    }
}

?>