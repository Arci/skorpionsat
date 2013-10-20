<?php

require_once(dirname(__FILE__)."/../controller/database.php");

class Filter{

    public static function sqlEscape($text){
        return self::encodeSql($text);
    }

    private static function encodeSql($string){
        DbConnection::getConnection();
        return mysql_real_escape_string(self::escape_utf8($string));
    }
    
}