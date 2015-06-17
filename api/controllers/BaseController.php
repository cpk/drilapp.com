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
  * Search in tags
  *
  * @url GET /v1/tags
  * @noAuth
  */
   public function searchInTags(){
      global $conn;
      $tagList = array();
      if(isset($_GET['term']) ){
          if(!isset($_GET['localeId'])){
            // 1 == English
            $localeId = 1; 
          }else{
            $localeId = intval($_GET['localeId']);
          }
        $tagService = new TagService($conn);  
        $tagList = $tagService->findTags($_GET['term'], $localeId);
        
      }
      return $tagList;
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


  /**
  * Save report
  *
  * @url POST /v1/contact
  * @noAuth
  */
  public function contact($data, $uid = null){
    global $conn;
    $user = null;
    if($uid != null){
      $user = $this->userService->getUserById($uid);
    }

    $contactService = new ContactService($conn);
    $contactService->saveReport($data, $user);
  }


   /**
  * Sync
  *
  * @url POST /v1/sync
  */
  public function sync($data, $uid ){
    global $conn;
    $syncService = new SyncService($conn);
    return $syncService->sync($data, $uid);
  }


   public function init(){
      global $conn;
      $this->commonService = new CommonService($conn);
      $this->userService = new UserService($conn);
   }






   	
}

?>