<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

//show summary of mail nums: for each sport, current mail num and list of names who have that mail num
echo $init_html;
echo GetHeaderJ($session);
echo "<center>";
echo "<a name=\"top\">&nbsp;</a>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2><caption><b>Summary of Judges' Mailings:</b></caption>";
//get file ready to write to:
$csv="";
$csv.="\"Speech\",\"Play Production\",\"Date Sent\",\"First\",\"Last\"\r\n";
$sql="SELECT * FROM judges WHERE datesent!='0000-00-00' ORDER BY datesent DESC,last,first";
$result=mysql_query($sql);
echo "<tr align=center><td><b>Speech</b></td><td><b>Play<br>Production</b></td><td><b>Date Sent</b></td><td><b>Name</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=center><td>".strtoupper($row[speech])."</td>";
   echo "<td>".strtoupper($row[play])."</td>";
   $date=split("-",$row[datesent]);
   echo "<td>$date[1]/$date[2]/$date[0]</td>";
   echo "<td align=left><a href=\"edit_judge.php?session=$session&header=no&offid=$row[id]\" class=small target=new>$row[last], $row[first]</a></td></tr>";
   $csv.="\"$row[speech]\",\"$row[play]\",\"$date[1]/$date[2]/$date[0]\",\"$row[first]\",\"$row[last]\"\r\n";
}
echo "</table>";
   //write to csv file
   $filename="mailingsummary.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<tr align=center><td><a class=small href=\"reports.php?session=$session&filename=$filename\" target=new>Export as a .CSV (Comma-Delimited) File</a></td></tr>";

echo "</table></form>";
echo "<a href=\"#top\" class=small>Return to Top</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"jwelcome.php?session=$session\" class=small>Home</a>";
echo $end_html;

?>
