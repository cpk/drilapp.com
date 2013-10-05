<div id="article">
<article>
    
<?php 

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$_POST =  array_map("trim", $_POST);
		try{
			$userService = new UserService($conn);
			$userService->validate($_POST);
		}catch(InvalidArgumentException $e){
			$_SESSION['status'] = $e->getMessage();
		}
			
	}
$article = getArticle("fullHidden", $meta['id_article'], $lang);  


echo '<h1>'.$article[0]["title_${lang}"].getMessage("registration").'</h1>';
?>
<script>
$(function() {
	$('form').submit(function(e){
		e.stopPropagation();
		if(!validate($(this))){
			//	return false;
		}
	});
});
</script>
	<form method="post" class="regform" action="/inc/user.action.php">
		<?php echo (isset($_SESSION["status"]) ? '<p class="err">'.$_SESSION["status"].'</p>' : ""); unset($_SESSION["status"]); ?>
        <div>
        	<label class="req"><em>*</em><?php printMessage("username")?>:</label>
        	<input type="text" name="login"  class="w200 required" value="<?php echo (isset($_POST["login"]) ?  $_POST["login"] : "");?>"/>
        	<div class="clear"></div>
        </div>
        <div>
        	<label class="req"><em>*</em><?php printMessage("userpass")?>:</label>
        	<input type="password" name="pass" class="w200 required fiveplus" />
        	<div class="clear"></div>
        </div>
        <div>
        	<label class="req"><em>*</em><?php printMessage("email")?>:</label>
        	<input type="text" name="email" class="w200 required email" value="<?php echo (isset($_POST["email"]) ?  $_POST["email"] : "");?>" />
        	<div class="clear"></div>
        </div>
        <div>
        	<label><?php printMessage("givenname")?>:</label>
        	<input type="text" name="givenname" class="w200" value="<?php echo (isset($_POST["givenname"]) ?  $_POST["givenname"] : "");?>"/>
        	<div class="clear"></div>
        </div>
         <div>
        	<label><?php printMessage("surname")?>:</label>
        	<input type="text" name="surname" class="w200" value="<?php echo (isset($_POST["surname"]) ?  $_POST["surname"] : "");?>" />
        	<div class="clear"></div>
        </div>

        <div class="btnbox">
        	<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
        	<input type="hidden" name="action" value="registration" />
        	<input type="submit" value="<?php printMessage("registrationCreate"); ?>" class="btn" />
        	<div class="clear"></div>
        </div>
        <input type="hidden" name="token" value="<?php echo session_id(); ?>" / >
	</form>


</article>
</div>    

  <!-- MOBILE SLIDER -->
<div id="mobile">
    <div id="slider">
       <ul> <?php echo printSlidesImages(1, 210, 372, ""); ?></ul>
    </div>
</div>