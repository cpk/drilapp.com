<?php 
$article = getArticle("fullHidden", 1, $lang);  
echo $article[0]["content_${lang}"];


?>
  <!-- MOBILE SLIDER -->
<div id="mobile">
    <div id="slider">
       <ul> <?php echo printSlidesImages(1, 210, 372, ""); ?></ul>
    </div>
</div>