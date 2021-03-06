<?php 
      $html = "";
      if($count > 0){
        $html .= '<h3>'.$book[0]['book_name'].'</h3>'.
                    '<table id="words">';
        for($i = 0; $i < $count; $i++){
            $html .= '<tr><td style="width:30px;text-align:center;">'.($i+1).'</td><td>'.$words[$i]['question'].'</td><td>'.$words[$i]['answer'].'</td></tr>';
        }
        $html .= '</table>';
        include("../mpdf/mpdf.php");

        // $mpdf = new mPDF('utf-8', 'A4'); 
        $mpdf=new mPDF('utf-8','A4', 10, '', 5, 5, 10, 10, 0, 0, 'L');

        $mpdf->SetDisplayMode('fullpage');

        // LOAD a stylesheet
        $stylesheet = file_get_contents('../css/print.css');
        $mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML($html);
        $filename = date('d-m-Y_').SEOLink($book[0]['book_name'].'.pdf');
        $mpdf->Output( $filename, 'i'); 

      }
exit;

?>
