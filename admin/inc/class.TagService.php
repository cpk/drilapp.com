<?php

class TagService 
{
	private $conn = null;

	public function __construct(&$conn){
       $this->conn = $conn;
    }



    public function createTags($tagList, $bookId, $uid){
        $localId = $this->getUserLocale($uid);
        if($localId == null){
            return;
        }
        $this->deleteAllBookTags( $bookId );
        $sql = "INSERT INTO  dril_book_has_tag  (dril_tag_id, dril_book_id) VALUES (?,?)";
        foreach($tagList as $tagName){
            if(strlen(trim($tagName)) > 0){
                $tagId = $this->createTag($tagName, $localId);
                if(!$this->tagExists($bookId, $tagId)){
                    $this->conn->insert( $sql , array( $tagId, $bookId) );
                }
            }
        }
    }

    public function createTag($tagName, $localId){
    	$tagName = $this->clear($tagName);
        $tagcode = $this->toCode($tagName);
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
            $tagCode = $this->toCode($tagName);
        }
        $tag = $this->conn->select("SELECT id, name FROM `dril_tag` WHERE code=? AND locale_id=? LIMIT 1",  
                    array( $tagCode, $localId) );
        if(count($tag) == 1){
            return $tag[0];
        }
        return null;
    }

    public function findTags($tagName, $localeId, $limit = 8){
        $code = trim($this->toCode($tagName));
        $result =  $this->conn->select(
            "SELECT `name` FROM `dril_tag` WHERE `code` LIKE '".$this->conn->clean($code).
            "%' AND `locale_id`=? LIMIT $limit ",  array( $localeId ) 
        );
        $array = array();
        for($i = 0; $i < count($result); $i++){
            $array[] = $result[$i]['name'];
        }
        return $array;
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

    private function tagExists($bookId, $tagId){
        $result = $this->conn->select(
            "SELECT * FROM dril_book_has_tag WHERE dril_tag_id=? AND dril_book_id=? LIMIT 1",
            array($tagId, $bookId)
        );
        return count($result) == 1;
    }

	private function isTagNameUniqe($name){
		$sql =  "SELECT count(*) as tag_count FROM  dril_tag tag ".
				"WHERE tag.name = ? ";	
		$result =  $this->conn->select( $sql, array($name));
		return $result[0]["tag_count"] == 0;
	}

    private function clear($name){
        $name = trim($this->xssClean($name));
        if(strlen($name) > 50){
            $name = substr($name, 0, 50);
        }
        return $name;
    }

    private function getUserLocale($uid){
        $user = $this->conn->select("SELECT u.`locale_id` as localeId FROM `user` u WHERE `id_user`=$uid ");
        if(count($user) == 0){
            return null;
        }else if($user[0]['localeId'] == null){
            return null;
        }
        return $user[0]['localeId'];
    }

    public function toCode($val){
        $val = preg_replace('~[^\\pL0-9_]+~u', '-', $val);
        $val = trim($val, "-");
        $val = iconv("utf-8", "us-ascii//TRANSLIT", $val);
        $val = strtolower($val);
        $val = preg_replace('~[^-a-z0-9_]+~', '', $val);
        return $val;
    }




    public function xssClean($data){
                $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
                $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
                $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
                $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

                // Remove any attribute starting with "on" or xmlns
                $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

                // Remove javascript: and vbscript: protocols
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

                // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

                // Remove namespaced elements (we do not need them)
                $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

                do{
                        // Remove really unwanted tags
                        $old_data = $data;
                        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
                }
                while ($old_data !== $data);
                return $data;
        }
}



?>