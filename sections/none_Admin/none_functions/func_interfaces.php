<?php
/*
 * Print main menu with options' icons
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_main() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	?><ul class="htabs">
		<li class="dashgroup">
			<a href="index.php?mod=<?php echo $mod?>" title="Dashboard" accesskey="0">
			<img src="images/dashboard/home.png" /><br/><?php echo _FNCC_DASHBOARD?>
			</a>
		</li>
		<li class="dashgroup">
			<?php echo build_fnajax_link($mod, "&amp;fncclist=fncclist1", "dashtab", "get"); ?>
			<img src="<?php echo get_fn_dir("sections")."/$mod"?>/none_images/info.png" /><br/><?php echo _FNCC_FNOPTIONS?>
			</a>
		</li>
		<li class="dashgroup">
			<?php echo build_fnajax_link($mod, "&amp;fncclist=fncclist2", "dashtab", "get"); ?>
			<img src="<?php echo get_fn_dir("sections")."/$mod"?>/none_images/app_kate.png" /><br/><?php echo _FNCC_MANAGEFN?>
			</a>
		</li>
		<li class="dashgroup">
			<?php echo build_fnajax_link($mod, "&amp;fncclist=fncclist3", "dashtab", "get"); ?>
			<img src="<?php echo get_fn_dir("sections")."/$mod"?>/none_images/kdmconfig.png" /><br/><?php echo _FNCC_USERS?>
			</a>
		</li>
		<li class="dashgroup">
			<?php echo build_fnajax_link($mod, "&amp;fncclist=fncclist4", "dashtab", "get"); ?>
			<img src="<?php echo get_fn_dir("sections")."/$mod"?>/none_images/security.png" /><br/><?php echo _FNCC_SECURITY?>
			</a>
		</li>
		<li class="dashgroup">
			<?php echo build_fnajax_link($mod, "&amp;fncclist=fncclist5", "dashtab", "get"); ?>
			<img src="<?php echo get_fn_dir("sections")."/$mod"?>/none_images/kig.png" /><br/><?php echo _FNCC_THEME?>
			</a>
		</li>
		<li class="dashgroup">
			<?php echo build_fnajax_link($mod, "&amp;fncclist=fnccplugins", "dashtab", "get"); ?>
			<img src="<?php echo get_fn_dir("sections")."/$mod"?>/none_images/hdd_unmount.png" /><br/>Plugins
			</a>
		</li>
	</ul><?php
}

/*
 * Print submenu with options' icons
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_list1() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	?><!-- GENERAL INFOS -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccinfo", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/info.png" alt="info" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_SERVERINFOS?>
	</a>
	<!-- GENERAL CONFIGURATION -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccconf", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/kcontrol.png" alt="info" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_GENERALCONF?>
	</a>
	<!-- FTP MANAGER -->
	<a href="#" onclick="window.open('sections/<?php echo $mod?>/none_tools/webadmin.php','','toolbar=no,scrollbars=yes,resizable=yes');">
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/kfm.png" alt="file manager" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_FILEMANAGER?>
	</a>
	<?php
}
function fncc_list2() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	?><!-- EDITING MOTD -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccmotd", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/app_kate.png" alt="motd" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_MOTD?>
	</a>
	<!-- EDITING POLL -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccpolledit", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/poll.png" alt="poll" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_POLL?>
	</a>
	<!-- DOWNLOAD CONFIGURATION -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccdownconf", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/down.png" alt="download" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_DOWNCONF?>
	</a><?php
}
function fncc_list3() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	?><!-- MEMBERS LIST -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccmembers", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/kdmconfig.png" alt="manage users" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_USERSLIST?>
	</a>
	<!-- ADD A MEMBER -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccnewprofile", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/add_user.png" alt="add user" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_ADDUSER?>
	</a>
	<!-- WAITING MEMBER -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccwaitingusers", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/waiting_user.png" alt="waiting" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_USERSTOACTIVATE?> (<?php echo fncc_countwaitingusers()?>)
	</a><?php
}
function fncc_list4() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	?><!-- BACKUPS -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccbackup", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/hdd_unmount.png" alt="backup" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_BACKUPS?>
	</a>
	<!-- VIEW LOGS -->
	<?php echo build_fnajax_link($mod, "&amp;op=fncclogs", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/mimetype_log.png" alt="logs" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_LOGS?>
	</a>
	<!-- MANAGE BLACKLISTS -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccblacklists", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/security.png" alt="blacklists" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_BLACKLISTS?>
	</a><?php
}
function fncc_list5() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	?><!-- THEME STRUCTURE -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccthemestruct", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/kig.png" alt="structure" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_THEMESTRUCTURE?>
	</a>
	<!-- THEME PERSONALIZATION -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccthemestyle", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/gimp.png" alt="theme" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_THEMESTYLE?>
	</a>
	<!-- CSS EDITING -->
	<?php echo build_fnajax_link($mod, "&amp;op=fnccthemecss", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/stylesheet1.png" alt="css" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_CSSTHEME?>
	</a>
	<?php echo build_fnajax_link($mod, "&amp;op=fnccforumcss", "fn_adminpanel", "get"); ?>
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/stylesheet2.png" alt="css" border="0" style="width:24px;" />&nbsp;<?php echo _FNCC_CSSFORUM?>
	</a><?php
}

/*
 * Print Flatnuke AJAX link/form
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 *
 * @param   string	$mod       FN mod to go to (GET)
 * @param   string	$option    FN option to execute (GET)
 * @param   string	$target    DIV target where to write results
 * @param   string	$method    'get' or 'post'
 * @param   string	$form      Form name from which keeping POST variables
 * @return  string	$fnajax    HTML code to print
 */
