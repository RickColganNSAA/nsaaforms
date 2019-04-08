<?php
require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

if($level==3)
{
   $schoolid=GetSchoolID($session); $loginid=0;
}
else if($level==4)
{
   $schoolid=0; $loginid=GetUserID($session);
}

$database="nsaascores";

if(!$musiteid) $musiteid=GetMusicSiteID($schoolid,$loginid);
//verify user
if($musiteid==0)
{
   header("Location:../index.php");
   exit();
}

if($save)
{
   for($i=0;$i<$total;$i++)
   {
      $award="award".$i; $muentriesid="muentriesid".$i;
      $studentnames="studentnames".$i; $muensembletypesid="muensembletypesid".$i;
      if($$award)
      {
         //GET DATA FROM THIS ENTRY TO ENTER INTO muawardwinners TABLE
         $sql="SELECT * FROM $database.muentries WHERE id='".$$muentriesid."'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);      
      
         //UPDATE/INSERT DATA INTO muawardwinners TABLE
         $sql2="SELECT * FROM $database.muawardwinners WHERE muentriesid='".$$muentriesid."'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0)	//UPDATE
	    $sql3="UPDATE $database.muawardwinners SET distid='$musiteid',muschoolsid='$row[schoolid]',muensembletypesid='".$$muensembletypesid."',studentnames='".addslashes($$studentnames)."',award='".$$award."' WHERE muentriesid='".$$muentriesid."'";
         else	//INSERT
            $sql3="INSERT INTO $database.muawardwinners (distid,muensembletypesid,muentriesid,muschoolsid,studentnames,award) VALUES ('$musiteid','".$$muensembletypesid."','".$$muentriesid."','".$row[schoolid]."','".addslashes($$studentnames)."','".$$award."')";
         $result3=mysql_query($sql3);
         //echo $sql3."<br>".mysql_error();
      }
   }
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/MusicAwards.js"></script>
</head>
<body onload="MusicAwards.initialize(<?php echo $musiteid?>);">
<div id='loading' style='display:none;'></div>
<?php
echo $header."<br>";

echo "<form method=post action='awardwinners.php'>";
echo "<input type=hidden name='session' value='$session'>";
echo "<input type=hidden name='musiteid' value='$musiteid'>";
echo "<table cellspacing=0 cellpadding=5 class=nine>";
echo "<caption><b>District Music Outstanding Performance/Honorable Mention Award</b><br>";
echo "<div class='alert' style='padding:10px;font-size:9pt;width:500px;'><b>INSTRUCTIONS:</b><ol>";
echo "<li><b>Select a solo/ensemble category</b> from the dropdown list below.</li>";
echo "<li>The students entered to perform in that category will appear. Please <b>indicate which students should receive which award.</b></li>";
echo "<li><b>Click \"Save\"</b> at the bottom of this page.</li>";
echo "<li><b>Repeat</b> the above steps for each ensemble/category.</li>";
	//GET DUE DATE
        $sql2="SELECT * FROM muawardsduedate";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        $date=split("-",$row2[duedate]);
echo "</ol><br>At any time, you can <a href=\"viewawardwinners.php?session=$session&musiteid=$musiteid\">Preview the Complete List of Award Winners</a> for your site.<br><br>The due date for this form is <b><u>$date[1]/$date[2]/$date[0]</b></u>. As of midnight on that date, your list will be considered COMPLETE by the NSAA.";
echo "</div>";
echo "</caption>";

$sql0="SELECT * FROM $database.mudistricts WHERE id='$musiteid'";
$result0=mysql_query($sql0);
$i=1;
$row0=mysql_fetch_array($result0);

   echo "<tr align=left><td>";
   echo "<b>Site:</b> ".$row0[site]."</td></tr>";
   echo "<tr align=left><td><select name=\"mucategid".$i."\" id=\"mucategid".$i."\"><option value=\"0\">Select Solo/Ensemble Type</option>";
   $sql="SELECT * FROM $database.mucategories WHERE smlg IS NULL OR smlg='Small' ORDER BY category";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      echo ">$row[category]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=center><td><div id=\"muentries".$i."\" id=\"muentries".$i."\" style=\"text-align:left;\">";
   if($save)
      echo "<div class=alert>Your changes have been saved. Please select another solo/ensemble category above or <a href=\"viewawardwinners.php?session=$session&musiteid=$musiteid\" class=small>Preview your Complete List of Award Winners</a>.</div>";
   echo "</div></td></tr>";

echo "</table>";
echo "</form>";
echo "<a href=\"../welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
