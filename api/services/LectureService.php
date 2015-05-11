<?php

class LectureService extends BaseService
{

    private $wordService;

	public function __construct( &$conn, &$wordService = null )
    {
       parent::__construct($conn);
       
       if($wordService == null){
        $this->wordService = new WordService($conn);
       }else{
        $this->wordService = $wordService;
       }
    }


    /**
    * Create new lecture
    *
    * @param array - with keys: 
    *                [name] -name of the lecture 
    *                [dril_book_id] - is of the book in witch given lecture should be assigned   
    */
    public function create( $lecture ){
        $this->validate($lecture);
        $sql = "INSERT INTO `dril_book_has_lecture` (`name`,`dril_book_id`,`changed`,`created`) ".
            "VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $this->conn->insert($sql,  array($lecture->name, $lecture->dril_book_id) );
        return $this->getLectureById($this->conn->getInsertId());
    }



    public function update( $data ){
        $this->validate($data);
        $sql =  "UPDATE `dril_book_has_lecture` SET ".
                " `name` = ?, `changed` = NOW() WHERE `id`=? ";
        $this->conn->update($sql,  array($data->name, $data->id) );
    }


    public function delete( $id ){
        $this->conn->delete("DELETE FROM `dril_lecture_has_word` WHERE dril_lecture_id = ?", array( $id ));
        $this->conn->delete("DELETE FROM `dril_book_has_lecture` WHERE id = ?", array( $id ));
    }


    public function deleteWordsOnly($lectureId){
      $this->conn->delete("DELETE FROM `dril_lecture_has_word` WHERE dril_lecture_id = ?", array( $lectureId ));

    }


    public function deleteAllBookLectures( $bookId ){
        $list = $this->getAllBookLectures( $bookId );
        foreach ($list as $key => $lecture) {
            $this->delete( $lecture['id'] );
        }
    }


    public function getLectureById( $id ){
        $result = $this->conn->select(
             "SELECT `l`.*, ".
                "UNIX_TIMESTAMP(`l`.`changed`) as `changed_timestamp`, ".
                "UNIX_TIMESTAMP(`l`.`created`) as `created_timestamp` ".
            "FROM `dril_book_has_lecture` `l` ".
            "WHERE id = ? LIMIT 1", 
            array($id)
        );
        if(count($result) == 1){
            return $result[0];
        }
        return null;
    }



    public function getFetchedLectureById( $id ){
        $lecture = $this->getLectureById($id);
        if($lecture != null){
            $lecture['words'] = $this->wordService($id);
         }
         return $lecture;
    }



    public function getAllBookLectures( $bookId ){
        return $this->conn->select(
           "SELECT `l`.*, ".
                "UNIX_TIMESTAMP(`l`.`changed`) as `changed_timestamp`, ".
                "UNIX_TIMESTAMP(`l`.`created`) as `created_timestamp` ".
            "FROM `dril_book_has_lecture` `l` ".
            "WHERE `l`.dril_book_id = ? ", 
            array($bookId)
        );
    }


    private function isLectureNameUniqe( $name, $bookId, $lectureId = null ){
        $sql =  "SELECT count(*) as lecture_count FROM  `dril_book_has_lecture` l ".
                "WHERE l.name = ? AND l.dril_book_id = ? ";  

        if($lectureId != null){
            $sql .= " AND l.id <> ".$lectureId;
        }

        $result =  $this->conn->select( $sql, array( $name, $bookId ));
        return $result[0]["lecture_count"] == 0;
    }

   
    private function validate(&$lecture){
      if(!isset($lecture)){
        throw new InvalidArgumentException("Invalid data", 1); 
      }
      $lecture->name = trim($lecture->name);
      if(strlen($lecture->name) < 1){
        throw new InvalidArgumentException(getMessage("errLectureShortName"), 1); 
      }
      if(strlen($lecture->name) > 150){
        throw new InvalidArgumentException(getMessage("errLectureLongName"), 1); 
      }
      $lectureId = isset($lecture->id) ? $lecture->id : null;
      if(!$this->isLectureNameUniqe($lecture->name, $lecture->dril_book_id, $lectureId )){
        throw new InvalidArgumentException(getMessage("errLectureUniqeName", $lecture->name), 1); 
      }
      if(intval($lecture->dril_book_id) == 0 ){
        throw new InvalidArgumentException(getMessage("errLectureBookId"), 1);  
      }
     
    }
   

}



?>