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



	$userService = new UserService($conn);
	$oldBook = $userService->getById($_GET['id']);
	print_r($oldBook);
	$errorMessage = false;
	if(count($oldBook) == 0){
		$errorMessage = "The book was not found";
	}else if($oldBook[0]['id_user'] != (int)$_SESSION['id']){
		$errorMessage = "Access denied";
	}else if($oldBook[0]['transmitted'] == 1){
		$errorMessage = "The book was already transmitted.";
	}
	$langList = $conn->select("SELECT id_lang as id, name_$lang as name FROM `lang`");
	$levelList = $conn->select("SELECT id_level as id, name_$lang as name FROM `level`");
	$bookList = $conn->select("SELECT * FROM dril_book WHERE user_id = ? order by name", array($_SESSION['id']));

function langsAreSame($oldBook, $newbook){
	return 
	(
		$oldBook['lang'] == $newbook['question_lang_id'] &&
		$oldBook['lang_a'] == $newbook['answer_lang_id']
	) || (
		$oldBook['lang_a'] == $newbook['question_lang_id'] &&
		$oldBook['lang'] == $newbook['answer_lang_id']
	);
}



?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/tag-it.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">

<link href='https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css' rel='stylesheet' type='text/css'>
<link href='/css/bootstrap.css' rel='stylesheet' type='text/css'>


<script type="text/javascript">
$(function(){
	


    $("select").chosen({width: "97%"});
    $('#accordion h3').click(function(){
    	$('.pj-active').removeClass('pj-active');
    	
    	$(this).addClass('pj-active').next().addClass('pj-active');
    });
     $("#myTags").tagit({
     	fieldName: "tags",
     	allowSpaces :true
     });
});
</script>

<div id="article">
	<article class="user-section fullscreen">
	

		<h1>Presun slovíčok do nového WebDrilu</h1>
		<?php if($errorMessage){
			echo '<p class="err">'.$errorMessage.'</p>';
		}else{  ?>

		<div class="user-content">
			<div id="accordion">
			  <h3>1. Vložiť do existujúcej učebnice</h3>
			  <div class="pj-bx content-f1">
			  	<p>
			  		Vyberte jednu z existujúsich učebníc do ktorej majú byť slovíčka importované. 
			  		Importovať slovíčka je možné len v prípade ak sa zhodujú ich jazky.
			  	</p> 

			  	<form>
			  		 <div class="form-group">
			  		 		<span for="bookId">Existujúca učebnica</span>
			  		 		<select class="existing" name="bookId">
							<option value="">-- Vyberete z existujúcich učebníc --</option>		
							<?php 
								$html = '';
								foreach ($bookList as $i => $book) {
									$html .= '<option '.(langsAreSame($oldBook[0], $book) ? '' : 'disabled="disabled"').' value="'.$book['id'].'">'.$book['name'].'</option>';
								}
								echo $html;
							?>
						</select>
			  		</div>
			  		 <div class="form-group">
			  		 	<span for="lectureName">Názov lekcie</span>
			  		 	<input type="text" name="lectureName" class="form-control" required value="<?php echo $oldBook[0]['book_name'] ?>" />
			  		 </div>
			  		<div class="form-group">
			  			<button class="btn btn-primary">Presunúť</button>
			  		</div>
					<div class="clear"></div>   
					<input type="hidden" name="existing" value="1" />
					<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
					
			  	</form>
			  	
					
				
			  </div>
			  <h3>2. Vyvoriť novú učebnicu</h3>
			  <div class="pj-bx content-f">
			  		<form>
			  			<div class="form-group">
				  		 	<span for="bookName">Zadajte názov učebnice:</span>
				  		 	<input type="text" name="bookName" class="form-control" required value="<?php echo $oldBook[0]['book_name'] ?>" />
			  		 	</div>
			  		 	<div class="form-group">
				  		 	<span for="bookName">Zadajte názov lekcie:</span>
				  		 	<input type="text" name="lectureName" class="form-control" required value="<?php echo $oldBook[0]['book_name'] ?>" />
			  		 	</div>
			  		 	<div class="form-group">
				  		 	<span for="bookName">Jazyk otázok:</span>
				  		 	<select name="langQuestion"><?php echo getBookOptions($oldBook[0]['lang'], $langList) ?></select>
			  		 	</div>
			  		 	<div class="form-group">
				  		 	<span for="bookName">Jazyk odpovedí:</span>
				  		 	<select name="langAnswer"><?php echo getBookOptions($oldBook[0]['lang_a'], $langList) ?></select>
			  		 	</div>
			  		 	<div class="form-group">
				  		 	<span for="bookName">Úroveň náročnosti:</span>
				  		 	<select name="level"><?php echo getBookOptions($oldBook[0]["level"], $levelList) ?></select>
			  		 	</div>
			  		 	<div class="form-group">
				  		 	<span for="bookName">Zaradiť do kategorie: <a style="padding-left:20px;" href="mailto:info@drilapp.com">navrhnúť novú kategóriu</a></span>
				  		 	<select name="category"><?php printCategories() ?></select>
			  		 	</div>
			  		 	<div class="form-group">
			  		 		<span for="bookName">Oštítkute učebnicu:</span>
			  		 		<ul id="myTags"></ul>
							<span>
								<br />
								Štítky odtelte čiarkov. Prečo označkovať učebnicu? Bude lahšie dohladateľná pre ostatných užívateľov.
								Príklad: "Frázové slovesa", "Chémia", "Šport"
							</span>
			  		 	</div>
			  		</form>	

			  
			  </div>
			</div>

			<?php } ?>
		</div>	
		<div class="clear"></div>
	</article>
</div>

<pre>
<?php

// printCategories();

function getBookOptions($id , $list){
    $html = '';
    foreach ($list as $val) {
        $html .= '<option value="'.$val["id"].'" '.($val["id"] == $id ? 'selected="selected"' : '').'>'.$val["name"].'</option>';
    }
    return $html;
}

function printCategories(){
	$list = getTreeCategories();
	$html = '<option value="">-- Vyberte z možností --</option>';
	foreach($list as $c){
		$html .= '<optgroup label="'.$c['name'].'">'.
					getBookOptions(-1, $c['subCategories']).
					'</optgroup>';
	}	
	echo $html;
}

function getTreeCategories(){
	$categories = getCategoryLevel(null);
    for($i = 0; $i < count($categories); $i++){
    	$categories[$i]['subCategories'] = getCategoryLevel($categories[$i]['id']);
    }
    return $categories;
}

	
function getCategoryLevel($id){
	global $lang, $conn;
	return $conn->select(
        "SELECT id , name_$lang as name ".
        "FROM `dril_category` ".
        "WHERE parent_id ".($id == null ? " IS NULL " : " = ".$id." ").
        "ORDER BY ordering"
    );
}

?>
</pre>