<?php

class UserController
{
   
   private $userService;    
   private $wordService;
   private $settingsService;

    /**
     * Login user
     *
     * @url PUT /v1/user/login
     * @noAuth
     */   
   public function login( $data ){
        global $drilConf;
        if(!isset($data) || !isset($data->username)){
            throw new RestException(401, 'Credentials are required.');
        }
        
        $user = $this->userService->getUserByLogin( $data->username );
        if($user == null){
            throw new RestException(401, 'Credentials are required.');
        }else if($user['active'] == 0){
            throw new InvalidArgumentException(getMessage("errUserUnactivated"));
        }
        if(hash_hmac('sha256', $data->password , $user['salt']) == $user['pass']){
            try {

                $token = array(
                   // "iss" => "http://www.drilapp.com",
                   // "aud" => "http://web.drilapp.com",
                    "iat" => time(),
                    "exp" => time() + 10200,
                    "uid" => $user['id']
                );
                unset($user['pass']);
                unset($user['salt']);
                $result['token'] = JWT::encode($token, $drilConf['dril_auth']);
                $result['user'] = $user;
                $logger = Logger::getLogger('api');
                $logger->info("User [id=" .$user['id']."] was successfully logged in. [ip=" .$_SERVER['SERVER_ADDR']."]");
               return $result;

            } catch(UnexpectedValueException $ex) { 
              throw new RestException(401, "Invalid security token " .$data->username);   
            }    
        }else{
            throw new RestException(401, "Bad username or password " .$data->username);   
        }

   }

    /**
     * Create a new user
     *
     * @url POST /v1/users
     * @noAuth
     */
    public function create( $data ) {
        $user = $this->userService->create($data);
        $this->userService->sendRegistrationEmail($user);
    }


    /**
     * Update user
     *
     * @url PUT /v1/users
     */
    public function update( $data ) {
        $this->userService->update( $data );
    }
    
    /**
     * Activate user account
     *
     * @url POST /v1/users/activate
     * @noAuth
     */
    public function activateAccount($data){
        return $user = $this->userService->activateAccount($data->token);
    }

    /**
     * Gets user statistics
     *
     * @url GET /v1/stats
     */
    public function getUserStatistics($uid){
        global $conn;
        $statisticService = new StatisticService($conn);
        $res['statistics'] = $statisticService->getUserStatistics($uid);
        $res['sessions'] = $statisticService->getUserSessions($uid);
        return $res;
    }


    public function init(){
        global $conn;
        $this->userService = new UserService($conn);
        $this->wordService = new WordService($conn);
    }
}   