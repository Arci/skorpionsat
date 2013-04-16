<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/photoController.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/database.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../logger.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/album.php");

//define("ALBUMS", "http://www.skorpionsat.com/albums/");
//define("ALBUMS_RELATIVE", "../albums/");
define("ALBUMS", "/Skorpionsat/site/albums/");
define("ALBUMS_RELATIVE", "../albums/");

class AlbumController implements Controller{
    
    public function save($album){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "saving ". $album->getName());
        $query = "INSERT INTO ".Database::TABLE_ALBUM." (".Database::ALBUM_NAME.", ".Database::ALBUM_DATE.", ".Database::ALBUM_DESCRIPTION.") VALUES ('".$album->getName()."', '".$album->getDate()."', '".$album->getDescription()."')";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore salvando l'album.");
        }
        self::createAlbumFolder($album->getName());
    }
    
    public function update($album){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "updating ". $album->getName());
        $oldAlbum = self::loadByID($album->getID());
        $query = "UPDATE ".Database::TABLE_ALBUM."
                SET ".Database::ALBUM_DATE." = '".$album->getDate()."', ".Database::ALBUM_DESCRIPTION." = '".$album->getDescription()."', ".Database::ALBUM_NAME." = '".$album->getName()."'
                WHERE ".Database::ALBUM_ID." = ".$album->getID()."";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore modificando l'album.");
        }
        if($oldAlbum->getName() != $album->getName()){
            self::createAlbumFolder($album->getName());
            $photoController = new PhotoController();
            $photoList = $photoController->loadByAlbum($album->getID());
            foreach($photoList as $photo){
                rename(self::getPath($oldAlbum)."/".$photo->getName(), self::getPath($album)."/".$photo->getName());
            }
            rmdir(self::getPath($oldAlbum));
        }
        return $album;
    }
    
    public function delete($album){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "deleting ". $album->getName());
        $photoController = new PhotoController();
        try{
            $photoList = $photoController->loadByAlbum($album->getID());
            foreach($photoList as $photo){
                $photoController->delete($photo);
            }
            $query = "DELETE FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_ID." = ".$album->getID()."";
            $logger->query(__METHOD__, $query);
            DbConnection::getConnection();
            $result = mysql_query($query);        
            if(!$result){
                throw new Exception("Si &egrave verificato un errore eliminando l'album.");
            }
            rmdir(self::getPath($album));
        }catch (Exception $e){
            throw new Exception("Si &egrave verificato un errore eliminando le foto dell'album.");
        }
    }

    public function loadByID($id){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading album ". $id);
        if(is_null($id)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT * FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_ID." = $id";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }

    public function loadAll($limit = 0){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "load all limit: ". $limit);
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_ALBUM." ORDER BY ".Database::ALBUM_DATE." DESC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_ALBUM." ORDER BY ".Database::ALBUM_DATE." DESC";
        }
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $albumList = array();
        while($row = mysql_fetch_array($result)){
            $albumList[] = self::createFromDBRow($row);
        }
        return $albumList;
    }
    
    public function loadByName($name){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading album: ". $name);
        if(is_null($name)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT * FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_NAME." = '$name'";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }
    
    public function getPath($album){
        if(!($album instanceof Album)){
            $album = self::loadByID($album);
        }
        $path = ALBUMS.$album->getName();
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving ". $path);
        return $path;
    }
    
    public function getRelativePath($albumID){
        $album = self::loadByID($albumID);
        $path = ALBUMS_RELATIVE.$album->getName();
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving ". $path);
        return $path;
    }
    
    private function createFromDBRow($row){
        $album = new Album($row[Database::ALBUM_NAME],$row[Database::ALBUM_DATE],$row[Database::ALBUM_DESCRIPTION]);
        $album->setID(intval($row[Database::ALBUM_ID]));
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving from database album: ". $album->getName());
        return $album;
    }
    
    private function updateFromDBRow($album, $row){
        $album->setName($row[Database::ALBUM_NAME]);
        $album->setDescription($row[Database::ALBUM_DESCRIPTION]);
        $album->setDate($row[Database::ALBUM_DATE]);
        $album->setID(intval($row[Database::ALBUM_ID]));
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "updating from databae album: ". $album->getName());
        return $album;
    }
    
    private function createAlbumFolder($name){
        if(!file_exists(ALBUMS.$name)){
            Mkdir(ALBUMS.$name,0777);
        }
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "creating folder for album: ". $name);
    }
}