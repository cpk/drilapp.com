<?php
require_once 'dependencies.php';

error_reporting(ERROR_REPORTING);

$server = new RestServer(MODE);
$server->addClass('LectureController');
$server->addClass('BookController');
$server->addClass('UserController');
$server->addClass('BaseController');
$server->addClass('WordController');

$server->handle();
