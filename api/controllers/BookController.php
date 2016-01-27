<?php

class BookController
{

    private $bookService;


    /**
     * Gets the book by id
     *
     * @url GET /v1/book/$id
     * @noAuth
     */
    public function getBook( $id , $uid = null)
    {

        $book =  $this->bookService->getFetchedBookById($id);
        checkBookPermision($book, $uid);
        return $book;
    }


    /**
     * Gets the book by id
     *
     * @url GET /v1/user/book/$id
     */
    public function getUserBook( $id , $uid)
    {

        $book =  $this->bookService->getFetchedBookById($id);
        checkBookPermision($book, $uid);
        return $book;
    }

    /**
     * Gets fetched lecture
     *
     * @url GET /v1/book/$bookId/lecture/$lectureId
     * @noAuth
     */
    public function getFetchedLecture($bookId, $lectureId, $uid = null){

        $book = $this->bookService->getFetchedLectureId( $bookId, $lectureId , $uid);
        checkBookPermision($book, $uid);
        return $book;
    }

    /**
     * Gets fetched lecture
     *
     * @url GET /v1/user/book/$bookId/lecture/$lectureId
     *
     */
    public function getUserFetchedLecture($bookId, $lectureId, $uid){

        $book = $this->bookService->getFetchedLectureId( $bookId, $lectureId, $uid );
        checkBookPermision($book, $uid);
        return $book;
    }

    /**
     * Create new book
     *
     * @url POST /v1/books
     */
    public function create( $data , $uid)
    {
        Logger::getLogger('api')->info('User $uid, creating book '. json_encode( (array)$data ) .' / '. $_SERVER['REMOTE_ADDR'] );
        $data->user_id = $uid;
        $id =  $this->bookService->create($data);
        return array("id" => $id);
    }


     /**
     * Update existing book
     *
     * @url PUT /v1/book/$id
     */
    public function update( $id, $data, $uid )
    {
        Logger::getLogger('api')->info('User $uid, updating book [id=$id] '. json_encode( (array)$data ) .' / '. $_SERVER['REMOTE_ADDR'] );
        $book = $this->bookService->getBookById( $id );
        checkBookPermision($book, $uid);
        $this->bookService->update($data);
        return $this->bookService->getFetchedBookById($id);

    }


    /**
     * DELETE book
     *
     * @url DELETE /v1/book/$id
     */
    public function delete( $id , $uid )
    {
        Logger::getLogger('api')->info('User $uid, removing book [id=$id] '. $_SERVER['REMOTE_ADDR'] );
        $book = $this->bookService->getBookById( $id );
        checkBookPermision($book, $uid);
        $this->bookService->delete($id);
        Logger::getLogger('api')->warn("User [id=$uid] deleted book [id=$id] from ". $_SERVER['REMOTE_ADDR'] );

    }

     /**
     * Retrieve book page
     *
     * @url GET /v1/books
     * @noAuth
     */
    public function getBookPage(  ){

        $_GET['sharedOnly'] = true;
        return $this->bookService->getFatchedBooks();
    }

     /**
     * Retrieve user book page
     *
     * @url GET /v1/user/books
     */
    public function getUserBookPage( $uid ){

        $_GET['userId'] = $uid;
        return $this->bookService->getFatchedBooks();
    }


    /**
     * Forks user books
     *
     * @url PUT /v1/books/$id/fork
     *
     */
    public function forkBook( $id, $uid ){
        return $this->bookService->forkBook($id, $uid);
    }


     public function init(){
        global $conn;
        $this->bookService = new BookService($conn);
    }

}
