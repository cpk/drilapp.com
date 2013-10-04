<div id="article">
	<article class="user-section fullscreen">
		<?php 
			$parent = getArticle("basic", $meta["id_parent"], $lang);
		?>

		<h1><?php echo $parent[0]["title_$lang"]?></h1>


		<div class="user-nav">
			<?php
				include 'section.user.nav.php';
			?>
		</div>
		<div class="user-content">

		</div>
	</article>
</div>