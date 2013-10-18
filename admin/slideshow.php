<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/../controller/photoController.php");
define("ACTION_ADD", "Aggiungi");
define("ACTION_DELETE", "Elimina foto selezionate");

buildTopPage("slideshow", true);

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
	<?php
        if(isset($_GET["mode"])){
            $mode = $_GET["mode"];
            switch($mode){
                case "edit":
                    edit();
                    break;
                default:
                    showForm();
            }
        }else{
            showForm();
        }
	?>
    </div>
    <?php
}

function edit(){
    if($_POST["action"] == ACTION_ADD){
	$error = checkValidity(ACTION_ADD, $_FILES);
	if (count($error) == 0){
	    $error =  performAction(ACTION_ADD, $_FILES);
	    if (count($error) == 0){
		showForm();
	    } else {
	    	showForm($error);
	    }
	} else {
	    showForm($error);
	}
    } else if($_POST["action"] == ACTION_DELETE){
	$error = checkValidity(ACTION_DELETE, $_POST);
	if (count($error) == 0){
	    $error =  performAction(ACTION_DELETE, $_POST);
	    if (count($error) == 0){
		showForm();
	    } else {
	    	showForm($error);
	    }
	} else {
	    showForm($error);
	}
    }
}

function checkValidity($action, $data){
    $error = array();
    if ($action == ACTION_ADD){
	if (array_key_exists("fileselect", $data)){
	    $allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
	    if (count($data["fileselect"]["name"]) == 1 && $data["fileselect"]["type"][0] == ""){
		$error[] = "Devi selezionare almeno un file";
	    } else {
		for($i=0; $i < count($data["fileselect"]["name"]); $i++){
		    if($data["fileselect"]["error"][$i] > 0){
			$error[] = "Si &egrave verificato un'errore caricando il file <strong>".$data["fileselect"]["name"][$i]."</strong>";
		    }
		    if($data["fileselect"]["size"][$i] > MAX_BYTE){
			$error[] = "Il file <strong>".$data["fileselect"]["name"][$i]."</strong> eccede le dimesioni massime (".MAX_BYTE." byte)";
		    }
		    if(!in_array($data["fileselect"]["type"][$i], $allowed_mime_types)){
			$error[] = "Il file <strong>".$data["fileselect"]["name"][$i]."</strong> non &egrave fra i tipi consentiti (\"png\", \"jpeg\", \"gif\")";
		    }
		}
	    }
	} else {
	    $error[] = "Problemi nel recuperare i dati";
	}
    } else if($action == ACTION_DELETE){
	if (!array_key_exists("photo", $data)){
	    $error[] = "Devi selezionare almeno una foto";
	}
    } else {
	$error[] = "Problemi nel recuperare i dati";
    }
    return $error;
}

function performAction($action, $data){
    $error = array();
    if ($action == ACTION_ADD){
	if (!(count($data["fileselect"]["name"]) == 1 && $data["fileselect"]["type"][0] == "")){
	    $photoController = new PhotoController();
	    for($i=0; $i < count($data["fileselect"]["name"]); $i++){
		$photoPath = SLIDESHOW_DIR.$data["fileselect"]["name"][$i];
		if (!move_uploaded_file($data["fileselect"]["tmp_name"][$i], $photoPath)){
		    $error[] = "Si &egrave verificato un errore caricando la foto.";
		}
		$photoController->compress($photoPath, IMAGE_QUALITY);
	    }
	}
    } else if($action == ACTION_DELETE){
	foreach ($data["photo"] as $photo){
	    if (!unlink(SLIDESHOW_DIR.$photo)){
		$error[] = "Si &egrave verificato un errore eliminando la foto.";
	    }
	}
    }
    return $error;
}

function showForm($error = null){
    ?>
    <p class="title">Puoi aggiungere o rimuovere le immagini che scorrono nella pagina "Chi siamo":</p>
    <form action="slideshow.php?mode=edit" method="POST" enctype="multipart/form-data">
	<?php
	if($error){
	    echo "<p class=\"error\">";
	    foreach($error as $e){
		echo $e."<br/>";
	    }
	    echo "</p>";
	}
	$count = 1;
	$arrayfiles = getSlideshowImages();
	if (count($arrayfiles) == 0){
	    echo "<p class=\"error\">Attualmente non ci sono immagini nello slideshow</p>";
	} else {
	    ?><div class="slideshow"><?php
	    foreach($arrayfiles as $img){
		?>
		<div class="album-div left">
		    <input class="album-checkbox" type="checkbox" name="photo[]" value="<?php echo $img; ?>">
		    <img class="thumbnail" src="<?php echo SLIDESHOW_PATH.$img; ?>">
		</div>
		<?php
		if($count == 3){
		    echo "<div class=\"clear\"></div>";
		    $count = 0;
		}
		$count++;
	    }
	    if($count > 0){
		echo "<div class=\"clear\"></div>";
	    }
	    ?></div><?php
	}
	?>
	<p class="file-input"><label for="fileselect">Foto da caricare: </label><input type="file" id="fileselect" name="fileselect[]" multiple="multiple" /></p>
	<input type="submit" name="action" value="<?php echo ACTION_ADD; ?>" />
	<input type="submit" name="action" value="<?php echo ACTION_DELETE; ?>" />
    </form>
    <?php
}

function getSlideshowImages(){
    $arrayfiles = array();
    $extensions = array('.jpg','.jpeg','.png');
    $dirname = SLIDESHOW_DIR;
    if(file_exists($dirname)){
	$handle = opendir($dirname);
	while (false !== ($file = readdir($handle))) {
	    if(is_file($dirname.$file)){
		$ext = strtolower(substr($file, strrpos($file, "."), strlen($file)-strrpos($file, ".")));
		if(in_array($ext,$extensions)){
			array_push($arrayfiles,$file);
		}
	    }
	}
	$handle = closedir($handle);
    }
    return $arrayfiles;
}