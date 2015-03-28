<?php

require_once "../admin/config.php";
require_once "../admin/inc/class.Database.php";
require_once "../admin/inc/class.MysqlException.php";


$conn = Database::getInstance($config['db_server'], $config['db_user'], $config['db_pass'], $config['db_name']);