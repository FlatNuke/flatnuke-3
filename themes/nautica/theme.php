<?php 

$bodycolor = "#f4f4f4";
$bgcolor1 = "#ffffff";
$bgcolor2 = "#74a8f5";
$bgcolor3 = "#edf3fe";
$logo = "logo.png";
$backimage = "";

$forumbody="#86c32a";
$forumborder="#86c32a";
$forumback="#ffffff";

define("_THEME_VER", 1);
define("_THEME_DOCTYPE", "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n");

function my_create_menu(){
	$modlist = list_sections("sections", "links");
	if($modlist == null)
		return;
	echo "<h2 class=\"hide\">Menu:</h2>\n
		<ul>\n
		<li><a href=\"index.php\">Home</a></li>\n";
	for ($item_num=0; $item_num < count($modlist); $item_num++) {
		echo "<li>".$modlist[$item_num]."</li>\n";
	}
	echo "</ul>\n";
}

function OpenTable() {
	echo "<div class=\"bluebox\">\n";
}

function OpenTableTitle($title) {
	echo "<br /><h3><span class=\"style7\">$title</span></h3><p class=\"style3\">\n";
}

function OpenBlock($img,$title) {
	?><span class="boxtitle"><?php echo $title?></span>
		<div class="divline"></div><?php 
}

function CloseBlock() {
	echo "<br />\n";
}

function CloseTableTitle(){
	echo "<br />\n";
}

function CloseTable() {
	echo "</div>\n";
}

// function to create footer site
function CreateFooterSite() {
	$footer_elements = get_footer_array();
	echo "<center>\n";
	echo $footer_elements['img_fn']." ";
	echo $footer_elements['img_w3c']." ";
	echo $footer_elements['img_css']." ";
	echo $footer_elements['img_rss']." ";
	echo $footer_elements['img_mail']."<br />";
	echo $footer_elements['legal']."<br />";
	echo $footer_elements['time'];
	echo "</center>\n";
}

?>
