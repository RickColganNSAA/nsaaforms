<?php
/*******************************************
logout.php
Log out for Judges
Created 04/10/18
Author: criticalitgroup
*******************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$sid= $_GET['school'];

$studs =explode("<result>",GetPlayers('jo',GetSchoolName($sid,'jo')));


$json = array();
        
		foreach($studs as $stud){
		$studd=explode("<detail>",$stud);
		$student['id'][]=$studd[0];
		$student['name'][]=$studd[1];
				}
	
 		$json = array(
			'student'                => $student,
		); 


		
         echo json_encode($json);


?>
