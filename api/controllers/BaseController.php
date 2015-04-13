<?php

class BaseController
{

  /**
  * Get all languages
  *
  * @url GET /v1/languages
  * @noAuth
  */
   public function getAllLanguages(){
        global $commonService;
        return $commonService->getAllLanguages();
   }


  /**
  * Get all languages
  *
  * @url GET /v1/levels
  * @noAuth
  */
   public function getAllLang(){
        global $commonService;
        return $commonService->getAllLevels();
   }

   /**
  * Get all languages
  *
  * @url GET /v1/filter
  * @noAuth
  */
   public function getFormContols(){
      global $commonService;
      $data["levels"] = $commonService->getAllLevels();
      $data["languages"] = $commonService->getAllLanguages();
      $data["categories"] = $commonService->getCategories();
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





   	
}

?>