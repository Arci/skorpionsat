<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/controller/albumController.php");

buildTopPage("gallery");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
        
    </div>
    <?php
}