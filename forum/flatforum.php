<?php 
/**
 * Flatforum: un forum integrato nella struttura di Flatnuke
 * 
 * Autore: Aldo Boccacci
 * sito web: www.aldoboccacci.it
 * 
 * This program is free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA
 */

global $forum_moderators;

$GLOBALS['forum_moderators'] = $forum_moderators;

include("forum/include/ff_functions.php");
include("forum/include/ffview.php");
if (is_admin() or is_forum_moderator()) include("forum/include/ffadmin.php");

check_get_params();

//scelgo l'azione
if (isset($_POST['ffaction'])){
	$ffaction = getparam("ffaction",PAR_POST,SAN_FLAT);
	if ($ffaction=="newgroup"){
		create_group_interface(get_forum_root());
	}
	else if ($ffaction=="creategroup"){
		create_group(get_forum_root());
	}
	else if ($ffaction=="newargument"){
		create_argument_interface(get_forum_root());
	}
	else if ($ffaction=="createargument"){
		create_argument(get_forum_root());
	}
	else if ($ffaction=="createnewtopic"){
		create_new_topic(get_forum_root());
	}
	else if ($ffaction=="addpost"){
		add_post();
	}
	else if ($ffaction=="edpost"){
		edit_post();
	}
	else if ($ffaction=="modifyargument"){
		save_argument();
	}
	else if ($ffaction=="alertuser"){
		alert_list_add();
	}
	else if ($ffaction=="removealertuser"){
		alert_list_remove();
	}
	else if ($ffaction=="renameargument"){
		rename_argument(get_forum_root());
	}
	else if ($ffaction=="deleteargument"){
		delete_argument(get_forum_root());
	}
	else if ($ffaction=="moveargument"){
		move_argument(get_forum_root());
	}
	else if ($ffaction=="deletegroup"){
		delete_group(get_forum_root());
	}
	else if ($ffaction=="renamegroup"){
		rename_group(get_forum_root());
	}
	else if ($ffaction=="backupforum"){
		backup_forum_structure(get_forum_root());
	}
	else if ($ffaction=="movetopic"){
		move_topic();
	}
	else if ($ffaction=="deletetopic"){
		delete_topic();
	}
	else if ($ffaction=="ffcontrolpanel"){
		ff_control_panel();
	}
}
else if (isset($_GET['ffaction'])){
	$ffaction = getparam("ffaction",PAR_GET,SAN_FLAT);
	if ($ffaction=="newtopic"){
		edit_post_interface("newtopic");
	}
	else if ($ffaction=="newpost"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		edit_post_interface("newpost",get_forum_root()."/$group/$argument/$topic");
	}
	else if ($ffaction=="editpost"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		edit_post_interface("editpost",get_forum_root()."/$group/$argument/$topic");
	}
	else if ($ffaction=="ontop"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		evidenzia_topic($group,$argument,$topic,"true");
	}
	else if ($ffaction=="normal"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		evidenzia_topic($group,$argument,$topic,"false");
	}
	else if ($ffaction=="hide"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		hide_topic($group,$argument,$topic);
	}
	else if ($ffaction=="show"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		show_topic($group,$argument,$topic);
	}
	else if ($ffaction=="lock"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		lock_topic($group,$argument,$topic);
	}
	else if ($ffaction=="unlock"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		unlock_topic($group,$argument,$topic);
	}
	else if ($ffaction=="deletepost"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		$number = getparam("number",PAR_GET,SAN_FLAT);
		if (!check_var($number,"digit")) 
			ff_die("\$number is invalid! (".strip_tags($number).")",__FILE__,__LINE__);
		delete_post($group,$argument,$topic,$number);
	}
	else if ($ffaction=="setpostontop"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		$number = getparam("number",PAR_GET,SAN_FLAT);
		if (!check_var($number,"digit")) 
			ff_die("\$number is invalid! (".strip_tags($number).")",__FILE__,__LINE__);
		set_post_on_top($group,$argument,$topic,$number);
	}
	else if ($ffaction=="removepostontop"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		$topic = getparam("topic",PAR_GET,SAN_FLAT);
		if (!check_path($topic,"","true")) 
			ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
		$number = getparam("number",PAR_GET,SAN_FLAT);
		if (!check_var($number,"digit")) 
			ff_die("\$number is invalid! (".strip_tags($number).")",__FILE__,__LINE__);
		remove_post_on_top($group,$argument,$topic,$number);
	}
	else if ($ffaction=="editargument"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);

		edit_argument_interface(get_forum_root(),$group,$argument);
	}
	else if ($ffaction=="deleteargumentinterface"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);

		delete_argument_interface(get_forum_root(),$group,$argument);
	}
	else if ($ffaction=="renameargument"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);

		rename_argument_interface(get_forum_root(),$group,$argument);
	}
	else if ($ffaction=="lockargument"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);

		lock_argument(get_forum_root(),$group,$argument);
	}
	else if ($ffaction=="unlockargument"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);

		unlock_argument(get_forum_root(),$group,$argument);
	}
	else if ($ffaction=="moveargumentinterface"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		$argument = getparam("argument",PAR_GET,SAN_FLAT);
		if (!check_path($argument,"","false")) 
			ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);

		move_argument_interface(get_forum_root(),$group,$argument);
	}
	else if ($ffaction=="deletegroupinterface"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		delete_group_interface(get_forum_root(),$group);
	}
	else if ($ffaction=="renamegroupinterface"){
		$group = getparam("group",PAR_GET,SAN_FLAT);
		if (!check_path($group,"","false")) 
			ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
		
		rename_group_interface(get_forum_root(),$group);
	}
	else if ($ffaction=="forumguide"){
		view_forum_header();
		include("forum/help.php");
	}
	else if ($ffaction=="movetopicinterface"){
		$topicpath=getparam("topicpath",PAR_GET,SAN_FLAT);
		if (!check_path($topicpath,get_forum_root(),"true")) 
			ff_die("\$topicpath is invalid! (".strip_tags($topicpath).")",__FILE__,__LINE__);
		move_topic_interface($topicpath);
	}
	else if ($ffaction=="deletetopicinterface"){
		$topicpath=getparam("topicpath",PAR_GET,SAN_FLAT);
		if (!check_path($topicpath,get_forum_root(),"true")) 
			ff_die("\$topicpath is invalid! (".strip_tags($topicpath).")",__FILE__,__LINE__);
		delete_topic_interface($topicpath);
	}
	else if ($ffaction=="viewrules"){

		include(get_forum_root()."/rules.php");
		
		echo "<br /><div style=\"text-align : center;\"><b><a href=\"javascript:history.back()\">Indietro</a></b></div>";
	}
}
else if (isset($_GET['group']) and isset($_GET['argument']) and !isset($_GET['topic'])){
	$group = getparam("group",PAR_GET,SAN_FLAT);
	$argument = getparam("argument",PAR_GET,SAN_FLAT);
	if (!check_path($group,"","false")) 
		ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
	$argument = getparam("argument",PAR_GET,SAN_FLAT);
	if (!check_path($argument,"","false")) 
		ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
		
	forum_view_argument(get_forum_root(),$group,$argument);

}
else if (isset($_GET['group']) and isset($_GET['argument']) and isset($_GET['topic'])){
	$group = getparam("group",PAR_GET,SAN_FLAT);
	$argument = getparam("argument",PAR_GET,SAN_FLAT);
	if (!check_path($group,"","false")) 
		ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);
	$argument = getparam("argument",PAR_GET,SAN_FLAT);
	if (!check_path($argument,"","false")) 
		ff_die("\$argument is invalid! (".strip_tags($argument).")",__FILE__,__LINE__);
	$topic = getparam("topic",PAR_GET,SAN_FLAT);
	if (!check_path($topic,"","true")) 
		ff_die("\$topic is invalid! (".strip_tags($topic).")",__FILE__,__LINE__);
	
	forum_view_topic(get_forum_root(),$group,$argument,$topic);
}
else if (isset($_GET['group']) and !isset($_GET['argument'])){
	$group = getparam("group",PAR_GET,SAN_FLAT);
	if (!check_path($group,"","false")) 
		ff_die("\$group is invalid! (".strip_tags($group).")",__FILE__,__LINE__);

	ff_view_group(get_forum_root(),$group);
}
else forum_overview(get_forum_root());

?>