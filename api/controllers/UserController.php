<?php

class UserController
{
   
    /**
     * Login user
     *
     * @url POST /user/login
     */   
   public function login( $data ){
        global $userService;    
        $secret = "abcd123456";
        $user = $userService->getUserByLogin( $data->login );
        if($user == null){
            throw new RestException(404, 'User not found');
        }
        if(hash_hmac('sha256', $data->password , $user['salt']) == $user['pass']){
            try {
                //$requestHeaders = apache_request_headers();
                //$authorizationHeader = $requestHeaders['AUTHORIZATION'];
                //$token = str_replace('Bearer ', '', $authorizationHeader);    
                //$decoded_token = JWT::decode($token, base64_decode(strtr($secret, '-_', '+/')) );
                $token = JWT::sign($data->login , $secret);
                //print_r($token);
               return $token;
            } catch(UnexpectedValueException $ex) {
              throw new RestException(401, 'Invalid security token');   
            }    
        }else{
            throw new RestException(401, 'Bad username or password');   
        }

   }

   private function createToken(){

   }

    /**
     * Gets the user by id 
     *
     * @url GET /users/$id
     */
    public function getUserById( $id )
    {
       
        //return $userService->getUserById( $id );
    }

    /**
     * Create new book
     *
     * @url POST /users
     */
    public function create( $data )
    {
        //print_r($data);exit;
        global $userService;
        return $userService->create($data);
    }


    

   
}