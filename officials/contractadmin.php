<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session,"contractadmin");

echo "<br><br><table><caption style=\"background-color:#E0E0E0\"><b>NSAA Contracts & Assignments:</b></caption>";
echo "<tr align=left><td>";
echo "<br><form method=post action=\"hostcontracts.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<b>Host Assignments & District Information:&nbsp;</b><select onchange=\"submit();\" name=sport><option value=''>Choose Sport</option>";
$sql="SHOW TABLES LIKE '%districts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("districts",$row[0]);
   $curact=$temp[0];
   if($curact!='sp' && $curact!='pp')
   {
      echo "<option value=\"$curact\">".GetSportName($curact)."</option>";
      if($curact=='ccg')
         echo "<option value=\"fb\">".GetSportName("fb")."</option>";
   }
}
echo "</select><ul><li><a href=\"hostappsearch.php?session=$session\">Apps to Host Advanced Search</a></li></ul>";
echo "</form><br>";
echo "<form method=post action=\"contractadmin.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<b>Officials Contracts & Assignments:</b>&nbsp;";
echo "<select name=sportch1 onchange=\"submit();\"><option>Choose Sport</option>";
$sql="SHOW TABLES LIKE '%contracts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("contracts",$row[0]);
   $curact=$temp[0];
   if($curact!='sp' && $curact!='pp')
   {
      echo "<option value=\"$curact\"";
      if($sportch1==$curact) echo " selected";
      echo ">".GetSportName($curact)."</option>";
   }
}
echo "</select><br>";
if($sportch1 && $sportch1!="Choose Sport")
{
   $sportname=GetSportName($sportch1);
   echo "<table cellspacing=1 cellpadding=3>";
   echo "<tr align=left><td><a class=small href=\"assign".$sportch1.".php?session=$session\">Assign $sportname Officials</a></td></tr>";
   if($sportch1=='fb')
      echo "<tr align=left><td><a class=small href=\"".$sportch1."assignreport.php?session=$session\">$sportname Assignments Report</a>";
   else
   {
      echo "<tr align=left><td><p><b>$sportname Assignments Report:</b> <a class=small href=\"".$sportch1."assignreport.php?session=$session\">NON-State</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"".$sportch1."assignreport.php?session=$session&type=state\">STATE</a></p>";
      if($sportch1=='wr')
         echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"".$sportch1."assignreport.php?session=$session&type=statedual\">STATE DUAL</a>";
      if(preg_match("/bb/",$sportch1))
         echo "<p><a class=\"small\" href=\"bbassignreport.php?session=$session\"><b>Boys AND Girls Basketball Assignments Report</b></a></p>";
      if(preg_match("/so/",$sportch1))
         echo "<p><a class=\"small\" href=\"soassignreport.php?session=$session\"><b>Boys AND Girls Soccer Assignments Report</b></a></p>";
   }
   if($sportch1=='bbb' || $sportch1=='bbg' || $sportch1=='sb')
   {
      $sql="SHOW DATABASES LIKE 'nsaaofficials2%'";
      $result=mysql_query($sql);
      echo "<b>$sportname Assignments from Previous Seasons:</b>";
      $x=0;
      while($row=mysql_fetch_array($result))
      {
         if($x%7==0)
	    echo "<p style=\"padding-left:30px;\">";
	 $years=preg_replace("/[^0-9]/","",$row[0]);
	 $years=substr($years,0,4)."-".substr($years,4,4);
         echo "<a class=small href=\"".$sportch1."assignreport.php?database=$row[0]&session=$session\" target=\"_blank\">$years</a>&nbsp;&nbsp;&nbsp;";
 	 $x++;
	 if($x%7==0) echo "</p>";
      }
      if($x%7!=0) echo "</p>";
   }
   echo "</td></tr>";
   echo "<tr align=left><td><a class=small href=\"".$sportch1."contracts.php?session=$session\">$sportname Contract Responses</a></td></tr>";
   echo "<tr align=left><td><b>Sample Contract(s):</b>&nbsp;";
   if($sportch1=='fb')
   {
      echo "<a class=small target=\"_blank\" href=\"".$sportch1."contract.php?session=$session&sample=1\">Football PLAYOFFS Contract</a>&nbsp;&nbsp;";
      echo "<a class=small target=\"_blank\" href=\"".$sportch1."statecontract.php?session=$session&sample=1\">Football FINALS Contract</a>";
   }
   else
   {
      echo "<a class=small target=\"_blank\" href=\"".$sportch1."contract.php?session=$session&sample=1\">District Contract</a>&nbsp;&nbsp;";
      echo "<a class=small target=\"_blank\" href=\"".$sportch1."statecontract.php?session=$session&sample=1\">State Contract</a>";
      if($sportch1=="wr")
         echo "&nbsp;&nbsp;<a class=small target=\"_blank\" href=\"wrstatedualcontract.php?session=$session&sample=1\">State Dual Contract</a>";
   }
   echo "</td></tr>";
	//EVALUATION REPORTS:
   if($sportch1=='bbb' || $sportch1=='bbg') $obssport="bb";
   else if($sportch1=='sob' || $sportch1=='sog') $obssport="so";
   else $obssport=$sportch1;
   $sql="SHOW TABLES LIKE '".$obssport."observe'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=left><td><b>REPORT: <a class=small href=\"offevalreport.php?session=$session&sport=$obssport\" target=\"_blank\">Year(s) each official has been EVALUATED</a></td></tr>";
   }
	//ASSIGNMENT REPORTS:
   $assignsport=$sportch1;
   $sql="SHOW TABLES LIKE '".$assignsport."contracts'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=left><td><b>REPORT: <a class=small href=\"offassignreport.php?session=$session&sport=$assignsport\" target=\"_blank\">Year(s) each official has been ASSIGNED TO POSTSEASON</a></td></tr>";
   }

   if($sportch1=="fb")
   {
      echo "</form><form method=post action=\"fbbracket.php\" target=\"_blank\"><input type=hidden name=\"officials\" value=\"1\"><input type=hidden name=\"session\" value=\"$session\">";
      echo "<tr align=left><td><b>Playoff Brackets with Crew Chiefs listed:</b> <select name=\"database\"><option value=\"\">THIS YEAR</option>";
      $sql="SHOW DATABASES LIKE 'nsaascores2%'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $years=preg_replace("/nsaascores/","",$row[0]);
	 $year=substr($years,0,4);
         echo "<option value=\"$row[0]\">$year Season</option>";
      }
      echo "</select>&nbsp;<select name=\"class\">";
      $sql="SELECT DISTINCT class FROM fbbrackets WHERE class!='' ORDER BY class";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[class]\">Class $row[class]</option>";
      }
      echo "</select> <input type=submit name=\"go\" value=\"Go\">";
      echo "</td></tr>";
   }
   echo "</table>";
}
echo "</form>";
echo "<br><a href=\"tourndates.php?session=$session\">&larr; Edit Postseason Dates for Apps to Host, Apps to Officiate, Lodging</a><br>";

//SUPERVISED TEST CONTRACTS: GONE AS OF FALL 2011
//echo "<br><br><a href=\"suptestcontracts.php?session=$session\">Supervised Test Host Contracts</a>";
echo "</td></tr></table>";
echo $end_html;
?>
