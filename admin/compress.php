<?php

$path =dirname(__FILE__)."/../albums";
echo "$path<br/><br/>";
$imagesPath = array();
$imagesPath = getImagesPath($path);
foreach($imagesPath as $imagePath){
    echo "compressing: " . $imagePath;
    compress($imagePath, 75);
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

function compress($source, $quality) {
    ini_set('memory_limit', '100M'); //imagecreatefrompng causes lot of memory usage
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg'){
        $image = imagecreatefromjpeg($source);
    }
    elseif ($info['mime'] == 'image/gif'){
        $image = imagecreatefromgif($source);
    }
    elseif ($info['mime'] == 'image/png'){
        $image = imagecreatefrompng($source);
    }
    imagejpeg($image, $source, $quality);
}

?>