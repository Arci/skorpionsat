<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller/albumController.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller/photoController.php");

buildTopPage("photogallery");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
        <div id="back-to-albums">
            <a href="gallery.php"><p>Torna agli album</p></a>
        </div>
        <?php
            if(array_key_exists("id", $_GET) && is_int(intval($_GET["id"]))){
                $albumController = new AlbumController();
                $photoController = new PhotoController();
                $photoList = $photoController->loadByAlbum(intval($_GET["id"]));
                echo "<div id=\"gallery\">";
                foreach($photoList as $photo){
                    echo "<a href=\"".$photoController->buildPath($photo)."\" title=\"".$photo->getDescription()."\">
                            <img src=\"".$photoController->buildPath($photo)."\" /></a>";
                }
                echo "</div>";
                
            } else {
                echo "<p class=\"error\">Impossibile determinare l'album da visualizzare</p>";
            }
        ?>
    </div>
    <?php
}