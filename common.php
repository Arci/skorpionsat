<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/settings.php");
require_once(DOCUMENT_ROOT."/logger.php");

function reverseDate($date){
    $dateArray = explode("/", $date);
    $reversedDate = $dateArray[2]."/".$dateArray[1]."/".$dateArray[0];
    return $reversedDate;
}

function buildTopPage($pageName, $description = null){
	openHtml();
	getHead($pageName, $description);
	getHeader($pageName);
	openBody();
}

function buildBottomPage(){
	closeBody();
	getFooter();
	getAnalytics();
	closeHtml();
}

function openHtml(){
	?> <html xmlns="http://www.w3.org/1999/xhtml"> <?php
}

function getHead($pageName,$description = null){
	?>
	<head>
		<?php
		if($pageName == "board"){
			?><meta name="description" content="News dalla skorpion Soft Air Team - visualizza le ultime notizie riguardanti
				la squadra, le ultime partite di softair e altro ancora!"><?php
		} else if($pageName == "who"){
		     	?><meta name="description" content="Chi sono gli skorpion, perchè giocano a softiar, come sono nati" /><?php
		} else if($pageName == "play"){
			?><meta name="description" content="Contatti della skorpion Soft Air Team - cosa fare se vuoi giocare a softair
				con noi, modulistica" /><?php
		} else if($pageName == "gallery"){
			?><meta name="description" content="Foto delle nostre partite a softair, partecipazioni ad eventi" /><?php
		} else if($pageName == "photogallery" && $description != null){
			?><meta name="description" content="<?php echo $description ?>" /><?php
		} else {
			?><meta name="description" content=" Skorpion - softair team - Author: Fabio Arcidiacono, Designer: Alice Vittoria Cappelletti" /><?php
		}
		?>
		<meta name="robots" content="index, follow" />
		<meta name="googlebot" content="index, follow" />
		<meta name="google" content="notranslate" />
		<meta name="copyright" content="Arcidiacono Fabio 2012" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, user-scalable=no"/>
		<meta name="p:domain_verify" content="1c091f2df0df0b77826b0adde1615fc3" />
		<?php
		if($pageName == "board"){
			?><title>News</title><?php
		} else if($pageName == "who"){
			?><title>Chi Siamo</title><?php
		} else if($pageName == "play"){
			?><title>Gioca con noi</title><?php
		} else if($pageName == "gallery" || $pageName == "photogallery"){
			?><title>Gallery</title><?php
		} else {
			?><title>Skorpion S.A.T.</title><?php
		}
		?>
		<link rel="shortcut icon" href= "<?php echo IMAGES_PATH."favicon.ico" ?>"/>

		<link rel="stylesheet" type="text/css" href="http://css-reset-sheet.googlecode.com/svn/reset.css" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:700,300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Julius+Sans+One' rel='stylesheet' type='text/css'>
		<link href="<?php echo CSS_PATH."style.css" ?>" rel="stylesheet" type="text/css" media="screen" />
		<?php
		if($pageName == "who"){
			?>
			<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			<script type="text/javascript" src="<?php echo JS_PATH."slideshow.js" ?> "></script>
			<?php
		} else if($pageName == "gallery"){
			?>
			<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			<script type="text/javascript" src="<?php echo JS_PATH."gallery.js" ?> "></script>
			<?php
		} else if($pageName == "photogallery"){
			?>
			<link rel="stylesheet" href="<?php echo CSS_PATH."prettyPhoto.css"; ?>" type="text/css" media="screen" charset="utf-8" />
			<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			<script type="text/javascript" src="<?php echo JS_PATH."prettyPhoto.js"; ?>" charset="utf-8"></script>
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function(){
					if($(window).width() >= 768){
						$("a[rel^='prettyPhoto']").prettyPhoto();
					}
				});
		    </script>
			<?php
		}
		?>
	</head>
	<?php
}

function openBody(){
	?> <body> <?php
}

function getHeader($pageName){
	?>
	<div id="wrapper">
	<div id="header">
		<div class="social" >
			<a href ="https://www.facebook.com/skorpionsoftairteam"><img src="<?php echo IMAGES_PATH."facebook.png" ?>" /></a>
		</div>
        <div id="logo" class="left"><img src="<?php echo IMAGES_PATH."skorpion.png" ?>" /></div>
        <div id="container-right" class="left">
            <div id="text" class="shadow">
			<a href="<?php echo ROOT_PATH ?>">Skorpion S.A.T.</a>
		    </div>
                    <div id="menu">
                        <ul>
                           <li <?php if($pageName == "board") echo "class=\"active\""; ?>><a href='board.php'><span class="shadow">News</span></a></li>
                           <li <?php if($pageName == "who") echo "class=\"active\""; ?>><a href='who.php'><span class="shadow">Chi siamo</span></a></li>
                           <li <?php if($pageName == "play") echo "class=\"active\""; ?>><a href='play.php'><span class="shadow">Gioca con noi</span></a></li>
                           <li <?php if($pageName == "forum") echo "class=\"active\""; ?>><a href='<?php echo FORUM_PATH; ?>'><span class="shadow">Forum</span></a></li>
                           <li <?php if($pageName == "gallery" || $pageName == "photogallery")
                        		echo "class=\"active last\""; else echo "class=\"last\"" ?>><a href='gallery.php'><span class="shadow">Gallery</span></a>
                           </li>
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
        </div>
	<?php
}

function getFooter(){
	?>
	<div id="footer">
        <p>skorpionsat.com. &copy; 2013.
        <span class="break-line"><br/></span>
        Developed By <a href="http://www.linkedin.com/pub/fabio-arcidiacono/61/307/9a0">Arcidiacono Fabio</a>.
		<div id="admin"><p><a href="<?php echo ADMIN_PATH ?>">Pannello di controllo</a></p></div>
        </div></div>
	<?php
}

function getAnalytics(){
       ?>
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-32258069-1']);
		_gaq.push(['_setDomainName', 'skorpionsat.com']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	<?php
}

function closeBody(){
	?></body> <?php
}

function closeHtml(){
	?> </html> <?php
}