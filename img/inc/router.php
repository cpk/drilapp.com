<?php
   // print_r($meta);
        //echo linker(7, 1, $lang).'<br>'.  linker(8, 1, $lang);
    if($meta['fid'] == $meta['id_article']){
        include "section.home.php";
    }elseif($meta['id_article'] == 2){
         include "section.import.php";
    } elseif($meta['id_article'] == 3){
         include "section.disqus.php";
    }elseif($meta['id_article'] == 5){
         include "section.contact.php";
    }elseif($meta['id_article'] == 9){
         include "section.word.php";
    }elseif($meta['id_article'] == 16 || $meta['id_parent'] == 16){
         include "section.user.php";
    }elseif($meta['id_article'] == 11){
         include "section.fqa.php";
    }else{
        include "section.article.php";
    }
?>
