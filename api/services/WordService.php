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