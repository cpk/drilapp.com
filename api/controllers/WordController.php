<?php

class WordController{

    private $wordService;
    private $userService;

	/**
     * Update given word
     *
     * @url POST /v1/user/words
     * 
     */
    public function update( $data, $uid )
    {
        $book = $this->wordService->getBookByWordId( $data->id );
        checkBookPermision($book, $uid);
        return $this->wordService->update($data);
        
    }


    /**
     * Create a new word
     *
     * @url PUT /v1/user/words
     */
    public function create( $data, $uid )
    {
        $book = $this->wordService->getBookByLectureId( $data->dril_lecture_id );
        checkBookPermision($book, $uid);
        return $this->wordService->create($data);
    }


    /**
     * DELETE user word
     *
     * @url DELETE /v1/user/words/$id
     * 
     */
    public function delete( $id, $uid )
    {
        $book = $this->wordService->getBookByWordId( $id );
        checkBookPermision($book, $uid);
        return $this->wordService->delete( $id );
        
    }


    /**
     * Activate user word
     *
     * @url GET /v1/words
     * @noAuth
     * 
     */
    public function getWords( $uid ){
        if($uid == null){
            return $this->wordService->getRandomWords( );
        }else{
            return $this->wordService->getAllUserActivatedWords( $uid );
        }
        
    }


    /**
    * Import xls/xlsx file
    *
    * @url POST /v1/user/words/import
    */
    public function importFile( $data, $uid ){
        $lectureId = intval($_POST['lectureId']);
        $book = $this->wordService->getBookByLectureId( $lectureId );
        checkBookPermision($book, $uid);
        require dirname(dirname(__FILE__)).'/inc/PHPExcel.php';
        global $conn;
        $IOService = new IOService($conn);
        $rows = $IOService->process($_FILES["file"]);

        $statisticService = new StatisticService($conn);
        $userStats = $statisticService->getUserStatistics($uid);
        $this->wordService->createWords($rows, $book, $userStats);

        $bookService = new BookService($conn);
        return $bookService->getFetchedLectureId( $book['id'], $lectureId, $uid );
    }

    /**
     * Activate user word
     *
     * @url POST /v1/user/rateWord
     * 
     */
    public function rateWord($data, $uid){
        $this->userService->rateWord($uid, $data);
    }

    public function init(){
        global $conn;
        $this->wordService = new WordService($conn);
        $this->userService = new UserService($conn);
    }



    /**
     * Toggle word / lecture / all words activatoin
     *
     * @url POST /v1/user/toggleActivation
     * 
     */
    public function toggleActivation($data, $uid){
        if(!isset($data->type)){
            throw new  InvalidArgumentException("Bad request");
        }
        switch ($data->type) {
            case 'word':
                $book = $this->wordService->getBookByWordId( $data->id );
                checkBookPermision($book, $uid);
                return $this->wordService->activateWord( $data);
             case 'lecture':
                $book = $this->wordService->getBookByLectureId( $data->id );
                checkBookPermision($book, $uid);
                return $this->wordService->updateLectureActivity( $data );   

            default:
                throw new InvalidArgumentException("Unknow activation type: " . $data->type );
                break;
        }
    }
    
}