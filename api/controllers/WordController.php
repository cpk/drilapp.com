<?php

class WordController{

	/**
     * Update given word
     *
     * @url POST /v1/user/words
     * 
     */
    public function update( $data, $uid )
    {
        global $wordService;
        $book = $wordService->getBookByWordId( $data->id );
        checkBookPermision($book, $uid);
        return $wordService->update($data);
        
    }


    /**
     * Create a new word
     *
     * @url PUT /v1/user/words
     * 
     */
    public function create( $data, $uid )
    {
        global $wordService;
        $book = $wordService->getBookByWordId( $data->dril_lecture_id );
        checkBookPermision($book, $uid);
        return $wordService->create($data);
        
    }


    /**
     * DELETE user word
     *
     * @url DELETE /v1/user/words/$id
     * 
     */
    public function delete( $id, $uid )
    {
        global $wordService;
        $book = $wordService->getBookByWordId( $id );
        checkBookPermision($book, $uid);
        return $wordService->delete( $id );
        
    }


    /**
     * Activate user word
     *
     * @url POST /v1/user/words/$id/activate
     * 
     */
    public function activateWord( $id, $uid ){
        global $wordService;
        $book = $wordService->getBookByWordId( $id );
        checkBookPermision($book, $uid);
        return $wordService->activateWord( $id );
    }
}