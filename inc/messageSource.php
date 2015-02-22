
<?php
	
	function getMessage(){
		global $lang;
		$args = func_get_args();
		$argsSize = func_num_args();

		$messages["sk"] = array(
			"yes" => "Áno",
			"no" => "Nie",
			"login" => "Prihlásiť",
			"username" => "Prihlasovacie meno",
			"userpass" => "Prihlasovacie heslo",
			"rememberMe" => "Prihlásiť sa natvravalo na tomto PC",
			"nav" => "Možnosti",
			"noBooks" => "Zatial ste nepridali žiadne vlastné učebnice.",
			"edit" => "Spravovať",
			"view" => "zobraz",
			"delete" => "Zmazať",
			"save" => "Uložiť",
			"registration" => " - registrácia nového užívateľa",
			"registrationCreate" => "Vytvoriť účet",
			"addBook" => "Pridať učebnicu",

			"email" => "E-mail",
			"givenname" => "Vaše meno",
			"surname" => "Vaše priezvisko",
			"add" => "Pridať",
			"tipUseEnter" => "<b>TIP!</b> Pre uloženie slovíčok používajte klávesu ENTER.",

			"bookName" => "Názov učebnice",
			"bookAuthor" => "Autor",
			"bookLang" => "Jazyk",
			"bookLangQuestion" => "Jazyk otázka",
			"bookLangAnswer" => "Jazyk odpoveď",
			"bookNote" => "Poznámka autora",
			"bookLevel" => "Úroveň",
			"bookCount" => "Pč.",
			"bookDate" => "Dátum",
			"bookPublished" => "Zdieľaná",

			"usrProfile" => "Profil užívateľa {0}",
			"userPassChange" => "Zmena užívateľského hesla",
			"userPassOld" => "Súčasné heslo",
			"userPassNew" => "Nové heslo",
			"userPassNewConfirm" => "Potvrdenie hesla",
			"errUserPassMatch" => "Zadané nové hesla sa nezhodujú",
			"errUserOldPassMatch" => "Súčastné heslo sa nezhoduje",

			"errLoginLen" => "Uživateľské meno musí mať min. 6 znakov",
			"errLoginUniqe" => "Uživateľ s prihlasovacím menom <b>{0}</b>, sa už v systéme nachádza, zvolte si iné.",
			"errEmailUniqe" => "Uživateľ s emailovou adresou <b>{0}</b>, sa už v systéme nachádza.",
			"errEmailInvalid" => "Neplatná emailová adresa.",
			"errPassLen" => "Heslo musí mať min. 6 znakov",
			"errLoginChars" => "Login obsahuje nepovolené znaky.",
			"errDatabase" => "Vyskytol sa problém s databázou, skúste to znova neskôr.",
			"err404" => "<b>404</b> Položka neexistuje.",
			"errLogined" => "Boli ste odhlásený, prihláste sa prosím a operáciu zopakujte.",
			"errPerm" => "Nemáte oprávnenie pre vykonanie tejto akcie.",
			"errNoLang" => "Zvolte, prosím, jazyk otázky a odpovede.",
			"errBookName" => "Chybne vyplený názov učebnice. (min. 8 znakov)",
			"errNoXlsFile" => "Nahrávať je možné len XLS súbory.",
			"errData" => "Dokument obsahuje chybné, alebo žiadne dáta.",
			//We apologize, there was some problem with the database, try it again leater.

			"successRegistraged" => "Registrácia prebehla úspešne.",
			"confirmCardDel" => "Skutočne si prajete odstraniť kartičku?",
			"confirmBookDel" => "Skutočne si prajete odstraniť učebnicu?",

			"print" => "Vytlačiť",
			"exportPdf" => "Exportovať do PDF",
			"exportXls" => "Exportovať do Excelu",
			"exportCsv" => "Exportovať do CSV",
			"favorite" => "Oblúbena",
			"favoriteRemove0" => "Pridať do oblubených",
			"favoriteRemove1" => "Odstraniť z oblubených",
			"regEmailSubject" => "Registrácia",
			"successfullySaved" => "Zmeny boli úspešne uložené",
			"successDelete"  => "Učebnica bola úspešne odstránená",

			"xlsImport" => "Excel import",
			"importSuccess" => "Import prebehol úspešne, počet importovaných kartičiek: <b>{0}</b>", 
			"chooseFile" => "Vyberte XLS súbor",
			"regEmailBody" => "Dobrý deň, <br>boli ste úspešne registrovaný na portály <a href=\"http:www.drilapp.com\">www.drilapp.com</a>. Vaše prihlasovacie údaje sú nasledovné:<br><br><b>Prihlasovacie meno:</b>{0}<br><b>Prihlasovacie heslo:</b>{1}",
		);
		$messages["en"] = array(
			"yes" => "Yes",
			"no" => "No",
			"login" => "Login",
			"username" => "User name",
			"userpass" => "User password",
			"rememberMe" => "Remember me on this PC",
			"nav" => "Options",
			"noBooks" => "You have not any textbooks.",
			"edit" => "Edit",
			"view" => "view",
			"delete" => "Delete",
			"save" => "Save",
			"registration" => " - new user registration",
			"registrationCreate" => "Create an accout",
			"addBook" => "Create new textbook",

			"email" => "E-mail",
			"givenname" => "Your name",
			"surname" => "Your surname",
			"add" => "Add",
			"tipUseEnter" => "<b>TIP!</b> You can push the Enter to add word.",

			"bookName" => "Name of textbook",
			"bookAuthor" => "Author",
			"bookLang" => "Language",
			"bookLangQuestion" => "Lang of question",
			"bookLangAnswer" => "Lang of answer",
			"bookNote" => "Author's note",
			"bookLevel" => "Level",
			"bookCount" => "Count",
			"bookDate" => "Date",
			"bookPublished" => "Shared",

			"usrProfile" => "User profile: {0}",
			"userPassChange" => "Change user password",
			"userPassOld" => "Current password",
			"userPassNew" => "New password",
			"userPassNewConfirm" => "New pass. confirmation",
			"errUserPassMatch" => "New password do not match",
			"errUserOldPassMatch" => "Current password do not match",

			"errLoginLen" => "User login must be greater than 6 chars",
			"errLoginUniqe" => "User with login <b>{0}</b>, already exists",
			"errEmailUniqe" => "User with email <b>{0}</b>, already exists",
			"errEmailInvalid" => "Invalid e-mail address",
			"errPassLen" => "Password must be greater then 6 characters",
			"errLoginChars" => "Login contains invalid characters",
			"errDatabase" => "Some database error occured, try it again leater",
			"err404" => "<b>404</b> Not found",
			"errLogined" => "You have been logged out, please login and repeat the operation.",
			"errPerm" => "You do not have permission to perform this action.",
			"errNoLang" => "Please, select languages",
			"errBookName" => "Invalid name of book. Please, select length of book greater than 7 characters.",
			"errNoXlsFile" => "Only XLS/XLSX files can be uploaded",
			"errData" => "Dokument contains invalid or any data",

			"successRegistraged" => "Registration was successful.",
			"confirmCardDel" => "Are you sure that you want delete this card?",
			"confirmBookDel" => "Are you sure that you want delete this book?",

			"print" => "Print",
			"exportPdf" => "PDF Export",
			"exportXls" => "XLS Export",
			"exportCsv" => "CSV Export",
			"favorite" => "Favorite",
			"favoriteRemove0" => "Add to favorite",
			"favoriteRemove1" => "Remove from favorite",
			"successDelete"  => "Sucessfully deleted",

			"xlsImport" => "Excel import",
			"importSuccess" => "Import was successfully. Count of imported words: <b>{0}</b>",
			"chooseFile" => "Choose file", 
			"regEmailSubject" => "Registration",
			"successfullySaved" => "Changes were successfully saved",
			"regEmailBody" => "Hello, <br><br> you have been successfully registrated on <a href=\"http:www.drilapp.com\">www.drilapp.com</a>. Yours credentials are:<br><br><b>Login:</b>{0}<br><b>Password:</b>{1}",

		);

		$key = $args[0];
		$msg = "";
		if(!array_key_exists($key, $messages[$lang])){
			if(!array_key_exists($key, $messages["sk"])){
				return "";
			}
			$msg = $messages["sk"][$key];
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