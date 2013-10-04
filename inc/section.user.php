<?php
	

	$auth = new Authenticate($conn);

	if(!$auth->isLogined()){
		include 'section.user.login.php';
	}else{
		switch ($meta['id_article']) {
			case 16:
			case 17:
				 include 'section.user.books.php';
				break;
			
			default:
				# code...
				break;
		}
	}
	
	

?>