<?php

class StatisticService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }


    public function getUserStatistics($uid){
        $sql = "SELECT ".
               " count(distinct b.id) as bookCount, ".
               " count(distinct l.id) as lectureCount, ".
               " count(distinct w.id) as wordCount, ".
               " count(case when w.is_activated then 1 else NULL end) as activatedWordCount, ".
               " (SELECT word_limit FROM `user` WHERE id_user = ?) as wordLimit ".
               "FROM dril_book b ".
               "LEFT JOIN dril_book_has_lecture l ON b.id = l.`dril_book_id` ".
               "LEFT JOIN dril_lecture_has_word w ON l.id = w.`dril_lecture_id` ".
               "WHERE b.user_id = ?";

        $data = $this->conn->select( $sql , array($uid, $uid)); 
        if(count($data) > 0){
            return $data[0];
        }
        return null;       
    }


    public function getUserSessions($uid){
        $sql = "SELECT  `date`, `learned_cards` as learndedCards, `hits` ".
               "FROM `dril_session` ".
               "WHERE user_id = ?  ".
               "ORDER BY `date` DESC";
        return $this->conn->select( $sql , array($uid)); 
    }

    

}

?>