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
            $book['question'], $book['answer'], $book['dril_lecture_id']
        ));
        return $this->conn->getInsertId();
    }


    public function getAllWordByLectureId( $id ){
      $sql = "SELECT `id`, `question`, `answer` ".
             "FROM `dril_lecture_has_word` ".
             "WHERE dril_lecture_id = ? ".
             "ORDER BY id DESC ";
      return $this->conn->select( $sql, array($id) );
    }

    public function getAllUserActivatedWords( $userId ){
     $sql = "SELECT w.`id`, w.`question`, w.`answer`, w.`last_rating`, w.`viewed`, w.`last_viewd`, ".
             "  w.`is_learned`, UNIX_TIMESTAMP(w.`changed`) as `changed_timestamp`, ".
             "  question_lang.code as question_lang_code, answer_lang.code as answer_lang_code ".
             "FROM `dril_lecture_has_word` w".
             "  INNER JOIN dril_book_has_lecture lhw ON lhw.id = w.dril_lecture_id ".
             "  INNER JOIN dril_book b ON b.id = lhw.dril_book_id ".
             "  INNER JOIN lang question_lang ON question_lang.id_lang = b.question_lang_id ".
             "  INNER JOIN lang answer_lang ON answer_lang.id_lang = b.answer_lang_id ".
             "WHERE w.is_activated = true AND b.user_id = ? ".
             "LIMIT 500";
      return $this->conn->select( $sql, array($userId) ); 
    }

    private function validate($word){
      if(strlen(trim($book['answer']) == 0) || 
         strlen(trim($book['question']) == 0)){
        throw new  IllegalArgumentException("The Question and the answer is required", 1); 
      }
      if(intval($boo['dril_lecture_id']) == 0){
        throw new IllegalArgumentException("The Lecture is not selected", 1);
      }
    }
}

?>