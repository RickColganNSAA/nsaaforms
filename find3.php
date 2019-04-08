<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');
 
function getDirectoryTree( $outerDir ){
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
	
	 
    $dir_array = Array();
    foreach( $dirs as $d ){
        if( is_dir($outerDir."/".$d) ) $dir_array[ $d ] = getDirectoryTree( $outerDir."/".$d );
		
        else { 
		$path=$outerDir ."/".$d ;
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		if($ext=='php' && $d!='find3.php') {
			
			//if( strpos(citgf_file_get_contents($path),'root') !== false) {
			if( strpos(citgf_file_get_contents($path),'tcpdf_php4/tcpdf.php') !== false) {
						echo $path."<br />";
					}
		}	
			$dir_array[ $d ] = $d;
		}
    }
    return $dir_array;
}



$dirlist = getDirectoryTree('/var/www/html/iahsaa.org/secure','.php'); 


?>