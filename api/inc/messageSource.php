
<?php
	
	function getMessage(){
		$lang = getLang();
		$args = func_get_args();
		$argsSize = func_num_args();

		$messages["sk"] = array(
			
		);
		$messages["en"] = array(
			"errBookUniqeName" => "You already have the book with name \"{0}\", choose different",
			"errBookShortName" => "The book name is too short.",
			"errBookLongName" => "The book name is too long. (max 200 characters)",
			"errBookLangs" => "Please choose language of question/answer.",
			"errBookLevel" => "Please choose level",
			"errBookDescr" => "The book description is too long. (max 250 characters)",

			"errLectureUniqeName" => "You already have lecture with name:  \"{0}\", choose different",
			"errLectureShortName" => "Name of the lecture is required",
			"errLectureLongName" => "Name of the lecture is too long. (max 150 characters)",
			"errLectureBookId" => "The book ID is missing.",

			"errUserEmailUniqe" => "The e-mail has already been taken",
			"errUserEmailLength" => "Alloved e-mail length is 45 characters",
			"errUserEmailInvalid" => "Invalid e-mail address",
			"errUserLoginUniqe" => "Login '{0}' has already been taken",
			"errUserLoginLength" => "Alloved login length is between 5 and 20 characters",
			"errUserFirstNameLength" => "Alloved fisrt name length is 30 characters",
			"errUserLastNameLength" => "Alloved fisrt name length is 30 characters",
			"errUserPasswordLength" => "The password is too short. It has to be at least 6 characters",
			"errUserPasswordMatch" => "The confirmation password does not match the password",
			"errUserLocaleEmpty" => "The user locale is required.",

			"errUserUnactivated" => "Your account has not been activated yet. Check your e-mail address.",	
			"errUnexpected" => "An unexpected error has occurred.",

			"activated" => "Your account has been activated",

			"errXlsFile" => "Invalid file. Alloved are only XLS, XLSX files.",	

			"errLecutreWordLimit" => "The lecture can contains max. {0} words. Create a new lecture and split the words.",
			"errWordLimit" => "The max number of words per account is {0} you currently have {1}.",

			"emailReg_head" => "Complete your registration",
			"emailReg_descr" => "Your account has been successfully created. Activate your account by clicking on following button",
			"emailReg_activate" => "Activate account", 
			"emailReg_ccopyUrl" => "Or copy following url and paste it into your browser" 



		);

		$key = $args[0];
		$msg = "";
		if(!array_key_exists($key, $messages[$lang])){
			if(!array_key_exists($key, $messages["en"])){
				return "";
			}
			$msg = $messages["en"][$key];
		}else{
			$msg = $messages[$lang][$key];
		}
		if($argsSize > 1){
			for ($i = 1; $i < $argsSize; $i++) {
				$msg = str_replace("{".($i-1)."}", $args[$i], $msg);
			}
		}
		return $msg;
	}

   function printMessage($key){
   		echo getMessage($key);
   }


?>