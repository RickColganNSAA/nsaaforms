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

$obsid=GetObsID($session);

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

$database="$db_name2";
$sportshow=GetSportName($sport);
$obstable=$sport."observe";
$observers="observers";
$yearch="this";

   echo "<table cellspacing=0 cellpadding=4 frames=all rules=all style=\"border:#000000 1px solid;\"><caption><b>Submitted $sportshow Observations</b></caption>";
   echo "<tr align=center><td>";
   echo "<a class=small href=\"obslist.php?sport=$sport&session=$session&sort=t1.last,t1.first,t2.dateeval\">Official's Name</a></td>";
   echo "<td>Opponents<br>(Click for Full Observation)</td>";
   echo "<td><a class=small href=\"obslist.php?sport=$sport&session=$session&sort=t2.dateeval,t1.last,t1.first\">Date Evaluated</a></td>";
   echo "<td>Observer's Name</td>";
   echo "</tr>";
	 if(!$sort) $sort="t1.last,t1.first,t2.dateeval";
	 $obs=GetObservations($yearch,$sport,'','',$sort);
         for($i=0;$i<count($obs[offid]);$i++)
         {
	    if($obs[obsid][$i]!=$obsid)
	    {
            $dateeval=date("m/d/y",$obs[dateeval][$i]);
	    if($obs[offid][$i]==3427 && trim($obs[official][$i])!='') $offname=$obs[official][$i];
	    else $offname=$obs[offfirst][$i]." ".$obs[offlast][$i];
            echo "<tr align=left><td>".$offname."</td>";
            if($sport=='bb' && $obs[postseasongame][$i]=='1')
               echo "<td><a href='#' class=small onclick=\"window.open('".$sport."observe.php?dbname=$database&session=$session&sport=$sport&gameid=".$obs[gameid][$i]."&postseasongame=1&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."','$sport','menubar=no,titlebar=no,resizable=yes,width=800,height=600,scrollbars=yes');\">".$obs[home][$i]." vs. ".$obs[visitor][$i]." (Postseason Game)</a></td><td>$dateeval</td><td>".$obs[obsname][$i]."</td></tr>";
            else
               echo "<td><a href='#' class=small onclick=\"window.open('".$sport."observe.php?dbname=$database&session=$session&sport=$sport&gameid=".$obs[gameid][$i]."&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."','$sport','menubar=no,titlebar=no,resizable=yes,width=800,height=600,scrollbars=yes');\">".$obs[home][$i]." vs. ".$obs[visitor][$i]."</a></td><td>$dateeval</td><td>".$obs[obsname][$i]."</td></tr>";
	    }
	 }
  
   echo "</table>";
   echo $end_html;
?>
