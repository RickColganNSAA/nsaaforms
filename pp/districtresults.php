<?php
/*********************************************
districtresults.php
Form for Director to enter District Results
to be displayed on the NSAA website
Created 9/26/14
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
$level=GetLevel($session);
if($level==2 || $level==3)
{
   $school=GetSchool($session);
   $schoolid=GetSchoolID2($school);
}

if($calculate)	//CALCULATE OVERALL PLACE
{
   $ties=array();
   for($i=0;$i<count($schoolname);$i++)
   {
      if($resultid[$i]==0)
      {
         $sql="INSERT INTO ppdistresults (distid,school,datesub) VALUES ('$distid','".addslashes($schoolname[$i])."','".time()."')";
         $result=mysql_query($sql);
	 $resultid[$i]=mysql_insert_id();
      }
      $ties[$i]=$tiebreaker[$i];
      $sql="UPDATE ppdistresults SET rank1='".$rank[$i][1]."',points1='".$points[$i][1]."',rank2='".$rank[$i][2]."',points2='".$points[$i][2]."',rank3='".$rank[$i][3]."',points3='".$points[$i][3]."',totalrank='$totalrank[$i]',totalpoints='$totalpoints[$i]',reciprocal='$reciprocal[$i]',tiebreaker='$tiebreaker[$i]' WHERE id='$resultid[$i]'";
      $result=mysql_query($sql);
   }
   //array_multisort($totalrank,SORT_ASC,SORT_NUMERIC,$totalpoints,SORT_DESC,SORT_NUMERIC,$ties,SORT_ASC,SORT_NUMERIC,$reciprocal,$rank,$points,$resultid);
   array_multisort($totalrank,SORT_ASC,SORT_NUMERIC,$ties,SORT_ASC,SORT_NUMERIC,$reciprocal,SORT_DESC,SORT_NUMERIC,$totalpoints,SORT_DESC,SORT_NUMERIC,$rank,$points,$resultid);
   $curplace=1; $placeholder=1;
   for($i=0;$i<count($totalrank);$i++)
   {
      if($rank[$i][1]>0 && $rank[$i][2]>0 && $rank[$i][3]>0) //IF THEY WERE SCORED by ALL JUDGES
      {
         $sql="UPDATE ppdistresults SET place='$curplace' WHERE id='$resultid[$i]'";
         $result=mysql_query($sql);
	 $nexti=$i+1;
         $placeholder++;
	 if($totalrank[$nexti]!=$totalrank[$i] || $reciprocal[$nexti]!=$reciprocal[$i] || $totalpoints[$nexti]!=$totalpoints[$i] || $ties[$nexti]!=$ties[$i])
            $curplace=$placeholder;
      }
      else
      {
         $sql="UPDATE ppdistresults SET place='0' WHERE id='$resultid[$i]'";
         $result=mysql_query($sql);
      }
   }

   $sql="UPDATE $db_name2.ppdistricts SET showresults='$showresults' WHERE id='$distid'";
   $result=mysql_query($sql);
}

if(!$distid)
{
   //Get Host ID
   $sql="SELECT t1.id FROM logins AS t1, headers AS t2 WHERE t1.school=t2.school AND t2.id='$schoolid' AND t1.level=2";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id];

   //Get District this School is Hosting
   $sql="SELECT * FROM $db_name2.ppdistricts WHERE hostid='$hostid'";
}
else
   $sql="SELECT * FROM $db_name2.ppdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$dist=mysql_fetch_array($result);
$distid=$dist[id];
$sids=split(",",$dist[sids]);
$ppschs[sid]=array(); $ppschs[school]=array();
for($i=0;$i<count($sids);$i++)
{
   $ppschs[sid][$i]=trim($sids[$i]);
   $ppschs[team][$i]=GetSchoolName($ppschs[sid][$i],'pp');
   $ppschs[school][$i]=GetMainSchoolName($ppschs[sid][$i],'pp');
   $sql="SELECT * FROM ppdistresults WHERE school='".addslashes($ppschs[school][$i])."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[place]==0) $ppschs[place][$i]=100;
   else $ppschs[place][$i]=$row[place];
}
array_multisort($ppschs[place],SORT_ASC,SORT_NUMERIC,$ppschs[school],SORT_STRING,SORT_ASC,$ppschs[team],$ppschs[sid]);

echo $init_html;
echo GetHeader($session);

?>
<script language="javascript">
function CalculateTotalRank(i)
{
   var varname;
   var total = 0;
   var currank;
   var reciprocal = 0;
   for(var j=1;j<=3;j++)
   {
      varname="rank"+i+j;
      if(document.getElementById(varname).value.match("[0-9]"))
      {
         currank=parseFloat(document.getElementById(varname).value);
         total += currank; 
	 reciprocal += (1/currank);
      }
   }
   document.getElementById('totalrank'+i).value=total;
   document.getElementById('reciprocal'+i).value=reciprocal.toFixed(5);
}
function CalculateTotalPoints(i)
{
   var varname;
   var total = 0;
   var curpts;
   for(var j=1;j<=3;j++)
   {
      varname="points"+i+j;
      if(document.getElementById(varname).value.match("[0-9]"))
      {
         curpts=parseFloat(document.getElementById(varname).value);
         total += curpts; 
      }
   }
   document.getElementById('totalpoints'+i).value=total;
}
</script>
<?php

echo "<br><h2>Play Production District $dist[class]-$dist[district] Results</h2>";

echo "<form method=\"post\" action=\"districtresults.php\">
	<input type=hidden name=\"session\" value=\"$session\">
	<input type=hidden name=\"distid\" value=\"$distid\">";

echo "<div class=\"alert\" style=\"text-align:left;width:600px;\"><p>Enter the <b>Rank and Points submitted by each Judge</b> below. As you enter them, the Grand Totals will be automatically calculated, as will the Reciprocal. Click \"Save and Calculate Place\" to calculate the overall place.</p>
	<p>If there is a <b><u>TIE</u></b>, enter numbers in the boxes in the Tie column to properly <b>order the schools that are tied</b>. For example, if School A is tied with School B, and School A is determined to be placed ahead of School B, enter a \"1\" in the Tie column for School A, and enter a \"2\" in the Tie column for School B. Then click \"Save and Calculate Place\" again.</p></div>";

echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<tr align=center><td rowspan=2><b>Play/School</b></td><td colspan=2><b>Judge 1</b></td><td colspan=2><b>Judge 2</b></td><td colspan=2><b>Judge 3</b></td><td colspan=2><b>Grand Total</b></td><td rowspan=2><b>PLACE</b></td><td rowspan=2><b>Reciprocal</b></td><td rowspan=2><b>Tie<br>Breaker</b></td></tr>";
echo "<tr align=center><td><b>Rank</b></td><td><b>Points</b></td><td><b>Rank</b></td><td><b>Points</b></td><td><b>Rank</b></td><td><b>Points</b></td><td><b>Rank</b></td><td><b>Points</b></td></tr>";
for($i=0;$i<count($ppschs[school]);$i++)
{
   $sql="SELECT * FROM pp WHERE school='".addslashes($ppschs[school][$i])."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0) $title="[No District Entry Submitted]";
   else $title="\"$row[title]\"";
   echo "<tr align=left><td>".$ppschs[school][$i]."<br>$title<input type=hidden name=\"schoolname[$i]\" value=\"".$ppschs[school][$i]."\">";
   $sql="SELECT * FROM ppdistresults WHERE distid='$distid' AND school='".addslashes($ppschs[school][$i])."'";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result))
      echo "<input type=hidden name=\"resultid[$i]\" value=\"$row[id]\">";
   else
      echo "<input type=hidden name=\"resultid[$i]\" value=\"0\">";
   echo "</td>";
   for($j=1;$j<=3;$j++)
   {
      $rankvar="rank".$j; $pointsvar="points".$j;
      echo "<td><input type=text name=\"rank[$i][$j]\" value=\"".$row[$rankvar]."\" size=3 id=\"rank".$i.$j."\" onKeyUp=\"CalculateTotalRank($i);\"></td>
	<td><input type=text name=\"points[$i][$j]\" value=\"".$row[$pointsvar]."\" size=4 id=\"points".$i.$j."\" onKeyUp=\"CalculateTotalPoints($i);\"></td>";
   }
   echo "<td bgcolor='#f0f0f0'><input type=text name=\"totalrank[$i]\" id=\"totalrank".$i."\" readOnly=true value=\"$row[totalrank]\" size=3></td>
	<td bgcolor='#f0f0f0'><input type=text name=\"totalpoints[$i]\" id=\"totalpoints".$i."\" readOnly=true value=\"$row[totalpoints]\" size=4></td>
	<td bgcolor='#d0d0d0'><input type=text name=\"place[$i]\" id=\"place".$i."\" value=\"$row[place]\" readOnly=true size=2></td>
	<td bgcolor='#f0f0f0'><input type=text name=\"reciprocal[$i]\" id=\"reciprocal".$i."\" readOnly=true value=\"$row[reciprocal]\" size=6></td>
	<td align=center";
	if($row[tiebreaker]>0) echo " bgcolor='yellow'";
	echo "><input type=text name=\"tiebreaker[$i]\" value=\"$row[tiebreaker]\" size=3></td></tr>";
}
echo "</table><input type=hidden name=\"totalplays\" id=\"totalplays\" value=\"$i\">";
//CHECKBOX TO APPROVE RESULTS FOR NSAA WEBSITE
echo "<div class=\"alert\" style=\"width:600px;\">";
   echo "<p><a href=\"showdistresults.php?session=$session&distid=$distid\" target=\"_blank\">Preview District $dist[class]-$dist[district] Results</a></p>";
   echo "<p><input type=checkbox name=\"showresults\" value=\"x\"";
   if($dist[showresults]=='x') echo " checked";
   echo "> <b>Check to approve these results as COMPLETE and to show them on the NSAA WEBSITE.</b> </p>";
echo "</div>";
//SUBMIT FORM
echo "<input type=submit name=\"calculate\" value=\"Save & Calculate Place\">";
echo "</form>";

echo GetFooter($session);

?>
