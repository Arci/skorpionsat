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
            <p>Skorpion SAT nasce il 22 maggio 2012, un Club voluto a tutti i costi dalla cocciutaggine 
                della Presidente Elisa <span>&ldquo;Avvocata&ldquo;</span> Milani e dagli altri cinque fondatori che,
                &nbsp;per un forte legame di amicizia, hanno voluto seguirla in questa nuova sfida!</p>
            <p class="text-paragraph">Nonostante sia nato da poco, il Club vanta 
                tra gli associati dei softgunner con una buonissima 
                esperienza sul campo e dei <span>&ldquo;principianti</span>&rdquo; che sin
                  dal primo momento di gioco hanno dato prova della loro abilit&aacute; e della loro voglia 
                  di giocare in modo <span>&ldquo;sano&rdquo;</span>. </p>
            <p class="text-paragraph">Anche i nuovi arrivati,&nbsp;come noi,&nbsp;hanno manifestato sin da 
                subito il sentimento di forte aggregazione e di amicizia che ci unisce sia sul 
                campo che in tenuta<span>&nbsp;&ldquo;civile&rdquo;</span>,&nbsp;&nbsp;perch&eacute; Skorpion va oltre la domenica di gioco 
                e il mercoled&igrave; di riunione: ogni giorno può essere una scusa plausibile per 
                organizzare cene o pizzate per stare tutti in compagnia (softgunner e consorti!).</p>
            <p class="text-paragraph">Non siamo solo un Club di softair, siamo una famiglia dove 
                tutti sono alla pari e ognuno ha un ruolo importante e fondamentale 
                per l'organizzazione e l'unione della squadra!</p>
            <p class="text-paragraph">Nella nostra famiglia abbiamo un preparatissimo verniciatore
                 e meccanico che, oltre a risolvere le problematiche dei nostri fucili, li 
                 sistema con un'accurata pulizia e se lo desideriamo a seconda dei nostri gusti, 
                 apporta delle modifiche estetiche di tipo mimetico alle nostre asg! 
                 <a href="https://www.facebook.com/pages/Spray-Gun/299120606849070">Spraygun</a> 
                 &egrave; il nome della pagina facebook del nostro Riccardo <span>&ldquo;Fato&rdquo;</span> Villa, in cui 
                 potrete vedere i lavori, o meglio le opere d'arte, che ha creato sino ad ora!</p>
            <p class="text-paragraph">Inoltre c’&egrave; il nostro preparatissimo responsabile e 
                consigliere per il marketing! Una sorta di consulente finanziario, ma che non 
                spaventa nessuno! Il nostro caro Massimiliano <span>&ldquo;Panda&rdquo;</span> Tagini, che tra una 
                percentuale, una moltiplicazione e una somma, ci permette di non perdere 
                e non far perdere delle occasioni <span>&ldquo;che non si possono rifiutare&rdquo;</span>!</p>
            <p class="text-paragraph">Ma non &egrave; finita qui!&nbsp;Questo meraviglioso sito secondo voi 
                da chi &egrave; stato interamente realizzato? Fabio <span>&ldquo;Arci&rdquo;</span> Arcidiacono &egrave; l’artefice di 
                questa meraviglia! Meticoloso e preciso Dottore in ingegneria 
                informatica, insomma l’indiscusso ingegnere dell’etere! 
                Senza di lui il world wide web non avrebbe nemmeno saputo della nostra esistenza!</p>  
            <p class="text-paragraph">Come non nominare inoltre il nostro Davide <span>&ldquo;Mamba&rdquo;</span> Maiocchi, 
                efficientissimo coordinatore e caposquadra durante i nostri game! Senza di lui 
                le tattiche sono solo parole! Un leader nato, capace di spronare e dirigere alla
                 perfezione i giocatori, senza mai dimenticare che si &egrave; prima di tutto amici ma, 
                 se si conquistano obiettivi e se si prende la bandiera &egrave; sempre meglio!</p>
            <p class="text-paragraph">Lodevole inoltre l’impegno e il lavoro del nostro Andrea 
                <span>&ldquo;McLain&rdquo;</span> Favaro, un altro vanto del nostro Club che conosce le tecniche e i movimenti
                 di gioco, attua le tattiche e gestisce in maniera esemplare i nuovi giocatori 
                 seguendo i loro movimenti e dando ottimi consigli per portarsi 
                 a casa la meritata vittoria!</p>
            <p class="text-paragraph">E dulcis in fundo non poteva mancare all'interno della 
                famiglia una fotografa, chef e foodblogger che durante i game ci vizia e delizia 
                con le sue prelibatezze e, attraverso gli scatti della sua reflex, riesce sempre 
                ad immortalare alla perfezione i momenti di gioco e le emozioni che  ogni giocatore in campo
                scaturisce! Noemi <span>&ldquo;Nelly&rdquo;</span> Cilenti, autrice del blog 
                <a href="http://www.bacididama-blog.com/">Baci di Dama</a>, &egrave; colei che riesce 
                sempre a descrivere nei suoi articoli in modo molto <span>&ldquo;romantico&rdquo;</span> il nostro 
                sport tra una ricetta e l'altra!</p>
             <p class="text-paragraph">Questa &egrave; una parte della nostra famiglia, 
                una famiglia che siamo determinati a far crescere in un clima di amicizia, 
                fratellanza e sempre con la voglia di divertirsi, senza mai perdere il nostro 
                sorriso disarmante!</p>
        </div>
    </div>
    <?php
}