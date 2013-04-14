<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller/newsController.php");


buildTopPage("board");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <?php showNewsList(); ?>
    <?php
}

function showNewsList(){
    $newsController = new NewsController();
    $newsList = $newsController->loadAll();
    if(count($newsList) == 0){
        echo "<p class=\"error\">Non ci sono notizie!</p>";
    }else{
        for($i=0; $i <= count($newsList)-1; $i++){
            $news = $newsList[$i];
            if($i < count($newsList)-1){
                if($i == 0){
                    echo "<div class=\"post\">";
                } else {
                    echo "<div class=\"post transparent\">";
                }
            }else{
                echo "<div class=\"post transparent last\">";
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