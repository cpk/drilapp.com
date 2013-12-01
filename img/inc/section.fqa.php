<div id="article">
<article>
    
<?php 

$article = getArticle("fullHidden", $meta['id_article'], $lang);  
//echo $article[0]["content_${lang}"];

echo '<h1>'.$article[0]["title_${lang}"].'</h1>'.$article[0]["content_${lang}"];
$questions = getArticle("categ", $meta['id_article'], $lang);
$html = '<ol class="fqa">';

foreach($questions as $q){
	$html .= '<li><strong> '.makeLinkByArticleId( $q['id_article'] ).'</strong></li>';
}
echo $html.'</ol>';
?>
</article>
</div>    

  <!-- MOBILE SLIDER -->
<div id="mobile">
    <div id="slider">
       <ul> <?php echo printSlidesImages(1, 210, 372, ""); ?></ul>
    </div>
</div>