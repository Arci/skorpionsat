<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");
require_once(DOCUMENT_ROOT."/controller/albumController.php");
require_once(DOCUMENT_ROOT."/model/photo.php");
require_once(DOCUMENT_ROOT."/controller/photoController.php");

buildTopPage("album", true);

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
    <?php
        if(isset($_GET["mode"])){
            $mode = $_GET["mode"];
            switch($mode){
                case "create":
                    create();
                    break;
                case "edit":
                    edit();
                    break;
                case "add";
                    add();
                    break;
                default:
                    showOptions();
            }
        }else{   
            showOptions();
        }
    ?>
   </div>
    <?php
}

function showOptions(){
    ?>
    <ul>
        <div><li><a href="album.php?mode=create"><img src="<?php echo ADMIN_IMAGES_PATH."create.png"; ?>" class="small-thumbnail" /><span>Crea nuovo album</span></a></li></div>
        <div><li><a href="album.php?mode=edit"><img src="<?php echo ADMIN_IMAGES_PATH."edit.png"; ?>" class="small-thumbnail" /><span>Modifica un album esistente</span></a></li></div>
        <div><li><a href="album.php?mode=add"><img src="<?php echo ADMIN_IMAGES_PATH."add.png"; ?>" class="small-thumbnail" /><span>Aggiungi foto ad un album esistente</span></a></li></div>
    </ul>
    <?php
}

function create(){
    if(!isset($_POST["name"]) && !isset($_POST["date"])){ 
        showCreateForm();
    } else {
        $error = array();
	$allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
        if($_POST["name"] == null){
            $error[] = "Il nome &egrave obbligatorio";
        }
        if($_POST["date"] == null){
            $error[] = "La data &egrave obbligatoria";
        }
        if(!(count($_FILES["fileselect"]["name"]) == 1 && $_FILES["fileselect"]["type"][0] == "")){
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
                $date = reverseDate($_POST["date"]);
                $album = new Album($_POST["name"], $date, $_POST["description"]);
                $albumController = new AlbumController();
                $albumController->save($album);
                $album = $albumController->loadByName($album->getName());
                if(!(count($_FILES["fileselect"]["name"]) == 1 && $_FILES["fileselect"]["type"][0] == "")){
                    for($i=0; $i < count($_FILES["fileselect"]["name"]); $i++){
                        if($i==0){
                            //first image is defalt album cover
                            $photo = new Photo($album->getID(), $_FILES["fileselect"]["name"][$i], "", 0, true);
                        }else{
                            $photo = new Photo($album->getID(), $_FILES["fileselect"]["name"][$i]);
                        }
                        $photoController = new PhotoController();
                        $photoController->upload($photo, $_FILES["fileselect"]["tmp_name"][$i]);
                    }
                }
                showEditForm(false, null, $album->getID());
            }catch (Exception $e){
                echo "<p class=\"error\">".$e->getMessage()."</p>";
            }
        }else{
            $prev = array("name" => $_POST["name"],
                          "date" => $_POST["date"],
                          "description" => $_POST["description"]);
            showCreateForm($error, $prev);
        }
    }
}

function showCreateForm($error = null, $prev = null){
    echo "<div class=\"left\">
            <p><a href=\"album.php\"><img class=\"small-thimbnail\" src=\"".ADMIN_IMAGES_PATH."back.png\" /></a></p>
            </div>";
    echo "<div class=\"sub-menu-container left\">";
    echo "<form action=\"album.php?mode=create\" method=\"POST\" enctype=\"multipart/form-data\">";
    if($error){
        echo "<p class=\"error\">";
        foreach($error as $e){
            echo $e."<br/>";
        }
        echo "</p>";
    }
    $old_date = $prev["date"];
    echo "<p class=\"date\"><label for=\"date\">Data: </label><input type=\"text\" id=\"date\" name=\"date\" value=\"$old_date\"/></p>";
    $old_name = $prev["name"];
    echo "<p class=\"title\"><label for=\"name\">Nome: </label><input type=\"text\" id=\"name\" name=\"name\" value=\"$old_name\"/></p>";
    $old_description = $prev["description"];
    echo "<p class=\"description-label\"><label for=\"description\">Descrizione: </label></p>
            <p class=\"description\"><textarea id=\"description\" name=\"description\">$old_description</textarea></p>";
    echo "<p class=\"file-input\"><label for=\"fileselect\">Foto da caricare: </label><input type=\"file\" id=\"fileselect\" name=\"fileselect[]\" multiple=\"multiple\" /></p>";
    echo "<p><input type=\"submit\" value=\"Crea\" /></p>";
    echo "</form></div>
            <div class=\"clear\"></div>";
}

