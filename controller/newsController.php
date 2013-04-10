<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/database.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/news.php");

class NewsController implements Controller{
    
    function save($news){
        $query = "INSERT INTO ".Database::TABLE_NEWS." (".Database::NEWS_TITLE.", ".Database::NEWS_DATE.", ".Database::NEWS_AUTHOR.", ".Database::NEWS_CONTENT.") VALUES ('".$news->getTitle()."', '".$news->getDate()."', '".$news->getAuthor()."', '".$news->getContent()."')";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore salvando la notizia.");
        }
    }
    
    public function update($news){
        $query = "UPDATE ".Database::TABLE_NEWS."
                SET ".Database::NEWS_TITLE." = '".$news->getTitle()."', ".Database::NEWS_AUTHOR." = '".$news->getAuthor()."', ".Database::NEWS_DATE." = '".$news->getDate()."', ".Database::NEWS_CONTENT." = '".$news->getContent()."'
                WHERE ".Database::NEWS_ID." = ".$news->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore modificando la notizia.");
        }
        return $news;
    }
    
    public function delete($news){
        $query = "DELETE FROM ".Database::TABLE_NEWS." WHERE ".Database::NEWS_ID." = ".$news->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore eliminando la notizia.");
        }
    }

    public function loadByID($id){
        if(is_null($id)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca delle notizie.");
        }
        $query = "SELECT * FROM ".Database::TABLE_NEWS." WHERE ".Database::NEWS_ID." = $id";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            throw new Exception("La notizia cercata non &egrave stata trovata.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }
    
    public function loadAll($limit=0){
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_NEWS." ORDER BY ".Database::NEWS_DATE.",".Database::NEWS_ID ." DESC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_NEWS." ORDER BY ".Database::NEWS_DATE.",".Database::NEWS_ID ." DESC";
        }
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            throw new Exception("La notizia cercata non &egrave stata trovata.");
        }
        $newsList = array();
        while($row = mysql_fetch_array($result)){
            $newsList[] = self::createFromDBRow($row);
        }
        return $newsList;
    }
        
    private function createFromDBRow($row){
        $news = new News($row[Database::NEWS_TITLE],$row[Database::NEWS_DATE],$row[Database::NEWS_AUTHOR],$row[Database::NEWS_CONTENT]);
        $news->setID(intval($row[Database::NEWS_ID]));
        return $news;
    }
    
    private function updateFromDBRow($news, $row){
        $news->setTitle($row[Database::NEWS_TITLE]);
        $news->setContent($row[Database::NEWS_CONTENT]);
        $news->setDate($row[Database::NEWS_DATE]);
        $news->setID(intval($row[Database::NEWS_ID]));
        return $news;
    }  
}