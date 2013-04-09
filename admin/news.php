<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

//session_start();

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../controller/newsController.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/news.php");

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

function showOptions(){
    ?>
    <ul>
        <div class="left"><li><a href="news.php?mode=publish">Pubblica una nuova notizia</a></li></div>
        <div class="left"><li><a href="news.php?mode=edit">Modifica una notizia esistente</a></li></div>
        <div class="left"><li><a href="news.php?mode=delete">Elimina una o pi&ugrave notizie esistenti</a></li></div>
        <div class="clear"></div>
    </ul>
    <?php
}

function publish(){
    if(!isset($_POST["title"]) && !isset($_POST["content"])){ 
        showPublishForm();
    } else {
        $error = array();
        if($_POST["title"] == null){
            $error[] = "Il titolo &egrave obbligatorio";
        }
        if($_POST["content"] == null){
            $error[] = "Il contenuto &egrave obbligatorio";
        }
        if(count($error) == 0){
            date_default_timezone_set('Europe/Rome');
            $date = date("d/m/Y", $_SERVER["REQUEST_TIME"]);
            $author = "Avvocata";
            //$author = $_SESSION["username"];
            $news = new News($_POST["title"], $date, $author, $_POST["content"]);
            $newsController = new NewsController();
            try{
                $newsController->save($news);
            }catch (Exception $e){
                echo "<p class=\"error\">".$e->getMessage()."</p>";
            }
            showOptions();
            echo "<p>La notizia &egrave stata aggiunta</p>";
        }else{
            $prev = array("title" => $_POST["title"],
                          "content" => $_POST["content"]);
            showPublishForm($error, $prev);
        }
    }
}

function showPublishForm($error = null, $prev = null){
    echo "<form action=\"news.php?mode=publish\" method=\"POST\">";
    if($error){
        echo "<p class=\"error\">";
        foreach($error as $e){
            echo $e."<br/>";
        }
        echo "</p>";
    }
    $old_title = $prev["title"];
    echo "<p><label for=\"title\">Titolo: </label><input type=\"text\" id=\"title\" name=\"title\" value=\"$old_title\"/></p>";     
    $old_content = $prev["content"];
    echo "<p><textarea id=\"editor\" name=\"content\">$old_content</textarea></p>";
    echo "<script type=\"text/javascript\">
            CKEDITOR.replace( 'editor' );
        </script>
        <p><input type=\"submit\" value=\"Pubblica\" /></p>
        </form>";
}

function edit(){
    if(isset($_POST["title"]) || isset($_POST["content"])){ 
        $error = array();
        if($_POST["title"] == null){
            $error[] = "Il titolo &egrave obbligatorio";
        }
        if($_POST["content"] == null){
            $error[] = "Il contenuto &egrave obbligatorio";
        }
        if(count($error) == 0){
            try{
                $newsController = new NewsController();
                $news = $newsController->loadByID($_POST["news"]);
                $news->setTitle($_POST["title"]);
                $news->setContent($_POST["content"]);
                $newsController->update($news);
            }catch (Exception $e){
                echo "<p class=\"error\">".$e->getMessage()."</p>";
            }
            showOptions();
            echo "<p>La notizia &egrave stata modificata</p>";
        }else{
            $newsID = $_POST["news"];
            showEditForm(false, $error, $newsID);
        }
    }else{
        if(!isset($_POST["news"]) && !isset($_POST["select"])){ 
            showEditForm(true);
        } else if(!isset($_POST["news"]) && isset($_POST["select"])){
            $error = array();
            $error[] = "Devi selezionare una notizia da modificare";
            showEditForm(true, $error);
        }else{
            $newsID = $_POST["news"];
            showEditForm(false, null, $newsID);
        }
    }
}

function showEditForm($select = true, $error = null, $newsID = null){
    echo "<form action=\"news.php?mode=edit\" method=\"POST\">";
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
            foreach($newsList as $news){
                echo"<p><input type=\"radio\" id=\"news".$news->getID()."\" name=\"news\" value=\"".$news->getID()."\" />
                        <label for=\"news".$news->getID()."\">".$news->getTitle()."</label></p>";
            }
            echo "<p><input type=\"hidden\" name=\"select\" value=\"true\" /></p>";
            echo "<p><input type=\"submit\" value=\"Modifica selezionato\" </p>";
        }else{
            echo "<p class=\"error\">Non ci sono notizie da modificare!</p>";
        }
    } elseif(!$select && !is_null($newsID)){ //modifica notizia selezionata
        try{
            $newsController = new NewsController();
            $news = $newsController->loadByID($newsID);
            echo "<p>Titolo: <input type=\"text\" name=\"title\" value=\"".$news->getTitle()."\"/></p>";     
            echo "<p><textarea id=\"editor\" name=\"content\">".$news->getContent()."</textarea></p>";
            echo "<p><input type=\"hidden\" name=\"news\" value=\"".$news->getID()."\" /></p>";
            echo "<script type=\"text/javascript\">
                    CKEDITOR.replace( 'editor' );
                    </script>
                    <input type=\"submit\" value=\"Modifica\" />";
        }catch (Exception $e){
            echo "<p class=\"error\">".$e->getMessage()."</p>";
        }
    } else{
        echo "<p class=\"error\">Something went wrong with the form</p>";
    }
    echo "</form>";
}

function delete(){
    if(!isset($_POST["news"]) && isset($_POST["select"])){ 
        $error = array();
        $error[] = "Devi selezionare una notizia per eliminarla";
        showDeleteForm($error);
    }else if(isset($_POST["news"])){
        foreach($_POST["news"] as $newsID){
            try{
                $newsController = new NewsController();
                $news = $newsController->loadByID($newsID);
                $newsController->delete($news);
            }catch (Exception $e){
                echo "<p class=\"error\">".$e->getMessage()."</p>";
            }
        }
        showOptions();
        echo "<p>Le notizie selezionate sono state rimosse</p>";
    } else {
        showDeleteForm();
    }
}

function showDeleteForm($error = null){
    echo "<form action=\"news.php?mode=delete\" method=\"POST\">";
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
        echo "<p class=\"error\">Non ci sono notizie da eliminare!</p>";
    }else{
        foreach($newsList as $news){
            
            echo"<p><input type=\"checkbox\" id=\"news".$news->getID()."\"name=\"news[]\" value=\"".$news->getID()."\" />
                    <label for=\"news".$news->getID()."\">".$news->getTitle()."</label></p>";
        }
        echo "<p><input type=\"hidden\" name=\"select\" value=\"true\" /></p>";
        echo "<p><input type=\"submit\" value=\"Elimina selezionati\" /></p>";
    }
    echo "</form>";
}
?>