function edit(){
    if(isset($_POST["action"])){
        if($_POST["action"] == "Salva Modifiche"){
            $error = array();
            if($_POST["name"] == null){
                $error[] = "Il nome &egrave obbligatorio";
            }
            if($_POST["date"] == null){
                $error[] = "La data &egrave obbligatoria";
            }
            if(count($error) == 0){
                $albumID = $_POST['album'];
                $albumController = new AlbumController();
                $album = $albumController->loadByID($albumID);
                $album->setName($_POST["name"]);
                $album->setDate(reverseDate($_POST["date"]));
                $album->setDescription($_POST["description"]);
                $albumController->update($album);

                $photoController = new PhotoController();
                $photoList = $photoController->loadByAlbum($album->getID());
                foreach($photoList as $photo){
                    $description = "description".$photo->getID();
                    if(isset($_POST[$description])){
                        $photo->setDescription($_POST[$description]);
                        $photoController->update($photo);
                    }
                }
                showOptions();
                echo "<p class=\"main-notice\">L'album &egrave stato registrato</p>";
            }else{
                $selectedPhoto = array();
                if(array_key_exists("photo", $_POST)){
                    foreach($_POST["photo"] as $selected){
                        $selectedPhoto[] = $selected;
                    }
                }
                $prev = array("name" => $_POST["name"],
                                "date" => $_POST["date"],
                                "description" => $_POST["description"],
                                "selected" => $selectedPhoto);
                showEditForm(false,$error,$_POST["album"],$prev);
            }
        }else if($_POST["action"] == "Elimina foto selezionate"){
            $error = array();
            if(!array_key_exists("photo", $_POST)){
                $error[] = "devi selezionare almeno una foto!";
            }
            if(count($error) == 0){
                $photoController = new PhotoController();
                $albumCoverDeleted = false;
                $albumID = null;
                foreach($_POST["photo"] as $selected){
                    try{
                        $photo = $photoController->loadByID($selected);
                        if($photo->isAlbumCover()){
                            $albumID = $photo->getAlbumID();
                            $albumCoverDeleted = true;
                        }
                        $photoController->delete($photo);
                    }catch (Exception $e){
                        echo "<p class=\"error\">".$e->getMessage()."</p>";
                    }
                }
                if($albumCoverDeleted){
                    $photoList = $photoController->loadByAlbum($albumID, 1);
                    $photo = $photoList[0];
                    $photo->setIfIsAlbumCover(true);
                    $photoController->update($photo);
                }
                showOptions();
                echo "<p class=\"main-notice\">Le foto selezionate sono state eliminate</p>";
            }else{
                $prev = array("name" => $_POST["name"],
                                "date" => $_POST["date"],
                                "description" => $_POST["description"]);
                showEditForm(false,$error, $_POST["album"], $prev);
            }
        }else if($_POST["action"] == "Elimina Album"){
            $albumID = $_POST['album'];
            $albumController = new AlbumController();
            try{
                $albumController->delete($albumController->loadByID($albumID));
                showOptions();
                echo "<p class=\"main-notice\">L'album &egrave stato eliminato</p>";
            }catch (Exception $e){
                echo "<p class=\"error\">".$e->getMessage()."</p>";
            }
        }else if($_POST["action"] == "Imposta foto selezionata come copertina"){
            $error = array();
            if(!array_key_exists("photo", $_POST)){
                $error[] = "devi selezionare almeno una foto!";
            }
            if(count($error) == 0){
                $photoController = new PhotoController();
                $selected = $_POST["photo"][0];
                try{
                    $photo = $photoController->loadByID($selected);
                    $exCover = $photoController->loadAlbumCover($photo->getAlbumID());
                    $exCover->setIfIsAlbumCover(false);
                    $photoController->update($exCover);
                    $photo->setIfIsAlbumCover(true);
                    $photoController->update($photo);
                    showOptions();
                    echo "<p class=\"main-notice\">La foto selezionata &egrave stata impostata come copertina</p>";
                }catch (Exception $e){
                    echo "<p class=\"error\">".$e->getMessage()."</p>";
                }
            }else{
                $prev = array("name" => $_POST["name"],
                                "date" => $_POST["date"],
                                "description" => $_POST["description"]);
                showEditForm(false,$error, $_POST["album"], $prev);
            }
        }
    }else{
        if(!isset($_POST["album"]) && !isset($_POST["select"])){ 
            showEditForm(true);
        } else if(!isset($_POST["album"]) && isset($_POST["select"])){
            $error = array();
            $error[] = "Devi selezionare un album da modificare";
            showEditForm(true, $error);
        }else{
            $albumID = $_POST["album"];
            showEditForm(false, null, $albumID);
        }
    }
}

