<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");
require_once(DOCUMENT_ROOT."/controller/newsController.php");
require_once(DOCUMENT_ROOT."/model/news.php");

if(DEPLOY){
    session_start();
}

buildTopPage("news", true);

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
    <?php
        if(isset($_GET["mode"])){
            $mode = $_GET["mode"];
            switch($mode){
                case "publish":
                    publish();
                    break;
                case "edit":
                    edit();
                    break;
                case "delete";
                    delete();
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

function showOptions($message = null){
    ?>
    <ul>
        <div><li><a href="news.php?mode=publish"><img src="<?php echo ADMIN_IMAGES_PATH."create.png"; ?>" class="small-thumbnail" /><span>Pubblica una nuova notizia</span></a></li></div>
        <div><li><a href="news.php?mode=edit"><img src="<?php echo ADMIN_IMAGES_PATH."edit.png"; ?>" class="small-thumbnail" /><span>Modifica una notizia esistente</span></a></li></div>
        <div><li><a href="news.php?mode=delete"><img src="<?php echo ADMIN_IMAGES_PATH."delete.png"; ?>" class="small-thumbnail" /><span>Elimina una o pi&ugrave notizie esistenti</span></a></li></div>
    </ul>
    <?php
    if($message){
        ?><p class="main-notice"><?php echo $message; ?></p><?php
    }
}

//----- CONTROL LOGIC -----//

function publish(){
    $error = array();
    if(!isset($_POST["title"]) && !isset($_POST["content"])){ 
        showPublishForm();
    } else {
        if($_POST["title"] == null){
            $error[] = "Il titolo &egrave obbligatorio";
        }
        if($_POST["content"] == null){
            $error[] = "Il contenuto &egrave obbligatorio";
        }
        if(count($error) == 0){
            date_default_timezone_set('Europe/Rome');
            $date = date("d/m/Y", $_SERVER["REQUEST_TIME"]);
            if(DEPLOY){
                $author = $_SESSION["username"];
            }else{
                $author = "Arci";
            }
            $news = new News(Filter::sqlEscape($_POST["title"]), $date, Filter::sqlEscape($author), $_POST["content"]);
            $newsController = new NewsController();
            try{
                $newsController->save($news);
                showOptions("La notizia &egrave stata aggiunta");
            }catch (Exception $e){
                $error[] = "Problemi nel salvataggio della notizia";
                showPublishForm($error);
            }
        }else{
            $prev = array("title" => $_POST["title"],
                          "content" => $_POST["content"]);
            showPublishForm($error, $prev);
        }
    }
}

function edit(){
    $error = array();
    if(isset($_POST["title"]) || isset($_POST["content"])){ 
        if($_POST["title"] == null){
            $error[] = "Il titolo &egrave obbligatorio";
        }
        if($_POST["content"] == null){
            $error[] = "Il contenuto &egrave obbligatorio";
        }
        if(count($error) == 0){
            try{
                $newsController = new NewsController();
                $news = $newsController->loadByID(Filter::sqlEscape($_POST["news"]));
                $news->setTitle(Filter::sqlEscape($_POST["title"]));
                $news->setContent($_POST["content"]);
                $newsController->update($news);
                showOptions("La notizia &egrave stata modificata");
            }catch (Exception $e){
                $error[] = "Problemi nella modifica  della notizia";
                showEditForm($error);
            }
        }else{
            $newsID = $_POST["news"];
            showEditForm(false, $error, $newsID);
        }
    }else{
        if(!isset($_POST["news"]) && !isset($_POST["select"])){ 
            showEditForm(true);
        } else if(!isset($_POST["news"]) && isset($_POST["select"])){
            $error[] = "Devi selezionare una notizia da modificare";
            showEditForm(true, $error);
        }else{
            $newsID = $_POST["news"];
            showEditForm(false, null, $newsID);
        }
    }
}

function delete(){
    $error = array();
    if(!isset($_POST["news"]) && isset($_POST["select"])){ 
        $error[] = "Devi selezionare una notizia per eliminarla";
        showDeleteForm($error);
    }else if(isset($_POST["news"])){
        foreach($_POST["news"] as $newsID){
            try{
                $newsController = new NewsController();
                $news = $newsController->loadByID(Filter::sqlEscape($newsID));
                $newsController->delete($news);
                showOptions("Le notizie selezionate sono state rimosse");
            }catch (Exception $e){
                $error[] = "Problemi nel recuperare le notizie selezionate";
                showDeleteForm($error);
            }
        }
    } else {
        showDeleteForm();
    }
}

//----- FORMS -----//

function showPublishForm($error = null, $prev = null){
    ?>
    <div class="left">
        <p><a href="news.php"><img class="small-thimbnail" src="<?php echo ADMIN_IMAGES_PATH."back.png"; ?>" /></a></p>
    </div>
    <div class="sub-menu-container left">
        <form action="news.php?mode=publish" method="POST">
            <?php
            if($error){
                echo "<p class=\"error\">";
                foreach($error as $e){
                    echo $e."<br/>";
                }
                echo "</p>";
            }
            $old_title = $prev["title"];
            $old_content = $prev["content"];
            ?>
            <p class="title"><label for="title">Titolo: </label><input type="text" id="title" name="title" value="<?php echo $old_title; ?>"/></p>    
            <p class="editor"><textarea id="editor" name="content"><?php echo $old_content; ?></textarea></p>
            <script type="text/javascript">
                CKEDITOR.replace( 'editor' );
            </script>
            <p><input type="submit" value="Pubblica" /></p>
        </form>
    </div>
    <div class="clear"></div>
    <?php
}

function showEditForm($select = true, $error = null, $newsID = null){
    ?>
    <div class="left">
        <p><a href="news.php"><img class="small-thimbnail" src="<?php echo ADMIN_IMAGES_PATH."back.png"; ?>" /></a></p>
    </div>
    <div class="sub-menu-container left">
        <?php
        if($select){
            ?><p class="list-title">Seleziona una notizia:</p><?php
        }
        ?>
        <form action="news.php?mode=edit" method="POST">
            <?php
            if($error){
                echo "<p class=\"error\">";
                foreach($error as $e){
                    echo $e."<br/>";
                }
                echo "</p>";
            }
            if($select){ //selezione notizia
                $newsController = new NewsController();
                $newsList = $newsController->loadAll();
                if(count($newsList) > 0){
                    for($i = (count($newsList)-1); $i >= 0; $i--){
                        ?>
                        <p class="form-list">
                            <input type="radio" id="<?php echo "news".$newsList[$i]->getID(); ?>" name="newsList[$i]" value="<?php echo $newsList[$i]->getID(); ?>" />
                            <label for="<?php echo "news".$newsList[$i]->getID(); ?>"><?php echo $newsList[$i]->getTitle(); ?></label>
                        </p>
                        <?php
                    }
                    ?>
                    <p><input type="hidden" name="select" value="true" /></p>
                    <p><input type="submit" value="Modifica selezionato" </p>
                    <?php
                }else{
                    ?><p class="error">Non ci sono notizie da modificare!</p><?php
                }
            }elseif(!$select && !is_null($newsID)){ //modifica notizia selezionata
                try{
                    $newsController = new NewsController();
                    $news = $newsController->loadByID($newsID);
                    ?>
                    <p class="title">Titolo: <input type="text" name="title" value="<?php echo $news->getTitle(); ?>"/></p>    
                    <p class="editor"><textarea id="editor" name="content"><?php echo $news->getContent(); ?></textarea></p>
                    <p><input type="hidden" name="news" value="<?php echo $news->getID(); ?>" /></p>
                    <script type="text/javascript">
                            CKEDITOR.replace( 'editor' );
                        </script>
                    <input type="submit" value="Modifica" />
                    <?php
                }catch (Exception $e){
                    ?><p class="error">Impossibile caricare la notizia selezionata</p><?php
                }
            }else{
                ?><p class="error">Something went wrong with the form</p><?php
            }
            ?>
        </form>
    </div>
    <div class="clear"></div>
    <?php
}

function showDeleteForm($error = null){
    ?>
    <div class="left">
        <p><a href="news.php"><img class="small-thimbnail" src="<?php echo ADMIN_IMAGES_PATH."back.png"; ?>" /></a></p>
    </div>
    <div class="sub-menu-container left">
        <p class="list-title">Seleziona una o pi&ugrave notizie</p>
        <form action="news.php?mode=delete" method="POST">
            <?php
            if($error){
                echo "<p class=\"error\">";
                foreach($error as $e){
                    echo $e."<br/>";
                }
                echo "</p>";
            }
            $newsController = new NewsController();
            $newsList = $newsController->loadAll();
            if(count($newsList) == 0){
                ?><p class="error">Non ci sono notizie da eliminare!</p><?php
            }else{
                 for($i = (count($newsList)-1); $i >= 0; $i--){
                    ?>
                    <p class="form-list">
                        <input type="checkbox" id="<?php echo "news".$newsList[$i]->getID(); ?>" name="$newsList[$i][]" value="<?php echo $newsList[$i]->getID(); ?>" />
                        <label for="<?php echo "news".$newsList[$i]->getID(); ?>"><?php echo $newsList[$i]->getTitle(); ?></label>
                    </p>
                    <?php
                }
                ?>
                <p><input type="hidden" name="select" value="true" /></p>
                <p><input type="submit" value="Elimina selezionati" /></p>
                <?php
            }
            ?>
        </form>
    </div>
    <div class="clear"></div>
    <?php
}