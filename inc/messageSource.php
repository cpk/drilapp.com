
<?php
	
	function getMessage(){
		global $lang;
		$args = func_get_args();
		$argsSize = func_num_args();

		$messages["sk"] = array(
			"login" => "Prihlásiť",
			"username" => "Prihlasovacie meno",
			"userpass" => "Prihlasovacie heslo",
			"rememberMe" => "Prihlásiť sa natvravalo na tomto PC",
			"nav" => "Možnosti",
			"noBooks" => "Zatial ste nepridali žiadne vlastné učebnice.",
			"edit" => "Spravovať",
			"delete" => "Zmazať",
			"registration" => " - registrácia nového užívateľa",
			"registrationCreate" => "Vytvoriť účet",

			"email" => "E-mail",
			"givenname" => "Vaše meno",
			"surname" => "Vaše priezvisko",
			"add" => "Pridať",
			"tipUseEnter" => "<b>TIP!</b> Pre uloženie slovíčok používajte klávesu ENTER.",

			"errLoginLen" => "Uživateľské meno musí mať min. 3 znaky",
			"errLoginUniqe" => "Uživateľ s prihlasovacím menom <b>{0}</b>, sa už v systéme nachádza, zvolte si iné.",
			"errEmailUniqe" => "Uživateľ s emailovou adresou <b>{0}</b>, sa už v systéme nachádza.",
			"errEmailInvalid" => "Neplatná emailová adresa.",
			"errPassLen" => "Heslo musí mať min. 5 znakov",
			"errLoginChars" => "Login obsahuje nepovolené znaky.",
			"errDatabase" => "Vyskytol sa problém s databázou, skúste to znova neskôr.",
			"err404" => "<b>404</b> Položka neexistuje.",
			"errLogined" => "Boli ste odhlásený, prihláste sa prosím a operáciu zopakujte.",
			"errPerm" => "Nemáte oprávnenie pre vykonanie tejto akcie.",
			//We apologize, there was some problem with the database, try it again leater.

			"successRegistraged" => "Registrácia prebehla úspešne.",
			"confirmCardDel" => "Skutočne si prajete odstraniť kartičku?",

			"regEmailSubject" => "Registrácia",
			"regEmailBody" => "Dobrý deň, <br>boli ste úspešne registrovaný na portály <a href=\"http:www.drilapp.com\">www.drilapp.com</a>. Vašpe prihlasovacie údaje sú nasledovné:<br><br><b>Prihlasovacie meno:</b>{0}<br><b>Prihlasovacie heslo:</b>{1}",
		);

		$key = $args[0];
		$msg = "";
		if(!array_key_exists($key, $messages[$lang])){
			if(!array_key_exists($key, $messages["sk"])){
				return "";
			}
			$msg = $messages["sk"][$key];
		}
		$msg = $messages[$lang][$key];

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