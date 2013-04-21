<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");
require_once(DOCUMENT_ROOT."/controller/newsController.php");

buildTopPage("board");

buildContent();

buildBottomPage();

function buildContent(){
    $newsController = new NewsController();
    $newsList = $newsController->loadAll();
    if(count($newsList) == 0){
        echo "<div class=\"post\"><p class=\"error\">Non ci sono notizie!</p></div>";
    }else{
        for($i=(count($newsList)-1); $i >=0 ; $i--){
            $news = $newsList[$i];
            if($i == (count($newsList)-1)){
                echo "<div class=\"post\">";
            } else {
                if($i >=0){
                    echo "<div class=\"post transparent\">";
                } else {
                    echo "<div class=\"post transparent last\">";
                }
            }
            ?>
                <p class="title"><?php echo $news->getTitle(); ?></p>
                <p class="meta"><?php echo "Pubblicato il <span class=\"date\">".$news->getDate()."</span> da 
                        <span class=\"author\">".$news->getAuthor()."</span>"; ?></p>
                <div class="entry">
                    <?php echo $news->getContent(); ?>
                </div>
            <?php
            echo "</div>";
        }
    }
}