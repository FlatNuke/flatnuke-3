<?php

/**
 * This module shows Admin area in a dashboard on Flatnuke.
 *
 * @author Alfredo Cosco <orazio.nelson@gmail.com>
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @author Lorenzo Caporale <piercolone@gmail.com>
 *
 * @version 20130303
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 */

if (preg_match("/section.php/i", $_SERVER['PHP_SELF'])) {
	Header("Location: ../../../index.php");
	die();
}

?><div id="dashboard-widgets" class="metabox-holder"><?php

// security checks
$mod = _FN_MOD;

// manage translations
global $lang;
$title    = "title-$lang";
$subtitle = "subtitle-$lang";

// scan widget directory and build widgets' list
$widgets = array();
if(file_exists(get_fn_dir("sections")."/$mod/none_widgets")) {
	$widgets_dir = opendir(get_fn_dir("sections")."/$mod/none_widgets");
	while($file = readdir($widgets_dir)) {
		if(preg_match("/[\.]*[[:alpha:]]+\.php$/i",$file) AND !preg_match("/none_/i",$file) AND $file!="section.php") {
			array_push($widgets, $file);
		}
	}
	closedir($widgets_dir);
}
if(count($widgets)>0) sort($widgets);

// load each widget
foreach($widgets as $current_widget) {
	// load widget XML definition
	if (file_exists(get_fn_dir("sections")."/$mod/none_widgets/".str_replace(".php",".xml",$current_widget))){
		$string = get_file(get_fn_dir("sections")."/$mod/none_widgets/".str_replace(".php",".xml",$current_widget));
		$xmldata = new SimpleXMLElement($string);
		// general informations about the widget
		foreach ($xmldata->info as $info){
			$widget_id_name  = utf8_decode((string) $info->id_name);
			$widget_descript = htmlentities(addslashes(utf8_decode((string) $info->description)),ENT_COMPAT,_CHARSET);
			$widget_version  = utf8_decode((string) $info->version);
		}
		// informations about widget's author
		foreach ($xmldata->author as $author){
			$widget_author   = addslashes(utf8_decode((string) $author->name));
			$widget_mail     = utf8_decode((string) $author->mail);
			$widget_website  = utf8_decode((string) $author->website);
		}
		// widget title and subtitle translations
		foreach ($xmldata->translations as $translations){
			$widget_title    = (utf8_decode((string) $translations->$title   )=="") ? (utf8_decode((string) $translations->title))    : (utf8_decode((string) $translations->$title));
			$widget_subtitle = (utf8_decode((string) $translations->$subtitle)=="") ? (utf8_decode((string) $translations->subtitle)) : (utf8_decode((string) $translations->$subtitle));
		}
		/* -- ONLY STANDARD AND UPDATED WIDGETS WILL BE LOADED! --
		   Please have a look to /sections/none_Admin/none_widgets directory and
		   start to study the two files none_example.php and none_example.xml:
		   you'll find the Flatnuke standard structure to apply to your own widget.
		   -- TECHNICAL PARAMETHERS --
		   Mandatory fields in XML file:
			* id_name     -> unique id to apply to your widget
			* description -> long description of your widget
			* version     -> date formatted AAAAMMGG
			* name        -> author's name
			* title       -> widget's main title
			* subtitle    -> widget's subtitle
		*/
		if ($widget_id_name!="" AND $widget_descript!="" AND $widget_version!="" AND $widget_author!="" AND $widget_title!="" AND $widget_subtitle!="") {
			// widget credits
			$credits = "<p>$widget_descript</p>Version: $widget_version<br/>Author: $widget_author<br/>Mail: $widget_mail<br/>Web: $widget_website";
			// widget main content
			?><div class="postbox-container" style="width: 47%;">
			<div id="<?php echo $widget_id_name ?>" class="postbox">
			<h3 class="hndle"><span onmouseover="Tip('<?php echo $credits ?>',FADEIN,400)" onmouseout="UnTip()"><?php echo $widget_title ?></span></h3>
			<div class="inside">
				<p class="sub"><?php echo $widget_subtitle ?></p>
				<div class="table">
					<?php include_once(get_fn_dir("sections")."/$mod/none_widgets/$current_widget") ?>
				</div>
				<div class="widget_footer">
					<p><?php echo $widget_footer ?></p>
				</div>
			</div>
			</div>
			</div><?php
		}
	}
}

?><div style="clear:both;"></div>
</div>
