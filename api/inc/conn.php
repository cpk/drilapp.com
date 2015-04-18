<?php
require_once dirname(dirname(__FILE__)). "/config/config.php";
require_once "class.Database.php";
require_once "class.MysqlException.php";
$conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);