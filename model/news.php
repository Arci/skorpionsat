<?php

class News {
    private $newsID;
    private $title;
    private $date;
    private $author;
    private $content;

    public function __construct($title = null, $date = null, $author = null, $content = null){
        if($date == null){
            date_default_timezone_set('Europe/Rome');
            $this->date = date("d/m/Y", $_SERVER["REQUEST_TIME"]);
        }else{
            $this->setTitle($title);
            $this->setDate($date);
            $this->setAuthor($author);
            $this->setContent($content);
        }
    }

    public function getID(){
        return $this->newsID;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getDate(){
        return $this->date;
    }
    
    public function getAuthor(){
        return $this->author;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function setID($id){
        $this->newsID = $id;
        return $this;
    }
    
    public function setTitle($title){
        $this->title = $title;
        return $this;
    }
    
    public function setDate($date){
        $this->date = $date;
        return $this;
    }
    
    public function setAuthor($author){
        $this->author = $author;
        return $this;
    }
    
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
}