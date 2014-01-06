<?php
	$userNav = printMenu(16, "user-nav-item", false);
	echo '<ul><li class="label">'.getMessage("nav").':</li>'.
		 '<li '.($meta["id_article"] == 2 ? ' class="curr" ' : '').' ><a href="'.linker(2, 1, $lang).'">'.getMessage("addBook").'</a></li>'.
		 $userNav.'</ul>';
?>