function build_fnajax_link($mod, $option, $target, $method, $form="") {
	// security conversions
	$mod    = getparam($mod,    PAR_NULL, SAN_FLAT);
	$option = getparam($option, PAR_NULL, SAN_FLAT);
	$target = getparam($target, PAR_NULL, SAN_FLAT);
	$method = strtolower(getparam($method, PAR_NULL, SAN_FLAT));
	// build the link
	switch($method) {
	case "get":
		$fnajax = "<a href=\"javascript:jQueryFNcall('".get_fn_dir("sections")."/$mod/section.php?mod=$mod"."$option','$method','$target');\">";
	break;
	case "post":
		$fnajax = "\n<form id=\"$form\" action=\"javascript:jQueryFNcall('".get_fn_dir("sections")."/$mod/section.php?mod=$mod','$method','$target','$form');\">\n";
	break;
	default: $fnajax = "";
	}
	// return string result
	return($fnajax);
}

/*
 * Print general informations about the site and the hosting
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100102
 */
function fncc_info() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	$req = getparam("REQUEST_URI", PAR_SERVER, SAN_NULL);
	// check if the GD library is installed or not
	if(function_exists("gd_info")) {
		$GDinfo = gd_info();
		$GDinfo = preg_replace('/[[:alpha:][:space:]()]+/i', '', $GDinfo['GD Version']);
	} else $GDinfo = _FNCC_NOGDLIB;
	// get siteurl
	$my_siteurl = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
	$my_siteurl = str_replace(get_fn_dir("sections")."/".$mod."/section.php","index.php",$my_siteurl);
	$protocol   = (isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS']=="on") ? ("https://") : ("http://");
	// print some informations about the site
	?><div style="padding: 0em 0.5em 2em 0.5em;">
	<span class="fncc_title"><?php echo _FNCC_SERVERINFO?></span>
	<div style="padding-top:1em;"><b><?php echo _FNCC_SITEURL?></b><?php echo $protocol.$my_siteurl?></div>
	<div style="padding-top:1em;"><b><?php echo _FNCC_OS?></b><?php echo PHP_OS?></div>
	<div style="padding-top:1em;"><b><?php echo _FNCC_WEBSERVER?></b><?php echo $_SERVER["SERVER_SOFTWARE"]?></div>
	<div style="padding-top:1em;"><b><?php echo _FNCC_PHP?></b><?php echo phpversion();?> - <a href="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_tools/phpinfo.php" target="new" title="PHP informations">PHPInfo</a></div>
	<div style="padding-top:1em;"><b><?php echo _FNCC_GDLIB?></b><?php echo $GDinfo;?></div>
	<div style="padding-top:1em;"><b><?php echo _FNCC_FLATNUKE?></b><?php if(function_exists("get_fn_version")) echo get_fn_version(); else echo _FNCC_FNUNKNOWN;?></div><?php
	$total_space   = round(disk_free_space("./")/1024/1024,2);
	$site_space    = round(fncc_getsize("./")/1024/1024,2);
	$perc_occupied = round($site_space*100/$total_space,1);
	$perc_free     = round(100-($site_space*100/$total_space),1);
	?>
	<div style="padding-top:1em;"><b><?php echo _FNCC_SERVERSPACE?></b><?php echo "$total_space Mb";?></div>
	<div style="padding:1em 0em 2em 0em;"><b><?php echo _FNCC_SITESPACE?></b><?php echo "$site_space Mb";?>
		<p><table align="center" cellspacing="0"><tbody><tr>
		<td><?php echo $perc_occupied?>%&nbsp;</td>
		<td style="width:<?php echo $perc_occupied?>px;background-color:#FF0000;"></td>
		<td style="width:<?php echo $perc_free?>px;background-color:#00FF00;"></td>
		<td>&nbsp;<?php echo $perc_free?>%</td>
		</tr></tbody></table></p>
	</div>
	<span class="fncc_title"><?php echo _FNCC_MYINFOS?></h4></span>
	<div style="padding-top:1em;"><b><?php echo _FNCC_IP?></b><?php echo $_SERVER["REMOTE_ADDR"]?></div>
	<div style="padding-top:1em;"><b><?php echo _FNCC_USERAGENT?></b><?php echo $_SERVER["HTTP_USER_AGENT"]?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Return directory size
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20061101
 *
 * @param	string	$dirpath	Path of the directory to check
 * @return	integer	$totalsize	Size of the directory
 */
function fncc_getsize($dirpath) {
	// security conversions
	$dirpath = getparam($dirpath, PAR_NULL, SAN_FLAT);
	// go calculate
	require_once("include/filesystem/DeepDir.php");
	$totalsize = 0;
	$dir = new DeepDir();
	$dir->setDir($dirpath);
	$dir->load();
	foreach($dir->files as $n => $pathToFile)
		$totalsize += filesize($pathToFile);
	return $totalsize;
}

/*
 * Flatnuke general configuration
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_conf() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESGENERALCONF?></span>
		<div style="padding-top:1em;"><?php fncc_generalconf(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage MOTD file
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_motd() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESMOTD?></span>
		<div style="padding-top:1em;"><?php fncc_editconffile(get_fn_dir("var")."/motd.php"); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage poll configuration
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_polledit() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESPOLL?></span>
		<div style="padding-top:1em;"><?php fncc_editpoll(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage FdPlus configuration
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_downconf() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESDOWNCONF?></span>
		<div style="padding-top:1em;"><?php fncc_fdplusconf(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * List all members of the site
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_members() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESUSERSLIST?></span>
		<div style="padding-top:1em;"><?php fncc_userslist(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Add a new user profile
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_newprofile() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESADDUSER?></span>
		<div style="padding-top:1em;"><?php fncc_newuserprofile(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Add a waiting user profile
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_waitingusers() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_WAITINGUSERS?></span>
		<div style="padding-top:1em;"><?php fncc_listwaiting(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Returns the number of users waiting for activation
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20070726
 *
 * @return	integer	Number of users waiting
 */
function fncc_countwaitingusers() {
	$waitinglist = array();
	$handle = opendir(get_waiting_users_dir());
	while($file = readdir($handle)) {
		if(preg_match("/^[0-9a-zA-Z]+\.php$/i", $file)) {
			array_push($waitinglist, $file);
		}
	}	//echo "<pre>";print_r($waitinglist);echo "</pre>";	//-> TEST
	closedir($handle);
	return(count($waitinglist));
}

/*
 * Admin can create and download FN backups
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_backups() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESBACKUPS?></span>
		<div style="padding-top:1em;"><?php fncc_managebackups(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Returns the list of backup files
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20070722
 *
 * @return	array	List of backup files created in /var directory
 */
function fncc_listbackups() {
	$backup_files = array();
	$handle = opendir(get_fn_dir("var"));
	while($file = readdir($handle)) {
		if(preg_match("/^backup_[a-zA-Z]+_[0-9]+\.zip$/i", $file)) {
			array_push($backup_files, $file);
		}
	}
	closedir($handle);
	return($backup_files);
}

/*
 * Admin can look at Flatnuke logs
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_logs() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESLOGS?></span>
		<div style="padding-top:1em;"><?php fncc_managelogs(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Admin can manage Flatnuke blacklists
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_blacklists() {
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESBLACKLISTS?></span>
		<div style="padding-top:1em;"><?php fncc_manageblacklists(); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage theme's structure
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_themestruct() {
	global $theme;
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESTHEMESTRUCTURE?></span>
		<div style="padding-top:1em;"><?php fncc_editconffile(get_fn_dir("themes")."/$theme/structure.php"); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage theme's personalisations
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_themestyle() {
	global $theme;
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESTHEMESTYLE?></span>
		<div style="padding-top:1em;"><?php fncc_editconffile(get_fn_dir("themes")."/$theme/theme.php"); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage theme's CSS file
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_themecss() {
	global $theme;
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESCSSTHEME?></span>
		<div style="padding-top:1em;"><?php fncc_editconffile(get_fn_dir("themes")."/$theme/style.css"); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Manage forum's CSS file
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_forumcss() {
	global $theme;
	?><div style="padding: 0 0.5em 2em 0.5em;">
		<span class="fncc_title"><?php echo _FNCC_DESCSSFORUM?></span>
		<div style="padding-top:1em;"><?php fncc_editconffile(get_fn_dir("themes")."/$theme/forum.css"); ?></div>
	</div><?php
	// print the back button
	echo _FNCC_GOTOP;
}

/*
 * Section reserved to site admins only
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_onlyadmin() {
	?><div style="padding: 2em 0.5em 2em 0.5em;text-align:center;"><h4><?php echo _FNCC_ONLYADMIN?></h4>
		<div style="padding-top:1em;"><img src="images/maintenance.png" alt="lock" border="0" /></div>
		<div style="padding-top:1em;"><?php echo _FNCC_DESONLYADMIN?></div>
	</div><?php
	// log the attempt
	$ip = getparam("REMOTE_ADDR", PAR_SERVER, SAN_NULL);
	fnlog("Security", "$ip||".get_username()."||Tried to access the administration panel.");
}

/*
 * Load the list of third party plugins in the dashboard
 *
 * @author Alfredo Cosco <orazio.nelson@gmail.com>
 * @version 20130216
 */
function get_thirdparty_plugins() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	$sections = get_fn_dir("sections");
	// check plugin's directory existance
	if(!is_dir("$sections/$mod/none_plugins")) {
		echo "The plugins directory does not exist, create the <i>none_plugins</i> directory in <i>$sections/$mod</i><br />";
	} else {
		// search for installed plugins
		$modlist   = array();
		$fileslist = array();
		$handle    = opendir("$sections/$mod/none_plugins");
		while ($tmpfile = readdir($handle)) {
			if(stristr($tmpfile,"none_")){
				if ( (!preg_match("/[.]/i",$tmpfile)) and is_dir("$sections/$mod/none_plugins/".$tmpfile)) {
					if ($tmpfile=="CVS") continue;
					array_push($modlist, $tmpfile);
				}
			}
		}
		closedir($handle);
		// order and print the list
		if(count($modlist)<=0) {
			echo "The plugins directory is empty";
		} else {
			$modlist = str_replace("none_","",$modlist);
			sort($modlist);
			foreach($modlist as $k=>$v) {
				$parsev  = str_replace("_", " ", $v);
				echo build_fnajax_link("$mod/none_plugins/none_$v", "&amp;plugin=none_$v", "fn_adminpanel", "get");
				if(file_exists("$sections/$mod/none_plugins/none_$v/modicon.png")){
					echo "<img src=\"$sections/$mod/none_plugins/none_$v/modicon.png\" alt=\"$parsev\" border=\"0\" style=\"width:24px;\" />&nbsp;";
				} else {
					echo "<img src=\"$sections/$mod/none_images/info.png\" alt=\"Add a 48x48 image modicon.png to: none_$v module to customize\" border=\"0\" style=\"width:24px;\" />&nbsp;";
				}
				echo $parsev;
				echo "</a>";
			}
		}
	}
}

?>
