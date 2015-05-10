<?php

class LectureController{

    private $bookService;
    private $lectureService;

	/**
     * Create a new lecture
     *
     * @url POST /v1/lectures
     * 
     */
    public function update( $data, $uid )
    {
        $book = $this->bookService->getBookById( $data->dril_book_id );
        checkBookPermision($book, $uid);
        return $this->lectureService->create($data);
    }


    /**
     * Activate user word
     *
     * @url DELETE /v1/user/lectures/$id
     * 
     */
    public function deleteLecture($id, $uid){
        global $conn;
        $wordService = new WordService($conn);
        $book = $wordService->getBookByLectureId( $id );
        checkBookPermision($book, $uid);
        $this->lectureService->deleteWordsOnly( $id );
    }

    
    public function init(){
        global $conn;
        $this->lectureService = new LectureService($conn);
        $this->bookService = new BookService($conn, $this->lectureService);
    }
    
}