<?php

class TagService extends BaseService
{
	
	public function __construct(&$conn)
    {
       parent::__construct($conn);
    }



    public function createTags($tagList, $bookId, $uid){
        $localId = $this->getUserLocale($uid);
        if($localId == null){
            return;
        }
        $this->deleteAllBookTags( $bookId );
        $sql = "INSERT INTO  dril_book_has_tag  (dril_tag_id, dril_book_id) VALUES (?,?)";
        foreach($tagList as $tagName){
            $tagId = $this->createTag($tagName, $localId);
            if(!$this->tagExists($bookId, $tagId)){
                $this->conn->insert( $sql , array( $tagId, $bookId) );
            }
        }
    }

    public function createTag($tagName, $localId){
    	$tagName = $this->clear($tagName);
        $tagcode = StringUtils::clear($tagName);
        $tag = $this->getTagByName($tagName,  $localId, $tagcode);
        if($tag == null){
	  		$this->conn->insert("INSERT INTO `dril_tag` (`name`, `code`, `locale_id`) VALUES (?,?,?)",  
                array( $tagName, $tagcode, $localId) );
	  		return $this->conn->getInsertId();
	  	}
	  	return $tag['id'];
    }

    public function getTagByName($tagName, $localId, $tagCode = null){
        if($tagCode == null){
            $tagCode = StringUtils::clear($tagName);
        }
        $tag = $this->conn->select("SELECT id, name FROM `dril_tag` WHERE code=? AND locale_id=? LIMIT 1",  
                    array( $tagCode, $localId) );
        if(count($tag) == 1){
            return $tag[0];
        }
        return null;
    }

    private function tagExists($bookId, $tagId){
        $result = $this->conn->select(
            "SELECT * FROM dril_book_has_tag WHERE dril_tag_id=? AND dril_book_id=? LIMIT 1",
            array($tagId, $bookId)
        );
        return count($result) == 1;
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

    private function clear($name){
        $name = trim(StringUtils::xssClean($name));
        if(strlen($name) > 50){
            $name = substr($name, 0, 50);
        }
        return $name;
    }

    private function getUserLocale($uid){
        $userService = new UserService($this->conn);
        $user = $userService->getUserById($uid);
        if($user == null){
            $logger = Logger::getLogger('api');
            $logger->warn('Determining user locale failed. User was not found: ' + $uid);
            return null;
        }else if($user['localeId'] == null){
            $logger = Logger::getLogger('api');
            $logger->warn('Determining user locale failed. User has not assigned any locale: ' + $uid);
            return null;
        }
        return $user['localeId'];
    }
}



?>