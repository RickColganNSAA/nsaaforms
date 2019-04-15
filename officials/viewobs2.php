<?php
/**********************************************
viewobs.php
Created 10/09/12
Same as viewobs.php EXCEPT this is for an
OBSERVER to see his/her submitted evals, as 
opposed to an official to see evals submitted
about him/her
***********************************************/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$obsid=GetObsID($session);

//Figure out what the last year archived was. 
$yearnow=date("Y"); $year1=$yearnow+1; $year0=$yearnow-1;
$archivedb="$db_name2".$year0.$yearnow;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb="$db_name2".$year00.$year0;
   $curyear="$year0-$yearnow";
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
   $curyear="$yearnow-$year1";
   $lastyear="$year0-$yearnow";
   $lastfall=$year0;
}

echo $init_html;
echo GetHeader($session);
echo "<br>";

$obsname=trim(GetObsName($obsid));
echo "<form method=post action='viewobs2.php'>";
echo "<input type=hidden name='session' value='$session'>";
echo "<table cellspacing=0 cellpadding=5 style='width:500px;'><caption><b>$obsname's Submitted Observations:</b><br>";
echo "<br>Observations for <select name='year' onchange='submit();'><option value='0'>Select Year</option>";
$curyear=$lastfall+1;
for($y=$curyear;$y>=2005;$y--)
{
   $y1=$y+1;
   if($y==$curyear) $yearch="this";
   else $yearch=$y.$y1;
   echo "<option value='$yearch'";
   if($year==$yearch) echo " selected";
   echo ">$y-$y1</option>";
}
echo "</select> <input type=submit name='go' value='Go'>";

if($year)
{
   $anyobs=0;
   echo "</caption>";
   if($year=="this" || substr($year,0,4)==$curyear) $curdb=$db_name2;
   else $curdb=$db_name2.$year;

   $sql="USE $curdb";
   $result=mysql_query($sql);
   $sql="SHOW TABLES LIKE '%observe'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $temp=split("observe",$row[0]);
      $sport=$temp[0];
      $obstable=$sport."observe";

      $sql2="SELECT * FROM $curdb.$obstable WHERE obsid='$obsid' AND dateeval!='' ORDER BY dateeval";
      $useobsid=$obsid;
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
	 $anyobs++;
         echo "<tr align=left><td><br><b>".strtoupper(GetSportName($sport)).":</b></td></tr>";
      }
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
            echo "<a href=\"".$sport."observe.php?dbname=$curdb&session=$session&sport=$sport&gameid=".$row2[gameid]."&postseasongame=1&offid=".$row2[offid]."&obsid=".$obsid."\" target=\"_blank\">".$row2[home]." vs. ".$row2[visitor]." (Evaluated $dateeval)</a>";
         else
            echo "<a href=\"".$sport."observe.php?dbname=$curdb&session=$session&sport=$sport&gameid=".$row2[gameid]."&offid=".$row2[offid]."&obsid=".$obsid."\" target=\"_blank\">".$row2[home]." vs. ".$row2[visitor]." (Evaluated $dateeval)</a>";
         echo "</td></tr>";
      }//end for each observation
   }//end for each sport
   if($anyobs==0)	//NONE SUBMITTE FOR THIS YEAR
   {
      echo "<tr align=center><td><br><p><i>You submitted no observations in the year selected above.</i></p></td></tr>";
   }
}//END IF YEAR CHOSEN
else
{
   echo "<p><i>Please select a year above.</i></p>";
   echo "</caption>";
}
echo "</table></form>";
echo "<br><br><a href=\"welcome.php?session=$session\">Return Home</a>";
echo $end_html;

   $sql="USE $db_name2";
   $result=mysql_query($sql);

exit();
?>
