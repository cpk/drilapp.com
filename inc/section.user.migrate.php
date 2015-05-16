<?php
try{
	$bookList = $conn->select("SELECT * FROM dril_book WHERE user_id = ? order by name", array($_SESSION['id']));
}catch(Exception $e){
	echo $e->getMessage();
}
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<link href='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.structure.min.css' rel='stylesheet' type='text/css'>
<link href='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.min.css' rel='stylesheet' type='text/css'>

<script type="text/javascript">
$(function(){
	$.getJSON('<?php echo API_URL; ?>filter?cb=?', function(res){
		
	});

	$( "#accordion" ).accordion();

    //$("select").chosen();

    $( "input[type=submit], button" ).button();
});

</script>
<style type="text/css">
.content-f1{min-height: 200px;}
.content-f1 p{font-size: 1.2em;}
.ui-button{min-height:30px;}
.chosen-container a{line-height: 30px;height: 30px;min-height: 30px !important;font-size: 14px;}

select {
    padding:5px;
    margin: 0;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow: 0 3px 0 #ccc, 0 -1px #fff inset;
    -moz-box-shadow: 0 3px 0 #ccc, 0 -1px #fff inset;
    box-shadow: 0 3px 0 #ccc, 0 -1px #fff inset;
    background: #f8f8f8;
    color:#333;
    border:none;
    outline:none;
    display: inline-block;
    -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;
    cursor:pointer;
    width: 880px;
}

/* Targetting Webkit browsers only. FF will show the dropdown arrow with so much padding. */
@media screen and (-webkit-min-device-pixel-ratio:0) {
    select {padding-right:18px}
}
select option{padding: 2px;font-size: 15px;}
label {position:relative}
label:after {
    content:'<>';
    font:14px "Consolas", monospace;
    color:#aaa;
    -webkit-transform:rotate(90deg);
    -moz-transform:rotate(90deg);
    -ms-transform:rotate(90deg);
    transform:rotate(90deg);
    right:8px; 
    top:2px;
    padding:0 0 2px;
    border-bottom:1px solid #ddd;
    position:absolute;
    pointer-events:none;
}
label:before {
    content:'';
    right:6px; top:0px;
    
    background:#f8f8f8;
    position:absolute;
    pointer-events:none;
    display:block;
}
</style>
<div id="article">
	<article class="user-section fullscreen">
	

		<h1>Presun slovíčok do nového WebDrilu</h1>

		<div class="user-content">
			<div id="accordion">
			  <h3>1. Vložiť do existujúcej učebnice</h3>
			  <div class="content-f1">
			  	<p>
			  		Vyberte jednu z existujúsich učebníc do ktorej majú byť slovíčka importované. 
			  		Importovať slovíčka je možné len v prípade ak sa zhodujú ich jazky.
			  	</p> 
			  	<label>
				  	<select name="bookId">
						<option value="">-- Vyberete z existujúcich učebníc --</option>		
						<?php 
							$html = '';
							foreach ($bookList as $i => $book) {
								$html .= '<option value="'.$book['id'].'">'.$book['name'].'</option>';
							}
							echo $html;
						?>
					</select>
				</label>
				<input type="submit" value="Presunúť" />
					
				
			  </div>
			  <h3>2. Vyvoriť novú učebnicu</h3>
			  <div class="content-f">
			  	Second content panel
			  </div>
			</div>


		</div>	
		<div class="clear"></div>
	</article>
</div>