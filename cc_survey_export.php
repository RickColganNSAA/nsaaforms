<?php
//NSAA Admin View for Class D CC Survey Export (cc/cc_survey.php)

require 'functions.php';
require 'variables.php';
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);
if($_GET['type']=='fullteam'){
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=bothteam.csv');
	$output = fopen('php://output', 'w');
    fputcsv($output, array('School Name','How Many Girls','How Many Boys'));
    $rows = mysql_query("SELECT school,how_many_boys,how_many_girls FROM cc_classd WHERE full_b='y' and  full_g='y'  ORDER BY school");
		
	$events=array();
    $events=array('School Name','How Many Girls','How Many Boys');
    while ($row = mysql_fetch_assoc($rows)) 
	  {	fputcsv($output, $row); }
	
	exit;
	
}

if($_GET['type']=='numberofgirls'){
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=boysteam.csv');
	$output = fopen('php://output', 'w');
    fputcsv($output, array('School Name','How Many Girls','How Many Boys'));
    $rows = mysql_query("SELECT school,how_many_girls,how_many_boys FROM cc_classd WHERE full_b='y' and  full_g='n'  ORDER BY school");
	while ($row = mysql_fetch_assoc($rows)) 
	  {	fputcsv($output, $row); }
	exit;
	
}
if($_GET['type']=='numberofboys'){
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=girlsteam.csv');
	$output = fopen('php://output', 'w');
    fputcsv($output, array('School Name','How Many Boys','How Many Girls'));
    $rows = mysql_query("SELECT school,how_many_boys,how_many_girls FROM cc_classd WHERE full_b='n' and  full_g='y'  ORDER BY school");
	 while ($row = mysql_fetch_assoc($rows)) 
	  {	fputcsv($output, $row); }
	
	exit;
	
}

if($_GET['type']=='noone'){
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=noboysnogirls.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('School Name','How Many Boys','How Many Girls'));
    $rows = mysql_query("SELECT school,how_many_boys,how_many_girls FROM cc_classd WHERE full_b='n' and  full_g='n'  ORDER BY school");
	 while ($row = mysql_fetch_assoc($rows)) 
	  {	fputcsv($output, $row); }
	
	exit;
	
}

if($_GET['type']=='notcomplitedboys'){
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=notcomplitedboys.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('School Name','How Many Boys','How Many Girls'));
    $sql="SELECT * FROM ccbschool WHERE class='D' ORDER BY school";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
	   $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
	   $result2=mysql_query($sql2);
	   $row2=mysql_fetch_array($result2);
	   $school=addslashes($row2[school]);
	   $sql3="SELECT * FROM cc_classd WHERE school='$school'";
	   $result3=mysql_query($sql3);
	   $row3=mysql_fetch_array($result3);
	   //if(mysql_num_rows($result3)>0 && ($row3[reg_b]=='' || $row3[reg_g]=='' || $row3[full_b]=='' || $row3[full_g]==''))	//PARTIAL
	   if(mysql_num_rows($result3)>0 && ( $row3[full_b]=='' || $row3[full_g]==''))	//PARTIAL
		  ;
	   //else if(mysql_num_rows($result3)==0 || ($row3[reg_b]=='' && $row3[reg_g]=='' && $row3[full_b]=='' && $row3[full_g]==''))
	   else if(mysql_num_rows($result3)==0 || ( $row3[full_b]=='' && $row3[full_g]=='')){
		   $events=array($row2[school]);
			fputcsv($output, $events);
	   }
     
	}
	 
	
	exit;
	
}if($_GET['type']=='notcomplitedgirls'){
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=notcomplitedgirls.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('School Name','How Many Boys','How Many Girls'));
	
	
    $sql="SELECT * FROM ccgschool WHERE class='D' ORDER BY school";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
	   $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
	   $result2=mysql_query($sql2);
	   $row2=mysql_fetch_array($result2);
	   $school=addslashes($row2[school]);
	   $sql3="SELECT * FROM cc_classd WHERE school='$school'";
	   $result3=mysql_query($sql3);
	   $row3=mysql_fetch_array($result3);
	   //if(mysql_num_rows($result3)>0 && ($row3[reg_b]=='' || $row3[reg_g]=='' || $row3[full_b]=='' || $row3[full_g]==''))   //PARTIAL
	   if(mysql_num_rows($result3)>0 && ( $row3[full_b]=='' || $row3[full_g]==''))   //PARTIAL
		  ;
	   //else if(mysql_num_rows($result3)==0 || ($row3[reg_b]=='' && $row3[reg_g]=='' && $row3[full_b]=='' && $row3[full_g]==''))
	   else if(mysql_num_rows($result3)==0 || ( $row3[full_b]=='' && $row3[full_g]=='')){
		   $events=array($row2[school]);
			fputcsv($output, $events);
	   }
	}
	 
	
	exit;
	
}