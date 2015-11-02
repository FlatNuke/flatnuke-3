<?php 

// security options
if (preg_match("/structure.php/",$_SERVER['PHP_SELF'])) {
  die();
}
$req = $_SERVER["REQUEST_URI"];
if(strstr($req,"myforum="))
  die(_NONPUOI);

// starting web page
echo "<body>";

// check if you're in the forum sections
$mod = getparam("mod", PAR_GET, SAN_FLAT);
if(preg_match("/_Forum/", $mod)) {
	$into_forum = TRUE;
} else {
	$into_forum = FALSE;
}

?>
<!-- THEME STRUCTURE START -->
<div id="tema" >
	<!-- TOP -->
	<div id="topmenu">
		<div id="topleft"><img alt="flat nuke" src="<?php echo "themes/$theme/$logo"?>" border="0">
			<!-- MENU -->
			<div class="menu">
			<?php create_menu_horiz(); ?>
			</div>
		</div>
		<div id="topright"></div>
	</div>
	<div id="bann"></div>
	<!-- BODY OF THE PAGE -->
	<div id="outer">
		<div id="inner">
		<!-- LEFT -->
		<div id="leftbody" >
			<?php 
			create_block_menu();
			create_blocks("sx");
			if($into_forum) {
				create_blocks("dx");
			}
			?>
		</div>
		<!-- CENTER --><?php 
			if($into_forum) {
				echo "<div id='centerbody_forum'>";
			} else {
				echo "<div id='centerbody'>";
			}
			getflopt();
        ?>
		</div>
		<!-- RIGHT --><?php 
		if(!$into_forum) {
			?><div id="rightbody"><?php 
			create_blocks("dx");
			?></div><?php 
			}
			?>
	<!-- CLEAR -->
	<div class="clr"></div>
	<div id="bann2">
	Freely inspired to <a href="http://www.mollio.org" title="Mollio" target="_blank">Mollio</a> template
	</div>
	<!-- FOOTER -->
	<div id="footer">
		<?php 
		CreateFooterSite();
		?>
	</div>
<!-- THEME STRUCTURE END -->
</div>

</div>
</div>
</body>
</html>
