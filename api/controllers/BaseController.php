<?php

class BaseController
{

  private $commonService;
  private $userService;

  /**
  * Get all languages
  *
  * @url GET /v1/languages
  * @noAuth
  */
   public function getAllLanguages(){
      return $this->commonService->getAllLanguages();
   }


  /**
  * Get all languages
  *
  * @url GET /v1/levels
  * @noAuth
  */
   public function getAllLang(){
      return $this->commonService->getAllLevels();
   }

  /**
  * Get all languages
  *
  * @url GET /v1/filter
  * @noAuth
  */
   public function getFormContols(){
      $data["levels"] = $this->commonService->getAllLevels();
      $data["languages"] = $this->commonService->getAllLanguages();
      $data["categories"] = $this->commonService->getCategories();
      return $data;
   }

  

  /**
  * Get all languages
  *
  * @url GET /v1/translate
  * @noAuth
  */
   public function translate(){
      global $drilConf;
      if(isset($_GET['from']) && isset($_GET['to']) && $_GET['text']){
        $bingTranslate = new BingTranslator($drilConf["dril_bing_client_id"], $drilConf["dril_bing_secret"]);
        $translation = $bingTranslate->getTranslation($_GET['from'], $_GET['to'], $_GET['text']);
        return array("result" => $translation);
      }
      return array("result" => '');
   }

   /**
  * Check if is given field uniqe
  *
  * @url POST /v1/check
  * @noAuth
  */
   public function checkValidity($data){
      $isUnique = false;
      $message = "Invalid value";
      if(isset($data->field) && isset($data->value)){
        switch ($data->field) {
          case 'email':
              $message = getMessage("errUserEmailUniqe");
            break;
          case 'login':
              $message = getMessage("errUserLoginUniqe");
            break;
          default:
            throw new RestException(400, $message);
        }
        $isUnique = $this->userService->isValueUniqe($data->field, $data->value);    
      }

      return array('isUnique' => $isUnique, 'errorMessage' => $isUnique ? "" : $message );
   }


  /**
  * Return generated file path
  *
  * @url GET /v1/tts
  * @noAuth
  */
   public function tts(){
      if(isset($_GET['text']) && isset($_GET['lang'])){
        $tts = new Text2Speach();
        $filePath = $tts->getFilePath($_GET['text'], $_GET['lang'] );
        return array( "path" => getDomain().$filePath);
      }
      throw new InvalidArgumentException('Nothing to speek');
   }



  /**
  * Log JS error
  *
  * @url POST /v1/errors
  * @noAuth
  */
  public function logError($data){
      $this->commonService->logError($data);
  }


   public function init(){
      global $conn;
      $this->commonService = new CommonService($conn);
      $this->userService = new UserService($conn);
   }






   	
}

?>