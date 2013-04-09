<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

session_start();

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/settings.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/common.php");
require_once(pathinfo(__FILE__, PATHINFO_DIRNAME)."/../controller/albumController.php");
define('IN_PHPBB', true);
define('ROOT_PATH', "../forum");

//if (!defined('IN_PHPBB') || !defined('ROOT_PATH')) {
//        exit();
//}
//$phpEx = "php";
//$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : ROOT_PATH . '/';
//require_once($phpbb_root_path . 'common.' . $phpEx);
//$user->session_begin();
//$auth->acl($user->data);
//$user->setup();
//$loggedGood = false;
//if ($user->data['user_id'] == ANONYMOUS ) {
//    $message = '<p class="error">Effettua il login su <a href="forum/">forum</a> per usufruire di questo servizio!</p>';
//} elseif($user->data['group_id'] == 9 /*Direttivo*/ || $user->data['group_id'] == 5 /*Admin*/) {
//    $message =  '<p>Sei loggato come, ' . $user->data['username_clean'] ."</p>";
//    $_SESSION["username"] = $user->data['username_clean'];
    $loggedGood = true;
//} else {
//    $message = "<p>Ciao ".$user->data['username_clean'].", non hai i permessi, torna alla <a href=\"../index.php\">home</a></p>";
//}

buildTopPage("", $loggedGood);

?>
<div id="page">
<div id="content">
    <?php echo $message; ?>
</div>
</div>
<?php
buildBottomPage();
