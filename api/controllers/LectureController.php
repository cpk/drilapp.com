<?php

class LectureController{

	/**
     * Create a new lecture
     *
     * @url POST /v1/lectures
     * 
     */
    public function update( $data, $uid )
    {
        global $bookService;
        global $lectureService;
        $book = $bookService->getBookById( $data->dril_book_id );
        if($book != null){
            checkBookPermision($book, $uid);
            return $lectureService->create($data);
        }
    }
}