<?php

/**
 * This widget will be loaded inside the Admin area in Flatnuke dashboard.
 * It displays accessing statistics of your site.
 *
 * @author Marco Segato <marco.segato@gmail.com>
 * @version 20130303
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 */

// intercept direct access to this file & rebuild the right path
if (strpos($_SERVER['PHP_SELF'],basename(__FILE__))) {
	//Time zone
	if (function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
		@date_default_timezone_set(date_default_timezone_get());

	chdir("../../../");
	include_once("config.php");
	include_once("functions.php");

 	if (!defined("_FN_MOD"))
		create_fn_constants();

	// set again cookies values of the language
	$userlang = getparam("userlang", PAR_COOKIE, SAN_FLAT);
	if ($userlang!="" AND is_alphanumeric($userlang) AND file_exists("languages/$userlang.php")) {
		$lang = $userlang;
	}
	include_once("languages/$lang.php");
	// set again cookies values of the theme
	$usertheme = getparam("usertheme", PAR_COOKIE, SAN_FLAT);
	if ($usertheme!="" AND !stristr("..",$usertheme) AND is_dir(get_fn_dir("themes")."/$usertheme")) {
		$theme = $usertheme;
	}
	// set again charset: using ajax, it could be rewritten by the web server
	@header("Content-Type: text/html; charset="._CHARSET."");
}

?>

<script language="javascript" type="text/javascript" src="include/javascripts/none_excanvas.min.js"></script>
<script language="javascript" type="text/javascript" src="include/javascripts/none_jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="include/css/none_jquery.jqplot.min.css" />

<style type="text/css">
#year_chart .jqplot-point-label {
  border: 1.5px solid #aaaaaa;
  padding: 1px 3px;
  background-color: #eeccdd;
}
</style>

<script type='text/javascript'>//<![CDATA[

$(document).ready(function(){

	var s1 = [<?php echo get_monthly_stats(get_selected_year()); ?>];  // ex: [200, 600, 700, 1000]

    // Can specify a custom tick Array.
    // Ticks should match up one for each y value (category) in the series.
	var ticks = [<?php echo get_month_list(); ?>];  // ex: ['May', 'June', 'July', 'August']

	var plot1 = $.jqplot('year_chart', [s1], {
        title: '<?php echo get_selected_year(); ?>',
		// The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {fillToZero: true},
			pointLabels: {show: true}
        },
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: ticks
            },
			yaxis: {
                //pad: 0.6 //,
                //tickOptions: {formatString: '$%d'}
            }
        }
    });
});

//]]>
</script>

<div id="year_chart" style="height: 250px;margin-top:5px;"></div>

<?php
$widget_footer = "<center>".get_years_links()."</center>";


/**
 * Returns the year for which to display statistics
 *
 * @author Marco Segato <marco.segato@gmail.com>
 * @version 20130303
 *
 * @return number Year to display
 */
function get_selected_year() {
	// default value is the curent year, but the user can request to display
	// specific data for another year with a GET action
	$year = getparam("year",PAR_GET,SAN_FLAT);
	// checks user's choice
	if($year=="")
		$year_sel = date("Y");
	else
		$year_sel = $year;
	// returns the value
	return $year_sel;
}

/**
 * Extracts statistic data for the chosen year.
 *
 * @author Marco Segato <marco.segato@gmail.com>
 * @version 20130303
 *
 * @param $year Number of the year to work with
 * @return string The list of values for each month, separated with comma
 */
function get_monthly_stats($year) {
	// define variables
	$year  = getparam($year,PAR_NULL,SAN_FLAT);
	$stats = "";
	// check existance and open the file with statistics
	if (file_exists(get_fn_dir("var")."/flatstat/$year/generale.php")){
		$fd = file (get_fn_dir("var")."/flatstat/$year/generale.php");
		// get monthly stats for the selected year
		for ($i=0 ; $i<count($fd); $i++){
			if(trim($fd[$i])!='') {
				$tmp=explode("|",$fd[$i]);
				// build the string [data1,data2,data3,...] with data
				$stats .= trim($tmp[1]).",";
			}
		}
		// return the string without the last comma
		$stats = preg_replace("/,$/","",$stats);
	} else $stats = "0,0,0,0,0,0,0,0,0,0,0,0";
	// return the string
	return $stats;
}

/**
 * Creates links to show all the years with statistic data
 *
 * @author Marco Segato <marco.segato@gmail.com>
 * @version 20130303
 *
 * @return string The links to all the years
 */
function get_years_links() {
	// define variables
	$years       = array();
	$years_links = "";
	// find all the years with statistic data and build an array
	$handle = opendir(get_fn_dir("var")."/flatstat");
	while ($file = readdir($handle)) {
	  if (!( $file=="." or $file==".." ) and (!preg_match("/^\./",$file)and ($file!="CVS"))) {
		if (is_dir(get_fn_dir("var")."/flatstat/$file"))
		array_push($years, $file);
		}
	}
	closedir($handle);
	// sort years in natural order
	sort($years);
	// build links with AJAX
	for ($j=1 ;$j<count($years); $j++){
		if (file_exists(get_fn_dir("var")."/flatstat/".($years[$j])."/generale.php")){
			$years_links .= "&nbsp;<a href=\"javascript:jQueryFNcall('"."sections/"._FN_MOD."/none_widgets/".(basename(__FILE__))."?mod="._FN_MOD."&amp;year=$years[$j]','GET','year_chart');\">$years[$j]</a>";
		}
	}
	// return the string
	return $years_links;
}

/**
 * Returns the labels of months to use with graph
 *
 * @author Marco Segato <marco.segato@gmail.com>
 * @version 20130303
 *
 * @return string Labels of months
 */
function get_month_list() {
	// define variables
	global $mesi;
	$months_string = "";
	// import month's names from Flatnuke translations (only the first 3 chars)
	foreach ($mesi as $little_month) {
		$months_string .= "\"".substr($little_month,0,3)."\",";
	}
	// return the string without the last comma
	$months_string = preg_replace("/,$/","",$months_string);
	return $months_string;
}

?>
