<?php

class Photo {
    private $photoID;
    private $albumID;
    private $name;
    private $description;
    private $rating;
    
    protected $max_file_name_lenght = 20;
    
    public function __construct($album = null, $name = null, $description = "", $rating = null){
        $this->setName($name);
        $this->setAlbumID($album);
        $this->setDescription($description);
        if($rating == null){
            $this->setRating(0);
        }else{
            $this->setRating($rating);
        }
    }

    public function getID(){
        return $this->photoID;
    }
    
    public function getAlbumID(){
        return $this->albumID;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getDescription(){
        return $this->description;
    }
        
    public function getRating(){
        return $this->rating;
    }
    
    public function setID($id){
        $this->photoID = $id;
        return $this;
    }
    
    public function setAlbumID($id){
        $this->albumID = $id;
        return $this;
    }
    
    public function setName($name){
        $name = self::truncateFileName($name);
        $this->name = $name;
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function setRating($rating){
        $this->rating = $rating;
        return $this;
    }
    
    private function truncateFileName($fname){
        $string = explode(".", $fname); //$string[0] = file name, $string[1]= extension
        $string[0] = str_replace('?','',$string[0]);
        $string[0] = str_replace('#','',$string[0]);
        $string[0] = str_replace(' ','_',$string[0]);
        if(strlen($string[0]) > $this->max_file_name_lenght){
            $string[0] = substr($string[0],0,$this->max_file_name_lenght);
        }
        $fname = $string[0]. "." . $string[1];
        return trim($fname);
    }
}