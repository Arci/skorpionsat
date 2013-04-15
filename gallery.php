<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller/albumController.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller/photoController.php");

buildTopPage("gallery");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
        <?php
        $albumController = new AlbumController();
        $albumList = $albumController->loadAll();
        if(count($albumList) == 0){
            echo "<p>Non ci sono album!</p>";
        }else{
            ?>
            <div id="main-wrapper">
                <div id="album-preview">
                    <div id="album-image">
                        <?php
                        foreach($albumList as $album){
                            $photoController = new PhotoController();
                            $photo = $photoController->loadAlbumCover($album->getID());
                            echo "<div class=\"album-slider\"><a class=\"image-link\" href=\"photogallery.php?id=".$album->getID()."\"><img src=\"".$photoController->buildPath($photo)."\" /></a></div>";
                        }
                        ?>
                    </div>
                    <div class="description">
                        <div class="button-container left"><img id="button-left" src="<?php echo IMAGES_PATH."left-button.png"; ?>" /></div>
                        <div class="info-container left">
                            <?php
                            for($i=0; $i < count($albumList); $i++){
                                $album = $albumList[$i];
                                echo "<div class=\"info left\" id=\"".($i+1)."\">
                                    <p class=\"album-date\">".reverseDate($album->getDate())."</p>
                                    <p class=\"album-title\"><a class=\"title-link\" href=\"photogallery.php?id=".$album->getID()."\">".$album->getName()."</a></p> 
                                    <p class=\"album-description\">".$album->getDescription()."</p>
                                </div>";
                            }
                            ?>
                        </div>
                        <div class="button-container left"><img id="button-right" src="<?php echo IMAGES_PATH."right-button.png"; ?>" /></div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}