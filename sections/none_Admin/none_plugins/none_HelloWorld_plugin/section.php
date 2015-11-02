<?php

chdir("../../../..");
include_once ("functions.php");

if(!is_admin()) {
	$ip = getparam("REMOTE_ADDR", PAR_SERVER, SAN_NULL);
	echo "<center><h2>Reserved area: keep out!</h2></center>";
	return;
}

if (!defined("_FN_MOD")) create_fn_constants();

// security checks
$mod         = _FN_MOD;
$plugin      = getparam('plugin', PAR_GET, SAN_HTML);
$plugin_name = str_replace("none_","",$plugin);
$plugin_name = str_replace("_"," ",$plugin_name);
//var_dump($plugin);

?>
<span class="fncc_title"><?php echo $plugin_name ?></span>
<div style="padding: 1em 0.5em 2em 0.5em;">

<span>Questo plugin non fa nulla, mostra solo poche nozioni fondamentali per scrivere un plugin integrato con la dashboard per Flatnuke</span>

<ol>
	<li>il nome del plugin deve essere preceduto dal prefisso '<b>none_</b>'</li>
	<li>il plugin deve avere un file radice che si chiama secondo lo standard di Flatnuke: <b>section.php</b></li>
	<li>il plugin deve essere copiato nella cartella <b>sections/none_Admin/none_plugins</b></li>
	<li>per personalizzare il plugin &egrave; possibile mettere un'icona nella stessa cartella del plugin e rinominarla <b>modicon.png</b>, &egrave; consigliabile usare immagini 48x48</li>
	<li>questo &egrave; l'array <b>$_GET</b> come passato dal link del plugin via jQuey, generato automaticamente nella dashboard (se il plugin &egrave; stato caricato correttamente):
        <pre>Array<br />(<br />
        &nbsp;&nbsp;&nbsp;&nbsp;[mod]&nbsp;=>&nbsp;<?php echo $mod;?><br />
        &nbsp;&nbsp;&nbsp;&nbsp;[plugin]&nbsp;=>&nbsp;<?php echo $plugin;?><br />
        )</pre>vengono passati di default il modulo di riferimento, che &egrave; sempre <b>none_Admin</b>, ed il nome del plugin
    </li>
	<li>per poter usare le API di Flatnuke &egrave; necessario cambiare la directory di lavoro del file section.php,
	che quindi andr&agrave; inizializzata con la funzione chdir() in questo modo: <pre>chdir("../../../..");<br />include_once ("functions.php");</pre></li>
	<li>i puntatori dei link e dei form nel plugin devono essere fatti con la chiamata a <b>jQuery</b></li>
	<li>Esempio di puntatore di un form, quindi via POST: <pre>action="javascript:jQueryFNcall('sections/none_Admin/none_plugins/$plugin/section.php?mod=$mod&amp;plugin=$plugin','POST','dashboard','formname');"</pre></li>
	<li>Esempio di puntatore tramite un link con jQuery, quindi via GET: <pre> a href="javascript:jQueryFNcall('sections/none_Admin/none_plugins/$plugin/section.php?mod=$mod&amp;plugin=$plugin,'GET','fn_adminpanel');"</pre>
	NB: il target sar&agrave; sempre il div: <b>fn_adminpanel</b> o un div incluso in esso, specifico dell'interfaccia di admin del modulo</li>
	<li>per creare ed usare plugin descritti secondo questo metodo &egrave; necessario aver installato la dashboard</li>
</ol>

</div>