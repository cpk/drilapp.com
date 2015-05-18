<?php 

$locale = getUserLocale();

if($locale == null){

?>
<div id="cl-dialog" title="<?php printMessage("dialog"); ?>">
  <p><?php printMessage("dialogDescr"); ?></p>
  <form id="cl">
    <fieldset>
      	<label><?php printMessage("dialogChooseLang") ?></label>
      	<select name="localeId">
      		<?php 
      			$langs = getLocales(); 
      			foreach ($langs as $l) {
      				echo '<option value="'.$l['id'].'">'.$l['name'].'</option>';
      			}
      		?>
      	</select>
    </fieldset>
  </form>
</div>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function(){
	var $dialog = $('#cl-dialog');
	$dialog.dialog({
      autoOpen: true,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Uložiť": function(){
        	$.getJSON('/inc/ajax.user.php',{
        		localeId : $('#cl select').eq(0).val(),
        		act : 9
        	}, function(res){
        	
        	});
        	$dialog.dialog( "close" );
        }
      }
    });
});
</script>

<?php } ?>
