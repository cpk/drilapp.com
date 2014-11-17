<?php


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
				 	if(isset($_GET["token"])){
				 		include 'section.user.books.remove.php';
				 	}
				 	include 'section.user.books.php';
				 }
				break;
			case 18 :
					include 'section.user.edit.php';
				break;
			case 19 :
					include 'section.user.favorite.php';
				break;	
			default:
				# code...
				break;
		}
	}
	
	

?>