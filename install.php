<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");
require_once(DOCUMENT_ROOT."/controller/database.php");

buildTopPage("");

buildContent();

buildBottomPage();

function buildContent(){
    echo "<table
        style='width: 100%; font-size: 1.1em; background-image: url(".IMAGES_PATH."background_05.png); margin: 0 auto; line-height: 3em;
        '><tr valign='top'>";
    
    //creo il database (lo droppo se esiste)
    echo "<td style='border-right: 0.5em solid #000000; width: 230em; padding: 1.5em;'>";
    echo "<p><span style='color:blue'><b><u>Installazione Database</u></b></span></p>";
    DbConnection::getConnection();
    if(mysql_select_db(Database::DB_NAME)){
            echo "<p><span style='color:orange'>DATABASE ALREADY EXISTS</span></p>";
            echo "<p>Trying to drop database</p>";
            mysql_query("DROP DATABASE " . Database::DB_NAME);
            echo "<p><span style='color:green'><b><i><u>DATABASE DROPPED!</u></i></b></span></p>";
    }
    echo "<p>Trying to create Database</p>";
    mysql_query("CREATE DATABASE " . Database::DB_NAME);
    echo "<p><span style='color:green'>DATABASE CREATED</span></p>";
    echo "</td>";
    
    //elimino precedenti upload e log
    echo "<td style='border-right: 0.5em solid #000000; width: 280em; padding: 1.5em;'>";
    echo "<p><span style='color:blue'><b><u>Gestione File System</u></b></span></p>";
    if(!file_exists("albums")){
        echo "<p>Trying to create 'albums' folder</p>";
        mkdir("albums", 0777);
        echo "<p><span style='color:green'>FOLDER 'ALBUMS' CREATED</span></p>";
    } else {
        echo "<p><span style='color:orange'>FOLDER 'ALBUMS' ALREADY EXISTS!</span></p>";
        echo "<p>Trying to delete 'albums' folder</p>";
        delfolder("albums", true);
        echo "<p><span style='color:green'><b><i><u>FOLDER 'ALBUMS' DELETED!</u></i></b></span></p>";
        echo "<p>Trying to create 'albums' folder</p>";
        mkdir("albums", 0777);
        echo "<p><span style='color:green'>FOLDER 'ALBUMS' CREATED</span></p>";
    }
    if(!file_exists("log")){
        echo "<p>Trying to create 'log' folder</p>";
        mkdir("log", 0777);
        echo "<p><span style='color:green'>FOLDER 'LOG' CREATED</span></p>";
    } else {
        echo "<p><span style='color:orange'>FOLDER 'LOG' ALREADY EXISTS!</span></p>";
        echo "<p>Trying to delete 'log' folder</p>";
        delfolder("log", true);
        echo "<p><span style='color:green'><b><i><u>FOLDER 'LOG' DELETED!</u></i></b></span></p>";
        echo "<p>Trying to create 'log' folder</p>";
        mkdir("log", 0777);
        echo "<p><span style='color:green'>FOLDER 'LOG' CREATED</span></p>";
    }
    if(!file_exists("slideshow")){
        echo "<p>Trying to create 'slideshow' folder</p>";
        mkdir("slideshow", 0777);
        echo "<p><span style='color:green'>FOLDER 'SLIDESHOW' CREATED</span></p>";
    } else {
        echo "<p><span style='color:orange'>FOLDER 'SLIDESHOW' ALREADY EXISTS!</span></p>";
        echo "<p>Trying to delete 'slideshow' folder</p>";
        delfolder("slideshow", true);
        echo "<p><span style='color:green'><b><i><u>FOLDER 'SLIDESHOW' DELETED!</u></i></b></span></p>";
        echo "<p>Trying to create 'slideshow' folder</p>";
        mkdir("slideshow", 0777);
        echo "<p><span style='color:green'>FOLDER 'SLIDESHOW' CREATED</span></p>";
    }
    echo "</td>";

    //creo le tabelle
    echo "<td style='width: 220em; padding: 1.5em;'>";
    echo "<p><span style='color:blue'><b><u>Inserimento Tabelle </b></u></span></p>";
    echo "<p>Trying to insert Tables into database</p>";
    $createTable = Database::getStructure();
    for($i=0; $i<count($createTable); $i++) {
        mysql_select_db(Database::DB_NAME);
        $s = explode("`",$createTable[$i]);
        $tbExist = mysql_query('select * from ' . $s[1]);
        if(!$tbExist){
            $result = mysql_query($createTable[$i]);
            if($result)
                echo"<p><span style='color:green'>TABLE " . $s[1] . " INSTALLED</span></p>";
            else {
                $s = str_replace(",", ",<br />", $createTable[$i]);
                $s = str_replace(") ENGINE", ")<br />ENGINE", $s);
                echo "<p><span style='color:red'>SOMETHING WENT WRONG WITH:</span><br />" . $s . "</p>" . mysql_error();
            }
        }else
            echo"<p><span style='color:orange'>TABLE " . $s[1] . " ALREADY EXISTS</span></p>";
    }
    echo "<p><span style='color:green'>INSERTED TABLES</span></p>";
    echo "</td>";
    
    echo "</tr></table>";
    
    $logger = Logger::getLogger();
    $logger->debug("Install::install", "INSTALLING APPLICATION"); 
}

/*
 *Cancella una cartella
 *@param $dirname: cartella da rimuovere
 *@param $subdir: boolean, se true cancella anche le sottodirectory e sottofile
*/
function delfolder($dirname, $subdir){
    if ($subdir){
        delfolderdeep($dirname);
    } else {
        $handle = opendir($dirname);
        while (false != ($file = readdir($handle))) {
            if(is_file($dirname."/".$file)){
                    unlink($dirname."/".$file);
            }
        }
    }
}

/*
 *Cancella una cartella e tutte le sottocartelle e sottofile
 *@param $dirname: cartella da rimuovere
*/
function delfolderdeep($dirname){
    if (!file_exists($dirname)) {
        return false;
    }
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }
    
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        delfolderdeep($dirname . DIRECTORY_SEPARATOR . $entry);
    }
 
    $dir->close();
    return rmdir($dirname);
}
