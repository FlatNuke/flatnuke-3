<?php

/************************************************************************/
/* FlatNuke - Flat Text Based Content Management System                 */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2003-2006 by Simone Vellei                             */
/* http://www.flatnuke.org/                                             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
ob_start();

// deny direct access to this file
if (preg_match("/header.php/i",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

// load Flatnuke configuration
include "config.php";
include_once "functions.php";

// language definition by configuration or by cookie
$userlang = getparam("userlang", PAR_COOKIE, SAN_FLAT);
if ($userlang!="" AND is_alphanumeric($userlang) AND file_exists("languages/$userlang.php")) {
	$lang = $userlang;
}
switch($lang) {
	case "de" OR "es" OR "fr" OR "it" OR "pt":
		include_once ("languages/$lang.php");
		include_once ("languages/fd+lang/fd+$lang.php");
	break;
	default:
		include_once ("languages/en.php");
		include_once ("languages/fd+lang/fd+en.php");
	break;
}

// theme definition by configuration or by cookie
$usertheme = getparam("usertheme", PAR_COOKIE, SAN_FLAT);
if ($usertheme!="" AND !stristr("..",$usertheme) AND is_dir("themes/$usertheme")) {
	$theme = $usertheme;
}
include "themes/$theme/theme.php";

// start HTML headers
if(defined('_THEME_DOCTYPE')) {
	$doctype = _THEME_DOCTYPE;
} else $doctype = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";	// default HTML 4.01 doctype
// define close tag for XHTML doctype
$namespace = "";
if(preg_match("/DTD HTML/", $doctype)) {
	$close_tag = "";
} elseif(preg_match("/DTD XHTML/", $doctype)) {
	$close_tag = " /";
        $namespace = "xmlns=\"http://www.w3.org/1999/xhtml\"";
}

// dynamically build page's title and meta tags
$mod    = _FN_MOD;
$action = getparam("action",  PAR_GET, SAN_FLAT);
$news   = getparam("news",  PAR_GET, SAN_FLAT);
$title  = $sitename;
if (get_mod()!="") {
	$news_dir = get_fn_dir("sections")."/".get_mod()."/none_newsdata/";
} else $news_dir = get_fn_dir("sections")."/none_News/none_newsdata/";
if(trim($mod)!="" AND $action!="viewnews" AND $action!="addcommentinterface") {
	// include specifics keywords for this section
	if (file_exists(get_fn_dir('sections')."/$mod/none_newmetatags.php")) {
		include_once (get_fn_dir('sections')."/$mod/none_newmetatags.php");
	}
	// build title for a section
	$page_title = str_replace("/", " - ", _FN_MOD);
	$page_title = str_replace("none_", "", $page_title);
	/* deprecated: PHP 5.3 upgrade
	$page_title = eregi_replace("^[0-9]*_", "", $page_title);
	$page_title = eregi_replace(" - [0-9]*_", " - ", $page_title);*/
	$page_title = preg_replace("/^[0-9]*_/", "", $page_title);
	$page_title = preg_replace("/ - [0-9]*_/", " - ", $page_title);
	$page_title = str_replace("_", " ", $page_title);
	$title = "$sitename &raquo; $page_title";
}
if(($action=="viewnews" OR $action=="addcommentinterface") AND file_exists("$news_dir/$news.fn.php")) {
	// build title for a news
	$newsfile   = get_file("$news_dir/$news.fn.php");
	$page_title = get_xml_element("title",$newsfile);
	/* deprecated: PHP 5.3 upgrade
	$keywords   = eregi_replace('[\.]*[[:alpha:]]+$','',get_xml_element("category",$newsfile)).", $keywords";*/
	$keywords   = preg_replace('/[\.]*[[:alpha:]]+$/i','',get_xml_element("category",$newsfile)).", $keywords";
	$sitedescription = substr(strip_tags(get_xml_element("header",$newsfile)), 0, 200);
	$sitedescription = _FLEGGI." &#58; $sitedescription";
	$title = "$sitename &raquo; $page_title";
}

echo $doctype;
echo "<html lang=\"$lang\" $namespace>\n";
echo "<head>\n";
echo "<title>".stripslashes($title)."</title>\n";
define("_FN_TITLE",$title);
echo "<meta http-equiv=\"content-type\" content=\"text/html; charset="._CHARSET."\"$close_tag>\n";
echo "<meta http-equiv=\"expires\" content=\"0\"$close_tag>\n";
echo "<meta name=\"resource-type\" content=\"document\"$close_tag>\n";
echo "<meta name=\"distribution\" content=\"global\"$close_tag>\n";
echo "<meta name=\"author\" content=\"$admin\"$close_tag>\n";
echo "<meta name=\"copyright\" content=\"Copyright (c) ".date("Y",time())." by $sitename\"$close_tag>\n";
echo "<meta name=\"keywords\" content=\"$keywords\"$close_tag>\n";
echo "<meta name=\"description\" content=\"$sitedescription\"$close_tag>\n";
if (file_exists(_FN_SECTIONS_DIR."/"._FN_MOD."/noindex"))
	echo "<meta name=\"robots\" content=\"noindex, nofollow\"$close_tag>\n";
else echo "<meta name=\"robots\" content=\"index, follow\"$close_tag>\n";
echo "<meta name=\"revisit-after\" content=\"1 days\"$close_tag>\n";
echo "<meta name=\"rating\" content=\"general\"$close_tag>\n";
?>

