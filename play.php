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
        <div id="play-left" class="left">
            <div id="download" >
                <span>Download:</span>
                <div class="first"><a href="<?php echo DOCUMENTS_PATH."Domanda_di_Ammissione.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Domanda di ammissione</span></a></div>
                <div><a href="<?php echo DOCUMENTS_PATH."Mod.Autorizzazione_Trattamento_Dati_Personali.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Autorizzazione al trattamento dei dati personali</span></a></div>
                <div><a href="<?php echo DOCUMENTS_PATH."Regolamento_Interno.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Regolamento interno</span></a></div>
                <div><a href="<?php echo DOCUMENTS_PATH."Scarico_Responsabilita_Minori.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Scarico di responsabilit&agrave per minori</span></a></div>
                <div><a href="<?php echo DOCUMENTS_PATH."Scarico_Responsabilita.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Scarico di responsabilit&agrave</span></a></div>
            </div>
            <div id="contact">
                <span>Contatti:</span>
                <div><img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" /><a href="mailto:presidente@skorpionsat.com">Presidente</a></div>
                <div><img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" /><a href="mailto:segretario@skorpionsat.com">Segretario</a></div>
                <div><img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" /><a href="mailto:noleggi@skorpionsat.com">Responsabile noleggi</a></div>
                <div><img src="<?php echo IMAGES_PATH."phone.png"?>" class="phone-thumbnail" />333 8130339 (Presidente)</div>
                <div><img src="<?php echo IMAGES_PATH."phone.png"?>" class="phone-thumbnail" />333 4819041 - 347 4374801 (Noleggi)</div>
            </div> 
        </div>
        <div id="play-right" class="right">
            <div id="play" class="right">
                <!-- TODO contenuto -->
                <img class="keep-calm right" src="<?php echo IMAGES_PATH."play_with_us.jpg"; ?>" />
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor semper scelerisque. Donec dui lacus, scelerisque eu sollicitudin et, condimentum quis diam. Morbi libero tortor, congue non feugiat in, luctus vitae odio. Donec risus mi, pulvinar vel aliquet in, volutpat eu erat. In eget sem leo, vitae scelerisque lorem. Nam aliquam lacinia ante ut mollis. Aliquam erat volutpat.</p>
                <p>Nunc malesuada varius risus et blandit. Nullam vel turpis ipsum, eu congue eros. Duis malesuada turpis quis ante scelerisque quis cursus orci elementum. Donec eleifend scelerisque luctus. Mauris vitae metus est, a pellentesque nulla. Donec mollis suscipit sodales. Sed luctus facilisis elementum. Curabitur felis nisi, ullamcorper sed malesuada nec, mollis gravida massa. Nunc felis arcu, aliquet at gravida ac, euismod at ante. Proin ante lorem, feugiat molestie commodo sed, fermentum vitae ante. Integer fermentum rutrum magna, eget faucibus ligula lobortis ac. Aliquam tristique scelerisque massa vestibulum facilisis. Vestibulum laoreet mauris et purus porta sodales. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <?php
}