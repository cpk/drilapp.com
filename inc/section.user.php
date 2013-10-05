<?php
	

	$auth = new Authenticate($conn);

	// logout
	if($meta['id_article'] == 20){
		$auth->logout();
	}


	if(!$auth->isLogined()){
		if(isset($_GET["action"]) && $_GET["action"] == "registration"){
			include 'section.user.registration.php';
		}else{
			include 'section.user.login.php';
		}
	}else{
		switch ($meta['id_article']) {
			case 16:
			case 17:
				 if(isset($_GET["book"])){
				 	include 'section.user.book.edit.php';
				 }else{
				 	include 'section.user.books.php';
				 }
				break;
			
			default:
				# code...
				break;
		}
	}
	
	

?>