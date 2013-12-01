<?php 

function printFooterBoxes(){
	global $conn, $meta, $lang;
	$html = "";
	$article = getArticle("set", array( 7, 8, 9), $lang);
	
	for($i = 0; $i < 3; $i++){
		$html .= '<div class="bx"><strong>'.$article[$i]["title_${lang}"].'</strong>'.$article[$i]["content_${lang}"].'</div>';
	
	}	
	return $html;	
}


function printLangNav(){
	global $conn, $meta, $lang, $config;
	$html = "";
	$langs = explode(",", $config['article_langs']);
	
	for($i = 0; $i < count($langs); $i++){
		$url = linker($meta['id_article'], $meta['type'], $langs[$i]); 
		if(!$url) continue;
		$html .= '<li class="'.$langs[$i].'"><a '.($lang == $langs[$i] ? 'class="curr"' : '').' href="'.linker($meta['id_article'], $meta['type'], $langs[$i]).'">'.$langs[$i].'</a></li>' ;
	
	}	
	return '<ul id="langs">'.$html.'</ul>';	
}



function getBookName($id){
	global $conn, $meta;
	$d = $conn->select("select `name` as book_name FROM `import_book` WHERE `_id`=? LIMIT 1", array($id));
        return $d[0]['book_name'];
}
