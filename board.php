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
    <div id="page" class="wrapper">
        <div id="content">
            <?php showNewsList(); ?>
        </div>
    </div>
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
                echo "<div class=\"post withSuccessor\">";
            }else{
                echo "<div class=\"post\">";
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