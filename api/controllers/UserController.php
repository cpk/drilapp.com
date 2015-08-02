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
        global $lang;
        $logger = Logger::getLogger('api');
        $logger->debug("login: " . $data->username);
        global $drilConf, $conn;
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
            $androidDeviceLogin = isset($data->deviceId);
            setLang( $user['localeCode'] );
            try {
                $token = array(
                    "iat" => time(),
                    "exp" => time() + ($androidDeviceLogin ?  31556926 : 10200),
                    "uid" => $user['id'],
                    "locale" => $lang
                );
                unset($user['pass']);
                unset($user['salt']);

                if($androidDeviceLogin){
                    $this->logAndroidDevice($data, $user['id']);
                    $syncService = new SyncService($conn);
                    $result = $syncService->sync($data, $user['id'], true);
                }
                $result['token'] = JWT::encode($token, $drilConf['dril_web_auth']);
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
   private function logAndroidDevice($data, $uid){
        global $conn;
        if(!isset($data->deviceName)){
            return false;
        }
        $res = $conn->select("SELECT count(*) FROM dril_device WHERE device_id = ? AND user_id = ?", array( $data->deviceId, $uid ));
        if($res[0]['count(*)'] == 0){
            $conn->insert("INSERT INTO `dril_device`(`device_id`, `user_id`,  `last_sync`, `name`) VALUES (?,?,NOW(),?)",
                    array($data->deviceId, $uid, $data->deviceName)
            );
        }
   }

    /**
     * Create a new user
     *
     * @url POST /v1/users
     * @noAuth
     */
    public function create( $data ) {
        $this->setLangByLocaleId($data->localeId);
        $user = $this->userService->create($data);
        $this->userService->sendRegistrationEmail($user);
        return array( "status" => "created");
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


    /**
     * Sends reset password email
     *
     * @url PUT /v1/user/sendResetPassEmail
     * @noAuth
     */
    public function sendResetPassEmail($data){
        $this->userService->generateForgottenPasswordToken($data->username);
    }

    /**
     * Resets user password email
     *
     * @url PUT /v1/user/resetPass
     * @noAuth
     */
    public function resetUserPassword($data){
        $this->userService->resetUserPassword($data);
    }


    /**
     * Toggle word / lecture / all words activatoin
     *
     * @url POST /v1/user/toggleActivation
     *
     */
    public function toggleActivation($data, $uid){
        if(!isset($data->type)){
            throw new  InvalidArgumentException("Bad request");
        }
        switch ($data->type) {
            case 'word':
                $book = $this->wordService->getBookByWordId( $data->id );
                checkBookPermision($book, $uid);
                return $this->wordService->activateWord( $data);
            case 'lecture':
                $book = $this->wordService->getBookByLectureId( $data->id );
                checkBookPermision($book, $uid);
                return $this->wordService->updateLectureActivity( $data );
            case 'all':
                $book = $this->wordService->deactiveAllUserWords($uid);
                return;
            default:
                throw new InvalidArgumentException("Unknow activation type: " . $data->type );
                break;
        }
    }

    private function setLangByLocaleId($localeId){
         global $conn;
        if(isset($localeId)){
            $res = $conn->select("SELECT `code` FROM `lang` WHERE `id_lang` = ?", array( $localeId ));
            if(count($res) == 1){
                setLang($res[0]['code']);
            }
        }
    }

    public function init(){
        global $conn;
        $this->userService = new UserService($conn);
        $this->wordService = new WordService($conn);
    }
}
