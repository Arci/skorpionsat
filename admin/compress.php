<?php

require_once(dirname(__FILE__)."/../controller/photoController.php");
require_once(dirname(__FILE__)."/../settings.php");

$photoController = new PhotoController();

echo "COMPRESS OF ALBUMS IMAGES<br/><br/>";
$imagePathList = array();
$imagePathList = getImagesPath(dirname(__FILE__)."/../albums");
if($imagePathList == null){
    echo "No images in albums<br/><br/>";
}
foreach($imagePathList as $imagePath){
    echo "compressing: " . $imagePath;
    $photoController->compress($imagePath, IMAGE_QUALITY);
    echo " ----- DONE!<br/>";
}

echo "<br/>COMPRESS OF SLIDESHOW IMAGES<br/><br/>";
$imagePathList = array();
$imagePathList = getImagesPath(dirname(__FILE__)."/../slideshow");
if($imagePathList == null){
    echo "No images in slideshow<br/><br/>";
}
foreach($imagePathList as $imagePath){
    echo "compressing: " . $imagePath;
    $photoController->compress($imagePath, IMAGE_QUALITY);
    echo " ----- DONE!<br/>";
}
echo "<br/><br/>EVERYTHING DONE!";


function getImagesPath($path){
    $arrfiles = array();
    if (is_dir($path)) {
        if ($handle = opendir($path)) {
            chdir($path);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($file)) {
                        $arr = getImagesPath($file);
                        foreach ($arr as $value) {
                            $arrfiles[] = $path."/".$value;
                        }
                    } else {
                        $arrfiles[] = $path."/".$file;
                    }
                }
            }
            chdir("../");
        }
        closedir($handle);
    }
    return $arrfiles;
}

?>