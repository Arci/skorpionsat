<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/common.php");

buildTopPage("who");

buildContent();

buildBottomPage();

function buildContent(){
    ?>
    <div id="content">
        <div id="mask">
            <div id="main-container">
                <?php
                $arrayfiles = array();
                $extensions = array('.jpg','.jpeg','.png');
                $dirname = SLIDESHOW_DIR;
                if(file_exists($dirname)){
                        $handle = opendir($dirname);
                        while (false !== ($file = readdir($handle))) { 
                                if(is_file($dirname.$file)){
                                        $ext = strtolower(substr($file, strrpos($file, "."), strlen($file)-strrpos($file, ".")));
                                        if(in_array($ext,$extensions)){
                                                array_push($arrayfiles,$file);
                                        }
                                }
                        }
                        $handle = closedir($handle);
                }
                for($i=0; $i< count($arrayfiles); $i++){
                    if($i==0){
                        ?><div class="slide current"><img src="<?php echo SLIDESHOW_PATH .$arrayfiles[$i] ?>" /></div><?php
                    }else{
                        ?><div class="slide"><img src="<?php echo SLIDESHOW_PATH .$arrayfiles[$i] ?>" /></div><?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="upper-shadow"><img src="<?php echo IMAGES_PATH . "upper_shadow.png" ?>" /></div>
        <div id="description">
            <p>Il nostro Club nasce il 22 maggio 2012! Un Club voluto a tutti i costi dalla cocciutaggine della Presidente
                Elisa &ldquo;Avvocata&rdquo; Milani e dagli altri cinque fondatori che, per un forte legame di amicizia, hanno voluto seguirla in
                questa nuova sfida!</p>
            <p class="text-paragraph">Nonostante il Club sia nato da poco, vanta tra gli associati dei softgunner con una buonissima esperienza sul campo!
                Ma non solo, anche chi di esperienza non ne ha moltissima, sin dal primo momento in cui si &egrave; giocato insieme, i nuovi
                hanno dato prova delle loro abilit&agrave; e della loro voglia di giocare in modo &ldquo;sano&rdquo;! Ma comunque tutti
                hanno manifestato il sentimento di forte aggregazione ed amicizia che ci unisce sia sul campo sia quando siamo vestiti
                &ldquo;civilmente&rdquo;!Si perch&egrave; oltre a incontrarci ogni mercoled&igrave; per le consuete riunioni ci
                piace anche organizzare cene e serate da passare in compagnia dei soci e rispettive/e consorti!</p> 
            <p class="text-paragraph">Non siamo solo un Club di softair, siamo una famiglia dove tutti sono alla pari e dove ognuno ha un ruolo importante e
                fondamentale per l'organizzazione e l'unione della squadra! Abbiamo la fortuna di avere in questa nostra famiglia una
                fotografa e chef che durante le nostre giocate ci vizia e delizia che le sue prelibatezze e che riesce ad immortalare
                alla perfezione  i momenti di gioco e le emozioni che scaturiscono da ogni giocatore mentre &egrave; concentrato nel game!
                E non solo! La nostra meravigliosa Noemi &ldquo;Nelly&rdquo;Cilenti riesce sempre a descrivere negli articoli del suo blog
                <a class="in-text-link" href="http://www.bacididama-blog.com/">Baci di Dama</a> in modo molto &ldquo;romantico&rdquo;
                il nostro sport tra una ricetta e l'altra!</p>
            <p class="text-paragraph">In pi&ugrave; abbiamo la fortuna di avere un preparatissimo verniciatore e meccanico che, oltre a risolvere le problematiche
                dei nostri fucili, li sistema con un'accurata pulizia e se lo desideriamo a seconda dei nostri gusti, apporta delle
                modifiche estetiche di tipo mimetico alle nostre asg!
                <a class="in-text-link" href="http://www.facebook.com/pages/Spray-Gun/299120606849070">Spraygun</a>
                &egrave; il nome della pagina facebook del nostro Riccardo &ldquo;Fato&rdquo; Villa, in cui potrete vedere i lavori,
                o meglio le opere d'arte, che ha fatto fino ad ora!</p>
            <p class="text-paragraph">Siamo determinati nel far crescere questa nostra famiglia in un clima di amicizia, fratellanza e sempre con la voglia di
                divertirsi, senza mai perdere il nostro sorriso disarmante!</p>
        </div>
    </div>
    <?php
}