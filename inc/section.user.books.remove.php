<?php
	$userService = new UserService($conn);
	$book = $userService->getById($_GET['id']);
  	$notFound = (count($book) == 0 || $book[0]['id_user'] != $_SESSION['id']);
  	if($notFound){
		echo "<div class=\"err\">".getMessage("err404")."</div>";
	}else{
		$userService->removeBook($_GET['token']);
	}
?>