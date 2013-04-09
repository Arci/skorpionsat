<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");

buildTopPage("play");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="play-container">
        <div id="play" class="right">
            <img class="keep-calm right" src="img/play_with_us.jpg" />
            <!-- TODO contenuto -->
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor semper scelerisque. Donec dui lacus, scelerisque eu sollicitudin et, condimentum quis diam. Morbi libero tortor, congue non feugiat in, luctus vitae odio. Donec risus mi, pulvinar vel aliquet in, volutpat eu erat. In eget sem leo, vitae scelerisque lorem. Nam aliquam lacinia ante ut mollis. Aliquam erat volutpat.</p>
            <p>Nunc malesuada varius risus et blandit. Nullam vel turpis ipsum, eu congue eros. Duis malesuada turpis quis ante scelerisque quis cursus orci elementum. Donec eleifend scelerisque luctus. Mauris vitae metus est, a pellentesque nulla. Donec mollis suscipit sodales. Sed luctus facilisis elementum. Curabitur felis nisi, ullamcorper sed malesuada nec, mollis gravida massa. Nunc felis arcu, aliquet at gravida ac, euismod at ante. Proin ante lorem, feugiat molestie commodo sed, fermentum vitae ante. Integer fermentum rutrum magna, eget faucibus ligula lobortis ac. Aliquam tristique scelerisque massa vestibulum facilisis. Vestibulum laoreet mauris et purus porta sodales. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
        </div>
        <div id="download" class="left">
            <span>Download:</span>
            <div class="first"><a href="documents/Domanda_di_Ammissione.pdf"><img src="img/download.png" class="download-thumbnail" /><span>Domanda di ammissione</span></a></div>
            <div><a href="documents/Mod.Autorizzazione_Trattamento_Dati_Personali.pdf"><img src="img/download.png" class="download-thumbnail" /><span>Autorizzazione al trattamento dei dati personali</span></a></div>
            <div><a href="documents/Regolamento_Interno.pdf"><img src="img/download.png" class="download-thumbnail" /><span>Regolamento interno</span></a></div>
            <div><a href="documents/Scarico_Responsabilita_Minori.pdf"><img src="img/download.png" class="download-thumbnail" /><span>Scarico di responsabilit&agrave per minori</span></a></div>
            <div><a href="documents/Scarico_Responsabilita.pdf"><img src="img/download.png" class="download-thumbnail" /><span>Scarico di responsabilit&agrave</span></a></div>
        </div>
        <div class="clear"></div>
    </div>
    <?php
}