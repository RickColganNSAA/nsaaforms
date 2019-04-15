<?php
    require_once('GoogleSuggestCloneJax.class.php');
 
    $ajax = new GoogleSuggestCloneJax();
    $ajax->handleRequest();
 
    $q = isset($_GET['q']) ? $_GET['q'] : '';
?>
<html>
    <head>
        <title>phpRiot Tutorial: GoogleSuggestClone</title>
        <?= $ajax->loadJsCore(true) ?>
        <link rel="StyleSheet" type="text/css" href="googlesuggestclone.css" />
    </head>
    <body>
        <?php if (strlen($q0) > 0) { 
	echo "q0: $offid<br>";
        } 
	if(strlen($q1) > 0 ) {
	echo "q1: $offid<br>";
	}
        echo "<form method='get' id='f'>";
	/*
	for($i=0;$i<10;$i++)
	{
	    $curid="fq".$i; $curq="q".$i; $cursearchresults="search-results".$i;
            echo "<input type=text name=\"$curq\" id=\"$curid\" value=\"".$$curq."\">";
            echo "<div id=\"$cursearchresults\"></div><br>$curid, $cursearchresults<br>";
?>
<script type="text/javascript">
ajaxac_attachWidget('__query', '<?php echo $curid; ?>');
ajaxac_attachWidget('__results', '<?php echo $cursearchresults; ?>');
</script>
<?php
	}
	*/
/*
	 <?= $ajax->attachWidgets(array('query' => 'fq', 'results' => 'search-results')) ?>
	 <?= $ajax->loadJsApp(true); 
*/
?>
<input type=text name='q0' id='fq0' value='<?php echo $q0;?>'><div id='search-results0'></div><br>
<script type="text/javascript">
ajaxac_attachWidget('__query', 'fq0');
ajaxac_attachWidget('__results', 'search-results0');
</script>
<input type=text name='q1' id='fq1' value='<?php echo $q1; ?>'><div id='search-results1'></div><br>
<script type="text/javascript">
ajaxac_attachWidget('__query', 'fq1');
ajaxac_attachWidget('__results', 'search-results1');
</script>
<script type="text/javascript" src="/nsaaforms/officials/googlesuggestclone/index.php/jsapp"></script>
	</form>
    </body>
</html>
