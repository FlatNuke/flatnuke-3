<?php

if (preg_match("/structure.php/i",$_SERVER['PHP_SELF'])){
    die();
}

$title1 = "Flatnuke home page";
$title2 = "This is a subtitle";
$title3 = "";

// opzione di sicurezza!
$req = getparam("REQUEST_URI", PAR_SERVER, SAN_FLAT);
if(strstr($req,"myforum="))
	die(_NONPUOI);

?>

<body>
<!-- COLONNA DI SINISTRA -->
<div id="leftsidebar">
	<div id="header_menu"></div>
	<div id="topmenu">
		<div id="little_box"><?php echo $title3?></div>
	</div>

	<div class="rightnews">
		<?php create_blocks("dx"); ?>
	</div>
	<div id="menu">
		<?php my_create_menu(); ?>
		<div class="leftnews">
			<?php create_blocks("sx"); ?>
		</div>
	</div>

	<!-- COLONNA CENTRALE -->
	<div id="content">
		<div class="blogtitle"><a href="index.php"><?php echo $title1?></a></div>
		<div class="blogsubtitle"><?php echo $title2?></div>
		<?php getflopt(); ?>
	</div>

	<div id="footer">original template by <a href="http://www.studio7designs.com">Aran Down</a>.<br />
		<?php CreateFooterSite(); ?>
	</div>
</div>

</body>
</html>