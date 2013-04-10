<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../controller/albumController.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../model/photo.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../controller/photoController.php");
define("MAX_BYTE", 1500000);

buildTopPage("slideshow", true);

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
	<!-- TODO visualizza ome in modofica album e permette di rimuoverle o d aggiungerle -->
    </div>
    <?php
}