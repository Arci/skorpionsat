<?php

class Album {
    private $albumID;
    private $name;
    private $description;
    private $date;

    public function __construct($name = null, $date = null, $description = null){
        $this->setName($name);
        $this->setDate($date);
        $this->setDescription($description);
    }

    public function getID(){
        return $this->albumID;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getDate(){
        return $this->date;
    }
    
    public function getDescription(){
        return $this->description;
    }
    
    public function setID($id){
        $this->albumID = $id;
        return $this;
    }
    
    public function setName($name){
        $name = str_replace("?", "", $name);
        $name = str_replace("#", "", $name);
        $this->name = $name;
        return $this;
    }
    
    public function setDate($date){
        $this->date = str_replace("-", "/", $date);
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
}