<?php
//Deploy
define("DEPLOY", false);

//Database settings.
if(DEPLOY){
    define("DB_HOSTNAME", "mysql.netsons.com");
    define("DB_USERNAME", "viiaosdl_admin");
    define("DB_PASSWORD", "skorpion");
    define("DATABASE_NAME", "viiaosdl_skorpion");
} else {
    define("DB_HOSTNAME", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "root");
    define("DATABASE_NAME", "skorpion");
}
//Domains and subdomains
if(DEPLOY){
    define("ROOT_PATH", "http://www.skorpionsat.com");
    define("FORUM_PATH", ROOT_PATH."/forum/");
    define("ADMIN_PATH", ROOT_PATH."/admin");
}else{
    define("ROOT_PATH", "/Web/Skorpionsat/site");
    define("FORUM_PATH", ROOT_PATH."#");
    define("ADMIN_PATH", ROOT_PATH."/admin");
}

//Document Root
if(DEPLOY){
    define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"]);
}else{
    define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"].ROOT_PATH);
}

//Files
define("IMAGES_PATH", ROOT_PATH."/img/");
define("CSS_PATH", ROOT_PATH."/css/");
define("JS_PATH", ROOT_PATH."/js/");
define("ADMIN_IMAGES_PATH", ADMIN_PATH."/img/");
define("ADMIN_CSS_PATH", ADMIN_PATH."/css/");
define("ADMIN_JS_PATH", ADMIN_PATH."/js/");

//Directories
define("LOG_DIR", DOCUMENT_ROOT."/log/");
define("SLIDESHOW_PATH", ROOT_PATH."/slideshow/");
define("SLIDESHOW_DIR", DOCUMENT_ROOT."/slideshow/");
define("DOCUMENTS_PATH", ROOT_PATH."/documents/");
define("ALBUMS_PATH", ROOT_PATH."/albums/");
define("ALBUMS_DIR", DOCUMENT_ROOT."/albums/");

//Miscellaneous
define("MAX_BYTE", 10000000);



