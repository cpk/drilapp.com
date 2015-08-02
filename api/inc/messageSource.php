
<?php

	function getMessage(){
		$lang = getLang();
		$args = func_get_args();
		$argsSize = func_num_args();

		$messages["cs"] = array(
			"errBookUniqeName" => "Učebnice s názvem \"{0}\"se již ve Vašem drilu nachází",
			"errBookShortName" => "Název učebnice musí být alespoň 8 znaků dlouhý",
			"errBookLongName" => "Název učebnice je příliš dlouhý (max 200 znaků)",
			"errBookLangs" => "Prosím, vyberte jazyky",
			"errBookLevel" => "Prosím, vyberte úroveň",
			"errBookDescr" => "Popis učebnice je příliš dlouhý (max. 200 znaků)",

			"errLectureUniqeName" => "Lekce s názvem \"{0}\"se již v učebnici nachází",
			"errLectureShortName" => "Vyplňte název lekce",
			"errLectureLongName" => "Název lekce je příliš dlouhý (max 150 znaků)",
			"errLectureBookId" => "Identifikátor učebnice je vyžadován",

			"errUserEmailUniqe" => "Zadaný e-mail se už v databázi nachází",
			"errUserEmailLength" => "Zadaný e-mail je příliš dlouhý (max 45 znaků)",
			"errUserEmailInvalid" => "Neplatná emailová adresa",
			"errUserLoginUniqe" => "Zadané přihlašovací jméno '{0}' se již používá",
			"errUserLoginLength" => "Povolná délka přihlašovacího jména je 5 až 20 znaků",
			"errUserFirstNameLength" => "Maximální délka jména je 30 znaků",
			"errUserLastNameLength" => "Maximální délka příjmení je 30 znaků",
			"errUserPasswordLength" => "Heslo je příliš krátké, musí mít min. 6 znaků",
			"errUserPasswordMatch" => "Zadaná hesla se neshodují",
			"errUserLocaleEmpty" => "Prosím zvolte Váš jazyk",
			"errFork" => "Nemáte dostatečná oprávnění",
			"errForkExists" => "Učebnice již byla do Vašich učebních přidána",

			"errUserUnactivated" => "Váš účet zatím nebyl aktivován, zkontrolujte Vaši e-mailovou schárnku",
			"errUnexpected" => "Nastala neočekávaná chyba, operaci zopakujte později",

			"activated" => "Váš učet byl úspěšně aktivován",

			"errXlsFile" => "Neplatný soubor. Nahrávat lze pouze soubory s koncovkou xls a xlsx",
			"errXlsFileEmpty" => "Soubor je prázdný",

			"errLecutreWordLimit" => "Lekce může obsahovat maximálně {0} slovíček. Vytvořte novou lekci a rozdělte slovíčka",
			"errWordLimit" => "Maximální povolený počet slovíček na Vašem účtu je {0} momentálně je evidováno {1}.",

			"emailReg_head" => "Dokončení registrace",
			"emailReg_descr" => "Váš účet byl úspěšně vytvořen. Dokončete registraci kliknutím na následující tlačítko",
			"emailReg_activate" => "Aktivace účtu",
			"emailReg_ccopyUrl" => "nebo vložte následující odkaz do Vašeho webového prohlížeče",

			"emailPass_head" => "Zapomenuté heslo",
			"emailPass_descr" => "Vaše heslo si můžete resetovat po kliknutí na následující tlačítko",
			"emailPass_activate" => "Resetovat heslo",
			"emailPass_ccopyUrl" => "nebo zkopírujte následující odkaz do vašeho prohlížeče"
		 );
		$messages["sk"] = array(
			"errBookUniqeName" => "Učebnica s názvom \"{0}\" sa už vo Vašom drile nachádza",
			"errBookShortName" => "Názov učebnice musí byť aspoň 8 znakov dlhý",
			"errBookLongName" => "Názov učebnice je príliš dlhý (max 200 znakov)",
			"errBookLangs" => "Prosím, zvoľte jazyky",
			"errBookLevel" => "Prosím, zvoľte úroveň",
			"errBookDescr" => "Popis učebnice je príliš dlhý (max. 200 znakov)",

			"errLectureUniqeName" => "Lekcia s názvom \"{0}\" sa už v učebnici nachádza",
			"errLectureShortName" => "Vyplnte názov lekcie",
			"errLectureLongName" => "Názov lekcie je príliš dlhý (max 150 znakov)",
			"errLectureBookId" => "Identifikátor učebnice je vyžadovaný",

			"errUserEmailUniqe" => "Zadaný e-mail sa už v databáze nachádza",
			"errUserEmailLength" => "Zadaný e-mail je príliš dlhý (max 45 znakov)",
			"errUserEmailInvalid" => "Neplatná emailová adresa",
			"errUserLoginUniqe" => "Zadané prihlasovacie meno '{0}' sa už používa",
			"errUserLoginLength" => "Povolná dĺžka prihlasovacieho mena je 5 až 20 znakov",
			"errUserFirstNameLength" => "Maximálna dĺžka mena je 30 znakov",
			"errUserLastNameLength" => "Maximálna dĺžka priezviska je 30 znakov",
			"errUserPasswordLength" => "Heslo je príliš krátke, musí mať min. 6 znakov",
			"errUserPasswordMatch" => "Zadané heslá sa nezhodujú",
			"errUserLocaleEmpty" => "Prosím zvoľte Váš jazyk",
			"errFork" => "Nemáte dostatočné oprávnenie",
			"errForkExists" => "Učebnica už bola do Vaších učebních pridaná",

			"errUserUnactivated" => "Váš účet zatial nebol aktivovaný, skontrolujte Vášu e-mailovú schárnku",
			"errUnexpected" => "Nastala neočakávana chyba, operáciu zopakujte neskôr",

			"activated" => "Váš učet bol úspešne aktivovaný",

			"errXlsFile" => "Neplatný súbor. Nahrávať je možné len súbory s koncovkou xls a xlsx",
			"errXlsFileEmpty" => "Súbor je prázdny",

			"errLecutreWordLimit" => "Lekcia môže obsahovať maximálne {0} slovíčok. Vytvorte novú lekciu a rozdelte slovíčka",
			"errWordLimit" => "Maximálný povolený počet slovíčok na Vašom účte je {0} momentálne je evidovaných {1}.",

			"emailReg_head" => "Dokončenie registrácie",
			"emailReg_descr" => "Váš účet bol úspešne vytvorený. Dokončite registráciu kliknutím na nasledujúce tlačítko",
			"emailReg_activate" => "Aktivácia účtu",
			"emailReg_ccopyUrl" => "alebo vložte nasledujúci odkaz do Vášho webového prehliadača",

			"emailPass_head" => "Zabudnuté heslo",
			"emailPass_descr" => "Môžete resetovať aktuálne heslo po kliknutí na nasledujúce tlačidlo",
			"emailPass_activate" => "Resetovať heslo",
			"emailPass_ccopyUrl" => "Alebo skopírujte nasledujúci odaz do vášho prehliadača"
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
			"errFork" => "You are not authorized to fork this book",
			"errForkExists" => "The book was already forked",

			"errUserNotFound" => "User with given e-mail address was not found",
			"errTokenNotFound" => "The token expired",

			"activated" => "Your account has been activated",

			"errXlsFile" => "Invalid file. Alloved are only XLS, XLSX files.",
			"errXlsFileEmpty" => "Nothing to import. Check format and content of the file",

			"errLecutreWordLimit" => "The lecture can contains max. {0} words. Create a new lecture and split the words.",
			"errWordLimit" => "The max number of words per account is {0} you currently have {1}.",

			"emailReg_head" => "Complete your registration",
			"emailReg_descr" => "Your account has been successfully created. Activate your account by clicking on the following button",
			"emailReg_activate" => "Activate account",
			"emailReg_ccopyUrl" => "Or copy following url and paste it into your browser",

			"emailPass_head" => "Forgotten password",
			"emailPass_descr" => "You can reset your current password after clicking on the following button",
			"emailPass_activate" => "Reset password",
			"emailPass_ccopyUrl" => "Or copy following url and paste it into your browser"

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
