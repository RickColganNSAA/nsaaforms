<?php
//export: Crew Chief Name, Address, Phone #'s, E-mail, Crew Members

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   //check if AD and if logged in
   mysql_select_db($db_name,$db);
   $sql="SELECT t1.id FROM $db_name.logins AS t1,$db_name.sessions AS t2 WHERE t2.session_id='$session' AND t2.login_id=t1.id";
   $result=mysql_query($sql); 
   if(mysql_num_rows($result)==0)
   {
      //header("Location:index.php?error=1");
      exit();
   }
   mysql_select_db($db_name2,$db);
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

//get crew members from fbapply and other info from officials
$sql="SELECT t1.* FROM fbapply as t1, officials as t2 WHERE t1.chief>0 and t2.id!='3427' and t1.chief=t2.id ORDER BY t2.last,t2.first";
$result=mysql_query($sql);
$csv="";
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT first,middle,last,address,city,state,zip,homeph,workph,cellph,email FROM officials WHERE id='$row[chief]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[homeph]!="")
      $homeph="(".substr($row2[homeph],0,3).")".substr($row2[homeph],3,3)."-".substr($row2[homeph],6,4);
   else
      $homeph="";
   if($row2[workph]!="")
      $workph="(".substr($row2[workph],0,3).")".substr($row2[workph],3,3)."-".substr($row2[workph],6,4);
   else
      $workph="";
   if($row2[cellph]!="")
      $cellph="(".substr($row2[cellph],0,3).")".substr($row2[cellph],3,3)."-".substr($row2[cellph],6,4);
   else
      $cellph="";

   $csv.="\"$row2[first] $row2[middle] $row2[last]\",\"$row2[address]\",\"$row2[city]\",\"$row2[state]\",\"$row2[zip]\",\"$homeph\", \"$workph\", \"$cellph\",\"$row2[email]\",";
   $csv.="\"".GetOffName($row[referee])."\", \"".GetOffName($row[umpire])."\", \"".GetOffName($row[linesman])."\", \"".GetOffName($row[linejudge])."\", \"".GetOffName($row[backjudge])."\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/fbcrewexport.csv"),"w");
$headers="\"Crew Chief\",\"Address\",\"City\",\"State\",\"Zip\",\"Home PH\",\"Work PH\",\"Cell PH\",\"E-mail\",\"Referee\",\"Umpire\",\"Linesman\",\"Linejudge\",\"Backjudge\"\r\n";
if(!fwrite($open,$headers.$csv)) echo "Could not write";
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/fbcrewexport.csv");
echo "<a target=new href=\"reports.php?session=$session&filename=fbcrewexport.csv\">Click to Download Football Crew Export (Excel File)</a><br>";

echo "<br><table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2>";
echo "<caption align=center><b>Football Crew Information:</b></caption>";
echo "<tr align=center><td><b>Crew Chief</b></td><td><b>Address</b></td><td><b>City</b></td><td><b>ST</b></td><td><b>Zip</b></td><td><b>Phone (H/W/C)</b></td><td><b>E-mail</b></td><td><b>Crew Members</b></td></tr>";
$output=ereg_replace("\",\"","</td><td>",$csv);
$output=ereg_replace("\", \"","<br>",$output);
$output=ereg_replace("\"\r\n","</td></tr>",$output);
$output=ereg_replace("\"","<tr valign=top align=left><td>",$output);
echo $output;
echo "</table>";

echo $end_html;

?>
