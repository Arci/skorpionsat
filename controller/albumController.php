<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/photoController.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/database.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/album.php");
define("ALBUMS", pathinfo(__FILE__, PATHINFO_DIRNAME)."/../albums/");
define("ALBUMS_RELATIVE", "../albums/");

class AlbumController implements Controller{
    
    public function save($album){
        $query = "INSERT INTO ".Database::TABLE_ALBUM." (".Database::ALBUM_NAME.", ".Database::ALBUM_DATE.", ".Database::ALBUM_DESCRIPTION.") VALUES ('".$album->getName()."', '".$album->getDate()."', '".$album->getDescription()."')";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore salvando l'album.");
        }
        self::createAlbumFolder($album->getName());
    }
    
    public function update($album){
        $oldAlbum = self::loadByID($album->getID());
        $query = "UPDATE ".Database::TABLE_ALBUM."
                SET ".Database::ALBUM_DATE." = '".$album->getDate()."', ".Database::ALBUM_DESCRIPTION." = '".$album->getDescription()."', ".Database::ALBUM_NAME." = '".$album->getName()."'
                WHERE ".Database::ALBUM_ID." = ".$album->getID()."";
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
                throw new Exception("Si &egrave verificato un errore eliminando l'album.");
            }
            rmdir(self::getPath($album));
        }catch (Exception $e){
            throw new Exception("Si &egrave verificato un errore eliminando le foto dell'album.");
        }
    }

    public function loadByID($id){
        if(is_null($id)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT * FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_ID." = $id";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
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
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $albumList = array();
        while($row = mysql_fetch_array($result)){
            $albumList[] = self::createFromDBRow($row);
        }
        return $albumList;
    }
    
    public function loadByName($name){
        if(is_null($name)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT * FROM ".Database::TABLE_ALBUM." WHERE ".Database::ALBUM_NAME." = '$name'";
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
        return ALBUMS.$album->getName();
    }
    
    public function getRelativePath($albumID){
        $album = self::loadByID($albumID);
        return ALBUMS_RELATIVE.$album->getName();
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
        if(!file_exists(ALBUMS.$name)){
            Mkdir(ALBUMS.$name,0777);
        }
    }
}