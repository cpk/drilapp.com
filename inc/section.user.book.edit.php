<?php
	$userService = new UserService($conn);
	$book = $userService->getById($_GET['book']);
  $notFound = (count($book) == 0 || $book[0]['id_user'] != $_SESSION['id']);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div id="article">
	<article class="user-section fullscreen">
		<?php 
			$parent = getArticle("basic", $meta["id_parent"], $lang);

			if($notFound){
				echo "<div class=\"err\">".getMessage("err404")."</div>";
			}else{
		?>

		<h1><?php echo $parent[0]["title_$lang"]?></h1>


		<div class="user-nav gradientGray">
			<?php include 'section.user.nav.php'; ?>
		</div>
		<div class="user-content">
			<?php
			      $bookPrezenter = new BookPrezenter($conn);
            $words = $bookPrezenter->getBooksWords($book[0]['import_id']);
            echo '<h1><span class="dataBookName">'.$book[0]['book_name'].'</span><span class="edit-box"><a href="#" class="edit e"></a></span></h1>';
            $count = count($words);

            if(isset($_SESSION['importStatus'])){
              echo '<p class="ok" style="display:inline-block">'.getMessage('importSuccess', $count).'</p>';
              unset($_SESSION['importStatus']);
            }

           
           $editLabel = getMessage("edit");
           $deleteLabel = getMessage("delete");
           $html =  '<table id="book">
                <tr>
                    <td class="bold">Autor učebnice / dátum:</td>
                    <td>'.(isset($book[0]['login']) ? $book[0]['login'] : $book[0]['author']). ' / '.date("d.m.Y H:i" ,strtotime($book[0]["create"])).'</td>
                </tr>
                <tr>
                     <td class="bold">Jazyk učebnice:</td>
                    <td class="dataLang">'.$book[0]['lang_question'].' / '.$book[0]['lang_answer'].'</td>    
                </tr>
                <tr>
                     <td class="bold">Úroveň náročnosti:</td>
                    <td class="dataLevel">'.$book[0]["name_$lang"].'</td>    
                </tr>
                   <tr>
                     <td class="bold">Poznámka autora:</td>
                    <td class="dataDescr">'.$book[0]['descr'].'</td>    
                </tr>
                   <tr>
                     <td class="bold">Import ID:</td>
                    <td><strong id="importId2">'.$book[0]['import_id'].'</strong></td>    
                </tr>
             </table>
             <ul id="export">
                <li class="head">'.getMessage('nav').'</li>
                <li><a  class="book-menu print" target="_blank" href="/inc/export.php?t=print&amp;id='.$_GET['book'].'">'.getMessage("print").'</a></li>
                <li><a class="book-menu pdf" href="/inc/export.php?t=pdf&amp;id='.$_GET['book'].'">'.getMessage('exportPdf').'</a></li>
                <li><a class="book-menu xls" href="/inc/export.php?t=xls&amp;id='.$_GET['book'].'">'.getMessage('exportXls').'</a></li>
                <li><a class="book-menu csv" href="/inc/export.php?t=csv&amp;id='.$_GET['book'].'">'.getMessage('exportCsv').'</a></li>
            </ul>';
            if($count > 0){
                $html .= '<h2 class="cst">Obsah učebnice <span class="dataBookName">'.$book[0]['book_name'].'</span></h2>'.
                            '<table id="words" data-lang="'.$lang.'">';
                for($i = 0; $i < $count; $i++){
                    $html .= '<tr id="id'.$words[$i]['_id'].'">
                                 <td>'.$words[$i]['question'].'</td>
                                 <td>'.$words[$i]['answer'].'</td>
                                 <td class="edit"><a class="e" href="#'.$words[$i]['_id'].'" tilte="'.$deleteLabel.'"></a></td>
                                 <td class="delete"><a class="d" href="#'.$words[$i]['_id'].'" tilte="'.$deleteLabel.'"></a></td>
                              </tr>';
                }
                $html .= '</table>';
           }else{
               $html .= '<table id="words" data-lang="'.$lang.'"><p class="alert">Učebnica neobsahuje žiadne kartičky.</p></table>';
           }
           echo $html;
          
		?>
		
		<div class="addNewWord" id="addNewWord">
			<input type="text" name="question" class="q" maxlength="255" />
			<input type="text" name="answer"  class="a" maxlength="255" />
			<a href="#" class="btn"><?php printMessage("add"); ?></a>
			<span class="tip block r"><?php printMessage("tipUseEnter"); ?> </span>
		</div>	
	<?php } ?>
		</div>
		<div class="hidden" id="confirmMsg"><?php  printMessage("confirmCardDel"); ?></div>
		<div class="clear"></div>
	</article>
</div>

<div id="dialog-form" data-lang="<?php echo $lang; ?>">
	<form class="dialog-form">
		<fieldset>
			<span class="row">
	            <span><?php printMessage('bookName'); ?></span>
	            <input name="name" class="w250 required fiveplus" value="<?php echo $book[0]['book_name']; ?>" />
	            <div class="clear"></div>
	        </span>
			<span class="row">
	            <span><?php printMessage('bookLangQuestion'); ?></span>
	            	<select name="lang_q" class="w250"><?php echo getOptions( $conn, "lang", "name_${lang}", $book[0]['lang'] ); ?></select>
	            <div class="clear"></div>
	        </span>
	        <span class="row">
	            <span><?php printMessage('bookLangAnswer'); ?></span>
	            <select name="lang_a" class="w250"><?php echo getOptions( $conn, "lang", "name_${lang}", $book[0]['lang_a'] ); ?></select>
	            <div class="clear"></div>
	        </span>
	         <span class="row">
	            <span><?php printMessage('bookLevel'); ?></span>
	            <select name="level" class="w250"><?php echo getOptions( $conn, "level", "name_${lang}" , $book[0]['level']); ?></select>
	            <div class="clear"></div>
	        </span>
	        <span class="row">
	            <span><?php printMessage('bookNote'); ?></span>
	            <textarea name="descr" rows="10" cols="50"><?php echo $book[0]['descr']; ?></textarea>
	            <div class="clear"></div>
	        </span>
	        <input type="hidden" name="id" value="<?php echo $_GET['book']; ?>" />
		</fieldset>
	</form>
</div>