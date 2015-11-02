<?php 
include_once("flatnews/include/news_functions.php");

$tagssection = ""; // create empty variable for current section
if (file_exists(get_fn_dir("sections")."/".get_mod()."/tags_list.php")) // if there's tags_list of current... section
	$tagssection=get_mod(); // ... store current section
elseif (!file_exists(_FN_VAR_DIR."/news/tags_list.php")){ // else, search tags_list of none_News
	echo "Tag non presenti"; // localization needed
	return;
}

//CONFIGURAZIONE

//numero massimo di tag da mostrare oltre il quale vengono tagliati i tag meno utilizzati
//(0 = nessun limite ai tag da mostrare)
$taglimit = 0;

//Valori:
//size: i tag pi� utilizzati hanno dimensione maggiore (default)
//list: i tag sono mostrati in un elenco con a fianco il numero di utilizzi
$tags_show = "size";

//Valori:
//ab: mostra i tag in ordine alfabetico (default)
//12: mostra i tag dal pi� utilizzato a scendere
$sort_tags = "ab";

//FINE CONFIGURAZIONE
$tags = load_tags_list($tagssection); // if there's tags_list for current section, use this

// Questo codice � parzialmente tratto da Wordpress 2.2
// WP CODE
	$largest = 25;
	$smallest = 8;
	if (count($tags)>0)
		$min_count = min( $tags );
	else $min_count = 1;
	if (count($tags)>0)
		$spread = max( $tags ) - $min_count;
	else $spread = 1;
	if ( $spread <= 0 )
		$spread = 1;
	$font_spread = $largest - $smallest;
	if ( $font_spread < 0 )
		$font_spread = 1;
	//il +1 al denominatore � stato aggiunto per evitare di avere caratteri
	//troppo grandi
	$font_step = $font_spread / ($spread+1);
//FINE WP CODE
//se il numero di tag � superiore al limite impostato
if (count($tags)>$taglimit AND $taglimit!=0){
	//ordino l'array dal value pi� grande al pi� piccolo
	//(senza perdere l'associazione con la kay)
	arsort($tags);
	$tagcount=0;
	foreach ($tags as $tag => $count){
		//se raggiungo o supero il limite dei tag
		if ($tagcount>=$taglimit)
			break;
		//ricostruisco la struttura dell'array
		$newarray[$tag] = $count;
		$tagcount++;
	}
	//reimposto l'elenco dei tags
	$tags = $newarray;
	//elimino l'array temporaneo
	unset($newarray);
	//riordino sul nome dei tag
	ksort($tags);
}
//se � impostato l'ordinamento secondo il numero di utilizzi eseguo un asort
if ($sort_tags=="12")
	arsort($tags);

foreach ($tags as $tag => $count){
	$fontsize = $smallest + ( ( $count - $min_count ) * $font_step );

	if ($count>0){
		if ($tags_show=="list"){
			if (strlen($tag)>11) $tagshow=substr($tag,0,8)."...";
			else $tagshow = $tag;
			echo "&#187;&nbsp;<a href=\"index.php?mod=none_Search&amp;where=news&amp;tags=$tag\" title=\"$tag: $count  news\">$tagshow ($count)</a><br />";
		}
		else {
			if ($fontsize> 25) $fontsize=25;
			if (strlen($tag)>8 and $fontsize>20) $tagshow=substr($tag,0,6)."...";
			else if (strlen($tag)>10 and $fontsize>16) $tagshow=substr($tag,0,7)."...";
			else if (strlen($tag)>14 and $fontsize>14) $tagshow=substr($tag,0,11)."...";
			else if (strlen($tag)>18) $tagshow=substr($tag,0,15)."...";
			else $tagshow = $tag;
			echo "<span style=\"font-size: ".$fontsize."pt;\"><a href=\"index.php?mod=none_Search&amp;where=news&amp;tags=$tag\" title=\"$tag: $count  news\">$tagshow</a> </span>";
	
		}
	}
}

?>