function showEditForm($select = true, $error = null, $albumID = null, $prev = null){
    echo "<div class=\"left\">
            <p><a href=\"album.php\"><img class=\"small-thimbnail\" src=\"".ADMIN_IMAGES_PATH."back.png\" /></a></p>
            </div>";
    echo "<div class=\"sub-menu-container left\">";
    if($select){
        echo "<p class=\"list-title\">Seleziona un album:</p>";
    }
    echo "<form action=\"album.php?mode=edit\" method=\"POST\">";
    if($error){
        echo "<p class=\"error\">";
        foreach($error as $e){
            echo $e."<br/>";
        }
        echo "</p>";
    }
    if($select){ //selezione album
        $albumController = new AlbumController();
        $albumList = $albumController->loadAll();
        if(count($albumList) > 0){
            foreach($albumList as $album){
                echo"<p class=\"form-list\"><input type=\"radio\" id=\"album".$album->getID()."\" name=\"album\" value=\"".$album->getID()."\" />
                        <label for=\"album".$album->getID()."\">".$album->getName()."</label></p>";
            }
            echo "<p><input type=\"hidden\" name=\"select\" value=\"true\" /></p>";
            echo "<p><input type=\"submit\" value=\"Modifica selezionato\" </p>";
        }else{
            echo "<p class=\"error\">Non ci sono album da modificare!</p>";
        }
    } elseif(!$select && !is_null($albumID)){ //modifica album selezionato
        $albumController = new AlbumController();
        $photoController = new PhotoController();
        $album = $albumController->loadByID($albumID);
        $photoList = $photoController->loadByAlbum($album->getID());
        
        echo "<p><input type=\"hidden\" name=\"album\" value=\"".$album->getID()."\" /></p>";
        echo "<p class=\"date\"><label for=\"date\">Data: </label><input type=\"text\" id=\"date\" name=\"date\" value=\"".reverseDate($album->getDate())."\"/></p>";
        echo "<p class=\"title\"><label for=\"name\">Nome: </label><input type=\"text\" id=\"name\" name=\"name\" value=\"".$album->getName()."\"/></p>";
        echo "<p class=\"description-label\"><label for=\"description\">Descrizione: </label></p>
                <p class=\"description\"><textarea id=\"description\" name=\"description\">".$album->getDescription()."</textarea></p>";
        $count = 1;
        foreach($photoList as $photo){
            if($photo->isAlbumCover()){
                echo "<div class=\"album-div left album-cover\">";
            }else{
                echo "<div class=\"album-div left\">";
            }
            if($prev != null && array_key_exists("selected", $prev) && in_array($photo->getID(), $prev["selected"])){
                echo "<input class=\"album-checkbox\" type=\"checkbox\" name=\"photo[]\" value=\"".$photo->getID()."\" checked =\"checked\"/>";
            }else{
                echo "<input class=\"album-checkbox\" type=\"checkbox\" name=\"photo[]\" value=\"".$photo->getID()."\"/>";
            }
            echo "<img class=\"thumbnail\"src=\"".$photoController->buildPath($photo)."\"/>";
            echo "<p><textarea class=\"album-textarea\" id=\"description".$photo->getID()."\" name=\"description".$photo->getID()."\" value=\"Descrizione\">".$photo->getDescription()."</textarea></p>";
            echo "</div>";
            if($count == 3){
                echo "<p class=\"clear\"></p>";
                $count = 0;
            }
            $count++;
        }
        if($count > 0){
            echo "<p class=\"clear\"></p>";
        }
        echo "<p><input type=\"submit\" name=\"action\" value=\"Salva Modifiche\" />";
        echo " <input type=\"submit\" name=\"action\"value=\"Imposta foto selezionata come copertina\" />";
        echo " <input type=\"submit\" name=\"action\"value=\"Elimina foto selezionate\" />";
        echo " <input type=\"submit\"  name=\"action\" value=\"Elimina Album\" /></p>";
    }
    echo "</form></div>
            <div class=\"clear\"></div>";
}

