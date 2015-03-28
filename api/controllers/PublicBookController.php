<?php

class PublicBookController
{
   
    /**
     * Gets the book by id 
     *
     * @url GET /v1/book/$id
     * @noAuth
     */
    public function getBook( $id , $uid = null)
    {
        global $bookService;
        $book =  $bookService->getFetchedBookById($id);
        $this->checkBookPermision($book, $uid);
        return $book;
    }


    /**
     * Gets the book by id 
     *
     * @url GET /v1/user/book/$id
     */
    public function getUserBook( $id , $uid)
    {
        global $bookService;
        $book =  $bookService->getFetchedBookById($id);
        $this->checkBookPermision($book, $uid);
        return $book;
    }

    /**
     * Gets fetched lecture
     *
     * @url GET /v1/book/$bookId/lecture/$lectureId
     * @noAuth
     */
    public function getFetchedLecture($bookId, $lectureId, $uid = null){
        global $bookService;
        $book = $bookService->getFetchedLectureId( $bookId, $lectureId );
        $this->checkBookPermision($book, $uid);
        return $book;
    }

    /**
     * Gets fetched lecture
     *
     * @url GET /v1/user/book/$bookId/lecture/$lectureId
     * 
     */
    public function getUserFetchedLecture($bookId, $lectureId, $uid){
        global $bookService;
        $book = $bookService->getFetchedLectureId( $bookId, $lectureId );
        $this->checkBookPermision($book, $uid);
        return $book;
    }

    /**
     * Create new book
     *
     * @url POST /v1/book
     */
    public function create( $data )
    {
        //print_r($data);exit;
        global $bookService;
        return $bookService->create($data);
    }


     /**
     * Update existing book
     *
     * @url PUT /v1/book/$id
     * @noAuth
     */
    public function update( $id, $data )
    {
        global $bookService;
        $bookService->update($data);
    }


    /**
     * DELETE book
     *
     * @url DELETE /v1/book/$id
     */
    public function delete( $id  )
    {
        global $bookService;
        $bookService->delete($id);
    }

     /**
     * Retrieve book page
     *
     * @url GET /v1/books
     * @noAuth
     */
    public function getBookPage(  ){
        global $bookService;
        $_GET['sharedOnly'] = true;
        return $bookService->getFatchedBooks();
    }

      /**
     * Retrieve user book page
     *
     * @url GET /v1/user/books
     */
    public function getUserBookPage( $uid ){
        global $bookService;
        $_GET['userId'] = $uid;
        return $bookService->getFatchedBooks();
    }


    private function checkBookPermision($book, $uid){
        if($book != null){
            if($book['is_shared'] == 0 && !isset($uid) || $book['is_shared'] == 0 && $book['user_id'] != $uid){
                throw new RestException(401, 'User has not permission to book [id='.$book['id'].']');
            }
        }
    }

   
}