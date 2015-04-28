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
        if($book != null){
            checkBookPermision($book, $uid);
            return $this->lectureService->create($data);
        }

    }

    
    public function init(){
        global $conn;
        $this->lectureService = new LectureService($conn);
        $this->bookService = new BookService($conn, $this->lectureService);
    }
    
}