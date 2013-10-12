<?php

require_once(dirname(__FILE__)."/controller.php");
require_once(DOCUMENT_ROOT."/model/photo.php");

class PhotoController implements Controller{

    public function upload($photo, $tmp_name){
        $logger = Logger::getLogger();
        try{
            $photoPath = ALBUMS_DIR.$photo->getAlbumID()."/".$photo->getName();
            if (!move_uploaded_file($tmp_name, $photoPath)){
                $logger->error(__METHOD__, "Si &egrave verificato un errore caricando la foto");
                throw new Exception("Si &egrave verificato un errore caricando la foto.");
            }
            self::compress($photoPath, IMAGE_QUALITY);
            self::save($photo);
        }catch (Exception $e){
            $logger->error(__METHOD__, $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private function compress($source, $quality) {
        ini_set('memory_limit', '100M'); //imagecreatefrompng causes lot of memory usage
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($source);
        }
        elseif ($info['mime'] == 'image/gif'){
            $image = imagecreatefromgif($source);
        }
        elseif ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($source);
        }
        imagejpeg($image, $source, $quality);
    }

    public function save($photo){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "insert ". $photo->getName());
        $isAlbumCover = "f";
        if($photo->isAlbumCover()){
            $isAlbumCover = "t";
        }
        $query = "INSERT INTO ".Database::TABLE_PHOTO." (".Database::PHOTO_ALBUM.", ".Database::PHOTO_NAME.", ".Database::PHOTO_RATING.", ".Database::PHOTO_DESCRIPTION.", ".Database::IS_ALBUM_COVER.") VALUES ('".$photo->getAlbumID()."', '".$photo->getName()."', '".$photo->getRating()."', '".$photo->getDescription()."', '".$isAlbumCover."')";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "Si &egrave verificato un errore salvando la foto");
            throw new Exception("Si &egrave verificato un errore salvando la foto.");
        }
    }

    public function update($photo){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "update ". $photo->getName());
        $isAlbumCover = "f";
        if($photo->isAlbumCover()){
            $isAlbumCover = "t";
        }
        $query = "UPDATE ".Database::TABLE_PHOTO."
                SET ".Database::PHOTO_ALBUM." = '".$photo->getAlbumID()."', ".Database::PHOTO_DESCRIPTION." = '".$photo->getDescription()."', ".Database::PHOTO_NAME." = '".$photo->getName()."', ".Database::PHOTO_RATING." = '".$photo->getRating()."', ".Database::IS_ALBUM_COVER." = '".$isAlbumCover."'
                WHERE ".Database::PHOTO_ID." = ".$photo->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "Si &egrave verificato un errore modificando la foto");
            throw new Exception("Si &egrave verificato un errore modificando la foto.");
        }
        return $photo;
    }

    public function delete($photo){
        $logger = Logger::getLogger();
        $logger->debug(__METHOD__, "delete ". $photo->getName());
        $query = "DELETE FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ID." = ".$photo->getID()."";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "Si &egrave verificato un errore eliminando la foto");
            throw new Exception("Si &egrave verificato un errore eliminando la foto.");
        }
        unlink(ALBUMS_DIR.$photo->getAlbumID()."/".$photo->getName());
    }

    public function countNumberOfPhoto($albumID){
        $logger = Logger::getLogger();
        if(is_null($albumID)){
            $logger->error(__METHOD__, "Attenzione! Non hai inserito il parametro per la ricerca dell'album - " . $albumID);
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca dell'album.");
        }
        $query = "SELECT COUNT(*) AS count FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = '$albumID'";
        $result = mysql_query($query);
        DbConnection::getConnection();
        if(!$result){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "L'album cercato non &egrave stato trovato");
            throw new Exception("L'album cercato non &egrave stato trovato.");
        }
        $row = mysql_fetch_array($result);
        return $row['count'];
    }

    public function loadByID($id){
        $logger = Logger::getLogger();
        if(is_null($id)){
            $logger->error(__METHOD__, "Attenzione! Non hai inserito il parametro per la ricerca della foto - ". $id);
            throw new Exception("Attenzione! Non hai inserito il parametro per la ricerca della foto.");
        }
        $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ID." = $id";
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(mysql_num_rows($result) != 1){
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "La  foto cercata non &egrave stata trovata");
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
            $logger = Logger::getLogger();
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "La  foto cercata non &egrave stata trovata");
            throw new Exception("La foto cercata non &egrave stata trovata.");
        }
        $photoList = array();
        while($row = mysql_fetch_array($result)){
            $photoList[] = self::createFromDBRow($row);
        }
        return $photoList;
    }

    public function loadByAlbum($albumID, $limit = 0){
        if($limit > 0 ){
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $albumID ORDER BY '".Database::PHOTO_RATING."' ASC LIMIT $limit";
        }else{
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $albumID ORDER BY '".Database::PHOTO_RATING."' ASC";
        }
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result){
            $logger = Logger::getLogger();
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "La  foto cercata non &egrave stata trovata");
            throw new Exception("La foto cercata non &egrave stata trovata.");
        }
        $photoAlbum = array();
        while($row = mysql_fetch_array($result)){
            $photoAlbum[] = self::createFromDBRow($row);
        }
        return $photoAlbum;
    }

    public function loadAlbumCover($album){
        if($album instanceof Album){
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = ".$album->getID()." AND ".Database::IS_ALBUM_COVER." = 't'";
        }else{
            //album = albumID
            $query = "SELECT * FROM ".Database::TABLE_PHOTO." WHERE ".Database::PHOTO_ALBUM." = $album AND ".Database::IS_ALBUM_COVER." = 't'";
        }
        DbConnection::getConnection();
        $result = mysql_query($query);
        if(!$result || count($result) > 1){
            $logger = Logger::getLogger();
            $logger->query(__METHOD__, $query);
            $logger->error(__METHOD__, "La foto di copertina non &egrave stata trovata");
            throw new Exception("La foto di copertina non &egrave stata trovata.");
        }
        $row = mysql_fetch_array($result);
        return self::createFromDBRow($row);
    }

    public function buildPath($photo){
        $albumController = new AlbumController();
        $path = $albumController->getPath($photo->getAlbumID())."/".$photo->getName();
        return $path;
    }

    private function createFromDBRow($row){
        $photo = new Photo($row[Database::PHOTO_ALBUM],$row[Database::PHOTO_NAME], $row[Database::PHOTO_DESCRIPTION], $row[Database::PHOTO_RATING], $row[Database::IS_ALBUM_COVER]);
        $photo->setID(intval($row[Database::PHOTO_ID]));
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
        return $photo;
    }
}