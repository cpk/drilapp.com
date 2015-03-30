
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

			"errUnexpected" => "An unexpected error has occurred."
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