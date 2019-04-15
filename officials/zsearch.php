<?php
$files = glob($_SERVER['DOCUMENT_ROOT'].'/nsaaforms/officials/*.php');
//print_r($files);
foreach($files as $file)
{
	
	if( strpos(file_get_contents($file),'criticalit') !== false) {
		
		echo $file; echo '<br />';
	}
}
?>
