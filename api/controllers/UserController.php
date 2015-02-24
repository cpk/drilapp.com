<?php

class UserController
{
   
    /**
     * Login user
     *
     * @url POST /user/login
     * @noAuth
     */   
   public function login( $data ){
        global $userService;    
        if(!isset($data) || !isset($data->username)){
             throw new RestException(404, 'Credentials are required.');
        }
        $user = $userService->getUserByLogin( $data->username );
        if($user == null){
            throw new RestException(404, 'User not found');
        }
        if(hash_hmac('sha256', $data->password , $user['salt']) == $user['pass']){
            try {
                $key = "example_key";
                $token = array(
                    "iss" => "http://www.drilapp.com",
                    "aud" => "http://web.drilapp.com",
                    "iat" => time(),
                    "exp" => time() + 3600,
                    "uid" => $user['id_user']
                );
                unset($user['pass']);
                unset($user['salt']);
                $result['token'] = JWT::encode($token, $key);
                $result['user'] = $user;
               return $result;

            } catch(UnexpectedValueException $ex) {
              throw new RestException(401, 'Invalid security token');   
            }    
        }else{
            throw new RestException(401, 'Bad username or password');   
        }

   }

    /**
     * Create new book
     *
     * @url POST /users
     * @noAuth
     */
    public function create( $data )
    {
        //print_r($data);exit;
        global $userService;
        return $userService->create($data);
    }


    

   
}