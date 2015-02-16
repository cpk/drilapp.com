<?php

class BookController
{
   
    /**
     * Gets the book by id 
     *
     * @url GET /book/$id
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
        return $bookService->update($id);
    }


    /**
     * DELETE book
     *
     * @url DELETE /book/$id
     */
    public function delete( $id  )
    {
        global $bookService;
        return $bookService->delete($id);
    }

   
}