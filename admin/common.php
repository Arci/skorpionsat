<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once(dirname(__FILE__)."/../settings.php");
	
function reverseDate($date){
    $dateArray = explode("/", $date);
    $reversedDate = $dateArray[2]."/".$dateArray[1]."/".$dateArray[0];
    return $reversedDate;
}

function buildTopPage($pageName, $goodLogon=false){
	openHtml();
	getHead();
	getHeader("", $goodLogon);
	openBody();
}

function buildBottomPage(){
	closeBody();
	getFooter();
	closeHtml();
}

function openHtml(){
	?> <html xmlns="http://www.w3.org/1999/xhtml"> <?php
}

function getHead(){
	?>
	<head>
		<meta name="robots" content="noindex, nofollow" />
		<meta name="googlebot" content="noindex, nofollow" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Skorpion S.A.T. - admin panel</title>

		<link rel="shortcut icon" href= "<?php echo ADMIN_IMAGES_PATH."favicon.ico" ?>"/>
		
		<link rel="stylesheet" type="text/css" href="http://css-reset-sheet.googlecode.com/svn/reset.css" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:700,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		<link href='http://fonts.googleapis.com/css?family=Julius+Sans+One' rel='stylesheet' type='text/css'>
		<link href="<?php echo ADMIN_CSS_PATH."style.css" ?>" rel="stylesheet" type="text/css" media="screen" />
		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
		<script src="<?php echo ADMIN_JS_PATH."/jqueryui/jquery.ui.datepicker-it.js" ?>"></script>
		<script src="<?php echo ADMIN_JS_PATH."/ckeditor/ckeditor.js" ?>"></script>
		<script>
			$(function() {
				$("#date").datepicker({ changeMonth: true, changeYear: true });
				$("#date").datepicker( "option", "showAnim", "slideDown" );
				$("#date").datepicker( "option", "dateFormat", "dd/mm/yy");
			});
		</script>
	</head>
	<?php
}

function openBody(){
	?> <body> <?php
}

function getHeader($pageName, $goodLogon){
	?>
	<div id="wrapper">
	<div id="header">
                <div id="logo" class="left"><img src="<?php echo ADMIN_IMAGES_PATH."skorpion.png" ?>" /></div>
                <div id="container-right" class="right">
                    <div id="text" class="shadow">
			<a href="<?php echo ROOT_PATH ?>">Skorpion S.A.T.
			<p style ='color: #C00000 !important; font-size: 0.2em !important;
					letter-spacing: 0.4em !important; padding-top: 1em;'>BETA</p>
			</a>
		    </div>
                    <div id="menu">
			<?php if($goodLogon == true){ ?>
                        <ul>
				<li <?php if($pageName == "news") echo "class=\"active\""; ?>><a href='news.php'><span class="shadow">Gestisci notizie</span></a></li>
				<li <?php if($pageName == "album") echo "class=\"active\""; ?>><a href='album.php'><span class="shadow">Gestisci album e foto</span></a></li>
				<li <?php if($pageName == "slideshow") echo "class=\"active last\""; else echo "class=\"last\""; ?>><a href='slideshow.php'><span class="shadow">Gestisci slideshow</span></a></li>
			</ul>
			<?php } ?>
                    </div>
                </div>
                <div class="clear"></div>
        </div>
	<?php
}

function getFooter(){
	?>
	
	<div id="footer">
		<p>skorpionsat.com. &copy; 2013. Developed By <a href="http://www.linkedin.com/pub/fabio-arcidiacono/61/307/9a0">Arcidiacono Fabio</a>.
		Designed By <a href="#">Alice Vittoria Cappelletti</a>
		<div id="admin"><p><a href="<?php echo ROOT_PATH ?>">Torna al sito</a></p></div>
	</div></div>
	<?php
}

function closeBody(){
	?></body> <?php
}

function closeHtml(){
	?> </html> <?php
}