<?php

class UserController
{
   
    /**
     * Login user
     *
     * @url POST /v1/user/login
     * @noAuth
     */   
   public function login( $data ){
        global $userService;    
        if(!isset($data) || !isset($data->username)){
            throw new RestException(401, 'Credentials are required.');
        }
        $logger = Logger::getLogger('api');
        $user = $userService->getUserByLogin( $data->username );
        if($user == null){
            $logger->debug("User was not found for usename " .$data->username );
            throw new RestException(401, 'User not found');
        }
        if(hash_hmac('sha256', $data->password , $user['salt']) == $user['pass']){
            try {
                $key = "example_key";
                $token = array(
                   // "iss" => "http://www.drilapp.com",
                   // "aud" => "http://web.drilapp.com",
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
              $logger->warn("Invalid security token " .$ex->getMessage() );  
              throw new RestException(401, 'Invalid security token');   
            }    
        }else{
            $logger->warn("Bad username or password " .$data->username );  
            throw new RestException(401, 'Bad username or password');   
        }

   }

    /**
     * Create new book
     *
     * @url POST /v1/users
     * @noAuth
     */
    public function create( $data )
    {
        //print_r($data);exit;
        global $userService;
        return $userService->create($data);
    }
   
}