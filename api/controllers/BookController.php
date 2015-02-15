<?php

class BookController
{
   
    /**
     * Gets the book by id 
     *
     * @url GET /book/$id
     */
    public function getBook($id = null)
    {
        global $bookService;
        return $bookService->getFetchedBookById($id);
    }

    /**
     * Saves a user to the database
     *
     * @url POST /users
     * @url PUT /users/$id
     */
    public function saveUser($id = null, $data)
    {
        // ... validate $data properties such as $data->username, $data->firstName, etc.
        // $data->id = $id;
        // $user = User::saveUser($data); // saving the user to the database
        $user = array("id" => $id, "name" => null);
        return $user; // returning the updated or newly created user object
    }
}