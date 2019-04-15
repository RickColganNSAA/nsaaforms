<?php
/**********************************************
viewobs.php
Created 09/05/06
Same as obsadmin2.php but variables are set for
this official ONLY to see his/her observations
from previous years
***********************************************/

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

$offid=GetOffID($session);

//Figure out what the last year archived was. 
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb="$db_name2".$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb="$db_name2".$year00.$year0;
   $curyear="$year0-$year";
   $lastyear="$year00-$year0";
   $lastfall=$year00;
   $sql="SHOW DATABASES LIKE '$archivedb'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archive=0;
   else $archive=1;
}
else
{
   $archive=1;
   $curyear="$year-$year1";
   $lastyear="$year0-$year";
   $lastfall=$year0;
}

echo $init_html;
echo GetHeader($session);
echo "<br>";

$yearch="all";
$sportch="all";
$offname=trim(GetOffName($offid));
echo "<table><caption><b>$offname's Observations:</b><br>";
echo "</caption>";

$curyear=$lastfall+1;
for($y=$curyear;$y>=2005;$y--)
{
   $y1=$y+1;
   if($y==$curyear) $year="this";
   else $year=$y.$y1;
   echo "<tr align=left><th><hr><b><u>$y-$y1:</b></u></td></tr>";
   if($y==$curyear) $curdb="$db_name2";
   else $curdb="$db_name2".$year;

   $sql="USE $curdb";
   $result=mysql_query($sql);
   $sql="SHOW TABLES LIKE '%observe'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $temp=split("observe",$row[0]);
      $sport=$temp[0];
      $obstable=$sport."observe";
      echo "<tr align=left><td><br><b>".strtoupper(GetSportName($sport)).":</b></td></tr>";
      $sql2="SELECT * FROM $curdb.$obstable WHERE offid='$offid' AND dateeval!='' ORDER BY dateeval";
      $useoffid=$offid;
      if($sport=='bb')
	  $sql2="SELECT * FROM $curdb.$obstable WHERE (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND dateeval!='' ORDER BY dateeval";
      else if($sport=='fb')
      {
	 $chiefid=GetCrewChief($offid); $useoffid=$chiefid;
         $sql2="SELECT * FROM $curdb.$obstable WHERE offid='$chiefid' AND dateeval!='' ORDER BY dateeval";
      }
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $dateeval=date("m/d/y",$row2[dateeval]);
         echo "<tr align=left><td>";
         if($year=="20052006")
	 {
	    $sql3="SELECT name FROM logins WHERE id='$row2[obsid]'";
	    $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	    $obsname=$row3[name];
	 }
	 else $obsname=GetObsName($row2[obsid]);
         if($sport=='bb' && $row2[postseasongame]=='1')
            echo "<a href=\"".$sport."observe.php?dbname=$curdb&session=$session&sport=$sport&gameid=".$row2[gameid]."&postseasongame=1&offid=".$offid."&obsid=".$row2[obsid]."\" target=\"_blank\">".$row2[home]." vs. ".$row2[visitor]." (Evaluated $dateeval by $obsname)</a>";
         else
            echo "<a href=\"".$sport."observe.php?dbname=$curdb&session=$session&sport=$sport&gameid=".$row2[gameid]."&offid=".$useoffid."&obsid=".$row2[obsid]."\" target=\"_blank\">".$row2[home]." vs. ".$row2[visitor]." (Evaluated $dateeval by $obsname)</a>";
         echo "</td></tr>";
      }//end for each observation
   }//end for each sport
}
echo "</table>";
echo $end_html;
exit();
?>
