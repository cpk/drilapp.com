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
    private $isLoggined = false;
    private $countOfItems = null;
    private $bookService = null;

    private $linkToUserSection = '';
    private $linkToBookDetail = '';

    private $isFavoritePage = false;

    public function __construct($conn, $bookService = null) {
        $this->conn = $conn;
        global $lang;
        
        if($bookService == null)
            $this->bookService = new BookService($conn);
        else
            $this->bookService = $bookService;
        $this->isLoggined = isset($_SESSION['id']);

        $this->linkToUserSection = linker(16, 1, $lang);
        $this->linkToBookDetail = linker(9, 1, $lang);
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

    public function printUserFavoriteBooks($uid, $pageNumber, $peerPage){
       $this->isFavoritePage = true;
       $data =  $this->bookService->getUserFavoriteBooks($uid, $pageNumber, $peerPage);
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
                    <th>'.getMessage("bookName").'</th>
                    <th>'.getMessage("bookAuthor").'</th>
                    <th>'.getMessage("bookLang").'</th>
                    <th>'.getMessage("bookLevel").'</th>
                    <th>'.getMessage("bookCount").'</th>
                    <th>'.getMessage("bookDate").'</th>
                    <th>Import ID</th>
                    '.$this->getFavoriteTH().'
                </tr></thead>';
    }

    private function getFavoriteTH(){
       if($this->isLoggined){
         return '<th>'.getMessage("favorite").'</th>';
       }
       return '';
    }
        
    private function getOrderTableRow($row){
        return "<tr id=\"".$row["_id"]."\">".
                '<td class="name"><a href="'.$this->linkToBookDetail."?id=".$row["_id"].'">'.$row["name"].'</a></td>'.
                '<td class="author c">'.(isset($row["login"]) ? $row["login"] : (strlen($row["author"]) == 0 ? 'neuvedený' : $row["author"] )).'</td>'.
                '<td class="lang c">'.mb_substr($row["lang_question"], 0, 4, "UTF-8").' / '. mb_substr($row["lang_answer"],0, 4,"UTF-8").'</td>'.
                '<td class="level c">'.($row["level_name"] == "Advanced - C1 (pokročilý)" ? 'Advanced - C1' : substr($row["level_name"], 0, strpos($row["level_name"], ' ', 15))).'</td>'.
                '<td class="count c">'.$row["count"].'</td>'.
                '<td class="date c">'.date("d.m.Y" ,strtotime($row["create"])).'</td>'.
                '<td class="import-id c"><em>'.$row["import_id"].'</em></td>'.
                $this->getFavoriteRow($row).
                $this->getDelColl($row).
               "</tr>";
    }

    private function getDelColl($row){
      if($this->isLoggined && $_SESSION['type'] > 1){
        return '<td class="delete"><a href="?rm='.$row["import_id"].'">delete</a></td>';
      }
      return '';
    }
    private function getFavoriteRow($book){
       if($this->isLoggined){
          if($this->isFavoritePage || $book['id_user'] <> $_SESSION['id']){
            $isFavorite = !empty($book['favorite']) || $this->isFavoritePage;
            return '<td class="favorite is'.($isFavorite ? 1 : 0).'" title="'.getMessage("favoriteRemove".($isFavorite ? 1 : 0)).'?"><span></span></td>';
          }else{
            return '<td><a href="'.$this->linkToUserSection.'?book='.$book["_id"].'">'.getMessage("view").'</a></td>';
          }
       }
       return '';
    }
    
    public function createNavigator($pageNumber, $peerPage){
        $queryStr = str_replace("lang=".$_GET['lang']."&p=".$_GET['p']."&a=index", '', $_SERVER['QUERY_STRING']);
        $queryStr = preg_replace("/&?s=[0-9]*/", "", $queryStr);

        $nav = new Navigator( 
                ($this->isFavoritePage ?  $this->bookService->getCountOfFavoriteUsersBook($_SESSION['id']) : $this->bookService->getCount()), 
                $pageNumber , 
                substr($_SERVER['REQUEST_URI'], 
                0, 
                strpos($_SERVER['REQUEST_URI'], '?')) , 
                $peerPage, 
                $queryStr
            );

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
