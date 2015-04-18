<?php
 	require_once './api/inc/Text2Speach.php';
	$t2s = new Text2Speach();
?> 
<!DOCTYPE HTML>


<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Your Website</title>
</head>

<body>
	
	<section>
			
		<audio controls="controls" autoplay="autoplay"> 
		  <source src="<?php echo $t2s->speak('Wie geht es Ihnen', 'de', 20 ); ?>" type="audio/mp3" /> 
		</audio> 
		
	</section>

</body>

</html>


