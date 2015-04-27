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
     * @url POST /v1/user/words/$id/activate
     * 
     */
    public function toggleWordActivation( $data, $id, $uid ){
        $book = $this->wordService->getBookByWordId( $id );
        checkBookPermision($book, $uid);
        return $this->wordService->activateWord( $data, $id );
    }


    /**
     * Activate user word
     *
     * @url GET /v1/words
     * @noAuth
     * 
     */
    public function getRandomWords(){
        return $this->wordService->getRandomWords( );
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
        $this->userService = new userService($conn);
    }
}