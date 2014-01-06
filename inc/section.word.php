<div id="article">
    <article class="fullscreen">
    <?php 
        
        
        
        $strings['sk']['captcha'] = "Nečitateľné? Vygenerovať iný.";  
        $strings['en']['captcha'] = "Not readable? Change text.";
        $strings['sk']['chooseLang'] = "-- Vyberte jazyk --";
        $strings['en']['chooseLang'] = "-- Choose language --";
        $strings['sk']['chooseLevel'] = "-- Vyberte úroveň --";  
        $strings['en']['chooseLevel'] = "-- Choose level --";  
        $strings['sk']['level'] = "Zobraziť úroveň:";  
        $strings['en']['level'] = "Only level:";  
        $strings['sk']['langDefault'] = "Výchozí jazyk:";  
        $strings['en']['langDefault'] = "Default language:";  
        $strings['sk']['langTarget'] = "Cieľový jazyk:";  
        $strings['en']['langTarget'] = "Target language:"; 
        $strings['sk']['author'] = "Vyhladať:";  
        $strings['en']['author'] = "Search:"; 
        $strings['sk']['order'] = "Zoradiť podľa:";  
        $strings['en']['order'] = "Order by:";  
        $strings['sk']['orderVals'] =  array( "Najnovších", "Najstarších", "Autora A-Z", "Autora Z-A", "Najstahovanejšie" );
        $strings['en']['orderVals'] =  array( "Newest", "Oldest", "Author A-Z", "Author Z-A", "Most download" );
        $strings['sk']['filter'] = "Filtrovať";
        $strings['en']['filter'] = "Filter";

        
        $_GET["lang_q"] = (isset($_GET["lang_q"]) ? (int)$_GET["lang_q"] : "");
        $_GET["lang_a"] = (isset($_GET["lang_a"]) ? (int)$_GET["lang_a"] : "");
        $_GET["level"] = (isset($_GET["level"]) ? (int)$_GET["level"] : "");


        $bookPrezenter = new BookPrezenter($conn);
        if(isset($_GET['id'])){
            $book = $bookPrezenter->getBook($_GET['id']);
            $words = $bookPrezenter->getBooksWords($book[0]['import_id']);
            echo '<h1>'.$bookName.'</h1>';
            echo '<div id="bc"><a href="'.linker(1, 1, $lang).'" >Home</a> &raquo; '.makeLinkByArticleId(9).' &raquo; <span>'.$bookName.'</span></div>';
            
           $count = count($words);
           $html =  '<table id="book">
                <tr>
                    <td class="bold">Autor učebnice / dátum:</td>
                    <td>'.(isset($book[0]['login']) ? $book[0]['login'] : $book[0]['author']). ' / '.date("d.m.Y H:i" ,strtotime($book[0]["create"])).'</td>
                </tr>
                <tr>
                     <td class="bold">Jazyk učebnice:</td>
                    <td>'.$book[0]['lang_question'].' / '.$book[0]['lang_answer'].'</td>    
                </tr>
                <tr>
                     <td class="bold">Úroveň náročnosti:</td>
                    <td>'.$book[0]['name'].'</td>    
                </tr>
                   <tr>
                     <td class="bold">Poznámka autora:</td>
                    <td>'.$book[0]['descr'].'</td>    
                </tr>
                   <tr>
                     <td class="bold">Počet kartičiek (slovíčok):</td>
                    <td><strong>'.$count.'</strong></td>    
                </tr>
             </table>'.'<div id="importId"><em>Import ID:</em>'.$book[0]['import_id'].'</div>';
            
           
           if($count > 0){
                $html .= '<h2 class="cst">Obsah učebnice '.$bookName.'</h2>'.
                            '<table id="words">';
                for($i = 0; $i < $count; $i++){
                    $html .= '<tr>
                                 <td class="no">'.($i+1).'</td>
                                 <td>'.$words[$i]['question'].'</td>
                                 <td>'.$words[$i]['answer'].'</td>
                              </tr>';
                }
                $html .= '</table>';
           }else{
               $html .= '<p class="alert">Učebnica neobsahuje žiadne kartičky.</p>';
           }
           echo $html;
           
        }else{
            $langs = $conn->select("SELECT * FROM `lang`");
            $article = getArticle("fullHidden", $meta['id_article'], $lang);  
            $pageContent = '<h1>'.$article[0]["title_${lang}"].'</h1>'.$article[0]["content_${lang}"];
            $pageContent .= '
                <form id="filter">
                    <div class="coll">
                        <div class="bx flang">
                            <label>'.$strings[$lang]["langDefault"].'</label>
                            <select name="lang_q">'.getLangsOptions(intval($_GET["lang_q"]) , $langs,  $strings, $lang).'</select>
                        </div>
                        <div class="bx flang">
                            <label>'.$strings[$lang]["langTarget"].'</label>
                            <select name="lang_a">'.getLangsOptions(intval($_GET["lang_a"]) , $langs,  $strings, $lang).'</select>
                        </div>
                    </div>

                    <div class="coll">
                        <div class="bx long">
                            <label>'.$strings[$lang]["level"].'</label>
                            <select class="fr" name="level">'.getLevelOptions($conn, $strings , $lang).'</select>
                            <div class="clear"></div>
                        </div>
                        <div class="bx long">
                            <label>'.$strings[$lang]['author'].'</label>
                            <input name="query" class="query fr" '.(isset($_GET["query"]) ? 'value="'.$_GET["query"].'"' : "").'  />
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="coll">
                        <div class="bx long">
                            <label>'.$strings[$lang]['order'].'</label>
                            <select class="fr" name="order">'.getOrderOpitons($strings[$lang]['orderVals']).'</select>
                            <div class="clear"></div> 
                        </div>
                        <div class="bx long">
                            <input type="submit" class="filter fr" value="'.$strings[$lang]['filter'].'" />
                            <div class="clear"></div> 
                        </div>
                    </div>
                    <div class="clear"></div>   
                </form>
            ';
            $pageContent .= $bookPrezenter->printBooks($_GET['s'], 15);
            echo $pageContent;
        }
        
        if($lang == "sk"){
            echo '<span class="author">Autor www.drilapp.com nie je zodpovedný za kvalitu a obsah učebníc.</span>';
        }else{
            echo '<span class="author">Author www.drilapp.com is not responsible for the content and quality of textbooks.</span>';
        }
            
          if(isset($_GET['id'])){
    ?>  
        <div id="comment"> 
        <div id="disqus_thread"></div>
        <script type="text/javascript">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'androiddril'; // required: replace example with your forum shortname

            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
        </div>
      <?php } ?>  
      <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
      <input type="hidden" name="is0" value="<?php printMessage("favoriteRemove0"); ?>" />
      <input type="hidden" name="is1" value="<?php printMessage("favoriteRemove1"); ?>" />
</article>
</div>    
<?php

function getLangsOptions($id , $langs,  $strings , $lang){
    $html = '<option value="0">'.$strings[$lang]["chooseLang"].'</option>';
    foreach ($langs as $val) {
        $html .= '<option value="'.$val["id_lang"].'" '.($val["id_lang"] == $id ? 'selected="selected"' : '').'>'.$val["name_${lang}"].'</option>';
    }

    return $html;
}
function getLevelOptions($conn, $strings , $lang){
    $levels = $conn->select("SELECT * FROM `level`");
    $html = '<option value="0">'.$strings[$lang]["chooseLevel"].'</option>';
    foreach ($levels as $val) {
        $html .= '<option value="'.$val["id_level"].'" '.($val["id_level"] == $_GET["level"] ? 'selected="selected"' : '').'>'.$val["name"].'</option>';
    }
    return $html;
}

function getOrderOpitons($orders){
    $selected = (isset($_GET["order"]) ? (int) $_GET["order"] : 0);
    $html = '';
    foreach ($orders as $key => $value) {
        $html .= '<option value="'.$key.'" '.
                    ($selected == $key ? 'selected="selected"' : '')
                    .'>'.$value.'</option>';
    }
    return $html;
}

?>