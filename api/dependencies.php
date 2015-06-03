<?php
require_once 'log4php/Logger.php';
require_once 'config/log4php.php';
require_once 'inc/JWT.php';
require_once 'inc/conn.php';
require_once 'inc/fnc.php';

require_once 'inc/RestServer.php';

require_once 'services/BingTranslator.php';
require_once 'services/BaseService.php';
require_once 'services/WordService.php';
require_once 'services/LectureService.php';
require_once 'services/TagService.php';
require_once 'services/BookService.php';
require_once 'services/UserService.php';
require_once 'services/CommonService.php';
require_once 'services/StatisticService.php';
require_once 'services/IOService.php';
require_once 'services/ContactService.php';
require_once 'services/SyncService.php';


require_once 'controllers/BaseController.php';
require_once 'controllers/BookController.php';
require_once 'controllers/LectureController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/WordController.php';

require_once 'inc/StringUtils.php';
require_once 'inc/messageSource.php';
require_once 'inc/class.PHPMailer.php';
require_once 'inc/class.SMTP.php';
require_once 'inc/Text2Speach.php';

$drilConf = getConf($conn);
?>