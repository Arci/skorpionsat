<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/database.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../logger.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/news.php");

class NewsController implements Controller{
    
    function save($news){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "saving ". $news->getTitle());
        $query = "INSERT INTO ".Database::TABLE_NEWS." (".Database::NEWS_TITLE.", ".Database::NEWS_DATE.", ".Database::NEWS_AUTHOR.", ".Database::NEWS_CONTENT.") VALUES ('".$news->getTitle()."', '".$news->getDate()."', '".$news->getAuthor()."', '".$news->getContent()."')";
        $logger->query(__METHOD__, $query); 
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore salvando la notizia.");
        }
    }
    
    public function update($news){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "updating ". $news->getTitle());
        $query = "UPDATE ".Database::TABLE_NEWS."
                SET ".Database::NEWS_TITLE." = '".$news->getTitle()."', ".Database::NEWS_AUTHOR." = '".$news->getAuthor()."', ".Database::NEWS_DATE." = '".$news->getDate()."', ".Database::NEWS_CONTENT." = '".$news->getContent()."'
                WHERE ".Database::NEWS_ID." = ".$news->getID()."";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore modificando la notizia.");
        }
        return $news;
    }
    
    public function delete($news){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "deleting ". $news->getTitle());
        $query = "DELETE FROM ".Database::TABLE_NEWS." WHERE ".Database::NEWS_ID." = ".$news->getID()."";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore eliminando la notizia.");
        }
    }

    public function loadByID($id){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading news ". $news->getTitle());
        if(is_null($id)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca delle notizie.");
        }
        $query = "SELECT * FROM ".Database::TABLE_NEWS." WHERE ".Database::NEWS_ID." = $id";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            throw new Exception("La notizia cercata non &egrave stata trovata.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }
    
    public function loadAll($limit=0){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading all limit: ". $limit);
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_NEWS." ORDER BY ".Database::NEWS_DATE.",".Database::NEWS_ID ." DESC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_NEWS." ORDER BY ".Database::NEWS_DATE.",".Database::NEWS_ID ." DESC";
        }
        $logger->query(__METHOD__, $query);
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
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving from database news: ". $news->getTitle());
        return $news;
    }
    
    private function updateFromDBRow($news, $row){
        $news->setTitle($row[Database::NEWS_TITLE]);
        $news->setContent($row[Database::NEWS_CONTENT]);
        $news->setDate($row[Database::NEWS_DATE]);
        $news->setID(intval($row[Database::NEWS_ID]));
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "updating from databae news: ".$news->getTitle());
        return $news;
    }  
}