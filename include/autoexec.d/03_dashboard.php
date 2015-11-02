<?php

/**
 * This module shows Admin area in a dashboard on Flatnuke.
 *
 * @author Alfredo Cosco <orazio.nelson@gmail.com>
 * @author Marco Segato <segatom@users.sourceforge.net>
 * @author Lorenzo Caporale <piercolone@gmail.com>
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 */

if (preg_match("/dashboard.php/i", $_SERVER['PHP_SELF'])) {
	Header("Location: ../../index.php");
	die();
}

// security checks
$mod = _FN_MOD;
global $sitename, $lang;

// access reserved to administrator
if($mod=="none_Admin" AND is_admin()) {
	// language definition
	switch($lang) {
		case "it":
			include_once ("languages/admin/$lang.php");
		break;
		default:
			include_once ("languages/admin/en.php");
	}
	// need some Flatnuke administration API
	include_once (get_fn_dir("sections")."/$mod/none_functions/func_interfaces.php");
	?>
	<body>

	<script type='text/javascript' src='include/javascripts/none_wz_tooltip.js'></script>

	<div class="top-dash">
		<!-- top anchor -->
		<a name="fncctoppage"></a>
		<!-- top-menu -->
		<div class="menu-topdash-left">
			<img src="images/dashboard/logo_gray.png" alt="logo" />&nbsp;<?php echo $sitename ?>
			<span>(<a href="index.php" title="<?php echo _FNCC_SHOWHOMEPAGE ?>"><?php echo _FNCC_SHOWHOMEPAGE ?></a>)</span>
		</div>
		<div class="menu-topdash-right">
			<?php echo _BENVENUTO ?>, <b><?php echo get_username()?></b>&nbsp;|
			<!--<a href="#" title="Help">Aiuto</a>&nbsp;| -->
			<a href="index.php?mod=none_Login&amp;action=logout&amp;from=home" title="<?php echo _LOGOUT ?>"><?php echo _LOGOUT ?></a>
		</div>
		<div style="clear:both;"></div>
		<!-- main menu with categories -->
		<div id="dashmenu">
			<?php fncc_main(); ?>
		</div>
		<!-- submenu with dynamic tabs -->
		<div class="dashtabs">
			<div id="dashtab"></div>
		</div>
	</div>
	<!-- dashboard body -->
	<div id="dashbody">
		<div id="fn_adminpanel">
			<?php include_once (get_fn_dir("sections")."/$mod/none_widgets/section.php"); ?>
			<?php getflopt(); ?>
		</div>
	</div>
	<!-- footer -->
	<div class="top-dash">
		<div class="menu-topdash-left"><?php
			$footer_elements = get_footer_array();
			echo $footer_elements['img_fn']." ";
			echo $footer_elements['img_w3c']." ";
			echo $footer_elements['img_css']." ";
			echo $footer_elements['img_rss']." ";
			echo $footer_elements['img_mail'];
		?></div>
		<div class="menu-topdash-right">
			Flatnuke dashboard by <a href="mailto:orazio.nelson@gmail.com" target="_blank">nelson@TT4FN</a>
		</div>
		<div style="clear:both;"></div>
	</div>

	</body>
	</html><?php
	exit();
}

?>
