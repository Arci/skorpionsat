<?php

// Local Database settings.
define("DB_HOSTNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");

//Netsons Database settings
//define("DB_HOSTNAME", "mysql.netsons.com");
//define("DB_USERNAME", "viiaosdl_admin");
//define("DB_PASSWORD", "skorpion");

class Database{
    
    //database
    const DB_NAME = "skorpion";
    //Netsons Database settings
    //const DB_NAME = "viiaosdl_skorpion";
    
    //table
    const TABLE_NEWS = "news";
    const TABLE_ALBUM = "album";
    const TABLE_PHOTO = "photo";
    
    //tabella news
    const NEWS_ID = "newsID";
    const NEWS_TITLE = "title";
    const NEWS_DATE = "date";
    const NEWS_AUTHOR = "author";
    const NEWS_CONTENT = "content";
    
    //table album
    const ALBUM_ID = "ID";
    const ALBUM_NAME = "name";
    const ALBUM_DATE = "date";
    const ALBUM_DESCRIPTION = "description";

    //table photo
    const PHOTO_ID = "ID";
    const PHOTO_ALBUM = "album";
    const PHOTO_NAME = "name";
    const PHOTO_RATING = "rating";
    const PHOTO_DESCRIPTION = "description";
    const IS_ALBUM_COVER = "is_album_cover";
    
    static function getStructure(){
        $q = array();
	$q[] =  "CREATE TABLE `" . self::TABLE_NEWS . "` (
                `" . self::NEWS_ID . "` bigint(20) NOT NULL AUTO_INCREMENT,
                `" . self::NEWS_TITLE . "` varchar(100) NOT NULL,
                `" . self::NEWS_DATE . "` varchar(10) NOT NULL,
                `" . self::NEWS_AUTHOR . "` varchar(50) NOT NULL,
                `" . self::NEWS_CONTENT . "` text NOT NULL,
                PRIMARY KEY (`" . self::NEWS_ID . "`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;";
	$q[] =  "CREATE TABLE `" . self::TABLE_ALBUM . "` (
                `" . self::ALBUM_ID . "` bigint(20) NOT NULL AUTO_INCREMENT,
                `" . self::ALBUM_NAME . "` varchar(100) UNIQUE NOT NULL,
                `" . self::ALBUM_DATE . "` date NOT NULL,
                `" . self::ALBUM_DESCRIPTION . "` text,
                PRIMARY KEY (`" . self::ALBUM_ID . "`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;";
        $q[] =  "CREATE TABLE `" . self::TABLE_PHOTO . "` (
                `" . self::PHOTO_ID . "` bigint(20) NOT NULL AUTO_INCREMENT,
		`" . self::PHOTO_ALBUM . "` bigint(20) NOT NULL,
                `" . self::PHOTO_NAME . "` varchar(255) NOT NULL,
                `" . self::PHOTO_RATING . "` int,
                `" . self::PHOTO_DESCRIPTION . "` varchar(100),
		`" . self::IS_ALBUM_COVER . "` varchar(1),
                PRIMARY KEY (`" . self::PHOTO_ID . "`),
		FOREIGN KEY (`" . self::PHOTO_ALBUM . "`) REFERENCES " . self::TABLE_ALBUM . "(`" . self::ALBUM_ID . "`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;";        
        return $q;
    }
}

class DbConnection {
 
    private static $conn;
 
    final private function __construct() {}
    final private function __clone() {}
 
    public static function getConnection() {
        if(is_null(self::$conn)) {
            $conn = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
            mysql_select_db(Database::DB_NAME);
            if (!$conn) {
                die('Could not connect: ' . mysql_error());
            }
        }
        return self::$conn;
    }
}