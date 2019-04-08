<?php
//stateadmin.php: NSAA Track & Field Admin for District Results
//Created 2/28/09 because e-mailed forms were not being received, possibly due to spam filter
//Author: Ann Gaffigan

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

$db1="nsaascores";
$db2="nsaaofficials";
$statedb="nsaastatetrack";

echo $init_html;
echo $header;

echo "<form method=post action=\"stateadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>District Track & Field Results Admin</b><br>";
$sportname="Track & Field";

$table="trbdistricts";	//THIS IS OUR MASTER TABLE THAT HAS results TIMESTAMPS
if(!$sort || $sort=="") $sort="resultssub_b DESC,resultssub_g DESC";
$sql="SELECT * FROM $db2.$table WHERE type='District' ORDER BY $sort";
$result=mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($result)==0)
   echo "<br><br><div class='alert'>No forms have been submitted yet.</div><br><br>";


//STATE PROGRAM LINK
   //echo "<br><p><a href=\"/statetrack\" target=\"_blank\">Go to STATE TRACK & FIELD Program</a></p><br>";

//EXPORT RESULTS LINK FOR JEREL'S PROGRAM (NO LONGER IN USE AS OF 2012)
   //echo "<br><a href=\"exportresults.php?session=$session\">Export All District Track & Field Results</a><br><br>";

//TRANSFER STATE QUALIFIERS
   echo "<br><div class=\"alert\" style=\"width:700px;\">";
//(Check if A&D and/or B&C results have all come in. If so, show thse links)
$sql3="SELECT DISTINCT class FROM $db2.$table WHERE class!='' AND type='District' ORDER BY class";
$result3=mysql_query($sql3);
while($row3=mysql_fetch_array($result3))
{
   $class=$row3[0]; $tableg="trgdistricts";
   $sql2="SELECT * FROM $db2.$table WHERE (class='$class') AND type='District' AND resultssub_b=''";
   $result2=mysql_query($sql2);
   $sql4="SELECT * FROM $db2.$tableg WHERE (class='$class') AND type='District' AND resultssub_g=''";
   $result4=mysql_query($sql4);
   if(mysql_num_rows($result2)==0 && mysql_num_rows($result4)==0)	//ALL SUBMITTED FOR THIS CLASS
   {
      echo "<h3>Class $class:</h3><p><b><i>All Class $class District Results are in!</b></i> ";
      $sql5="SELECT * FROM $statedb.trstatequalifiers WHERE class='$class'";
      $result5=mysql_query($sql5);
      if(mysql_num_rows($result5)==0) 
         echo "<a href=\"transferdistresults.php?class1=$class&session=$session\">TRANSFER Class $class District Results to State Qualifiers</a>";
       else
         echo "<a href=\"transferdistresults.php?class1=$class&session=$session\">Reset Class $class in the STATE program and RE-TRANSFER Class $class District Results to State Qualifiers</a>";
   }
   else
      echo "<h3>Class $class:</h3><p style=\"color:red\"><b><i>When ALL Class $class District results have been submitted, you will be able to transfer qualifiers to the State Meet.</b></i></p>";
}
echo "<hr>";
echo "<p style=\"text-align:left;\">The links above will take the District Results and send them to the State Track & Field Meet Program. Once ALL district results are in, click the link above and make note of any \"Odd Performances\" which may indicate that a coach entered a result incorrectly.</p><p style=\"text-align:left;\">You will be able to re-run this export of information if you need to make changes to District Results after the fact.</p><p style=\"text-align:left;\">If you have already sent District Results to the State Meet Program, you can go directly to the <a href=\"/statetrack\" target=\"_blank\">State Meet Program</a> (Username: <b><u>nsaahome</b></u>, Password: <b><u>state!!track</b></u>).</p></div><br>";

echo "<p><b>QUICK LINKS:</b> <a class=\"small\" href=\"standards.php?session=$session\">Update Automatic Qualifying Standards</a></p>";
echO "<br />";

   //echo "<i>The following District $sportname Results have been submitted:</i>";
   echo "</caption>";
   echo "<tr align=center><td><a class=small href=\"stateadmin.php?session=$session&sort=class,district\">District</a>";
   if($sort=="class,district") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td><b>EDIT RESULTS</b></td><td><b>PREVIEW & POST RESULTS</b></td>";
   echo "<td><a class=small href=\"stateadmin.php?session=$session&sort=resultssub_b%20DESC\">BOYS Date Submitted</a>";
   if(ereg("resultssub_b DESC",$sort)) echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   echo "<td><a class=small href=\"stateadmin.php?session=$session&sort=resultssub_g%20DESC\">GIRLS Date Submitted</a>";
   if(ereg("resultssub_g DESC",$sort)) echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   echo "</tr>";

while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>$row[class]-$row[district] (Host: $row[hostschool])</td>";
     	//GET GIRLS DISTID
	$sql2="SELECT * FROM $db_name2.trgdistricts WHERE class='$row[class]' AND district='$row[district]'";
	$result2=mysql_query($sql2);
	$row2=mysql_fetch_array($result2); 
   echo "<td><a class=small href=\"tr_state_edit_g.php?session=$session&distid=$row2[id]\">Edit $row[class]-$row[district] GIRLS Results</a><br>";
   echo "<a class=small href=\"tr_state_edit_b.php?session=$session&distid=$row[id]\">Edit $row[class]-$row[district] BOYS Results</a></td>";
   echo "<td><a target=\"_blank\" class=small href=\"previewresults.php?session=$session&distid=$row[id]\">Preview $row[class]-$row[district] Girls & Boys Results</a></td>";
   if($row[resultssub_b]!='')
   {
      echo "<td>".date("m/d/y",$row[resultssub_b])." at ".date("g:ia T",$row[resultssub_b])."</td>";
   }
   else echo "<td bgcolor='#ff0000'>NOT SUBMITTED YET</td>";
   $filename1=$row['class'].$row[district]."girls.dst";
   $filename2=$row['class'].$row[district]."girlstmsco.dst";
   if($row2[resultssub_g]!='')
   {
      echo "<td>".date("m/d/y",$row2[resultssub_g])." at ".date("g:ia T",$row2[resultssub_g])."</td>";
   }
   else echo "<td bgcolor='#ff0000'>NOT SUBMITTED YET</td>";
   echo "</tr>";
}
echo "</table>";

echo "</form>";

echo $end_html;
?>
