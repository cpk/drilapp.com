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
class UserPrezenter {

    private $conn;
    private $countOfItems = null;
    private $userService = null;

    public function __construct($conn, $userService = null) {
        $this->conn = $conn;
        
        if($userService == null)
            $this->userService = new UserService($conn);
        else
            $this->userService = $userService;
       
    }
    
    


    public function printUserBooks($uid, $pageNumber){
       $data =  $this->userService->getUserBooks($uid, $pageNumber);
       if($data == null || count($data) == 0){
           return '<p class="alert">'.getMessage("noBooks").'</p>';
       }
       
       $this->createNavigator($pageNumber, $this->userService->getPeerPage());
       
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
                    <th>'.getMessage("bookLang").'</th>
                    <th>'.getMessage("bookLevel").'</th>
                    <th>'.getMessage("bookCount").'</th>
                    <th>'.getMessage("bookDate").'</th>
                    <th>'.getMessage("bookPublished").'</th>
                    <th>'.getMessage("edit").'</th>
                    <th>&nbsp;</th>
                </tr></thead>';
    }
        
    private function getOrderTableRow($row){
        $yes = getMessage("yes");
        $no = getMessage("no");
        return "<tr id=\"".$row["_id"]."\">".
                '<td><a href="?book='.$row["_id"].'">'.$row["name"].'</a></td>'.
                '<td class="c">'.mb_substr($row["lang_question"], 0, 4, "UTF-8").' / '. mb_substr($row["lang_answer"],0, 4,"UTF-8").'</td>'.
                '<td class="c">'.substr($row["level_name"], 0, strpos($row["level_name"], ' ', 15)).'</td>'.
                '<td class="c">'.$row["count"].'</td>'.
                '<td class="c">'.date("d.m.Y" ,strtotime($row["create"])).'</td>'.
                '<td class="c shared'.$row["shared"].'"><a href="#">'.($row["shared"] == 1 ? $yes : $no).'</a></td>'.
                '<td class="c edit"><a href="?book='.$row["_id"].'">'.getMessage("edit").'</a></td>'.
                '<td class="c delete"><a href="?id='.$row["_id"].'&amp;token='.$row["import_id"].'">'.getMessage("delete").'</a></td>'.
               "</tr>";
    }
    
    public function createNavigator($pageNumber, $peerPage){
        $queryStr = str_replace("lang=".$_GET['lang']."&p=".$_GET['p']."&a=index", '', $_SERVER['QUERY_STRING']);
        $queryStr = preg_replace("/&?s=[0-9]*/", "", $queryStr);

        $nav = new Navigator( $this->userService->getCount() , $pageNumber , substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) , $peerPage, $queryStr);
        $nav->setSeparator("?s=");
        $this->navigator =  $nav->smartNavigator();    
    }
    
  
     public function getProfit($nakup, $predaj){
        if($predaj == 0  || $nakup == 0  ) return "0";
        return  round(((($predaj - $nakup) / $nakup) * 100)). " %";
    }
    
    
    public function getBook($id){
        return $this->userService->getById($id); 
    }
    
    public function getBooksWords($importId){
        return $this->userService->getBooksWords($importId); 
    }

    public function removeBook($importId){
            $user = $this->userService->getUserById($_SESSION['id']);
            if(count($user) <> 1){
                echo "<div class=\"err\">".getMessage("err404")."</div>";
            }else{
                $this->userService->removeBook($importId);
                echo '<p class="ok" style="display:inline-block">'.getMessage("successDelete").'</p>';
            }
    }
    
}

?>
