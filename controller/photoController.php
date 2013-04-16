<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/database.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../logger.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/photo.php");

class PhotoController implements Controller{
    
    public function upload($photo, $tmp_name){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "uploading ". $photo->getName()); 
        try{
            self::save($photo);
            $albumController = new AlbumController();
            $path = $albumController->getPath($photo->getAlbumID())."/".$photo->getName();
            if (!move_uploaded_file($tmp_name, $path)){
                throw new Exception("Si &egrave verificato un errore caricando la foto.");
            }
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function save($photo){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "saving ". $photo->getName()); 
        $isAlbumCover = "f";
        if($photo->isAlbumCover()){
            $isAlbumCover = "t";
        }
        $query = "INSERT INTO ".Database::TABLE_PHOTO." (".Database::PHOTO_ALBUM.", ".Database::PHOTO_NAME.", ".Database::PHOTO_RATING.", ".Database::PHOTO_DESCRIPTION.", ".Database::IS_ALBUM_COVER.") VALUES ('".$photo->getAlbumID()."', '".$photo->getName()."', '".$photo->getRating()."', '".$photo->getDescription()."', '".$isAlbumCover."')";
        $logger->query(__METHOD__, $query); 
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore salvando la foto.");
            $logger->error(__METHOD__, $e->getMessage() ." photo: ". $photo->getName());
        }
    }
    
    public function update($photo){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "updating ". $photo->getName());
        $isAlbumCover = "f";
        if($photo->isAlbumCover()){
            $isAlbumCover = "t";
        }
        $query = "UPDATE ".Database::TABLE_PHOTO."
                SET ".Database::PHOTO_ALBUM." = '".$photo->getAlbumID()."', ".Database::PHOTO_DESCRIPTION." = '".$photo->getDescription()."', ".Database::PHOTO_NAME." = '".$photo->getName()."', ".Database::PHOTO_RATING." = '".$photo->getRating()."', ".Database::IS_ALBUM_COVER." = '".$isAlbumCover."'
                WHERE ".Database::PHOTO_ID." = ".$photo->getID()."";
        $logger->query(__METHOD__, $query); 
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore modificando la foto.");
        }
        return $photo;
    }
    
    public function delete($photo){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "deleting ". $photo->getName());
        $query = "DELETE FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ID." = ".$photo->getID()."";
        $logger->query(__METHOD__, $query);     
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore eliminando la foto.");
        }
        unlink(self::buildPath($photo));
    }

    public function loadByID($id){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading photo ". $id);
        if(is_null($id)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca della foto.");
        }
        $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ID." = $id";
        $logger->query(__METHOD__, $query); 
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            throw new Exception("La  foto cercata non &egrave stata trovata.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }

    public function loadAll($limit=0){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "load all limit: ". $limit);
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." ORDER BY '".Database::PHOTO_RATING."' ASC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." ORDER BY '".Database::PHOTO_RATING."' ASC";
        }
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            throw new Exception("La foto cercata non &egrave stata trovata.");
        }
        $photoList = array();
        while($row = mysql_fetch_array($result)){
            $photoList[] = self::createFromDBRow($row);
        }
        return $photoList;
    }
    
    public function loadAlbumCover($albumID){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading cover of album ". $albumID);
        $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $albumID AND ".Database::IS_ALBUM_COVER." = 't'";
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result || count($result) > 1){
            throw new Exception("La foto cercata non &egrave stata trovata.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }
    
    public function loadByAlbum($albumID, $limit = 0){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "loading album ". $albumID . " with limit: ". $limit);
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $albumID ORDER BY '".Database::PHOTO_RATING."' ASC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $albumID ORDER BY '".Database::PHOTO_RATING."' ASC";
        }
        $logger->query(__METHOD__, $query);
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            throw new Exception("La foto cercata non &egrave stata trovata.");
        }
        $photoAlbum = array();
        while($row = mysql_fetch_array($result)){
            $photoAlbum[] = self::createFromDBRow($row);
        }
        return $photoAlbum;
    }
    
    public function buildRelativePath($photo){
        $albumController = new AlbumController();
        $path = $albumController->getRelativePath($photo->getAlbumID())."/".$photo->getName();
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving ". $path);
        return $path;
    }
    
    public function buildPath($photo){
        $albumController = new AlbumController();
        $path = $albumController->getPath($photo->getAlbumID())."/".$photo->getName();
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving ". $path);
        return $path;
    }
        
    private function createFromDBRow($row){
        $photo = new Photo($row[Database::PHOTO_ALBUM],$row[Database::PHOTO_NAME], $row[Database::PHOTO_DESCRIPTION], $row[Database::PHOTO_RATING], $row[Database::IS_ALBUM_COVER]);
        $photo->setID(intval($row[Database::PHOTO_ID]));
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "retriving from database photo: ". $photo->getName());
        return $photo;
    }
    
    private function updateFromDBRow($photo, $row){
        $photo->setName($row[Database::PHOTO_NAME]);
        $photo->setDescription($row[Database::PHOTO_DESCRIPTION]);
        $photo->setRating($row[Database::PHOTO_RATING]);
        $photo->setID(intval($row[Database::PHOTO_ID]));
        $isAlbumCover = false;
        if($row[Database::IS_ALBUM_COVER] == "t"){
            $isAlbumCover = true;
        }
        $photo->setIfIsAlbumCover($isAlbumCover);
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "updating from databae photo: ".$photo->getName());
        return $photo;
    }    
}