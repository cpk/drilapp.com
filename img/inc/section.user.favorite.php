<div id="article">
	<article class="user-section fullscreen">
		<?php 
			$parent = getArticle("basic", $meta["id_parent"], $lang);
		?>

		<h1><?php echo $parent[0]["title_$lang"] . ' - '. $meta["title_$lang"] ?></h1>


		<div class="user-nav gradientGray">
			<?php include 'section.user.nav.php'; ?>
		</div>
		<div class="user-content">
			<?php
			
			if(isset($_SESSION["status"]) && $_SESSION["status"]){
				echo '<p class="ok">'.getMessage("successRegistraged").'</p>'; 
				unset($_SESSION["status"]); 
			}
			

			$BookPrezenter = new BookPrezenter($conn);
			$pageContent = $BookPrezenter->printUserFavoriteBooks(intval($_SESSION["id"]), intval($_GET['s']), 20);
            echo $pageContent;
			?>
		</div>
		<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
		<div class="clear"></div>
	</article>
</div>