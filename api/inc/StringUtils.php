<?php

class StringUtils{


	public function getRandomString($len = 32){
		$string = md5(uniqid(rand(), true));
		if($len != 32){
			$string = substr($string, 0, $len);
		}
		return $string;
	}	

	public static function clear($val, $keepExt = false){
		if($keepExt){
			$ext = substr($val, strrpos($val, '.') + 1);
			$val = preg_replace('/\\.[^.\\s]{3,4}$/', '', $val);
		}	
	    $val = preg_replace('~[^\\pL0-9_]+~u', '-', $val);
	    $val = trim($val, "-");
	    $val = iconv("utf-8", "us-ascii//TRANSLIT", $val);
	    $val = strtolower($val);
	    $val = preg_replace('~[^-a-z0-9_]+~', '', $val);
	    return $val. ($keepExt ? ".".$ext : "") ;
	}
}