<?php
	$userNav = printMenu(16, "user-nav-item", false);
	echo '<ul><li class="label">'.getMessage("nav").':</li>'.
		 '<li '.($meta["id_article"] == 2 ? ' class="curr" ' : '').' ><a target="_blank" href="http://web.drilapp.com/login">'.getMessage("addBook").'</a></li>'.
		 $userNav.'</ul>';
?>