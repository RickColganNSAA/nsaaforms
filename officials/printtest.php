<?php
require 'functions.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$sportname=GetSportName($sport);
$testtable=$sport."test";
$resultstable=$sport."test_results";

//check that test is past due
$duedate=GetDueDate($sport,"test");
$date=split("-",$duedate);
$fakedate=GetTestDueDate($sport,'fakeduedate');
$date=split("-",$fakedate);

echo $init_html;
echo "<table class=tiny width=95%><tr align=left><td><i>Select <b>File-->Print</b> to print this test.</i></td></tr><tr align=center><td>";
echo "<b>$date[0] NFHS $sportname Open Book Test Questions:<br>";
echo "This test is due <u>ONLINE</u> by $date[1]/$date[2]/$date[0].&nbsp;";
echo "(Do NOT mail this exam to our office.  You MUST submit it online.)";
echo "<br></b><i>Copyrighted and Published in $date[0] by the National Federation of State High School Associations</i><br>";
echo "<table cellspacing=0 cellpadding=2 class=tiny frame=all rules=all style=\"border:#808080 1px solid;width:700px;\">";
$ix=0;
for($i=1;$i<=10;$i++)
{
   $placestart=($i*10)-9;
   $placeend=$placestart+9;
   //echo "<tr align=left><td colspan=2><b>Questions $placestart - $placeend:</b></td></tr>"; 
   $sql2="SELECT * FROM $testtable WHERE place>='$placestart' AND place<='$placeend' ORDER BY place";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $place=$row2[place];
      $index="ques".$place;
      echo "<tr align=left valign=top>";
      echo "<td width='500px'>$row2[place].&nbsp;$row2[question]</td><td>";
      //GET MULTIPLE CHOICES
      $sql3="SELECT * FROM ".$testtable."_mchoices WHERE questionid='$row2[id]' ORDER BY orderby";
      $result3=mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
         echo "$row3[choicevalue].&nbsp;$row3[choicelabel]<br>";
      }
      echo "</td></tr>";
      $ix++;
   }
}

echo "</table>";
echo "</td></tr></table>";
echo $end_html;
?>
