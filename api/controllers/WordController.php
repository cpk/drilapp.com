<?php

class WordController{

	/**
     * Create a new lecture
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
}