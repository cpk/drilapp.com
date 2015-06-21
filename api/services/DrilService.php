<?php

class DrilService extends BaseService
{

	public function __construct(&$conn){
       parent::__construct($conn);
    }


    public function getData($data){
        if(isset($data->localeId) && isset($data->targetLocaleId) && isset($data->device)){
            $localeId = intval($data->localeId);
        	$targetLocaleId = intval($data->targetLocaleId);
            try{
                $this->conn->beginTransaction();
                $bookList = $this->findBooks($localeId, $targetLocaleId);
                $res = $this->fetchDataFor($bookList);
                $this->logRequest($data);
                $this->conn->commit();
                return $res;
            }catch(MysqlException $e){
                $this->conn->rollback();
                $logger = Logger::getLogger('api');
                $logger->error("Loading books for Android device failed");
            }
        }
        return array(
            "bookList" => array(),
            "lectureList" => array(),
            "wordList" => array(),
        );
    	
    }

    private function logRequest($data){
        $this->conn->insert(
            "INSERT INTO dril_install_log (`locale_id`, `target_locale_id`, `device`, `ip_address`) ".
            "VALUES (?,?,?,?)", array($data->localeId, $data->targetLocaleId, $data->device, $_SERVER['REMOTE_ADDR']));
    }

    private function findBooks($loc1, $loc2){
    	return $this->conn->select(
    		"SELECT b.id, b.name as bookName, b.question_lang_id as questionLang, b.answer_lang_id as answerLang, ".
            "b.is_shared as shared, b.level_id as level, b.dril_category_id as categoryId ".
    		"FROM dril_book b ".
    		"INNER JOIN dril_book_has_lecture l ON l.dril_book_id = b.id ".
			"WHERE is_shared=1 AND (question_lang_id = $loc1 AND answer_lang_id = $loc2) OR (question_lang_id = $loc2 AND answer_lang_id = $loc1)".
			"HAVING sum(l.no_of_words) > 2 ".
			"ORDER BY RAND()".
			"LIMIT 5");
    }


    private function fetchDataFor($bookList){
    	$response = array(
    		"bookList" => $bookList,
    		"lectureList" => array(),
    		"wordList" => array(),
    	);
    	if(count($bookList) == 0){
    		return $response;
    	}
    	$where = $this->getWhereClause($bookList);
    	$response["lectureList"] = $this->getLectures($where);
    	$response["wordList"] = $this->getWords($where);
    	return $response;
    }


    private function getWords($where){
    	return $this->conn->select(
				"SELECT w.id, w.question, w.answer, w.is_activated as active, 0 as hits, ".
           		"0 as avgRating, w.dril_lecture_id as lectureId, 0 as lastRate  ".
				"FROM dril_lecture_has_word w ".
				"INNER JOIN dril_book_has_lecture l ON w.dril_lecture_id = l.id ".
				"INNER JOIN dril_book b ON l.dril_book_id = b.id ".
				"WHERE ".$where
    		);
    }

    private function getLectures($where){
    	return $this->conn->select(
				"SELECT l.id, l.name as lectureName, l.dril_book_id as bookId ".
				"FROM dril_book_has_lecture l ".
				"INNER JOIN dril_book b ON l.dril_book_id = b.id ".
				"WHERE ".$where
    		);
    }


    private function getWhereClause($bookList){
    	$isFirst = true;
    	$where = '';
    	foreach ($bookList as $book) {
    		if(!$isFirst){
    			$where .= ' OR ';
    		}
    		$where .= ' b.id = ' . $book['id'];
    	}
    	return $where ;
    }
}
