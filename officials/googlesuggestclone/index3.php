<?php
    require_once('GoogleSuggestCloneJax.class.php');
 
    $ajax = new GoogleSuggestCloneJax("query","results");
    $ajax->handleRequest();
    $ajax2 = new GoogleSuggestCloneJax("query2","results2");
    $ajax2->handleRequest();
 
    $q = isset($_GET['q']) ? $_GET['q'] : '';
?>
<html>
    <head>
        <title>phpRiot Tutorial: GoogleSuggestClone</title>
        <?= $ajax->loadJsCore(true) ?>
	<?= $ajax2->loadJsCore(true) ?>
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
        echo "<input type=text name='q' id='fq' value='$q'><div id='search-results'></div><br>";
	?>
	 <?= $ajax->attachWidgets(array('query' => 'fq', 'results' => 'search-results')) ?>
	 <?= $ajax->loadJsApp(true); ?>
	<?php
        echo "<input type=text name='q2' id='fq2' value='$q2'><div id='search-results2'></div><br>";
	?>
         <?= $ajax2->attachWidgets(array('query2' => 'fq2', 'results2' => 'search-results2')) ?>
         <?= $ajax2->loadJsApp(true); ?>
	</form>
    </body>
</html>
