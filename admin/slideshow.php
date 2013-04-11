<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
define("MAX_BYTE", 1500000);

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
    $error = array();
    if($_POST["action"] == "Aggiungi"){
	if(array_key_exists("fileselect", $_FILES)){
	    $error = array();
	    $allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
	    if(count($_FILES["fileselect"]["name"]) == 1 && $_FILES["fileselect"]["type"][0] == ""){
		$error[] = "Devi selezionare almeno un file";
	    }else{
		for($i=0; $i < count($_FILES["fileselect"]["name"]); $i++){
		    if($_FILES["fileselect"]["error"][$i] > 0){
			$error[] = "Si &egrave verificato un'errore caricando il file <strong>".$_FILES["fileselect"]["name"][$i]."</strong>"; 
		    }
		    if($_FILES["fileselect"]["size"][$i] > MAX_BYTE){
			$error[] = "Il file <strong>".$_FILES["fileselect"]["name"][$i]."</strong> eccede le dimesioni massime (".MAX_BYTE." byte)";
		    }
		    if(!in_array($_FILES["fileselect"]["type"][$i], $allowed_mime_types)){
			$error[] = "Il file <strong>".$_FILES["fileselect"]["name"][$i]."</strong> non &egrave fra i tipi consentiti (\"png\", \"jpeg\", \"gif\")"; 
		    }
		}
	    }
	    if(count($error) == 0){
		try{
		    if(!(count($_FILES["fileselect"]["name"]) == 1 && $_FILES["fileselect"]["type"][0] == "")){
			for($i=0; $i < count($_FILES["fileselect"]["name"]); $i++){
			    if (!move_uploaded_file($_FILES["fileselect"]["tmp_name"][$i], SLIDESHOW_PATH.$_FILES["fileselect"]["name"][$i])){
				echo "Si &egrave verificato un errore caricando la foto.";
			    }
			}
		    }
		    showForm();
		}catch (Exception $e){
		    echo "<p class=\"error\">".$e->getMessage()."</p>";
		}
	    }else{
		showForm($error);
	    }
	}else{
	    showForm($error);
	}
    }else if($_POST["action"] == "Elimina foto selezionate"){
	foreach($_POST["photo"] as $photo){
	    unlink(SLIDESHOW_PATH.$photo);
	}
	showForm($error);
    }
}

function showForm($error = null){
    $arrayfiles = array();
    $extensions = array('.jpg','.jpeg','.png');
    $dirname = SLIDESHOW_PATH;
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
    ?>
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
	?>
	<p><label for="fileselect">Foto da caricare: </label><input type="file" id="fileselect" name="fileselect[]" multiple="multiple" /></p>
	<input type="submit" name="action" value="Aggiungi" />
	<input type="submit" name="action" value="Elimina foto selezionate" />
    </form>
    <?php
}