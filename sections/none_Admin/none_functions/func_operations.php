<?php

/*
 * Print forms to manage Flatnuke general configuration
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20090918
 */
function fncc_generalconf() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	// check file existance
	$file = "config.php";
	if(file_exists($file)) {
		echo "<form action=\"index.php?mod=$mod\" method=\"post\">";
		// scan configuration file to find all settings
		$settings  = array();
		$conf_file = file($file);
		//ALDO BOCCACCI:
		//fixes for configuration lines with multiple spaces on the right, left
		//or in the middle of the line
		for($i=0;$i<count($conf_file);$i++) {
			$conf_line = trim($conf_file[$i]);
			//remove comments before value declaration
			$conf_line = trim(preg_replace("/^\/\*.*\*\//","",$conf_line));
			if(preg_match("/^\\\$./",$conf_line))	{// take only rows starting with '$'
				$line_tmp = explode(";", $conf_line);// purge strings from eventual comments on the right
				$line = explode("=", $line_tmp[0]);// split variable from its value
				// build array with settings [variable name, value]
				$settings[str_replace("$","",trim($line[0]))] = htmlentities(trim($line[1],"\" "),ENT_COMPAT,_CHARSET);
			}
		}	//print_r($settings);	//-> TEST
		// scan for installed themes (do not list hidden ones)
		$themes_array = array();
		$theme_num    = 0;

		$themes = glob(get_fn_dir("themes")."/*");
		if (!$themes) $themes = array(); // glob may returns boolean false instead of an empty array on some systems

		foreach ($themes as $theme_one){
			if(is_dir($theme_one) AND $theme_one!="CVS" AND $theme_one!="." AND $theme_one!=".." AND !stristr($theme_one,"none_")) {
				$themes_array[$theme_num] = $theme_one;
				$theme_num++;
			}
		}
		if($theme_num>0) {
			sort($themes_array);
		}	//print_r($themes_array);	//-> TEST
		// scan for installed languages
		$languages_array = array();
		$language_num    = 0;

		$languages = glob("languages/*.php");
		if (!$languages) $languages = array(); // glob may returns boolean false instead of an empty array on some systems

		foreach ($languages as $language_one){
			if(is_file($language_one) AND $language_one!="CVS" AND $language_one!="." AND $language_one!="..") {
				$languages_array[$language_num] = $language_one;
				$language_num++;
			}
		}
		if($language_num>0) {
			sort($languages_array);
		}	//print_r($languages_array);	//-> TEST
		?>
		<div style="margin-bottom:1em;">
			<label for="sitename"><?php echo _FNCC_CONFSITENAME?></label><br />
			<input type="text" name="sitename" id="sitename" style="width:100%;" maxlength="100" value="<?php echo $settings['sitename']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="sitedescription"><?php echo _FNCC_CONFSITEDESCRIPTION?></label><br />
			<input type="text" name="sitedescription" id="sitedescription" style="width:100%;" maxlength="500" value="<?php echo $settings['sitedescription']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="keywords"><?php echo _FNCC_CONFKEYWORDS?></label><br />
			<input type="text" name="keywords" id="keywords" style="width:100%;" maxlength="1500" value="<?php echo $settings['keywords']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<div style="float:left;width:100%">
			<label for="theme"><?php echo _FNCC_CONFTHEME?></label>
			<ul><?php
			// print the list of selectable themes
			foreach ($themes_array as $mytheme) {
				echo "<li style=\"margin:5px;float:left;display:block;\">\n";
				$mytheme = preg_replace("/^".get_fn_dir("themes")."\//","",$mytheme);
				$screenshot = (file_exists(get_fn_dir("themes")."/$mytheme/screenshot.png")) ? (get_fn_dir("themes")."/$mytheme/screenshot.png") : (get_fn_dir("sections")."/$mod/none_images/no_preview.png");
				echo "<img src=\"$screenshot\" style='border:1px solid #000;max-width:130px;max-height:98px;' alt=\"$mytheme\" />\n";
				$checked = ($settings['theme'] == $mytheme) ? ("checked='checked'") : ("");
				echo "<br /><input type='radio' $checked name='theme' id='theme' value=\"$mytheme\" /> $mytheme</li>\n";
			}
			?></ul>
			</div>
		</div>
		<div style="margin-bottom:1em;">
			<label for="newspp"><?php echo _FNCC_CONFNEWSPP?></label><br />
			<input type="text" name="newspp" id="newspp" style="width:100%;" maxlength="3" value="<?php echo $settings['newspp']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="admin"><?php echo _FNCC_CONFADMIN?></label><br />
			<input type="text" name="admin" id="admin" style="width:100%;" maxlength="100" value="<?php echo $settings['admin']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="admin_mail"><?php echo _FNCC_CONFADMINMAIL?></label><br />
			<input type="text" name="admin_mail" id="admin_mail" style="width:100%;" maxlength="100" value="<?php echo $settings['admin_mail']?>" /><br /><i>(<?php echo _FNCC_CONFADMINMAIL_CONTACT;?>)</i>
		</div>
		<div style="margin-bottom:1em;">
			<label for="lang"><?php echo _FNCC_CONFLANG?></label><br /><?php
			// print the list of available languages
			foreach ($languages_array as $mylanguage) {
				$mylanguage = preg_replace("/^languages\//i","",$mylanguage);
				$mylanguage = str_replace(".php","",$mylanguage);
				$checked = ($settings['lang'] == $mylanguage) ? ("checked='checked'") : ("");
				echo "<input type='radio' $checked name='lang' id='lang' value='$mylanguage' /><img src='images/languages/$mylanguage.png' alt='$mylanguage' title='$mylanguage' />&nbsp;&nbsp;";
			}
		?></div>
		<div style="margin-bottom:1em;">
			<label for="reguser"><?php echo _FNCC_CONFREGUSER?></label><br /><?php
			$reguser_sel0 = "";
			$reguser_sel1 = "";
			$reguser_sel2 = "";
			switch($settings['reguser']) {
				case 0: $reguser_sel0 = "checked='checked'"; break;
				case 1: $reguser_sel1 = "checked='checked'"; break;
				case 2: $reguser_sel2 = "checked='checked'"; break;
			}
			?><input type="radio" name="reguser" id="reguser" value="0" <?php echo $reguser_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="reguser" id="reguser" value="1" <?php echo $reguser_sel1?> /><?php echo _FNCC_YES?>
			<input type="radio" name="reguser" id="reguser" value="2" <?php echo $reguser_sel2?> /><?php echo _FEMAIL?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="guestnews"><?php echo _FNCC_CONFGUESTNEWS?></label><br /><?php
			$guestnews_sel0 = "";
			$guestnews_sel1 = "";
			switch($settings['guestnews']) {
				case 0: $guestnews_sel0 = "checked='checked'"; break;
				case 1: $guestnews_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="guestnews" id="guestnews" value="0" <?php echo $guestnews_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="guestnews" id="guestnews" value="1" <?php echo $guestnews_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="guestcomment"><?php echo _FNCC_CONFGUESTCOMMENT?></label><br /><?php
			$guestcomment_sel0 = "";
			$guestcomment_sel1 = "";
			switch($settings['guestcomment']) {
				case 0: $guestcomment_sel0 = "checked='checked'"; break;
				case 1: $guestcomment_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="guestcomment" id="guestcomment" value="0" <?php echo $guestcomment_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="guestcomment" id="guestcomment" value="1" <?php echo $guestcomment_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="newseditor"><?php echo _FNCC_NEWSEDITOR?></label><br /><?php
			if ($settings['news_editor']=="fckeditor") $news_editor = "fckeditor";
			else if ($settings['news_editor']=="ckeditor") $news_editor = "ckeditor";
			else $news_editor = "bbcode";
			$bbcode_selected="";
			$fckeditor_selected="";
			$ckeditor_selected="";

			switch($news_editor) {
				case "bbcode": $bbcode_selected = "checked='checked'"; break;
				case "fckeditor": $fckeditor_selected = "checked='checked'"; break;
				case "ckeditor": $ckeditor_selected = "checked='checked'"; break;
			}
			?><input type="radio" name="newseditor" id="newseditor" value="bbcode" <?php
			if (!file_exists("include/plugins/editors/FCKeditor/fckeditor.php")){
				echo "checked='checked'";
			}
			else echo $bbcode_selected
			?> />bbcode
			<input type="radio" name="newseditor" id="newseditor" value="fckeditor"
			<?php
			if (file_exists("include/plugins/editors/FCKeditor/fckeditor.php")){
				echo $fckeditor_selected;
			}
			else {
				echo "disabled='disabled'";
			}
			?> />FCKEditor
			<input type="radio" name="newseditor" id="newseditor" value="ckeditor"
			<?php
			if (file_exists("include/plugins/editors/ckeditor/ckeditor.php")){
				echo $ckeditor_selected;
			}
			else {
				echo "disabled='disabled'";
			}
			?> />CKEditor
		</div>
		<div style="margin-bottom:1em;">
			<label for="news_moderators"><?php echo _FNCC_CONFNEWSMODERATORS?></label><br />
			<input type="text" name="news_moderators" id="news_moderators" style="width:100%;" maxlength="500" value="<?php echo $settings['news_moderators']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="remember_login"><?php echo _FNCC_CONFREMEMBERLOGIN?></label><br /><?php
			$remember_login_sel0 = "";
			$remember_login_sel1 = "";
			switch($settings['remember_login']) {
				case 0: $remember_login_sel0 = "checked='checked'"; break;
				case 1: $remember_login_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="remember_login" id="remember_login" value="0" <?php echo $remember_login_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="remember_login" id="remember_login" value="1" <?php echo $remember_login_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="fuso_orario"><?php echo _FUSO?> (1h 30m = 1,5):</label><br />
			<input type="text" name="fuso_orario" id="fuso_orario" style="width:100%;" maxlength="4" value="<?php echo $settings['fuso_orario']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="maintenance"><?php echo _FNCC_CONFMAINTENANCE?></label><br /><?php
			$maintenance_sel0 = "";
			$maintenance_sel1 = "";
			switch($settings['maintenance']) {
				case 0: $maintenance_sel0 = "checked='checked'"; break;
				case 1: $maintenance_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="maintenance" id="maintenance" value="0" <?php echo $maintenance_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="maintenance" id="maintenance" value="1" <?php echo $maintenance_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="home_section"><?php echo _FNCC_CONFHOMESECTION?></label><br />
			<input type="text" name="home_section" id="home_section" style="width:100%;" maxlength="100" value="<?php echo $settings['home_section']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="topicperpage"><?php echo _FNCC_CONFTOPICPERPAGE?></label><br />
			<input type="text" name="topicperpage" id="topicperpage" style="width:100%;" maxlength="3" value="<?php echo $settings['topicperpage']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="postperpage"><?php echo _FNCC_CONFPOSTPERPAGE?></label><br />
			<input type="text" name="postperpage" id="postperpage" style="width:100%;" maxlength="3" value="<?php echo $settings['postperpage']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="memberperpage"><?php echo _FNCC_CONFMEMBERPERPAGE?></label><br />
			<input type="text" name="memberperpage" id="memberperpage" style="width:100%;" maxlength="3" value="<?php echo $settings['memberperpage']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="forum_moderators"><?php echo _FNCC_CONFFORUMMODERATORS?></label><br />
			<input type="text" name="forum_moderators" id="forum_moderators" style="width:100%;" maxlength="500" value="<?php echo $settings['forum_moderators']?>" />
		</div>
		<input type="hidden" name="conf_mod" value="modgeneralconf" />
		<input type="hidden" name="conf_file" value="<?php echo $file?>" />
		<?php
		// check writing permissions
		if(is_writeable($file))	{
			?><div align="center" style="font-weight:bold;">
				<?php echo _FNCC_WARNINGDOC?><br /><br /><input type="submit" value="<?php echo _MODIFICA?>" />
			</div><?php
		} else {
			?><div align="center" style="font-weight:bold;font-color:red;">
				<?php echo _FNCC_WARNINGRIGHTS?>
			</div><?php
		}
		?></form><?php
	} else echo "<div align='center' style='font-weight:bold;font-color:red;'>"._FNCC_WARNINGNOFILE."</div>";
}

/*
 * Read configuration files and print them ready to be managed
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20080726
 *
 * @param	string	$file	File name to modify
 */
function fncc_editconffile($file) {
	// security conversions
	$mod  = getparam("mod", PAR_GET,  SAN_FLAT);
	$file = getparam($file, PAR_NULL, SAN_FLAT);
	global $news_editor;
	if (!preg_match("/^fckeditor$|^bbcode$/i", $news_editor)) $news_editor = "bbcode";
	// check file existance
	if(file_exists($file)) {
		echo "<form action=\"index.php?mod=$mod\" method=\"post\">";
		// manage MOTD file with FCKeditor
		if ($file==get_fn_dir("var")."/motd.php" AND $news_editor=="fckeditor" AND file_exists("include/plugins/editors/FCKeditor/fckeditor.php")) {
			include("include/plugins/editors/FCKeditor/fckeditor.php");
			$oFCKeditor = new FCKeditor('conf_body');
			$oFCKeditor->BasePath = "include/plugins/editors/FCKeditor/";
			$oFCKeditor->Value = file_get_contents($file);
			$oFCKeditor->Width = "100%";
			$oFCKeditor->Height = "400";
			$oFCKeditor->ToolbarSet = "Default";
			$oFCKeditor->Create();
		} else {
			// manage standard files or FCKeditor is disabled
			echo "<textarea rows='20' style='width:100%;' name='conf_body'>";
			echo htmlspecialchars(file_get_contents($file));
			echo "</textarea>";
		}
		?><br /><br />
		<input type="hidden" name="conf_mod" value="modbodyfile" />
		<input type="hidden" name="conf_file" value="<?php echo $file?>" />
		<?php
		// check writing permissions
		if(is_writeable($file))	{
			?><div align="center" style="font-weight:bold;">
				<?php echo _FNCC_WARNINGDOC?><br /><br /><input type="submit" value="<?php echo _MODIFICA?>" />
			</div><?php
		} else {
			?><div align="center" style="font-weight:bold;font-color:red;">
				<?php echo _FNCC_WARNINGRIGHTS?>
			</div><?php
		}
		?></form><?php
	} else echo "<div align='center' style'font-weight:bold;font-color:red;'>"._FNCC_WARNINGNOFILE."</div>";
}

/*
 * Manage Flatnuke poll
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20100101
 */
function fncc_editpoll() {
	// security conversions
	$mod     = getparam("mod",     PAR_GET,    SAN_FLAT);
	$myforum = getparam("myforum", PAR_COOKIE, SAN_FLAT);
	include (get_fn_dir("sections")."/none_Sondaggio/config.php");
	// files' declarations
	$file_xml = get_file($sondaggio_file_dati);
	$attivo   = get_xml_element("attivo",$file_xml);
	$opzioni  = get_xml_element("opzioni",$file_xml);
	$opzione  = get_xml_array("opzione",$opzioni);
	// print html form
	echo "<form action=\"index.php?mod=$mod\" method=\"post\">";
	// poll status: open/closed
	echo "<div style='margin-bottom:1em;'>"._FP_STATOSONDAGGIO;
	if($attivo=="y") {
		echo "<input type='radio' name='fp_stato' value='y' checked='checked' />"._FP_APERTO;
		echo "<input type='radio' name='fp_stato' value='n' />"._FP_CHIUSO;
	} else {
		echo "<input type='radio' name='fp_stato' value='y' />"._FP_APERTO;
		echo "<input type='radio' name='fp_stato' value='n' checked='checked' />"._FP_CHIUSO;
	}
	echo "</div>";
	// poll argument
	echo "<div style='margin-bottom:1em;'>"._FP_DOMANDASONDAGGIO.": ";
	echo "<input type='text' name='salva_domanda' value='".get_xml_element("domanda",$file_xml)."' style='width:60%;' />";
	echo "</div>";
	// instructions
	echo "<div align='justify' style='margin-bottom:1em;'>"._FP_ISTRUZIONIMODIFICA."</div>";
	// poll options
	echo "<table width='70%'><tbody>";
	for($n=0; $n<count($opzione); $n++) {	// print possible answers and votes (max 20)
		echo "<tr width='100%'>";
		echo "<td align='right'>"; echo $n+1; echo "</td>";
		echo "<td><input type='text' name='salva_opzioni[]' value='".get_xml_element("testo",$opzione[$n])."' /></td>";
		echo "<td>"._FP_VOTI."</td>";
		echo "<td><input type='text' name='salva_voti[]' value='".get_xml_element("voto",$opzione[$n])."' /></td>";
		echo "</tr>";
	}
	for($n=count($opzione); $n<20; $n++) {	// print empty options
		echo "<tr width='100%'>";
		echo "<td align='right'>"; echo $n+1; echo "</td>";
		echo "<td><input type='text' name='salva_opzioni[]' value='' /></td>";
		echo "<td>"._FP_VOTI."</td>";
		echo "<td><input type='text' name='salva_voti[]' value='' /></td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
	// save poll configuration
	?><br />
	<input type="hidden" name="conf_mod" value="savepoll" />
	<center><input type="submit" value="<?php echo _FP_MODIFICA?>" /></center>
	</form><?php
	// archive poll
	echo "<form action=\"index.php?mod=$mod\" method=\"post\">";
	?><input type="hidden" name="conf_mod" value="archpoll" />
	<center><input type="submit" value="<?php echo _FP_CHIUDIARCHIVIA?>" /></center>
	</form>
	<?php
}

/*
 * Print forms to manage Flatnuke general configuration
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20080607
 */
function fncc_fdplusconf() {
	// security conversions
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	// check file existance
	$file = "download/fdconfig.php";
	if(file_exists($file)) {
		echo "<form action=\"index.php?mod=$mod\" method=\"post\">";
		// scan configuration file to find all settings
		$settings  = array();
		$conf_file = file($file);
		//ALDO BOCCACCI:
		//fixes for configuration lines with multiple spaces on the right, left
		//or in the middle of the line
		for($i=0;$i<count($conf_file);$i++) {
			$conf_line = trim($conf_file[$i]);
			//remove comments before value declaration
			$conf_line = trim(preg_replace("/^\/\*.*\*\//i","",$conf_line));
			if(preg_match("/^\\\$./",$conf_line))	{			// take only rows starting with '$'
				$line_tmp = explode(";", $conf_file[$i]);// purge strings from eventual comments on the right
				$line = explode("=", $line_tmp[0]);// split variable from its value
				// build array with settings [variable name, value]
				$settings[str_replace("$","",trim($line[0]))] = htmlentities(trim($line[1],"\" "),ENT_COMPAT,_CHARSET);
			}
		}	//print_r($settings);	//-> TEST
		// scan for installed mime icon sets
		$icons_array = array();
		$icons_num   = 0;

		$icons = glob("images/mime/*");
		if (!$icons) $icons = array(); // glob may returns boolean false instead of an empty array on some systems

		foreach ($icons as $icon_one){
			if(is_dir($icon_one) AND !preg_match("/CVS/i",$icon_one) AND $icon_one!="." AND $icon_one!="..") {
				$icons_array[$icons_num] = $icon_one;
				$icons_num++;
			}
		}
		if($icons_num>0) {
			sort($icons_array);
		}	//print_r($icons_array);	//-> TEST
		?>
		<div style="margin-bottom:1em;">
			<label for="extensions"><?php echo _FNCC_FDPEXTENSIONS?></label><br />
			<input type="text" name="extensions" id="extensions" style="width:100%;" maxlength="300" value="<?php echo $settings['extensions']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="maxFileSize"><?php echo _FNCC_FDPMAXFILESIZE?></label><br />
			<input type="text" name="maxFileSize" id="maxFileSize" style="width:100%;" maxlength="500" value="<?php echo $settings['maxFileSize']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="icon_style"><?php echo _FNCC_FDPICONSTYLE?></label><br /><?php
			// print the list of available mime icon sets
			foreach ($icons_array as $myicon) {
				$myicon = preg_replace("/^images\/mime\//i","",$myicon);
				$checked = ($settings['icon_style'] == $myicon) ? ("checked='checked'") : ("");
				echo "<input type='radio' $checked name='icon_style' id='icon_style' value='$myicon' />$myicon&nbsp;&nbsp;";
			}
		?></div>
		<div style="margin-bottom:1em;">
			<label for="newfiletime"><?php echo _FNCC_FDPNEWFILETIME?></label><br />
			<input type="text" name="newfiletime" id="newfiletime" style="width:100%;" maxlength="1500" value="<?php echo $settings['newfiletime']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="automd5"><?php echo _FNCC_FDPAUTOMD5?></label><br /><?php
			$automd5_sel0 = "";
			$automd5_sel1 = "";
			switch($settings['automd5']) {
				case 0: $automd5_sel0 = "checked='checked'"; break;
				case 1: $automd5_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="automd5" id="automd5" value="0" <?php echo $automd5_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="automd5" id="automd5" value="1" <?php echo $automd5_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="autosha1"><?php echo _FNCC_FDPAUTOSHA1?></label><br /><?php
			$autosha1_sel0 = "";
			$autosha1_sel1 = "";
			switch($settings['autosha1']) {
				case 0: $autosha1_sel0 = "checked='checked'"; break;
				case 1: $autosha1_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="autosha1" id="autosha1" value="0" <?php echo $autosha1_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="autosha1" id="autosha1" value="1" <?php echo $autosha1_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="showuploader"><?php echo _FNCC_FDPSHOWUPLOADER?></label><br /><?php
			$showuploader_sel0 = "";
			$showuploader_sel1 = "";
			switch($settings['showuploader']) {
				case 0: $showuploader_sel0 = "checked='checked'"; break;
				case 1: $showuploader_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="showuploader" id="showuploader" value="0" <?php echo $showuploader_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="showuploader" id="showuploader" value="1" <?php echo $showuploader_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="extsig"><?php echo _FNCC_FDPGPG?></label><br />
			<input type="text" name="extsig" id="extsig" style="width:100%;" maxlength="4" value="<?php echo $settings['extsig']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="extscreenshot"><?php echo _FNCC_FDPSCREENSHOTS?></label><br />
			<input type="text" name="extscreenshot" id="extscreenshot" style="width:100%;" maxlength="4" value="<?php echo $settings['extscreenshot']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="admins"><?php echo _FNCC_FDPADMINS?></label><br />
			<input type="text" name="admins" id="admins" style="width:100%;" maxlength="200" value="<?php echo $settings['admins']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="enable_admin_options"><?php echo _FNCC_FDPADMINOPTOK?></label><br /><?php
			$enable_admin_options_sel0 = "";
			$enable_admin_options_sel1 = "";
			switch($settings['enable_admin_options']) {
				case 0: $enable_admin_options_sel0 = "checked='checked'"; break;
				case 1: $enable_admin_options_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="enable_admin_options" id="enable_admin_options" value="0" <?php echo $enable_admin_options_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="enable_admin_options" id="enable_admin_options" value="1" <?php echo $enable_admin_options_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="showdownloadlink"><?php echo _FNCC_FDPSHOWLINK?></label><br /><?php
			$showdownloadlink_sel0 = "";
			$showdownloadlink_sel1 = "";
			switch($settings['showdownloadlink']) {
				case 0: $showdownloadlink_sel0 = "checked='checked'"; break;
				case 1: $showdownloadlink_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="showdownloadlink" id="showdownloadlink" value="0" <?php echo $showdownloadlink_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="showdownloadlink" id="showdownloadlink" value="1" <?php echo $showdownloadlink_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="overview_show_files"><?php echo _FNCC_FDPFILELIST?></label><br /><?php
			$overview_show_files_sel0 = "";
			$overview_show_files_sel1 = "";
			switch($settings['overview_show_files']) {
				case 0: $overview_show_files_sel0 = "checked='checked'"; break;
				case 1: $overview_show_files_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="overview_show_files" id="overview_show_files" value="0" <?php echo $overview_show_files_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="overview_show_files" id="overview_show_files" value="1" <?php echo $overview_show_files_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="section_show_header"><?php echo _FNCC_FDPSUMMARY?></label><br /><?php
			$section_show_header_sel0 = "";
			$section_show_header_sel1 = "";
			switch($settings['section_show_header']) {
				case 0: $section_show_header_sel0 = "checked='checked'"; break;
				case 1: $section_show_header_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="section_show_header" id="section_show_header" value="0" <?php echo $section_show_header_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="section_show_header" id="section_show_header" value="1" <?php echo $section_show_header_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="defaultvoteon"><?php echo _FNCC_FDPVOTE?></label><br /><?php
			$defaultvoteon_sel0 = "";
			$defaultvoteon_sel1 = "";
			switch($settings['defaultvoteon']) {
				case 0: $defaultvoteon_sel0 = "checked='checked'"; break;
				case 1: $defaultvoteon_sel1 = "checked='checked'"; break;
			}
			?><input type="radio" name="defaultvoteon" id="defaultvoteon" value="0" <?php echo $defaultvoteon_sel0?> /><?php echo _FNCC_NO?>
			<input type="radio" name="defaultvoteon" id="defaultvoteon" value="1" <?php echo $defaultvoteon_sel1?> /><?php echo _FNCC_YES?>
		</div>
		<div style="margin-bottom:1em;">
			<label for="usermaxFileSize"><?php echo _FNCC_FDPMAXUSERSIZE?></label><br />
			<input type="text" name="usermaxFileSize" id="usermaxFileSize" style="width:100%;" maxlength="500" value="<?php echo $settings['usermaxFileSize']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="userfilelimit"><?php echo _FNCC_FDPNUMWAITING?></label><br />
			<input type="text" name="userfilelimit" id="userfilelimit" style="width:100%;" maxlength="500" value="<?php echo $settings['userfilelimit']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="userwaitingfile"><?php echo _FNCC_FDPFILEWAITING?></label><br />
			<input type="text" name="userwaitingfile" id="userwaitingfile" style="width:100%;" maxlength="500" value="<?php echo $settings['userwaitingfile']?>" readonly="readonly" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="userblacklist"><?php echo _FNCC_FDPBLACKLIST?></label><br />
			<input type="text" name="userblacklist" id="userblacklist" style="width:100%;" maxlength="500" value="<?php echo $settings['userblacklist']?>" />
		</div>
		<div style="margin-bottom:1em;">
			<label for="minlevel"><?php echo _FNCC_FDPMINLEVEL?></label><br />
			<select name="minlevel" id="minlevel"><?php
				for($i=0;$i<=10;$i++) {
					$selected = ($settings['minlevel']==$i) ? ("selected='selected'") : ("");
					echo "<option value='$i' $selected>$i</option>";
				}
			?></select>
		</div>
		<input type="hidden" name="conf_mod" value="moddownconf" />
		<input type="hidden" name="conf_file" value="<?php echo $file?>" />
		<?php
		// check writing permissions
		if(is_writeable($file))	{
			?><div align="center" style="font-weight:bold;">
				<?php echo _FNCC_WARNINGDOC?><br /><br /><input type="submit" value="<?php echo _MODIFICA?>" />
			</div><?php
		} else {
			?><div align="center" style="font-weight:bold;font-color:red;">
				<?php echo _FNCC_WARNINGRIGHTS?>
			</div><?php
		}
		?></form><?php
	} else echo "<div align='center' style='font-weight:bold;font-color:red;'>"._FNCC_WARNINGNOFILE."</div>";
}

/*
 * List all members of the site, with the possibility
 * to list them in order by name, by level or by time
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_userslist() {
	// security conversions
	$mod   = getparam("mod", PAR_GET, SAN_FLAT);
	$order = getparam("order", PAR_GET, SAN_FLAT);
	// variables
	global $fuso_orario;
	$time_fresh = 192;	// number of hours in a week
	// load members in an array
	$users = list_users();
	$members = array();
	for($i=0;$i<count($users);$i++) {
		array_push($members, array(
			"name"  => $users[$i],
			"level" => getlevel($users[$i],"home"),
			"time"  => filemtime(get_fn_dir("users")."/".$users[$i].".php")+(3600*$fuso_orario))
		);
	}	//echo "<pre>";print_r($members);echo "</pre>";	//-> TEST
	// sort the array as chosen
	if(count($members)>0) {
		switch($order) {
			case "name_a":
				sort ($members); // ascending by name
			break;
			case "name_d":
				rsort ($members); // descending name
			break;
			case "level_a":
				usort($members, create_function('$a, $b', "return strnatcasecmp(\$a['level'], \$b['level']);")); // ascending by level
			break;
			case "level_d":
				usort($members, create_function('$a, $b', "return strnatcasecmp(\$a['level'], \$b['level']);")); // descending by level
				$members = array_reverse($members, FALSE);
			break;
			case "time_a":
				usort($members, create_function('$a, $b', "return strnatcasecmp(\$a['time'], \$b['time']);")); // ascending by time
			break;
			case "time_d":
				usort($members, create_function('$a, $b', "return strnatcasecmp(\$a['time'], \$b['time']);")); // descending by time
				$members = array_reverse($members, FALSE);
			break;
			default: sort ($members);
		}
	}
	// print links to order the list
	$ord_name_a  = build_fnajax_link($mod, "&amp;op=fnccmembers&amp;order=name_a", "fn_adminpanel", "get");
	$ord_name_d  = build_fnajax_link($mod, "&amp;op=fnccmembers&amp;order=name_d", "fn_adminpanel", "get");
	$ord_level_a = build_fnajax_link($mod, "&amp;op=fnccmembers&amp;order=level_a", "fn_adminpanel", "get");
	$ord_level_d = build_fnajax_link($mod, "&amp;op=fnccmembers&amp;order=level_d", "fn_adminpanel", "get");
	$ord_time_a  = build_fnajax_link($mod, "&amp;op=fnccmembers&amp;order=time_a", "fn_adminpanel", "get");
	$ord_time_d  = build_fnajax_link($mod, "&amp;op=fnccmembers&amp;order=time_d", "fn_adminpanel", "get");
	// print the list of the members
	$style_h  = " style=\"border:1px solid;border-collapse:collapse;font-weight:bold;text-align:center;\"";
	$style_c  = " style=\"border-bottom:1px solid;padding-left:1.5em;";
	$style_cm = " style=\"border-bottom:1px solid;text-align:center;";
	echo "<table cellspacing='0' cellpadding='0' style='width:70%'><tbody>";
	echo "<tr>";
		echo "<td ".$style_h.">Id</td>";
		echo "<td ".$style_h.">$ord_name_a&#8595;</a> "._NOMEUTENTE." $ord_name_d&#8593;</a></td>";
		echo "<td ".$style_h.">$ord_level_a&#8595;</a> "._LEVEL." $ord_level_d&#8593;</a></td>";
		echo "<td ".$style_h.">$ord_time_a&#8595;</a> "._FNCC_CHANGEDATE." $ord_time_d&#8593;</a></td>";
	echo "</tr>";
	for($i=0; $i<count($members); $i++) {
		$style_r = ($members[$i]['level']==10) ? ("style=\"font-weight:bold;\"") : ("");
		echo "<tr ".$style_r.">";
		echo "<td ".$style_cm."\">".($i+1)."</td>";
		$member = str_replace(".php", "", $members[$i]['name']);
		echo "<td $style_c\"><a href=\"index.php?mod=none_Login&amp;action=viewprofile&amp;user=$member\" title=\""._VIEW_USERPROFILE." $member\">$member</a></td>";
		// print image 'new.gif' if userprofile has been modified within 1 week
		if(time()-$members[$i]['time']<$time_fresh*3600) {
			$img_fresh = "<img src='images/mime/new.gif' alt='new' />";
		} else {
			$img_fresh = "";
		}
		echo "<td  ".$style_cm."\">".$members[$i]['level']."</td>";
		echo "<td  ".$style_cm."\">".date(" d.m.Y, H:i:s", $members[$i]['time'])." $img_fresh</td>";
		echo "</tr>";
	}
	?></tbody></table><?php
}

/*
 * Add a new user profile
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20070716
 */
function fncc_newuserprofile() {
	global $reguser, $action;
	// security conversions
	$mod   = getparam("mod", PAR_GET, SAN_FLAT);
	// print fields to fill
	echo "<form action=\"index.php?mod=$mod\" method=\"post\">";
	$style1 = "font-style:bold; padding:0.2em;";
	$style2 = "padding:0.2em;";
	?><table width='70%' align='center' border='1' style="border-collapse:collapse">
	<tbody>
	<tr>
		<td style="<?php echo $style1?>"><label for="nome"><b><span>*</span> <?php echo _NOMEUTENTE?></b></label></td>
		<td style="<?php echo $style2?>"><input name="nome" type="text" id="nome"/></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="regpass"><b><span>*</span> <?php echo _PASSWORD?></b></label></td>
		<td style="<?php echo $style2?>"><input name="regpass" type="password" id="regpass" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="reregpass"><b><span>*</span> <?php echo _PASSWORD?></b></label></td>
		<td style="<?php echo $style2?>"><input name="reregpass" type="password" id="reregpass" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="anag"><?php echo _FNOME?></label></td>
		<td style="<?php echo $style2?>"><input name="anag" type="text" id="anag" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="email"><?php
			if ($reguser=="2" AND $action=="reguser") echo "<span>*</span>&nbsp;<b>";
			echo _FEMAIL;
			if ($reguser=="2" AND $action=="reguser") echo "</b>";
		?></label></td>
		<td style="<?php echo $style2?>"><input name="email" type="text" id="email" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="hiddenmail"><?php echo _HIDDENMAIL?></label></td>
		<td style="<?php echo $style2?>"><input name="hiddenmail" type="checkbox" id="hiddenmail" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="homep"><?php echo _FHOME?></label></td>
		<td style="<?php echo $style2?>"><input name="homep" type="text" id="homep" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="prof"><?php echo _FPROFES?></label></td>
		<td style="<?php echo $style2?>"><input name="prof" type="text" id="prof" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="prov"><?php echo _FPROV?></label></td>
		<td style="<?php echo $style2?>"><input name="prov" type="text" id="prov" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="jabber">Jabber / Google Talk</label></td>
		<td style="<?php echo $style2?>"><input name="jabber" type="text" id="jabber" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="skype">Skype</label></td>
		<td style="<?php echo $style2?>"><input name="skype" type="text" id="skype" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="icq">ICQ</label></td>
		<td style="<?php echo $style2?>"><input name="icq" type="text" id="icq" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="msn">MSN</label></td>
		<td style="<?php echo $style2?>"><input name="msn" type="text" id="msn" /></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="ava"><?php echo _FAVAT?></label></td>
		<td style="<?php echo $style2?>">
			<img name="avatar" src="forum/images/blank.png" alt="avatar" border="0" style="max-width:120px;" id="avatar" />
			<br />
			<select name="ava" onchange='document.avatar.src="forum/images/"+this.options[this.selectedIndex].value'>
			<option value="blank.png">----</option><?php
			$modlist = array();
			$handle = opendir('forum/images');
			while ($file = readdir($handle)) {
				if (!( $file=="." or $file==".." )) {
					array_push($modlist, $file);
				}
			}
			closedir($handle);
			if(count($modlist)>0)
				sort($modlist);
			for ($i=0; $i < sizeof($modlist); $i++) {
				echo "<option value=\"$modlist[$i]\">$modlist[$i]</option>\n";
			}
			?></select><br /><br />
			<?php echo _FAVATREM?>:<br /><?php
			echo "<input type=\"text\" name=\"url_avatar\" />";
		?></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="firma"><?php echo _FFIRMA?></label></td>
		<td style="<?php echo $style2?>"><textarea name="firma" id="firma" rows="5" cols="23"></textarea></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="presentation"><?php echo _FNPRESENTATION?></label></td>
		<td style="<?php echo $style2?>"><textarea name="presentation" id="presentation" rows="5" cols="23"></textarea></td>
	</tr>
	<tr>
		<td style="<?php echo $style1?>"><label for="level"><?php echo _LEVEL?></label></td>
		<td style="<?php echo $style2?>">
			<select name="level" id="level"><?php
				for($i=0; $i<11; $i++){
					echo "<option value=\"$i\">$i</option>";
				}
			?></select>
		</td>
	</tr>
	</tbody>
	</table>
	<div align="center" style="margin:1em;"><input type="submit" value="<?php echo _FINVIA?>" /></div>
	<input type="hidden" name="conf_mod" value="saveprofile" />
	</form><?php
}

/*
 * Manage profiles waiting for activation
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_listwaiting() {
	// security conversions
	$mod   = getparam("mod",   PAR_GET, SAN_FLAT);
	$order = getparam("order", PAR_GET, SAN_FLAT);
	$user  = getparam("user",  PAR_GET, SAN_FLAT);
	// variables
	global $fuso_orario;
	$time_fresh = 192;	// number of hours in a week
	// load members in an array
	$waitinglist = array();
	$handle = opendir(get_waiting_users_dir());
	while($file = readdir($handle)) {
		if(preg_match("/^[0-9a-zA-Z]+\.php$/i", $file)) {
			$file = str_replace(".php", "", $file);
			array_push($waitinglist, $file);
		}
	}	//echo "<pre>";print_r($waitinglist);echo "</pre>";	//-> TEST
	closedir($handle);
	// check the number of profiles to activate
	if(count($waitinglist)==0) {
		echo _FNCC_NOUSERSTOACTIVATE;
		return;
	}
	$members = array();
	for($i=0;$i<count($waitinglist);$i++) {
		array_push($members, array("name" => $waitinglist[$i], "time" => filemtime(get_waiting_users_dir()."/".$waitinglist[$i].".php")+(3600*$fuso_orario)));
	}	//echo "<pre>";print_r($members);echo "</pre>";	//-> TEST
	// sort the array as chosen
	if(count($members)>0) {
		switch($order) {
			case "name_a":
				sort ($members); // ascending by name
			break;
			case "name_d":
				rsort ($members); // descending name
			break;
			case "time_a":
				usort($members, create_function('$a, $b', "return strnatcasecmp(\$a['time'], \$b['time']);")); // ascending by time
			break;
			case "time_d":
				usort($members, create_function('$a, $b', "return strnatcasecmp(\$a['time'], \$b['time']);")); // descending by time
				$members = array_reverse($members, FALSE);
			break;
			default: sort ($members);
		}
	}
	// print links to order the list
	$ord_name_a = build_fnajax_link($mod, "&amp;op=fnccwaitingusers&amp;order=name_a", "fn_adminpanel", "get");
	$ord_name_d = build_fnajax_link($mod, "&amp;op=fnccwaitingusers&amp;order=name_d", "fn_adminpanel", "get");
	$ord_time_a = build_fnajax_link($mod, "&amp;op=fnccwaitingusers&amp;order=time_a", "fn_adminpanel", "get");
	$ord_time_d = build_fnajax_link($mod, "&amp;op=fnccwaitingusers&amp;order=time_d", "fn_adminpanel", "get");
	// print the list of the profiles
	$style_h = " style=\"border:1px solid;border-collapse:collapse;font-weight:bold;text-align:center;\"";
	$style_c = " style=\"border-bottom:1px solid;padding-left:1.5em;";
	echo "<table cellspacing='0' cellpadding='0' style='width:100%'><tbody>";
	echo "<tr>";
		echo "<td ".$style_h.">Id</td>";
		echo "<td ".$style_h.">$ord_name_a&#8595;</a> "._NOMEUTENTE." $ord_name_d&#8593;</a></td>";
		echo "<td ".$style_h.">$ord_time_a&#8595;</a> "._FNCC_CHANGEDATE." $ord_time_d&#8593;</a></td>";
	echo "</tr>";
	for($i=0; $i<count($members); $i++) {
		echo "<tr>";
		echo "<td ".$style_c."text-align:right;padding-right:0.5em;\">".($i+1)."</td>";
		$member = str_replace(".php", "", $members[$i]['name']);
		$link = build_fnajax_link($mod, "&amp;op=fnccwaitingusers&amp;user=$member", "fn_adminpanel", "get");
		echo "<td $style_c\">".$link.$member."</a></td>";
		// print image 'new.gif' if userprofile has been registered within 1 week
		if(time()-$members[$i]['time']<$time_fresh*3600) {
			$img_fresh = "<img src='images/mime/new.gif' alt='new' />";
		} else {
			$img_fresh = "";
		}
		echo "<td  ".$style_c."\">".date(" d.m.Y, H:i:s", $members[$i]['time'])." $img_fresh</td>";
		echo "</tr>";
	}
	?></tbody></table><?php
	// print all the details of the profile chosen
	switch("$user") {
		case "":
			continue;
		break;
		default:
			$user_xml = array();
			$user_xml = load_user_profile($user, 1);	//echo "<pre>";print_r($user_xml);echo "</pre>"; //-> TEST
			$detstyle1 = "float:left;height:2em;width:25%;";
			$detstyle2 = "float:left;height:2em;width:60%;";
			?><p><center><b><?php echo $user?></b></center></p>
			<div id='user_profile' style='width:85%;padding: 1em 0 0.5em 15%;border:1px dashed;'>
			<div id='password' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _PASSWORD?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['password']?></div>
			</div>
			<div id='name' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FNOME?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['name']?></div>
			</div>
			<div id='mail' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FEMAIL?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['mail']?></div>
			</div>
			<div id='mail' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _HIDDENMAIL?></div>
				<div style='<?php echo $detstyle2?>'><input id="hiddenmail" type="checkbox" disabled='disabled' <?php if ($user_xml['hiddenmail']=="1") echo "checked='checked'";?> /></div>
			</div>
			<div id='homepage' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FHOME?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['homepage']?></div>
			</div>
			<div id='work' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FPROFES?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['work']?></div>
			</div>
			<div id='from' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FPROV?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['from']?></div>
			</div>
			<div id='avatar' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FAVAT?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['avatar']?></div>
			</div>
			<div id='sign' style='float:left;width:100%;'>
				<div style='<?php echo $detstyle1?>'><?php echo _FFIRMA?></div>
				<div style='<?php echo $detstyle2?>'>&nbsp;<?php echo $user_xml['sign']?></div>
			</div>
			<div id='level' style='float:left;width:100%;margin-bottom:1.5em;'>
				<div style='<?php echo $detstyle1?>border-bottom:1px solid;'><?php echo _LEVEL?></div>
				<div style='<?php echo $detstyle2?>border-bottom:1px solid;'>&nbsp;<?php echo $user_xml['level']?></div>
			</div>
			<div id='regmail' style='float:left;width:100%;'><?php
				echo build_fnajax_link($mod, "", "fn_adminpanel", "post", "form_updatewaiting");
				?><input type="hidden" name="conf_mod" value="updatewaiting" />
				<input type="hidden" name="user" value="<?php echo $user?>" />
				<div style='<?php echo $detstyle1?>'><?php echo _FNCC_REGMAIL?></div>
				<div style='float:left;height:2em;width:55%;margin-right:2px;'><input name='regmail' type='text' style='width:100%' value='<?php echo $user_xml['regmail']?>' /></div><?php
				$url_mod = "<button type='submit' title=\""._FNCC_REGMAILDES."\">";
				$url_mod .= "<img src='".get_fn_dir("sections")."/$mod/none_images/save.png' alt='save' border='0' />";
				$url_mod .= "</button>\n";
				?><div style='float:left'><?php echo $url_mod?></div>
				</form>
			</div>
			<div id='regcode' style='float:left;width:100%;'><?php
				echo build_fnajax_link($mod, "", "fn_adminpanel", "post", "form_sendactivation");
				?><input type="hidden" name="conf_mod" value="sendactivation" />
				<input type="hidden" name="mod" value="<?php echo $mod?>" />
				<input type="hidden" name="user" value="<?php echo $user?>" />
				<input type='hidden' name='regcode' value='<?php echo $user_xml['regcode']?>' />
				<input type="hidden" name="mail" value="<?php echo $user_xml['regmail']?>" />
				<div style='<?php echo $detstyle1?>'><?php echo _FNCC_REGCODE?></div>
				<div style='float:left;height:2em;width:55%;margin-right:2px;'><input type='text' style='width:100%' disabled='disabled' value='<?php echo $user_xml['regcode']?>' /></div><?php
				$url_reg = "<button type='submit' title=\""._FNCC_REGCODEDES."\">";
				$url_reg .= "<img src='forum/icons/mail.png' alt='mail' border='0' />";
				$url_reg .= "</button>\n";
				?><div style='float:left'><?php echo $url_reg?></div>
				</form>
			</div>
			<p style='margin-left:30%;font-weight:bold'><?php
			echo build_fnajax_link($mod, "&amp;op=fnccwaitingusers&amp;get_act=deletewaiting&amp;deluser=$user", "fn_adminpanel", "get");
			echo _ELIMINA."</a> | ";
			echo "<a href='index.php?mod=none_Login&amp;action=activateuser&amp;user=$user&amp;regcode=".$user_xml['regcode']."'>"._FNCC_ACTIVATE."</a>";
			?></p>
			</div><?php
		break;
	}
}

/*
 * Manage Flatnuke backups
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_managebackups() {
	// security checks
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	$log = getparam("log", PAR_GET, SAN_FLAT);
	// list all types of backup allowed
	$backup_array = array(
		"news"    =>array("value"=>get_fn_dir("news"),    			"desc"=>_FNCC_BACKUPNEWS),
		"users"   =>array("value"=>get_fn_dir("users"),   			"desc"=>_FNCC_BACKUPUSERS),
		"var"     =>array("value"=>get_fn_dir("var"),     			"desc"=>_FNCC_BACKUPMISC." /".get_fn_dir("var")),
		"sections"=>array("value"=>get_fn_dir("sections"),			"desc"=>_FNCC_BACKUPSECT),
		"forum"   =>array("value"=>get_fn_dir("var")."/flatforum",	"desc"=>_FNCC_BACKUPFORUM),
		"site"    =>array("value"=>"./",                  			"desc"=>_FNCC_BACKUPSITE),
    );	//echo "<pre>";print_r($backup_array);echo "</pre>"; //-> TEST
	// print html forms
	$icon = get_fn_dir("sections")."/$mod/none_images/floppy_unmount.png";
	foreach($backup_array as $tosave) {
		?><form action="index.php?mod=<?php echo $mod ?>" method="post">
		<div style="float:left;width:100%;border-bottom:solid 1px;">
			<div style="float:left;width:70%;padding:0.5em 0 0.5em 1em;">
			<img src="<?php echo $icon?>" alt="backup" style="vertical-align:middle;margin-right:1em;"><?php echo $tosave['desc']?>
			<input type="hidden" name="conf_mod" value="dobackup" />
			<input type="hidden" name="tosave" value="<?php echo $tosave['value']?>" />
			</div>
			<div style="float:left;padding:1em 0 1em 0;">
			<input type="submit" value="<?php echo _FNCC_SAVE?>" />
			</div>
		</div>
		</form><?php
	}
	// print cancel form
	echo build_fnajax_link($mod, "", "fn_adminpanel", "post", "form_cleanbackup");
	?><div style="float:left;width:100%;padding-bottom:2em;">
		<div style="float:left;width:70%;padding:0.5em 0 0.5em 1em;">
		<img src="<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/none_images/button_cancel.png" alt="canc" style="vertical-align:middle;padding-right:1em;">
		<?php echo _FNCC_DELBACKUP." (".count(fncc_listbackups()).")"?>
		</div>
		<div style="float:left;padding:1em 0 1em 0;">
		<input type="hidden" name="conf_mod" value="cleanbackup" />
		<input type="submit" value="<?php echo _ELIMINA?>" />
		</div>
	</div>
	</form><?php
}

/*
 * Manage Flatnuke logs
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_managelogs() {
	// security checks
	$mod = getparam("mod", PAR_GET, SAN_FLAT);
	$log = getparam("log", PAR_GET, SAN_FLAT);
	$rewrite     = "false";
	// load content of the log you choosed
	if(isset($log) AND file_exists(get_fn_dir("var")."/log/$log.php")) {
		$content = stripslashes(get_file(get_fn_dir("var")."/log/$log.php"));
		$content = preg_replace("/^\<\?php exit\(1\);\?\>/","",$content);  // rimozione riga intestazione
	}
	// list of the log files
	$logs_array = array();
	$logs_dir = opendir(get_fn_dir("var")."/log");
	while($logs_file=readdir($logs_dir)) {
		if($logs_file!="." AND $logs_file!=".." ) {
			array_push($logs_array, str_replace(".php","",$logs_file));
		}
	} //echo "<pre>";print_r($logs_array);echo "</pre>";	//-> TEST
	closedir($logs_dir);
	if(count($logs_array)==0) {
		echo "<b>"._NORESULT." !</b>";
		return;
	} else {
		sort($logs_array);
	}
	?><select style="max-width:100%" onchange="javascript:jQueryFNcall('<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/section.php?mod=<?php echo $mod?>&amp;op=fncclogs&amp;log='+this.options[this.selectedIndex].value,'get','fn_adminpanel');"><?php
		echo "\n<option value=\"\">---  "._FNCC_LOGLIST."  ---</option>\n";
		for($i=0;$i<count($logs_array);$i++) {
			echo "<option value=\"$logs_array[$i]\"";
			if($logs_array[$i]==$log) echo "selected=\"selected\"";
			echo ">$logs_array[$i]</option>\n";
		}
	?></select><?php
	// print html form (only if you chose a log)
	if(trim($log)=="") {
		return;
	}
	?><br /><br /><?php
	echo "<textarea name='log_content' readonly='readonly' wrap='off' rows='20' style='width:100%;'>";
	echo $content;
	echo "</textarea>\n<br /><br />";
	// delete button
	echo build_fnajax_link($mod, "", "fn_adminpanel", "post", "form_cleanlog");
	?><input type="hidden" name="conf_mod" value="cleanlog" />
	<input type="hidden" name="logfile" value="<?php echo get_fn_dir("var")?>/log/<?php echo $log?>.php" />
	<input type="submit" value="<?php echo _FNCC_CLEANLOG?>" />
	</form><?php
}

/*
 * Manage Flatnuke blacklists
 *
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @version 20130216
 */
function fncc_manageblacklists() {
	// security checks
	$mod  = getparam("mod",  PAR_GET, SAN_FLAT);
	$list = getparam("list", PAR_GET, SAN_FLAT);
	$rewrite     = "false";
	// load content of the log you choosed
	if(isset($list) AND file_exists("include/blacklists/$list.php")) {
		$rewrite = $list;
		$content = get_file("include/blacklists/$list.php");
	}
	// list of the blacklist files
	$lists_array = array();
	$lists_dir = opendir("include/blacklists");
	while($lists_file=readdir($lists_dir)) {
		if($lists_file!="." AND $lists_file!="..") {
			array_push($lists_array, str_replace(".php","",$lists_file));
		}
	} //echo "<pre>";print_r($lists_array);echo "</pre>";	//-> TEST
	closedir($lists_dir);
	if(count($lists_array)==0) {
		echo "<b>"._NORESULT." !</b>";
		return;
	} else {
		sort($lists_array);
	}
	?><select style="max-width:100%" onchange="javascript:jQueryFNcall('<?php echo get_fn_dir("sections")?>/<?php echo $mod?>/section.php?mod=<?php echo $mod?>&amp;op=fnccblacklists&amp;list='+this.options[this.selectedIndex].value,'get','fn_adminpanel');"><?php
		echo "\n<option value=\"\">---  "._FNCC_MANAGEBLACKLISTS."  ---</option>\n";
		for($i=0;$i<count($lists_array);$i++) {
			echo "<option value=\"$lists_array[$i]\"";
			if($lists_array[$i]==$list) echo "selected=\"selected\"";
			echo ">$lists_array[$i]</option>\n";
		}
	?></select><?php
	// print html form (only if you chose a blacklist)
	if(trim($list)=="") {
		return;
	}
	?><br /><br /><?php
	echo build_fnajax_link($mod, "", "fn_adminpanel", "post", "form_modblacklist");
	echo "<textarea name='conf_body' rows='20' style='width:100%;' wrap='off'>";
	echo $content;
	echo "</textarea>\n<br /><br />";
	// check writing permissions
	if(is_writeable("include/blacklists/$list.php"))	{
		?><div align="center" style="font-weight:bold;">
			<?php echo _FNCC_WARNINGDOC?><br /><br />
			<input type="hidden" name="conf_mod" value="modblacklist" />
			<input type="hidden" name="conf_file" value="include/blacklists/<?php echo $list?>.php" />
			<input type="submit" value="<?php echo _MODIFICA?>" />
		</div><?php
	} else {
		?><div align="center" style="font-weight:bold;font-color:red;">
			<?php echo _FNCC_WARNINGRIGHTS?>
		</div><?php
	}
	?></form><?php
}

?>
