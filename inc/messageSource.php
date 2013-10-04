
<?php
	
	function getMessage($key){
		global $lang;
		
		$messages["sk"] = array(
			"login" => "Prihlásiť",
			"username" => "Prihlasovacie meno",
			"userpass" => "Prihlasovacie heslo",
			"rememberMe" => "Prihlásiť sa natvravalo na tomto PC",
			"nav" => "Navigácia &nabla;",

		);

		if(!array_key_exists($key, $messages[$lang])){
			if(!array_key_exists($key, $messages["sk"])){
				return "";
			}
			return $messages["sk"][$key];
		}
		return $messages[$lang][$key];
	}

   function printMessage($key){
   		echo getMessage($key);
   }


?>