<?php

// intercept direct access to this file & rebuild the right path
if (preg_match("/section.php/i",$_SERVER['PHP_SELF'])) {
	//Time zone
	if (function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
		@date_default_timezone_set(date_default_timezone_get());

	chdir("../../");
	include_once("config.php");
	include_once("functions.php");

 	if (!defined("_FN_MOD"))
		create_fn_constants();

	// set again cookies values of the language
	$userlang = getparam("userlang", PAR_COOKIE, SAN_FLAT);
	if ($userlang!="" AND is_alphanumeric($userlang) AND file_exists("languages/$userlang.php")) {
		$lang = $userlang;
	}
	include_once("languages/$lang.php");
	// set again cookies values of the theme
	$usertheme = getparam("usertheme", PAR_COOKIE, SAN_FLAT);
	if ($usertheme!="" AND !stristr("..",$usertheme) AND is_dir(get_fn_dir("themes")."/$usertheme")) {
		$theme = $usertheme;
	}
	// set again charset: using ajax, it could be rewritten by the web server
	@header("Content-Type: text/html; charset="._CHARSET."");
}

// security checks
$req = getparam("REQUEST_URI", PAR_SERVER, SAN_NULL);
if(strstr($req,"myforum="))
	die(_NONPUOI);
$conf_mod  = getparam("conf_mod",  PAR_POST, SAN_FLAT);
$get_act   = getparam("get_act",   PAR_GET,  SAN_FLAT);
$mod       = getparam("mod",       PAR_GET,  SAN_FLAT);
$op        = getparam("op",        PAR_GET,  SAN_FLAT);
$fncclist  = getparam("fncclist",  PAR_GET,  SAN_FLAT);

// language definition
global $lang;
switch($lang) {
	case "it":
		include_once ("languages/admin/$lang.php");
	break;
	default:
		include_once ("languages/admin/en.php");
}

// external code declarations
include_once (get_fn_dir("sections")."/$mod/none_functions/func_interfaces.php");

// constants definitions
define("_FNCC_GOTOP", "<a href=\"#fncctoppage\"><img src='".get_fn_dir("sections")."/$mod/none_images/top.png' alt='top' border='0' title='top' style='vertical-align:middle'></a>");

################################################################
/*                     MAIN EXECUTION                         */
################################################################

if(is_admin()) {
	// external code declarations
	include_once (get_fn_dir("sections")."/$mod/none_functions/func_operations.php");
	include_once (get_fn_dir("sections")."/$mod/none_functions/func_verify.php");
	// GET menu list
	switch($fncclist) {
		case "fncclist1" :		fncc_list1();		break;	// option group 1 # flatnuke
		case "fncclist2" :		fncc_list2();		break;	// option group 2 # other configurations
		case "fncclist3" :		fncc_list3();		break;	// option group 3 # users
		case "fncclist4" :		fncc_list4();		break;	// option group 4 # security
		case "fncclist5" :		fncc_list5();		break;	// option group 5 # graphics
		case "fnccplugins": get_thirdparty_plugins(); break;// # plugins
	}
	// POST actions
	switch($conf_mod) {
		case "phpinfo":			fncc_phpinfo();			break;	// print PHP configuration on the web server
		case "modgeneralconf":	fncc_modgeneralconf();	break;	// save main Flatnuke configuration
		case "modbodyfile":		fncc_modbodyfile();		break;	// save standard text file
		case "savepoll":		fncc_savepoll();		break;	// save poll informations
		case "archpoll":		fncc_archpoll();		break;	// archive poll and build a new one
		case "moddownconf":		fncc_savedownconf();	break;	// save fdplus configuration
		case "saveprofile":		fncc_saveprofile();		break;	// save new user profile
		case "updatewaiting":	fncc_updatewaiting();	break;	// update email address of a profile waiting for activation
		case "sendactivation":	fncc_sendactivation();	break;	// re-send activation code to users
		case "dobackup":		fncc_dobackup();		break;	// make the backup
		case "cleanbackup":		fncc_cleanbackup();		break;	// delete backup files on the server
		case "cleanlog":		fncc_cleanlog();		break;	// clean log file
		case "modblacklist":	fncc_modbodyfile();		break;	// save blacklist text file
	}
	// GET actions
	switch($get_act) {
		case "deletewaiting":	fncc_delwaiting();	break;	// delete waiting user
	}
	// GET options
	switch($op) {
		case "fnccinfo":		fncc_info();		break;	// general infos on the site
		case "fnccconf":		fncc_conf();		break;	// main Flatnuke configuration
		/*----------------------------------------------*/
		case "fnccmotd"    :	fncc_motd();		break;	// manage MOTD file
		case "fnccpolledit":	fncc_polledit();	break;	// manage poll configuration
		case "fnccdownconf":	fncc_downconf();	break;	// manage fdplus configuration
		/*----------------------------------------------*/
		case "fnccmembers"   :	fncc_members();		break;	// manage users of the site
		case "fnccnewprofile":	fncc_newprofile();	break;	// add a new user profile
		case "fnccwaitingusers":fncc_waitingusers();break;	// manage profiles waiting for activation
		/*----------------------------------------------*/
		case "fnccbackup":		fncc_backups();		break;	// manage FN backups
		case "fncclogs":		fncc_logs();		break;	// manage system logs
		case "fnccblacklists":	fncc_blacklists();	break;	// manage FN blacklists
		/*----------------------------------------------*/
		case "fnccthemestruct":	fncc_themestruct();	break;	// manage theme's structure
		case "fnccthemestyle" :	fncc_themestyle();	break;	// manage theme's style
		case "fnccthemecss"   :	fncc_themecss();	break;	// manage theme's CSS
		case "fnccforumcss"   :	fncc_forumcss();	break;	// manage forum's CSS
	}
} else fncc_onlyadmin();	// only admins can access this section

?>
