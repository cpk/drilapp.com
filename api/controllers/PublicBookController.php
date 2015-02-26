<?php

class PublicBookController
{
   
    /**
     * Gets the book by id 
     *
     * @url GET /book/$id
     * @noAuth
     */
    public function getBook( $id )
    {
        global $bookService;
        return $bookService->getFetchedBookById($id);
    }

    /**
     * Create new book
     *
     * @url POST /book
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
     * @url PUT /book/$id
     */
    public function update( $id, $data )
    {
        global $bookService;
        $bookService->update($data);
    }


    /**
     * DELETE book
     *
     * @url DELETE /book/$id
     */
    public function delete( $id  )
    {
        global $bookService;
        $bookService->delete($id);
    }

     /**
     * Retrieve book page
     *
     * @url GET /books
     * @noAuth
     */
    public function getBookPage(  ){
        global $bookService;
        return $bookService->getFatchedBooks( array() );
    }

   
}