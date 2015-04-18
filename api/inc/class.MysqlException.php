<?php

class MysqlException extends Exception{

    public function __construct($code, $message, $query = null){
    	$logger = Logger::getLogger('database');
    	$logger->error("MySQL error: ".$message." [ip=" .$_SERVER['REMOTE_ADDR']."]", $this);
        parent::__construct( $message, $code);
    } 
}

?>