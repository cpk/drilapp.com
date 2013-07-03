<div id="article">
    <article class="fullscreen">
    
<?php 
 
  
  
$strings['en']['captcha'] = "Not readable? Change text.";
$strings['sk']['captcha'] = "Nečitateľné? Vygenerovať iný.";
$strings['sk']['submit'] = "Dokončiť a vygenerovať identifikátor";
$strings['en']['submit'] = "Submit and generate your ID";
$strings["sk"]['captchalabel'] = "Opíšte text obrázka";
$strings["en"]['captchalabel'] = "Rewrite text";
$strings["sk"]['email'] = '<span class="einfo">Ak zadáte Váš email a nazdielate slovíčka, v budúcnosti budete mať možnosť spätnej editácie.</span> Odoslať identifikátor na e-mail <em>(nepovinné)</em>';
$strings["en"]['email'] = 'Send ID to email address. <em>(optional)</em>';
$strings["sk"]['book'] = '<em style="color:red">*</em> <strong>Názov učebnice:</strong> ';
$strings["en"]['book'] = '<em style="color:red">*</em> <strong>Book name</strong>:';
$strings["sk"]['author'] = 'Vaše meno/nick:';
$strings["en"]['author'] = 'Your name (optional):';
$strings["sk"]['lang'] = 'Jazyk otázka:';
$strings["en"]['lang'] = 'Language of question:';
$strings["sk"]['lang2'] = 'Jazyk odpoveď:';
$strings["en"]['lang2'] = 'Language of answer:';
$strings["sk"]['level'] = 'Jazyková úroveň:';
$strings["en"]['level'] = 'Choose level:';
$strings["sk"]['share'] = 'Zdielať slovíčka:';
$strings["en"]['share'] = 'Share words:';
$strings["sk"]['note'] = 'Poznámka k slovíčkam:';
$strings["en"]['note'] = 'Your note:';
$strings["sk"]['share_btn'] = 'Chcete umožniť stiahnuť slovíčka aj ostatným užívateľom?';
$strings["en"]['share_btn'] = 'Do you want share your words to other?';
$strings["sk"]['answer'] = 'Odpoveď';
$strings["en"]['answer'] = 'Answer';
$strings["sk"]['question'] = 'Otázka';
$strings["en"]['question'] = 'Question';

$article = getArticle("fullHidden", $meta['id_article'], $lang);  
//echo $article[0]["content_${lang}"];

echo '<h1>'.$article[0]["title_${lang}"].'</h1>'.$article[0]["content_${lang}"];

?>  
        <form class="ajax">
            <span class="nol">Pč.</span><span class="ql"><?php echo $strings[$lang]['question']; ?></span><span class="al"><?php echo $strings[$lang]['answer']; ?></span>
        <table id="table">
            <tr>
                <td class="w50"><span class="no">1</span></td>
                <td><input type="text" name="question[]" class="focus" maxlength="255" /></td>
                <td><input type="text" name="answer[]"  maxlength="255" /></td>
                <td class="w50"></td>
            </tr>
            
        </table>
            <div id="share">
                <a href="#" id="share-btn" title="<?php echo $strings[$lang]['share_btn']; ?>">
                    <?php echo $strings[$lang]['share_btn']; ?>
                </a>
                
                <div id="share-form">
                    <span class="row">
                        <span><?php echo $strings[$lang]['book']; ?></span><input type="text" name="name" maxlength="50" class="w250" />
                        <div class="clear"></div>
                    </span>
                     <span class="row">
                        <span><?php echo $strings[$lang]['author']; ?></span><input type="text" name="author" maxlength="50" class="w250" />
                        <div class="clear"></div>
                    </span>
                    <span class="row">
                        <span><?php echo $strings[$lang]['lang']; ?></span><select name="lang2" class="w250"><?php echo getOptions( $conn, "lang", "name_${lang}" ); ?></select>
                        <div class="clear"></div>
                    </span>
                    <span class="row">
                        <span><?php echo $strings[$lang]['lang2']; ?></span><select name="lang_a" class="w250"><?php echo getOptions( $conn, "lang", "name_${lang}" ); ?></select>
                        <div class="clear"></div>
                    </span>
                     <span class="row">
                        <span><?php echo $strings[$lang]['level']; ?></span><select name="level" class="w250"><?php echo getOptions( $conn, "level", "name" ); ?></select>
                        <div class="clear"></div>
                    </span>
                    <span class="row">
                        <span><?php echo $strings[$lang]['note']; ?></span><textarea name="descr" rows="10" cols="50"></textarea>
                        <div class="clear"></div>
                    </span>
                    <span class="row">
                        <span><?php echo $strings[$lang]['share']; ?> </span><input type="checkbox" name="share" />
                        <div class="clear"></div>
                    </span>
                </div>
                
            </div>
            <div class="email">
                <span><?php echo $strings[$lang]['email']?>: </span><input type="text" name="email" maxlength="50" />
            </div>
            <a id="importMini" href="/img/import.png" class="lightbox" title="Návod">
                <img  src="/img/import_mini.png" alt="Nnávod" />
            </a>
            <input type="submit" value="<?php echo $strings[$lang]['submit'] ?>" class="btn" />
            <label><?php echo $strings[$lang]['captchalabel'] ?>
                <input type="text" maxlength="15" class="captcha w100" name="captcha"  /></label>
           
            <div id="captcha-box">
            <img src="../captcha.php" id="captcha" /><br/>
            <!-- CHANGE TEXT LINK -->
<a href="#" onclick="
    document.getElementById('captcha').src='../captcha.php?'+Math.random();return false;" id="change-image"><?php echo $strings[$lang]['captcha'] ?></a>
            
            
            </div>
            <input type="hidden" value="<?php echo $lang; ?>" name="lang" />
            <div class="clear"></div>
        </form>
        <img id="infoImport" src="/img/import.png" alt="Dril - angličtina / nemčina efektívne" />
</article>
</div>    
