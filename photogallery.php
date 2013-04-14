<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");

buildTopPage("photogallery");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="back-to-albums">
        
    </div>
    <div id="smart-gallery">
        <img src="images/cubagallery-img-1.jpg" /> 
        <img src="images/cubagallery-img-2.jpg" />
        <img src="images/cubagallery-img-3.jpg" />
        <img src="images/cubagallery-img-4.jpg" />
        <img src="images/cubagallery-img-5.jpg" />
        <img src="images/cubagallery-img-6.jpg" />
        <img src="images/cubagallery-img-7.jpg" />
        <img src="images/cubagallery-img-8.jpg" />
        <img src="images/cubagallery-img-9.jpg" />
        <img src="images/cubagallery-img-10.jpg" />
        <img src="images/cubagallery-img-11.jpg" />
        <img src="images/cubagallery-img-12.jpg" /></a>
    </div>
    <?php
}