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
        $word->id = $id;
        return $word;
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


    public function getAllWordByLectureId( $id ){
      $sql = "SELECT `id`, `question`, `answer` ".
             "FROM `dril_lecture_has_word` ".
             "WHERE dril_lecture_id = ? ".
             "ORDER BY id";
      return $this->conn->select( $sql, array($id) );
    }

    public function getAllUserActivatedWords( $userId ){
     $sql = "SELECT w.`id`, w.`question`, w.`answer`, w.`last_rating` as lastRating, w.`viewed`,".
             "  UNIX_TIMESTAMP(w.`last_viewd`) as lastViewed, ".
             "  UNIX_TIMESTAMP(w.`changed`) as `changed_timestamp`, w.`is_learned`,".
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

    private function validate($word){
      if(strlen(trim($word->answer)) == 0 || strlen(trim($word->question)) == 0){
        throw new  InvalidArgumentException("The Question and the answer is required", 1); 
      }
    
    }


    public function getBookByWordId( $wordId ){
      $sql =  "SELECT b.* ".
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
}

?>