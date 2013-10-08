<?php
	$userService = new UserService($conn);
	$book = $userService->getById($_GET['book']);
	
  $notFound = (count($book) == 0 || $book[0]['id_user'] != $_SESSION['id']);
?>
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
            echo '<h1>'.$book[0]['book_name'].'</h1>';
            
           $count = count($words);
           $editLabel = getMessage("edit");
           $deleteLabel = getMessage("delete");
           $html =  '<table id="book">
                <tr>
                    <td class="bold">Autor učebnice / dátum:</td>
                    <td>'.$book[0]['author']. ' / '.date("d.m.Y H:i" ,strtotime($book[0]["create"])).'</td>
                </tr>
                <tr>
                     <td class="bold">Jazyk učebnice:</td>
                    <td>'.$book[0]['lang_answer'].' / '.$book[0]['lang_question'].'</td>    
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
                     <td class="bold">Import ID:</td>
                    <td><strong id="importId2">'.$book[0]['import_id'].'</strong></td>    
                </tr>
             </table>
             <ul id="export">
                <li class="head">Možnosti</li>
                <li><a  class="book-menu print" target="_blank" href="/inc/export.php?t=print&amp;id='.$_GET['book'].'">Vytlačiť</a></li>
                <li><a class="book-menu pdf" href="/inc/export.php?t=pdf&amp;id='.$_GET['book'].'">Exportovať do PDF</a></li>
                <li><a class="book-menu xls" href="/inc/export.php?t=xls&amp;id='.$_GET['book'].'">Exportovať do Excelu</a></li>
                <li><a class="book-menu csv" href="/inc/export.php?t=csv&amp;id='.$_GET['book'].'">Exportovať do CSV</a></li>
            </ul>';
            if($count > 0){
                $html .= '<h2 class="cst">Obsah učebnice '.$book[0]['book_name'].'</h2>'.
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