<div id="article">
<article>
    
<?php 

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(!isset($auth)){
			$auth = new Authenticate($conn);
		}
		try{
			if(isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on'){
				$_POST['rememberMe'] = true;
			}else{
				$_POST['rememberMe'] = false;
			}
			$auth->login($_POST['login'] ,$_POST['pass'], $_POST['rememberMe'], $_POST['token']);
		}catch(AuthException $e){
			$_SESSION['status'] = $e->getMessage();
		}
	}
$article = getArticle("fullHidden", $meta['id_article'], $lang);  
//echo $article[0]["content_${lang}"];

echo '<h1>'.$article[0]["title_${lang}"].'</h1>'.$article[0]["content_${lang}"];
?>
<script>
$(function() {
	$('form[name=login]').submit(function (){
		if(!validate($(this))){
			return false;
		}
	});
	$('input[name=login]').focus();
});
</script>
	<form method="post" class="loginform">
		<?php echo (isset($_SESSION["status"]) ? '<p class="error">'.$_SESSION["status"].'</p>' : ""); unset($_SESSION["status"]); ?>
        <div>
        	<label><?php printMessage("username")?>:</label>
        	<input type="text" name="login"  class="w200 required" />
        	<div class="clear"></div>
        </div>
        <div>
        	<label><?php printMessage("userpass")?>:</label>
        	<input type="password" name="pass" class="w200 required fiveplus" />
        	<div class="clear"></div>
        </div>
        <div class="checkbox">
        	<input type="checkbox" name="rememberMe" class="ch" /><span><?php printMessage("rememberMe")?></span>
        	<input type="submit" value="<?php printMessage("login"); ?>" class="btn" />
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