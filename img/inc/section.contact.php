<div id="article" >
<article class="contact">
    
    <?php 

    $article = getArticle("fullHidden", $meta['id_article'], $lang);  
    //echo $article[0]["content_${lang}"];

    echo '<h1>'.$article[0]["title_${lang}"].'</h1>'.$article[0]["content_${lang}"];
    ?>
    
</article>
    
    
</div>    

 

  <!-- Facebook plugin -->
<div id="fb-box">
    <div id="fb-root"></div>
    <script>
       (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=100757916674278";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
    
    <div class="fb-like-box" data-href="https://www.facebook.com/drilAnglictinaEfektivne" 
       data-width="292" data-height="530" data-show-faces="true" data-border-color="#5dae62" data-stream="true" data-header="true"></div>
</div>
  
  