<?php
	$config["version"]		= "3.0.0";
	$config["deployed"]		= "";
	$config["adminPagi"]	= 10; // strankovanie articles
	$config["galleryPagi"]	= 15; // strankovanie galeria

	$config["db_server"]	= "localhost";
	$config["db_user"]		= "root";
	$config["db_pass"]		= "";
	$config["db_name"]		= "db_drilapp_com";


	$config["ga_token"]		= "1/zJiDAON3Qq_KhAgamwHckEAlVjuS466PHe5Ii6f8AVQ";
	$config["ga_profile"]	= "ga:66126284";

	$config["article_langs"]	= "sk,en";
	$config['root_dir'] = dirname(__FILE__) ;

	$config['JWT_KEY'] = "asdfasdfazxfgsdgsdfg";

	$config['mailHost'] = "mail.drilapp.com";
	$config['mailUsername'] = "info@drilapp.com";
	$config['mailPass'] = "Uauv3Uh5Hcym38G";
	$config['mailFromEmail'] = "info@drilapp.com";
	$config['mailFromLabel'] = "Dril";

	define("LECTURE_WORD_LIMIT", 300);
	define("UNLIMITED", -1);
	define('CLIENT_URL', 'http://localhost:9000');
	define('MODE', 'debug');
	define('ERROR_REPORTING', 'E_ALL');

	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set("log_errors", "1");
	ini_set("error_log", "php-errors.log");
?>
