<?php 
   
      
    if($count > 0){
        $filename = date('dmY')."_drilapp_com.pdf";
        
        

        $objPHPExcel = new PHPExcel();  

        
        $objPHPExcel->getProperties()->setCreator("www.drilapp.com");
        $objPHPExcel->getActiveSheet()->setShowGridLines(false);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(40);
        for($i = 0; $i < $count; $i++){
           $row = ($i+1);
           $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row, utf8_encode($words[$i]['question']))
                    ->setCellValue('B'.$row, utf8_encode($words[$i]['answer']));
        }   

        $objPHPExcel->setActiveSheetIndex(0);
        PHPExcel_Settings::setPdfRenderer(PHPExcel_Settings::PDF_RENDERER_DOMPDF, LITHIUM_LIBRARY_PATH . 'domPDF0.6.0beta3');
        header('Content-type:Application/pdf'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        $objWriter->save('php://output');
        exit;
    }


?>
