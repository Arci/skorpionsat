<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/database.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/photo.php");

class PhotoController implements Controller{
    
    public function upload($photo, $tmp_name){
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
        $query = "INSERT INTO ".Database::TABLE_PHOTO." (".Database::PHOTO_ALBUM.", ".Database::PHOTO_NAME.", ".Database::PHOTO_RATING.", ".Database::PHOTO_DESCRIPTION.") VALUES ('".$photo->getAlbumID()."', '".$photo->getName()."', '".$photo->getRating()."', '".$photo->getDescription()."')";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore salvando la foto.");
        }
    }
    
    public function update($photo){
        $query = "UPDATE ".Database::TABLE_PHOTO."
                SET ".Database::PHOTO_ALBUM." = '".$photo->getAlbumID()."', ".Database::PHOTO_DESCRIPTION." = '".$photo->getDescription()."', ".Database::PHOTO_NAME." = '".$photo->getName()."', ".Database::PHOTO_RATING." = '".$photo->getRating()."'
                WHERE ".Database::PHOTO_ID." = ".$photo->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore modificando la foto.");
        }
        return $photo;
    }
    
    public function delete($photo){
        $query = "DELETE FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ID." = ".$photo->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);        
        if(!$result){
            throw new Exception("Si &egrave verificato un errore eliminando la foto.");
        }
        unlink(self::buildPath($photo));
    }

    public function loadByID($id){
        if(is_null($id)){
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca della foto.");
        }
        $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ID." = $id";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            throw new Exception("La  foto cercata non &egrave stata trovata.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }

    public function loadAll($limit=0){
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." ORDER BY '".Database::PHOTO_RATING."' ASC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." ORDER BY '".Database::PHOTO_RATING."' ASC";
        }
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
    
    public function loadByAlbum($albumID){
        $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $albumID ORDER BY '".Database::PHOTO_RATING."' ASC";
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
    
    public function buildPath($photo){
        $albumController = new AlbumController();
        return $albumController->getRelativePath($photo->getAlbumID())."/".$photo->getName();
    }
        
    private function createFromDBRow($row){
        $photo = new Photo($row[Database::PHOTO_ALBUM],$row[Database::PHOTO_NAME], $row[Database::PHOTO_DESCRIPTION], $row[Database::PHOTO_RATING]);
        $photo->setID(intval($row[Database::PHOTO_ID]));
        return $photo;
    }
    
    private function updateFromDBRow($photo, $row){
        $photo->setName($row[Database::PHOTO_NAME]);
        $photo->setDescription($row[Database::PHOTO_DESCRIPTION]);
        $photo->setRating($row[Database::PHOTO_RATING]);
        $photo->setID(intval($row[Database::PHOTO_ID]));
        return $photo;
    }    
}