function add(){
    if(array_key_exists("fileselect", $_FILES)){
        $error = array();
	$allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
        if(count($_FILES["fileselect"]["name"]) == 1 && $_FILES["fileselect"]["type"][0] == ""){
            $error[] = "Devi selezionare almeno un file";
        }else{
            $photoController = new PhotoController();
            $photoList = $photoController->loadByAlbum($_POST["album"]);
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
                foreach($photoList as $photo){
                    if($photo->getName() == $_FILES["fileselect"]["name"][$i]){
                        $error[] = "Il file <strong>".$_FILES["fileselect"]["name"][$i]."</strong> &egrave gi&agrave presente nell'album";
                    }
                }
            }
        }
        if(count($error) == 0){
            try{
                $albumController = new AlbumController();
                $album = $albumController->loadByID($_POST["album"]);
                if(!(count($_FILES["fileselect"]["name"]) == 1 && $_FILES["fileselect"]["type"][0] == "")){
                    for($i=0; $i < count($_FILES["fileselect"]["name"]); $i++){
                        $photo = new Photo($album->getID(), $_FILES["fileselect"]["name"][$i]);
                        $photoController = new PhotoController();
                        $photoController->upload($photo, $_FILES["fileselect"]["tmp_name"][$i]);
                    }
                }
                showEditForm(false, null, $album->getID());
            }catch (Exception $e){
                echo "<p class=\"error\">".$e->getMessage()."</p>";
            }
        }else{
            $albumID = $_POST["album"];
            showAddForm(false, $error, $albumID);
        }
    }else{
        if(!isset($_POST["album"]) && !isset($_POST["select"])){ 
            showAddForm(true);
        } else if(!isset($_POST["album"]) && isset($_POST["select"])){
            $error = array();
            $error[] = "Devi selezionare un album da modificare";
            showAddForm(true, $error);
        }else{
            $albumID = $_POST["album"];
            showAddForm(false, null, $albumID);
        }
    }
}

function showAddForm($select = true, $error = null, $albumID = null){
    echo "<div class=\"back-menu-container left\">
            <p><a href=\"album.php\"><img class=\"small-thimbnail\" src=\"".ADMIN_IMAGES_PATH."back.png\" /></a></p>
            </div>";
    echo "<div class=\"sub-menu-container left\">";
    if($select){
        echo"<p class=\"list-title\">Seleziona un album:</p>";
    }
    echo "<form action=\"album.php?mode=add\" method=\"POST\" enctype=\"multipart/form-data\">";
    if($error){
        echo "<p class=\"error\">";
        foreach($error as $e){
            echo $e."<br/>";
        }
        echo "</p>";
    }
    if($select){ //selezione album
        $albumController = new AlbumController();
        $albumList = $albumController->loadAll();
        if(count($albumList) > 0){
            foreach($albumList as $album){
                echo"<p class=\"form-list\"><input type=\"radio\" id=\"album".$album->getID()."\" name=\"album\" value=\"".$album->getID()."\" />
                        <label for=\"album".$album->getID()."\">".$album->getName()."</label></p>";
            }
            echo "<p><input type=\"hidden\" name=\"select\" value=\"true\" /></p>";
            echo "<p><input type=\"submit\" value=\"Modifica selezionato\" </p>";
        }else{
            echo "<p class=\"error\">Non ci sono album da modificare!</p>";
        }
    } elseif(!$select && !is_null($albumID)){ //aggiunta foto all' album selezionato
        $albumController = new AlbumController();
        $photoController = new PhotoController();
        $album = $albumController->loadByID($albumID);
        $photoList = $photoController->loadByAlbum($album->getID());
        
        echo "<p class=\"date\">Data: <span>".reverseDate($album->getDate())."</span></p>";
        echo "<p class=\"title\">Titolo: <span>".$album->getName()."</span></p>";
        echo "<p class=\"description-label\">Descrizione:</p>";
        echo "<p class=\"description-plain\">".$album->getDescription()."</p>";
        echo "<p class=\"photo-info\">Foto gi&agrave presenti nell'album:</p>";
        $count = 1;
        foreach($photoList as $photo){
            if($photo->isAlbumCover()){
                echo "<div class=\"album-div left album-cover\">";
            }else{
                echo "<div class=\"album-div left\">";
            }
            echo "<img class=\"thumbnail\"src=\"".$photoController->buildPath($photo)."\"/>";
            echo "<p>".$photo->getDescription()."</p>";
            echo "</div>";
            if($count == 3){
                echo "<p class=\"clear\"></p>";
                $count = 0;
            }
            $count++;
        }
        if($count > 0){
            echo "<p class=\"clear\"></p>";
        }
        echo "<p><input type=\"hidden\" name=\"album\" value=\"$albumID\" /></p>";
        echo "<p class=\"file-input\"><label for=\"fileselect\">Foto da caricare: </label><input type=\"file\" id=\"fileselect\" name=\"fileselect[]\" multiple=\"multiple\" /></p>";
        echo "<p><input type=\"submit\" value=\"Aggiungi\" /></p>";
    }
    echo "</form></div>
            <div class=\"clear\"></div>";
}