<!--[if lt IE 7]>
<script language="JavaScript">
function correctPNG() // correctly handle PNG transparency in Win IE 5.5 & 6.
{
   var arVersion = navigator.appVersion.split("MSIE")
   var version = parseFloat(arVersion[1])
   if ((version >= 5.5) && (document.body.filters))
   {
      for(var i=0; i<document.images.length; i++)
      {
         var img = document.images[i]
         var imgName = img.src.toUpperCase()
         if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
         {
            var imgID = (img.id) ? "id='" + img.id + "' " : ""
            var imgClass = (img.className) ? "class='" + img.className + "' " : ""
            var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
            var imgStyle = "display:inline-block;" + img.style.cssText
            if (img.align == "left") imgStyle = "float:left;" + imgStyle
            if (img.align == "right") imgStyle = "float:right;" + imgStyle
            if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle
            var strNewHTML = "<span " + imgID + imgClass + imgTitle
            + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
            + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
            + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"
            img.outerHTML = strNewHTML
            i = i-1
         }
      }
   }
}
window.attachEvent("onload", correctPNG);
</script>
<![endif]-->

<script type="text/javascript">
<!--
// Request confirmation before continue action
function check(url){
if(confirm ("<?php echo _SICURO?>"))
	window.location=url;
}

// Let overload window.onload function
function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}
// -->
</script>

<?php


// declaration of all default StyleSheets provided by the system
$path_css_sys = "include/css";
if(file_exists($path_css_sys)) {
	$dir_css_sys = opendir($path_css_sys);
	$file_css_sys = 0;
	while ($filename_css_sys = readdir($dir_css_sys)) {
		/* deprecated: PHP 5.3 upgrade
		eregi('[\.]*[[:alpha:]]+$', $filename_css_sys, $extension_css_sys);
		if(strtolower($extension_css_sys)==".css" AND $filename_css_sys!="." AND $filename_css_sys!=".." AND !eregi("^none_", $filename_css_sys)) {
		$extension_css_sys = preg_match('/[\.]*[[:alpha:]]+$/i', $filename_css_sys);*/
		if(preg_match('/[\.]css$/', $filename_css_sys) AND $filename_css_sys!="." AND $filename_css_sys!=".." AND !preg_match("/^none_/", $filename_css_sys)) {
			$array_css_sys[$file_css_sys] = $filename_css_sys;
			$file_css_sys++;
		}
	}
	closedir($dir_css_sys);
	for($i=0; $i<$file_css_sys; $i++) {
		echo "\n<link rel='StyleSheet' type='text/css' href='$path_css_sys/$array_css_sys[$i]'$close_tag>";
	}
	if($mod=="none_Admin" AND _FN_IS_ADMIN AND file_exists("$path_css_sys/none_dashboard.css")) {
		echo "\n<link rel='StyleSheet' type='text/css' href='$path_css_sys/none_dashboard.css'$close_tag>";
	}
}
// declaration of all StyleSheets provided by the theme in use (if not using Administration section)
$path_css_thm = "themes/$theme";
if($mod!="none_Admin" AND file_exists($path_css_thm)) {
	$dir_css_thm = opendir($path_css_thm);
	$file_css_thm = 0;
	while ($filename_css_thm = readdir($dir_css_thm)) {
		/* deprecated: PHP 5.3 upgrade
		eregi('[\.]*[[:alpha:]]+$', $filename_css_thm, $extension_css_thm);
		if(strtolower($extension_css_thm[0])==".css" AND $filename_css_thm!="." AND $filename_css_thm!=".." AND !eregi("^none_", $filename_css_thm)) {
		$extension_css_thm = preg_match('/[\.]*[[:alpha:]]+$/', $filename_css_thm);*/
		if(preg_match('/[\.]css$/', $filename_css_thm) AND $filename_css_thm!="." AND $filename_css_thm!=".." AND !preg_match("/^none_/", $filename_css_thm)) {
			$array_css_thm[$file_css_thm] = $filename_css_thm;
			$file_css_thm++;
		}
	}
	closedir($dir_css_thm);
	for($i=0; $i<$file_css_thm; $i++) {
		echo "\n<link rel='StyleSheet' type='text/css' href='$path_css_thm/$array_css_thm[$i]'$close_tag>";
	}
}

// declaration of the XML file with rss-feeds
if(file_exists(get_fn_dir("var")."/backend.xml"))
	echo "\n<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".get_fn_dir("var")."/backend.xml\" title=\"$sitename\"$close_tag>";

// favicon
if(file_exists("favicon.ico"))
	echo "\n<link rel=\"shortcut icon\" href=\"favicon.ico\"$close_tag>\n";

// loading all JavaScripts that are present in '/include/javascripts' directory
$path_js = "include/javascripts";
if(file_exists($path_js)) {
	$dir_js = opendir($path_js);
	$file_js = 0;
	while ($filename_js = readdir($dir_js)) {
		/* deprecated: PHP 5.3 upgrade
		eregi('[\.]*[[:alpha:]]+$', $filename_js, $extension_js);
		if(strtolower($extension_js[0])==".js" AND $filename_js!="." AND $filename_js!=".." AND !eregi("^none_", $filename_js) AND !eregi("^\.", $filename_js)) {
		$extension_js = preg_match('/[\.]*[[:alpha:]]+$/', $filename_js);*/
		if(preg_match('/[\.]js$/', $filename_js) AND $filename_js!="." AND $filename_js!=".." AND !preg_match("/^none_/", $filename_js) AND !preg_match("/^\./", $filename_js)) {
			$array_js[$file_js] = $filename_js;
			$file_js++;
		}
	}
	closedir($dir_js);
	if($file_js>0) sort($array_js);
	for($i=0; $i<$file_js; $i++) {
		echo "\n<script type='text/javascript' src='$path_js/$array_js[$i]'></script>";
	}
}

// end of HTML headers
echo "\n\n</head>\n";
?>
