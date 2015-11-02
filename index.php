<?php
/************************************************************************/
/* FlatNuke - Flat Text Based Content Management System                 */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2003-2004 by Simone Vellei                             */
/* http://flatnuke.sourceforge.net                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// include Flatnuke APIs
include_once "functions.php";

//Time zone
if (function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
	@date_default_timezone_set(date_default_timezone_get());

create_fn_constants();

// headersite
include "header.php";

// first microtime to calculate time generation page
$time1 = get_microtime();

// automatically run PHP scripts contained in include/autoexec.d
load_php_code("include/autoexec.d");

/**
 * Flatnuke MAIN function
 *
 * This is the function that manages all Flatnuke actions.
 *
 * @author Simone Vellei <simone_vellei@users.sourceforge.net>
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @author Aldo Boccacci <zorba_@tin.it>
 */
function getflopt(){
	global $home_section, $theme;

	$op       = _FN_MOD;
	$file     = stripslashes(getparam("file",PAR_GET,SAN_FLAT));
	$id       = getparam("id",       PAR_GET,SAN_FLAT);
	$fnaction = getparam("fnaction", PAR_POST,SAN_FLAT);
	$sect     = getparam("sect",     PAR_GET,SAN_FLAT);
	$fnfile   = getparam("fnfile",   PAR_GET,SAN_FLAT);
	$fneditor = getparam("fneditor", PAR_GET,SAN_FLAT);
	$action   = getparam("action",   PAR_GET,SAN_FLAT);
	$news     = getparam("news",     PAR_GET,SAN_FLAT);


	// check option to execute
	switch($op){
		// no option given: display homepage
		case "":
			if ($fnaction != "") continue;
			if ($action == ""){
				// print motd content if exists
				//and if $postaction is not set!
				$postaction = getparam("newsaction",PAR_POST,SAN_FLAT);
				if(file_exists(get_fn_dir("var")."/motd.php") AND trim(get_file(get_fn_dir("var")."/motd.php"))!=""
					and $postaction=="") {
					OpenTable();
					echo "<div class=\"motd\">";
					// print motd image if exists
					if(file_exists("themes/$theme/images/motd.png")) {
						echo "<img src='themes/$theme/images/motd.png' align='left' hspace='10' vspace='10' alt='Motd' />";
					} else echo "<!-- MOTD image \"themes/$theme/images/motd.png\" not found -->";
					include (get_fn_dir("var")."/motd.php");
					//fix: when motd text is too short
					echo "<div style=\"clear:both;\"></div>";
					if (_FN_IS_ADMIN){
						global $news_editor;
						echo "<br /><img src=\"themes/$theme/images/modify.png\" alt=\"Modify\" />&nbsp;<a href=\"index.php?mod=modcont&amp;from=index.php&amp;file="._FN_VAR_DIR."%2Fmotd.php";
						if ($news_editor=="fckeditor" AND file_exists("include/plugins/editors/FCKeditor/fckeditor.php"))
							echo "&amp;fneditor=fckeditor";
						else if ($news_editor=="ckeditor" AND file_exists("include/plugins/editors/ckeditor/ckeditor.php"))
							echo "&amp;fneditor=ckeditor";
						echo " \" title=\""._MODIFICA."\">"._MODIFICA."</a>";
					}
					echo "</div>";
					CloseTable();
				}
				// display top central block(s)
				load_php_code("blocks/center/top");
			}
			if(($home_section == "") or !isset($home_section)){
				if ($action == ""){
					include("flatnews/flatnews.php");
 					// display bottom central block(s)
					load_php_code("blocks/center/bottom");
				}
			} else {
				view_section($home_section);	// display section in homepage
				// display bottom central block(s)
				load_php_code("blocks/center/bottom");
			}
		break;
		// modify a file
		case "modcont":
			if(is_admin())
				edit_content($file,$fneditor);
			else {
				OpenTable();
				print("<div align='center'><b>"._NOLEVELSECT."</b></div>");
				CloseTable();
				return;
			}
		break;
		case "fnrenamesectinterface":
			rename_sect_interface($sect);
		break;
		case "fnnewsectinterface":
			create_sect_interface($sect);
		break;
		case "fnnewfileinterface":
			create_file_interface($sect);
		break;
		case "fndeletesectinterface":
			delete_sect_interface($sect);
		break;
		case "fnmovesectinterface":
			move_sect_interface($sect);
		break;
		case "fnmovefileinterface":
			fn_move_file_interface($fnfile);
		break;
		case "fndeletefileinterface":
			delete_file_interface($fnfile);
		break;
		case "fnrenamefileinterface":
			rename_file_interface($fnfile);
		break;
		case "usermodcont":
			user_edit_content($file,$fneditor);
		break;
		case "fnchoosesecttypeinterface":
			choose_sect_type_interface($sect);
		break;
		// view a section
		default:
			if (trim($fnaction=="")) view_section($op);
		break;
	}

	// check POST action to perform
	switch($fnaction){
		// manage sections
		case "fnrenamesect"    : rename_section();      break;
		case "fnmovesect"      : move_section();        break;
		case "fncreatesect"    : create_section();      break;
		case "fndeletesect"    : delete_section();      break;
		case "fnerasesect"     : erase_section();       break;
		case "fnchangesecttype": change_section_type(); break;
		// manage files
		case "fncreatefile": create_file();    break;
		case "fndeletefile": delete_file();    break;
		case "fnrenamefile": fn_rename_file(); break;
		case "fnmovefile"  : fn_move_file();   break;
		// manage permissions
		case "fnaddusersectperm"           : fn_add_user_view_perm();    break;
		case "fnremoveusersectperm"        : fn_remove_user_view_perm(); break;
		case "fnaddusereditsectpermconfirm": fn_add_edit_perm_confirm(); break;
		case "fnaddusereditsectperm"       : fn_add_user_edit_perm();    break;
		case "fnremoveusereditsectperm"    : fn_remove_user_edit_perm(); break;
	}

	// check GET action to perform
	if (_FN_MOD==""){
		// include flatnews engine
		include_once "flatnews/include/news_view.php";
		// list of actions to perform
		switch ($action){
			case "viewnews":
				if (_FN_MOD==""){
					view_news("none_News",$news);
				}
			break;
			case "addcommentinterface":
				if (_FN_MOD==""){
					OpenTableTitle(_ADDCOMM);
					add_comment_interface("none_News",$news);
					CloseTableTitle();
					}
			break;
			case "addnewsinterface":
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle(_ADDNEWS);
				edit_news_interface("none_News","","add");
				CloseTableTitle();
			break;
			case "editnewsinterface":
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle(_EDITNEWS);
				edit_news_interface("none_News",$news,"edit");
				CloseTableTitle();
			break;
			case "deletenewsinterface";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle(_DELETENEWS);
				delete_news_interface("none_News",$news);
				CloseTableTitle();
			break;
			case "movenewsinterface";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle(_MOVENEWS);
				move_news_interface("none_News",$news);
				CloseTableTitle();
			break;
			case "deletecomment";
				include_once "flatnews/include/news_admin.php";
				$comment = getparam("comment",PAR_GET,SAN_FLAT);
				OpenTableTitle("News");
				delete_comment("none_News",$news,$comment);
				CloseTableTitle();
			break;
			case "editcommentinterface";
				include_once "flatnews/include/news_admin.php";
				$comment = getparam("comment",PAR_GET,SAN_FLAT);
				OpenTableTitle("News");
				edit_comment_interface("none_News",$news,$comment);
				CloseTableTitle();
			break;
			case "ontopnews";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle("News");
				set_news_ontop("none_News",$news,TRUE);
				CloseTableTitle();
			break;
			case "normalnews";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle("News");
				set_news_ontop("none_News",$news,FALSE);
				CloseTableTitle();
			break;
			case "hidenews";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle("News");
				hide_news("none_News",$news,TRUE);
				CloseTableTitle();
			break;
			case "shownews";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle("News");
				hide_news("none_News",$news,FALSE);
				CloseTableTitle();
			break;
			case "proposenewsinterface";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle(_SEGNEWS);
				edit_news("none_News","","propose");
				CloseTableTitle();
			break;
			case "manageproposednews";
				include_once "flatnews/include/news_admin.php";
				OpenTableTitle(_SEGNNOTIZIE);
				manage_proposed_news_interface();
				CloseTableTitle();
			break;
			case "viewproposednews":
				if (_FN_MOD==""){
					view_news("none_News",$news,TRUE);
				}
			break;
		}
	}
}



// include theme APIs and layout
include_once "themes/$theme/theme.php";
include "themes/$theme/structure.php";

// footersite
if(defined('_THEME_VER')) {
	if(_THEME_VER > 0) {
	}
} else {
	$footer_elements = get_footer_array();
	echo $footer_elements['img_fn']." ";
	echo $footer_elements['img_w3c']." ";
	echo $footer_elements['img_css']." ";
	echo $footer_elements['img_rss']." ";
	echo $footer_elements['img_mail']."<br />";
	echo $footer_elements['legal']."<br />";
	echo $footer_elements['time'];
}

// fix for non-xhtml themes
if (!preg_match("/xhtml/",_THEME_DOCTYPE)){
	$str=ob_get_contents();
	ob_end_clean();
	$str=str_replace(" />",">",$str);
	$str=str_replace("/>",">",$str);
	die($str);
}

?>
