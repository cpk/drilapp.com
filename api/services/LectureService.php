<?php

class LectureService extends BaseService
{

    private $wordService;

	public function __construct( &$conn, &$wordService )
    {
       parent::__construct($conn);
       $this->wordService = $wordService;
    }


    /**
    * Create new lecture
    *
    * @param array - with keys: 
    *                [name] -name of the lecture 
    *                [dril_book_id] - is of the book in witch given lecture should be assigned   
    */
    public function create( $lecture ){
        $name = trim($lecture['name']);
        $bookId = intval($lecture['dril_book_id']);
        if($this->isLectureNameUniqe($name, $bookId)){
            $sql = "INSERT INTO `dril_book_has_lecture` (`name`,`dril_book_id`) ".
                "VALUES (?, ?)";
            $this->conn->insert($sql,  array($name, $bookId) );
            return $this->conn->getInsertId();
        }
        throw new IllegalAgumentException("The Lecture with given name already exists.", 1);
    }



    /**
    *
    * 
    */
    public function update( $lecture ){
        $name = trim($lecture['name']);
        $bookId = intval($lecture['dril_book_id']);
        $id = intval($lecture['id']);
        if($this->isLectureNameUniqe($name, $bookId, $id)){
            $sql =  "UPDATE `dril_book_has_lecture` SET ".
                    " `name` = ?, `dril_book_id` = ?, `changed` = CURRENT_TIMESTAMP  ";
            $this->conn->update($sql,  array($name, $bookId) );
            return true;
        }
        return false;
    }


    public function delete( $id ){
        $this->conn->delete("DELETE FROM `dril_lecture_has_word` WHERE dril_lecture_id = ?", $array( $id ));
        $this->conn->delete("DELETE FROM `dril_book_has_lecture` WHERE id = ? LIMIT 1", $array( $id ));
    }


    public function deleteAllBookLectures( $bookId ){
        $list = $this->getAllBookLectures( $bookId );
        foreach ($list as $key => $lecture) {
            $this->delete( $lecture['id'] );
        }
    }


    public function getLectureById( $id ){
        $result = $this->conn->select(
            "SELECT `l`.*, count(`w`) as count_of_words FROM `dril_book_has_lecture` `l` ".
            "LEFT JOIN dril_lecture_has_word w ON w.dril_lecture_id = `l`.id".
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
            "SELECT `l`.*, count(`w`.id) as count_of_words FROM `dril_book_has_lecture` `l` ".
            "LEFT OUTER JOIN dril_lecture_has_word `w` ON `w`.`dril_lecture_id` = `l`.id ".
            "WHERE `l`.dril_book_id = ? ".
            "GROUP BY `l`.id", 
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

   

}



?>