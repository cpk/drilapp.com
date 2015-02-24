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
        global $publicBookService;
        return $publicBookService->getFetchedBookById($id);
    }

    /**
     * Create new book
     *
     * @url POST /book
     */
    public function create( $data )
    {
        //print_r($data);exit;
        global $publicBookService;
        return $publicBookService->create($data);
    }


     /**
     * Update existing book
     *
     * @url PUT /book/$id
     */
    public function update( $id, $data )
    {
        global $publicBookService;
        $publicBookService->update($data);
    }


    /**
     * DELETE book
     *
     * @url DELETE /book/$id
     */
    public function delete( $id  )
    {
        global $publicBookService;
        $publicBookService->delete($id);
    }

   
}