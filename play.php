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
                <p class="text-paragraph">
                    In questa sezione saranno spiegate brevemente le direttive per partecipare ad una giornata di
                    prova con il nostro Club!
                </p>
                <p class="text-paragraph">
                    Innanzi tutto nella sezione &ldquo;partite&rdquo; del nostro forum si potranno trovare
                    le date programmate per le giornate di gioco con l'ammissione di provanti.
                </p>
                <p class="text-paragraph">
                    Per poter &ldquo;prenotare&rdquo; la giornata di gioco &egrave; necessario mettersi
                    in contatto con il responsabile noleggi (Marco alias Freaky) necessariamente almeno 15/10 giorni prima della data fissata,
                    chiamando il numero presente in basso a destra su questa pagina.
                </p>
                <p class="break-line"/>
                    <br/>
                </p>
                <p class="text-paragraph">
                    Se invece di una telefonata preferite conoscerci di persona, saremo ben
                    lieti di rendervi partecipi durante le nostre riunioni che si svolgono il mercoled&igrave; sera alle
                    21.30, presso la sede del Club in via Nazario Sauro 13 a Senago!
                </p>
                <p class="text-paragraph">
                    Al momento della prenotazione dovrete specificare il numero dei partecipanti
                    totali, suddividendoli in partecipanti che necessitano di tutta l'attrezzatura e in
                    partecipanti gi&agrave; attrezzati.
                </p>
                <p class="text-paragraph">
                    In base alla disponibilit&agrave; delle date vi si dar&agrave; conferma del giorno,
                    orario e luogo di ritrovo.
                    Una volta definito iltutto si dovranno consegnare i seguenti moduli:
                </p>
                <div class="text-paragraph">
                    <ul>
                      <li>domanda di ammissione a socio ordinario (corredata di fotocopia della carta di identità e del codice fiscale)</li>
                      <li>modulo di accettazione dei rischi connessi alla pratica del Soft Air</li>
                      <li>certificato medico di sana e robusta costituzione o in alternativa, autocertificazione di sana e robusta costituzione</li>
                      <li>regolamento interno controfirmato per presa visione.</li>
                    </ul>
                </div>
                <p class="text-paragraph">
                  Detti moduli sono reperibili sempre su questa pagina nella sezione &ldquo;modulistica&rdquo; presente sulla destra.
                  Inoltre sar&agrave; necessario versare una piccola caparra per &ldquo;bloccare&rdquo; la giocata ed assicurarsi
                  una domenica di sano divertimento!
                </p>
                <p class="text-paragraph">
                  Quindi vi aspettiamo numerosi alle nostre giornate di sport a contatto con la natura!
                </p>
                <p class="text-paragraph">
                  Con l'augurio di poter avere il piacere di giocare insieme per pi&ugrave; di una volta!!!
                </p>
                <p class="text-paragraph">
                  A presto!
                </p>
            </div>
        </div>
        <div id="play-right" class="right">
            <div id="download" >
                <span>Modulistica:</span>
                <div class="first">
                  <a href="<?php echo DOCUMENTS_PATH."domanda_ammissione_provanti.pdf"?>">
                    <img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" />
                    <span>Domanda di ammissione a socio provante</span>
                  </a>
                  </div>
                <div>
                  <a href="<?php echo DOCUMENTS_PATH."domanda_ammissione.pdf"?>">
                    <img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" />
                    <span>Domanda di ammissione</span>
                  </a>
                </div>
                <div>
                  <a href="<?php echo DOCUMENTS_PATH."accettazione_rischi_minori.pdf"?>">
                    <img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" />
                    <span>Modulo di accettazione dei rischi per minori</span>
                  </a>
                </div>
                <div>
                  <a href="<?php echo DOCUMENTS_PATH."accettazione_rischi.pdf"?>">
                    <img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" />
                    <span>Modulo di accettazione dei rischi</span>
                  </a>
                </div>
                <div>
                  <a href="<?php echo DOCUMENTS_PATH."regolamento_interno.pdf"?>">
                    <img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" />
                    <span>Regolamento interno</span>
                  </a>
                </div>
                <div>
                  <a href="<?php echo DOCUMENTS_PATH."statuto_skorpion.pdf"?>">
                    <img src="<?php echo IMAGES_PATH."download.png"; ?>" class="download-thumbnail" />
                    <span>Statuto</span>
                  </a>
                </div>
            </div>
            <div id="contact">
                <span>Contatti:</span>
                <div>
                  <img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" />
                  <a href="mailto:pr@skorpionsat.com">PR</a>
                </div>
                <div>
                  <img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" />
                  <a href="mailto:presidente@skorpionsat.com">Presidente</a>
                </div>
                <div>
                  <img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" />
                  <a href="mailto:segretario@skorpionsat.com">Segretario</a>
                </div>
                <div>
                  <img src="<?php echo IMAGES_PATH."email.png"?>" class="email-thumbnail" />
                  <a href="mailto:noleggi@skorpionsat.com">Responsabile noleggi</a>
                </div>
                <!--<div>
                  <img src="<?php echo IMAGES_PATH."phone.png"?>" class="phone-thumbnail" />
                  <a href="tel:3338130339">333 8130339 (Presidente)</a>
                </div>-->
                <div>
                  <img src="<?php echo IMAGES_PATH."phone.png"?>" class="phone-thumbnail" />
                  <a href="tel:3470079938">347 0079938 (PR)</a>
                </div>
                <div>
                  <img src="<?php echo IMAGES_PATH."phone.png"?>" class="phone-thumbnail" />
                  <a href="tel:3407259854">340 7259854 (Noleggi)</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <?php
}
