<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");
require_once(DOCUMENT_ROOT."/controller/albumController.php");
require_once(DOCUMENT_ROOT."/controller/photoController.php");

try{
	$albumControlelr = new AlbumController();
	$album = $albumControlelr->loadByID(intval($_GET["id"]));

	buildTopPage("photogallery", $album->getDescription());

	buildContent();

	buildBottomPage();
}catch(Exception $e){
	buildErrorPage($e->getMessage());
}

function buildContent(){
    ?>
    <div id="content">
            <?php
            if(array_key_exists("id", $_GET) && is_int(intval($_GET["id"]))){
                ?>
                <div class="back left">
                    <p><a href="gallery.php"><img class="small-thimbnail" src="<?php echo IMAGES_PATH."back.png"; ?>" /></a></p>
                </div>
                <div id="photogallery" class="left">
                    <?php
                    $photoController = new PhotoController();
                    $photoList = $photoController->loadByAlbum(intval($_GET["id"]));
                    foreach($photoList as $photo){
                        echo "<a href=\"".$photoController->buildPath($photo)."\" rel=\"prettyPhoto[pp_gal]\" title=\"".$photo->getDescription()."\">
                                <img src=\"".$photoController->buildPath($photo)."\" /></a>";
                    }
                    ?>
                    </div>
                <div class="clear"></div><?php
            } else {
                echo "<p class=\"normalized-error\">Impossibile determinare l'album da visualizzare</p>";
            }
            ?>

    </div>
    <?php
}
