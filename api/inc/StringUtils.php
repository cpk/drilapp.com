<?php

class StringUtils{

	public function getRandomString($len = 32){
		$string = md5(uniqid(rand(), true));
		if($len != 32){
			$string = substr($string, 0, $len);
		}
		return $string;
	}	
}