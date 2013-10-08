<?php 
      $html = "";
      if($count > 0){
        $html .= '<h1>'.$book[0]['book_name'].'</h1>'.
                    '<table id="words">';
        for($i = 0; $i < $count; $i++){
            $html .= '<tr><td>'.$words[$i]['question'].'</td><td>'.$words[$i]['answer'].'</td></tr>';
        }
        $html .= '</table>';
      }else{
         $html .= '<table id="words" ><p class="alert">Učebnica neobsahuje žiadne kartičky.</p></table>';
      }

        
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $book[0]['book_name']; ?></title>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex,follow"/>
    <link rel="stylesheet" href="/css/print.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

</head>
<body onload=" window.print();">
    <?php echo $html; ?>
</body>
</html>
