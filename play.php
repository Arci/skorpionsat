<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");

buildTopPage("play");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="play-container">
        <div id="play-left" class="left">
            <div id="play">
                <img class="keep-calm left" src="<?php echo IMAGES_PATH."play_with_us.jpg"; ?>" />
                <p>Benvenuti nel nostro sito!</p>
                <p class="text-paragraph">In questa sezione saranno spiegate brevemente le direttive per partecipare ad una giornata di
                    prova con il nostro Club!</p>
                <p class="text-paragraph">Innanzi tutto nella sezione &ldquo;partite&rdquo; del nostro forum si potranno trovare le
                    date programmate per le giornate che si svolgeranno in casa a cui potranno partecipare coloro
                    i quali desiderano provare a giocare con noi.</p>
                <p class="text-paragraph">Per poter &ldquo;prenotare&rdquo; la giornata di gioco &egrave; necessario mettersi
                    in contatto con il responsabile noleggi (Riccardo alias Fato) o in alternativa potrete chiamare il presidente
                    (Elisa alias Avvocata) ai numeri presenti in basso a destra su questa pagina.</p>
                <p class="text-paragraph">Se invece di una telefonata preferite conoscerci di persona, saremo ben
                    lieti di rendervi partecipi durante le nostre riunioni che si svolgono il mercoled&igrave; sera alle
                    21.30, presso la sede del Club in via Nazario Sauro 13 a Senago!</p>
                <p class="text-paragraph">Al momento della prenotazione dovrete specificare il numero dei partecipanti
                    totali, suddividendoli in partecipanti che necessitano di tutta l'attrezzatura e in
                    partecipanti gi&agrave; attrezzati.</p>
                <p class="text-paragraph">In base alla disponibilit&agrave; delle date vi si dar&agrave; conferma del giorno,
                    orario e luogo di ritrovo.
                    Una volta definito iltutto si dovr&agrave; consegnare il modulo di scarico di responsabilit&agrave;,
                    oltre al modulo di trattamento dei dati personali ed alla fotocopia della carta di identit&agrave; e del codice fiscale.
                    Detti moduli sono reperibili sempre su questa pagina nella sezione &ldquo;modulistica&rdquo; presente sulla destra.
                    Inoltre sar&agrave; necessario versare una piccola caparra
                    per &ldquo;bloccare&rdquo; la giocata ed assicurarsi una domenica di sano divertimento! </p>
                <p class="text-paragraph">Quindi vi aspettiamo numerosi alle nostre giornate di sport a contatto con la natura!</p>
                <p class="text-paragraph">Con l'augurio di poter avere il piacere di giocare insieme per pi&ugrave; di una volta!!!</p>
                <p class="text-paragraph">A presto!</p>
            </div>
        </div>
        <div id="play-right" class="right">
            <div id="download" >
                <span>Modulistica:</span>
                <div class="first"><a href="<?php echo DOCUMENTS_PATH."Domanda_di_Ammissione.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Domanda di ammissione</span></a></div>
                <div><a href="<?php echo DOCUMENTS_PATH."Mod.Autorizzazione_Trattamento_Dati_Personali.pdf"?>"><img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" /><span>Trattamento dei dati personali</span></a></div>
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
        <div class="clear"></div>
    </div>
    <?php
}