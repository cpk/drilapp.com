<?php

class DrilService extends BaseService
{

    private $logger;

    public function __construct(&$conn){
       parent::__construct($conn);
       $this->logger = Logger::getLogger('api');
    }


    public function getData($data){
        if(isset($data->device)){
            $deviceId = $data->device;
        }else if(isset($data->deviceId)){
            $deviceId = $data->deviceId;
        }
        if(isset($data->localeId) && isset($data->targetLocaleId) && isset($deviceId)){
            $localeId = intval($data->localeId);
            $targetLocaleId = intval($data->targetLocaleId);
            $this->logger->info("Fetching data for [device=".$deviceId."] [localeId=$localeId] [targetLocaleId=$targetLocaleId]");
            try{
                $this->conn->beginTransaction();
                $bookList = $this->findBooks($localeId, $targetLocaleId);
                $this->logger->debug("No of books found: ".count($bookList));
                $this->updateDownloads($bookList);
                $res = $this->fetchDataFor($bookList);
                $this->logRequest($localeId, $targetLocaleId, $deviceId);
                $this->conn->commit();
                return $res;
            }catch(MysqlException $e){
                $this->conn->rollback();
            }
        }
        $this->logger->warn("Request failed. Body: [". @file_get_contents('php://input'). "] [ip=".$_SERVER['REMOTE_ADDR']."]");
        return $this->emptyResponse();

    }

    private function logRequest($localeId, $targetLocaleId, $deviceId){
        $this->conn->insert(
            "INSERT INTO dril_install_log (`locale_id`, `target_locale_id`, `device`, `ip_address`) ".
            "VALUES (?,?,?,?)", array($localeId, $targetLocaleId, $deviceId, $_SERVER['REMOTE_ADDR']));
    }

    private function findBooks($loc1, $loc2){
        return $this->conn->select(
            "SELECT b.id, b.name as bookName, b.question_lang_id as questionLang, b.answer_lang_id as answerLang, ".
            "b.is_shared as shared, b.level_id as level, b.dril_category_id as categoryId ".
            "FROM dril_book b ".
            "INNER JOIN dril_book_has_lecture l ON l.dril_book_id = b.id ".
            "WHERE b.is_shared=1 AND ((b.question_lang_id = $loc1 AND b.answer_lang_id = $loc2) OR (b.question_lang_id = $loc2 AND b.answer_lang_id = $loc1))".
            "GROUP BY b.id ".
            "HAVING sum(l.no_of_words) > 1 ".
            "ORDER BY RAND()".
            "LIMIT 5");
    }

    private function updateDownloads($bookList){
        if(count($bookList) == 0){
          return;
        }
        $ids = array();
        foreach ($bookList as  $book) {
            $ids[] = "id=". $book['id'];
        }
        $this->conn->update("UPDATE dril_book SET download = download + 1 WHERE ". implode(" OR ", $ids)) ;
    }


    private function fetchDataFor($bookList){
        if(count($bookList) == 0){
            return $this->emptyResponse();
        }
        $where = $this->getWhereClause($bookList);
        $response["bookList"] = $bookList;
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

    private function emptyResponse(){
        return array(
            "bookList" => array(),
            "lectureList" => array(),
            "wordList" => array()
        );
    }


    private function getWhereClause($bookList){
        $isFirst = true;
        $where = '';
        foreach ($bookList as $book) {
            if(!$isFirst){
                $where .= ' OR ';
            }
            $where .= ' (b.id=' . $book['id']. ') ' ;
            $isFirst = false;
        }
        return $where ;
    }
}
