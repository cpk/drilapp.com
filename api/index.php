<?php
session_start();

require_once 'conn.php';
require_once 'RestServer.php';
require_once 'services/WordService.php';
require_once 'services/LectureService.php';
require_once 'services/TagService.php';
require_once 'services/BookService.php';
require_once 'controllers/BookController.php';

$tagService = new TagService($conn);
$wordService = new WordService($conn);
$lectureService = new LectureService($conn, $wordService);
$bookService = new BookService($conn, $tagService, $lectureService);

$server = new RestServer('debug');
$server->addClass('BookController');

$server->handle();
