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
      return $data;
   }
   	
}

?>