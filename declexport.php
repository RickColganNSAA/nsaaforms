<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

$fallact=array("fb11","fb8","fb6","vb","go_g","te_b","cc_b","cc_g","sb","pp");
$fallactlong=array("Football 11-Man","Football 8-Man","Football 6-Man","Volleyball","Girls Golf","Boys Tennis","Boys Cross-Country","Girls Cross-Country","Softball","Play Production");

$sql="SELECT * FROM declaration ORDER BY school";
$result=mysql_query($sql);
$csv="School,";
$html="<table><tr><td>School</td>";
for($i=0;$i<count($fallact);$i++)
{
   $csv.=strtoupper($fallact[$i]).",";
   $html.="<td>".strtoupper($fallact[$i])." DECLARED</td>";
   if($fallact[$i]!='fb11' && $fallact[$i]!='fb8')
   {
      $html.="<td>".strtoupper(ereg_replace("[0-9]","",$fallact[$i]))." REGISTERED</td>";
   }
}
$html.="</tr>";
$csv=substr($csv,0,strlen($csv)-1);
$csv.="\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.=$row[school].",";
   $html.="<tr><td>$row[school]</td>";
   for($i=0;$i<count($fallact);$i++)
   {
      if($row[$fallact[$i]]=='y') { $csv.="x,"; $html.="<td>X</td>"; }
      else { $csv.=","; $html.="<td>&nbsp;</td>"; }
      if($fallact[$i]!='fb11' && $fallact[$i]!='fb8')
      {
	 $regsp=ereg_replace("[0-9]","",$fallact[$i]);

         if($fallact[$i]=='fb6' && $row[fb11]=='y') $row[fb6]='y';
	 else if($fallact[$i]=='fb6' && $row[fb8]=='y') $row[fb6]='y';

         if($row[$fallact[$i]]=='y' && !IsRegistered2011(GetSchoolID2($row[school]),$regsp))	//DECLARED BUT NOT REGISTERED
	    $html.="<td bgcolor='#ff0000'>NOT PAID</td>"; 
         else if($row[$fallact[$i]]=='y')	//DECLARED AND REGISTERED
	    $html.="<td>REGISTERED</td>";
         else if(IsRegistered2011(GetSchoolID2($row[school]))) 			//DID NOT DECLARE BUT REGISTERED
            $html.="<td bgcolor='#00ff00'>REGISTERED</td>"; 
	 else					//NEITHER
	    $html.="<td>&nbsp;</td>";
      }
   }
   $csv=substr($csv,0,strlen($csv)-1);
   $csv.="\r\n";
   $html.="</tr>";
}
$html.="</table>";
$filename1="DeclarationsExport_".date("mdy").".csv";
$open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename1),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename1);
$filename2="DeclarationsRegistrationsExport_".date("mdy").".xls";
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename2"),"w");
fwrite($open,$html);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename2");

if($registrations)
   header("Location:https://secure.nsaahome.org/nsaaforms/exports.php?session=$session&filename=$filename2");
else
   header("Location:https://secure.nsaahome.org/nsaaforms/exports.php?session=$session&filename=$filename1");
exit();
?>
