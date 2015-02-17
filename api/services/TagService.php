<?php

class TagService
{
	private $conn;



	public function __construct(&$conn)
    {
       $this->conn = $conn;
    }



    public function createTags($tagList, $bookId){
        $this->deleteAllBookTags( $bookId );
        $sql = "INSERT INTO  dril_book_has_tag  (dril_tag_id,dril_book_id) VALUES (?,?)";
        foreach($tagList as $tagName){
            $tagId = $this->createTag($tagName);
            $this->conn->insert( $sql , array( $tagId, $bookId) );
        }
    }

    public function createTag($tagName){
    	$tagName = $this->getCleanedTagName($tagName);
        $tag = $this->getTagByName($tagName);
        if($tag == null){
	  		$this->conn->insert("INSERT INTO `dril_tag` (`name`) VALUES (?)",  array( $tagName) );
	  		return $this->conn->getInsertId();
	  	}
	  	return $tag['id'];
    }

    public function getTagByName($tagName){
        $tag = $this->conn->select("SELECT * FROM `dril_tag` WHERE name=? LIMIT 1",  
                    array( $tagName) );
        if(count($tag) == 1){
            return $tag[0];
        }
        return null;
    }

    public function deleteTagByName($tagName){
    	$this->conn->delete("DELETE FROM `dril_tag` tag WHERE tag.name = ? LIMIT 1",  array( $tagName) );
    }

    public function deleteAllBookTags( $bookId ){
    	$this->conn->delete("DELETE FROM dril_book_has_tag WHERE dril_book_id = ?;", array( $bookId ) );
    }


    public function getAllBookTags($bookId){
    	$sql = "SELECT tag.name FROM dril_tag tag ".
    			" INNER JOIN `dril_book_has_tag` bht ON bht.dril_tag_id = tag.id ".
    			" WHERE bht.dril_book_id = ? ";
    	return $this->conn->select($sql, array($bookId) );
    }


	private function isTagNameUniqe($name){
		$sql =  "SELECT count(*) as tag_count FROM  dril_tag tag ".
				"WHERE tag.name = ? ";	
		$result =  $this->conn->select( $sql, array($name));
		return $result[0]["tag_count"] == 0;
	}

    private function getCleanedTagName($name){
        return strtolower(trim($name));
    }
}



?>