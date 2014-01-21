<?php 
   
      
    if($count > 0){
        $filename = date('dmY')."_drilapp_com.csv";
        $objPHPExcel = new PHPExcel();  
        $objPHPExcel->getProperties()->setCreator("www.drilapp.com");

        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(40);
        for($i = 0; $i < $count; $i++){
           $row = ($i+1);
           $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row, ($words[$i]['question']))
                    ->setCellValue('B'.$row, ($words[$i]['answer']));
        }   


        $objPHPExcel->setActiveSheetIndex(0);
        ob_clean();
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',')
                                                                  ->setEnclosure('"')
                                                                  ->setLineEnding("\r\n")
                                                                  ->setSheetIndex(0);
        header("Content-Type: application/csv");
        header('Content-Disposition:attachment; filename="'.$filename.'"');
        header("Cache-Control: max-age=0");                                                                  
        $objWriter->save('php://output');
        exit;
    }


?>
