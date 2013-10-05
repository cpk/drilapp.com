<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderPresenter
 *
 * @author Peto
 */
class BookPrezenter {

    private $conn;
    private $countOfItems = null;
    private $bookService = null;

    public function __construct($conn, $bookService = null) {
        $this->conn = $conn;
        
        if($bookService == null)
            $this->bookService = new BookService($conn);
        else
            $this->bookService = $bookService;
       
    }
    
    

     public function printBooks($pageNumber, $peerPage){
       $data =  $this->bookService->getBooks($pageNumber, $peerPage);
       if($data == null || count($data) == 0){
           return '<p class="alert">Požiadavke nevyhovuje žiadna učebnica.</p>';
       }
       
       $this->createNavigator($pageNumber, $peerPage);
       
       $html = $this->navigator.'<div class="claer"></div><table id="books">';
       $html .= $this->getTableHead().'<tbody>';
       for($i=0 ; $i < count($data); $i++ ){
           $html .= $this->getOrderTableRow($data[$i]);
       }
       $html .= "</tbody></table>".$this->navigator;
       return $html;
    }

    public function printUserBooks($uid, $pageNumber, $peerPage){
       $data =  $this->bookService->getUserBooks($uid, $pageNumber, $peerPage);
       if($data == null || count($data) == 0){
           return '<p class="alert">'.getMessage("noBooks").'</p>';
       }
       
       $this->createNavigator($pageNumber, $peerPage);
       
       $html = $this->navigator.'<div class="claer"></div><table id="books">';
       $html .= $this->getTableHead().'<tbody>';
       for($i=0 ; $i < count($data); $i++ ){
           $html .= $this->getOrderTableRow($data[$i]);
       }
       $html .= "</tbody></table>".$this->navigator;
       return $html;
    }
    
    
    private function getTableHead(){
        return '<thead><tr>
                    <th>Názov učebnice</th>
                    <th>Autor</th>
                    <th>Jazyk</th>
                    <th>Úroveň</th>
                    <th>Pč.</th>
                    <th>Dátum</th>
                    <th>Import ID</th>
                </tr></thead>';
    }
        
    private function getOrderTableRow($row){
        return "<tr>".
                '<td class="name"><a href="'.$_GET['p']."/?id=".$row["_id"].'">'.$row["name"].'</a></td>'.
                '<td class="author c">'.(strlen($row["author"]) == 0 ? 'neuvedený' : $row["author"] ).'</td>'.
                '<td class="lang c">'.mb_substr($row["lang_question"], 0, 4, "UTF-8").' / '. mb_substr($row["lang_answer"],0, 4,"UTF-8").'</td>'.
                '<td class="level c">'.substr($row["level_name"], 0, strpos($row["level_name"], ' ', 15)).'</td>'.
                '<td class="count c">'.$row["count"].'</td>'.
                '<td class="date c">'.date("d.m.Y" ,strtotime($row["create"])).'</td>'.
                '<td class="import-id c"><em>'.$row["import_id"].'</em></td>'.
               "</tr>";
    }
    
    public function createNavigator($pageNumber, $peerPage){
        $queryStr = str_replace("lang=".$_GET['lang']."&p=".$_GET['p']."&a=index", '', $_SERVER['QUERY_STRING']);
        $queryStr = preg_replace("/&?s=[0-9]*/", "", $queryStr);

        $nav = new Navigator( $this->bookService->getCount() , $pageNumber , substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) , $peerPage, $queryStr);
        $nav->setSeparator("?s=");
        $this->navigator =  $nav->smartNavigator();    
    }
    
  
     public function getProfit($nakup, $predaj){
        if($predaj == 0  || $nakup == 0  ) return "0";
        return  round(((($predaj - $nakup) / $nakup) * 100)). " %";
    }
    
    
    public function getBook($id){
        return $this->bookService->getById($id); 
    }
    
     public function getBooksWords($importId){
        return $this->bookService->getBooksWords($importId); 
    }
    
}

?>
