<?php 
   
      
    if($count > 0){
        $filename = date('dmY')."_drilapp_com.xls";
        $objPHPExcel = new PHPExcel();  
        PHPExcel_Settings::setLocale('sk_sk'); 
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
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


?>
