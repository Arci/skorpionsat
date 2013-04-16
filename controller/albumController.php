<?php

require_once(dirname(__FILE__)."/controller.php");
require_once(DOCUMENT_ROOT."/controller/photoController.php");
require_once(DOCUMENT_ROOT."/model/album.php");

class AlbumController implements Controller{
    
    public function save($album){
        $logger = Logger::getLogger();
        try{
            $logger->debug(__METHOD__, "insert ". $album->getName());
            self::createAlbumFolder($album->getName());
            $query = "INSERT INTO ".Database::TABLE_ALBUM." (".Database::ALBUM_NAME.", ".Database::ALBUM_DATE.", ".Database::ALBUM_DESCRIPTION.") VALUES ('".$album->getName()."', '".$album->getDate()."', '".$album->getDescription()."')";
            DbConnection::getConnection();
            $result = mysql_query($query);        
            if(!$result){
                $logger->query(__METHOD__, $query);
                $logger->error(__METHOD__, "Si &egrave verificato un errore salvando l'album");
                throw new Exception("Si &egrave verificato un errore salvando l'album.");
            }
        }catch (Exception $e){
            $logger->error(__METHOD__, $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
    
    public function update($album){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "update ". $album->getName());
        $oldAlbum = self::loadByID($album->getID());
        $query = "UPDATE ".Database::TABLE_ALBUM."
                SET ".Database::ALBUM_DATE." = '".$album->getDate()."', ".Database::ALBUM_DESCRIPTION." = '".$album->getDescription()."', ".Database::ALBUM_NAME." = '".$album->getName()."'
                WHERE ".Database::ALBUM_ID." = ".$album->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "Si &egrave verificato un errore modificando l'album");
            throw new Exception("Si &egrave verificato un errore modificando l'album.");
        }
        if($oldAlbum->getName() != $album->getName()){
            self::createAlbumFolder($album->getName());
            $photoController = new PhotoController();
            $photoList = $photoController->loadByAlbum($album->getID());
            foreach($photoList as $photo){
                rename(ALBUMS_DIR."/".$oldAlbum->getName()."/".$photo->getName(), ALBUMS_DIR."/".$album->getName()."/".$photo->getName());
            }
            rmdir(ALBUMS_DIR."/".$oldAlbum->getName());
        }
        return $album;
    }
    
    public function delete($album){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "delete ". $album->getName());
        $photoController = new PhotoController();
        try{
            $photoList = $photoController->loadByAlbum($album->getID());
            foreach($photoList as $photo){
                $photoController->delete($photo);
            }
            $query = "DELETE FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_ID." = ".$album->getID()."";
            DbConnection::getConnection();
            $result = mysql_query($query);        
            if(!$result){
                $logger->query(__METHOD__, $query);
                $logger->error(__METHOD__, "Si &egrave verificato un errore eliminando l'album");
                throw new Exception("Si &egrave verificato un errore eliminando l'album.");
            }
            rmdir(ALBUMS_DIR."/".$album->getName());
        }catch (Exception $e){
            $logger->error(__METHOD__, "Si &egrave verificato un errore eliminando le foto dell'album");            
            throw new Exception("Si &egrave verificato un errore eliminando le foto dell'album.");
        }
    }

    public function loadByID($id){
        $logger = Logger::getLogger();
        if(is_null($id)){
            $logger->error(__METHOD__, "Attenzione! Non hai inserito il parametro per la ricerca dell'album - ". $id);            
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT * FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_ID." = $id";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "L'album cercato non &egrave stato trovato");
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }

    public function loadAll($limit = 0){
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_ALBUM." ORDER BY ".Database::ALBUM_DATE." DESC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_ALBUM." ORDER BY ".Database::ALBUM_DATE." DESC";
        }
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            $logger = Logger::getLogger();
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "L'album cercato non &egrave stato trovato");                    
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
        if(is_null($name)){
            $logger->error(__METHOD__, "Attenzione! Non hai inserito il parametro per la ricerca dell'album - " . $name);
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT * FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_NAME." = '$name'";
        $result = mysql_query($query);
        DbConnection::getConnection();
        if(!$result){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "L'album cercato non &egrave stato trovato");
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }
    
    public function getPath($album){
        if(!($album instanceof Album)){
            $album = self::loadByID($album);
        }
        $path = ALBUMS_PATH.$album->getName();
        return $path;
    }
    
    private function createFromDBRow($row){
        $album = new Album($row[Database::ALBUM_NAME],$row[Database::ALBUM_DATE],$row[Database::ALBUM_DESCRIPTION]);
        $album->setID(intval($row[Database::ALBUM_ID]));
        return $album;
    }
    
    private function updateFromDBRow($album, $row){
        $album->setName($row[Database::ALBUM_NAME]);
        $album->setDescription($row[Database::ALBUM_DESCRIPTION]);
        $album->setDate($row[Database::ALBUM_DATE]);
        $album->setID(intval($row[Database::ALBUM_ID]));
        return $album;
    }
    
    private function createAlbumFolder($name){
        $logger = Logger::getLogger();
        if(!file_exists(ALBUMS_DIR.$name)){
            if(!Mkdir(ALBUMS_DIR.$name, 0777)){
                $logger->error(__METHOD__, "Impossibile creare la cartella: ". ALBUMS_PATH.$name);
                throw new Exception("Impossibile creare la cartella per l'album");
            }else{
                $logger->debug(__METHOD__, "create folder for album: ". $name);
            }
        }
    }
}