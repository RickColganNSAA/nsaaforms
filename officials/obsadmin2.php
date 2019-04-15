<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($level!=1 && (!$sportch || $sportch=="")) $sportch="fb";
if($level==1) $obsid=0;
else $obsid=GetObsID($session);

//Figure out what the last year archived was. 
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb=$db_name2.$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb=$db_name2.$year00.$year0;
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
echo GetHeader($session,"obshome");
echo "<br>";

if($search || $sortname)	//search submitted, create query:
{
   $lastch=addslashes($lastch);
   $firstch=addslashes($firstch);

   if($yearch=="this") $database=$db_name2;
   else if($yearch!="all") 
   {
      $yearshow=$yearch; 
      $years=explode("-",$yearch);
      $yearch=ereg_replace("-","",$yearch);
      $database=$db_name2.$yearch;
   }

   echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\" class='nine'><caption>";

echo "<form method=post action=\"obsadmin2.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=4 width=400><caption><b>";
if($level!=1) 
{
   if($sportch=="bbclinic") echo "Basketball CLINIC ";
   else echo GetSportName($sportch)." ";
}
echo "Observations Advanced Search:<br>";
if($level==1)
   echo "<font style=\"font-size:8pt;\">(Not to be confused with <a class=small href=\"obs_query.php?session=$session\">Observers Advanced Search</a></font>)</b>";
echo "<br><br></caption>";
echo "<tr align=left bgcolor=#E0E0E0><th align=left>Year(s):</th>";
echo "<td><select name='yearch'><option value='this'>This Year</option>";
echo "<option value='all'";
if($yearch=="all") echo " selected";
echo ">All (2005-Present)</option>";
for($i=$lastfall;$i>=2005;$i--) //observations weren't online until 2005-2006 year
{
   $j=$i+1;
   echo "<option value=\"$i-$j\"";
   if($yearch==$i.$j) echo " selected";
   echo ">$i-$j</option>";
}
echo "</select></td></tr>";
if($level==1)
{
   echo "<tr align=left><th align=left>Sport(s):</th>";
   echo "<td><select name=sportch><option value='all'>All Sports</option>";
   for($i=0;$i<count($activity);$i++)
   {
      $sql="SHOW TABLES LIKE '".$activity[$i]."%observe'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $temp=explode("observe",$row[0]);
         echo "<option value='$temp[0]'";
         if($sportch==$temp[0]) echo " selected";
         echo ">".GetSportName($activity[$i]);
	 if(preg_match("/clinic/",$temp[0])) echo " CLINIC";
         echo "</option>";
      }
   }
   echo "</select></td></tr>";
}
else
{
   echo "<tr align=center><td><input type=hidden name='sportch' value='$sportch'></td></tr>";
}
echo "<tr valign=top align=left bgcolor=#E0E0E0><th align=left>Official's Name:</th>";
echo "<td><b>Last:</b>&nbsp;<input type=text name=lastch class=tiny size=25 value=\"$lastch\"><br>";
echo "<b>First:</b>&nbsp;<input type=text name=firstch class=tiny size=25 value=\"$firstch\"><br>";
echo "<i>(Leave both fields blank for \"All Officials\")</i></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name=search value=\"Search\"></td></tr>";
echo "</table><br><br>";
   echo "</caption>";
   echo "<tr align=center>";
   if(!$sort) $sort="clinicdate,t1.last,t1.first";
   //GAME DATE
   if($sortname=="Game Date")
   {
	$curimg="../arrowdown.png"; $sort="clinicdate,t1.last,t1.first";
   }
   else $curimg="";
   echo "<td>";
   if($curimg!='')
      echo "<img src=\"$curimg\" style=\"width:20px;border:0;margin:3px;\">";
   echo "<input type=submit name=\"sortname\" value=\"Game Date\">";
   echo "</td>";
   //OFFICIAL
   if($sortname=="Official")
   {
        $curimg="../arrowdown.png"; $sort="t1.last,t1.first,clinicdate";
   }
   else $curimg="";
   echo "<td>";
   if($curimg!='')
      echo "<img src=\"$curimg\" style=\"width:20px;border:0;margin:3px;\">";
   echo "<input type=submit name=\"sortname\" value=\"Official\">";
   echo "</td>";
   //OPPONENTS
   echo "<th>Game/Opponents (Click for Evaluation)</th>";
   //OBSERVER
   if($sortname=="Observer")
   {
        $curimg="../arrowdown.png";  $sort="t2.obsid";
   }
   else $curimg="";
   echo "<td>";
   if($curimg!='')
      echo "<img src=\"$curimg\" style=\"width:20px;border:0;margin:3px;\">";
   echo "<input type=submit name=\"sortname\" value=\"Observer\">";
   echo "</td>";
   echo "</tr>";

   $obstable=$sportch."observe";
   if($yearch=="20052006") $observers="logins";
   else $observers="observers";
   
   if($yearch!="all")	//specific year
   {
      if($yearch=="this") $database=$db_name2;
      else $database="nsaaofficials".$yearch;
      if($sportch!="all")	//specific sport chosen
      {
	 $sportch2=$sportch;
	 $obs=GetObservations($yearch,$sportch2,trim($lastch),trim($firstch),$sort,$obsid);
         for($i=0;$i<count($obs[offid]);$i++)
         {
            $dateeval=date("m/d/y",$obs[dateeval][$i]);
	    $date=explode("-",$obs[gamedate][$i]);
	    if($obs[offid][$i]==3427 && trim($obs[official][$i])!='') $offname=$obs[official][$i];
    	    else $offname=$obs[offfirst][$i]." ".$obs[offlast][$i];
            echo "<tr align=left><td>$date[1]/$date[2]/$date[0]</td><td>".$offname."</td><td>";
	    if(!preg_match("/clinic/",$sportch))
    	    {
               if($sportch=='bb' && $obs[postseasongame][$i]=='1')
	   	  echo "<a href=\"".$sportch2."observe.php?dbname=$database&session=$session&sport=$sportch2&gameid=".$obs[gameid][$i]."&postseasongame=1&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">".$obs[home][$i]." vs. ".$obs[visitor][$i]." (Postseason Game)</a>";
               else
	 	   echo "<a target=\"_blank\" href=\"".$sportch2."observe.php?dbname=$database&session=$session&sport=$sportch2&gameid=".$obs[gameid][$i]."&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\">".$obs[home][$i]." vs. ".$obs[visitor][$i]."</a>";
            }
	    else
	    {
	       echo "<a href=\"".$sportch2."observe.php?dbname=$database&session=$session&sport=$sportch2&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">Clinic at ".$obs[location][$i]."</a>";
   	    }
            echo "</td><td>".$obs[obsname][$i];
   	    if($obs[obsname][$i]!="NSAA")
	       echo "<br><a href=\"mailto:".$obs[obsemail][$i]."\">".$obs[obsemail][$i]."</a><br>Phone: ".FormatPhone($obs[obsphone][$i]);
	    echo "</td></tr>";
	 }
      }//end if sportch!=all
      else	//no specific sport chosen: all sports
      {
	 $sql="USE $database";
	 $result=mysql_query($sql);
	 $sql="SHOW TABLES LIKE '%observe'";
	 $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
	 {
	    $obstable=$row[0];
	    $temp=split("observe",$obstable);
	    $sport=preg_replace("/observe/","",$obstable);
	    $sportch2=$sport;
	    $sportname=GetSportName($sport);
	    echo "<tr align=left><td><b>".strtoupper($sportname).":</b></td></tr>";
	    $obs=GetObservations($yearch,$sport,$lastch,$firstch,$sort,$obsid);
	    for($i=0;$i<count($obs[offid]);$i++)
	    {
            $dateeval=date("m/d/y",$obs[dateeval][$i]);
            $date=explode("-",$obs[gamedate][$i]);
            echo "<tr align=left><td>$date[1]/$date[2]/$date[0]</td><td>".$obs[offfirst][$i]." ".$obs[offlast][$i]."</td><td>";
            if(!preg_match("/clinic/",$sportch))
            {
               if($sportch=='bb' && $obs[postseasongame][$i]=='1')
                  echo "<a href=\"".$sportch2."observe.php?dbname=$database&session=$session&sport=$sportch2&gameid=".$obs[gameid][$i]."&postseasongame=1&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">".$obs[home][$i]." vs. ".$obs[visitor][$i]." (Postseason Game)</a>";
               else
                   echo "<a target=\"_blank\" href=\"".$sportch2."observe.php?dbname=$database&session=$session&sport=$sportch2&gameid=".$obs[gameid][$i]."&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\">".$obs[home][$i]." vs. ".$obs[visitor][$i]."</a>";
            }
            else
            {
               echo "<a href=\"".$sportch2."observe.php?dbname=$database&session=$session&sport=$sportch2&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">Clinic at ".$obs[location][$i]."</a>";
            }
            echo "</td><td>".$obs[obsname][$i];
            if($obs[obsname][$i]!="NSAA")
               echo "<br><a href=\"mailto:".$obs[obsemail][$i]."\">".$obs[obsemail][$i]."</a><br>Phone: ".FormatPhone($obs[obsphone][$i]);
	    echo "</td></tr>";
	    }//end for each table entry
 	 }//end for each observe table
      }//end if all sport chosen 
   }//end if yearch!=all
   else		//ALL YEARS
   {
      $obsid=0;
	  $curyear=$lastfall+1;
      for($y=$curyear;$y>=2005;$y--)
      {
	 $y1=$y+1;
	 $database="nsaaofficials".$y.$y1;
         if($y==$curyear) $year="this";
         else $year=$y.$y1;
	 if($year=="this") $database=$db_name2;
	 echo "<tr align=left><td><b>$y-$y1:</b></td></tr>";
         if($y==$curyear) $curdb="$db_name2";
	 else $curdb="$db_name2".$year;
         if($sportch!="all")	//specific sport chosen
	 {
	    $obs=GetObservations($year,$sportch,$lastch,$firstch,$sort,$obsid);
	    //echo "$year,$sportch,$lastch,$firstch: ".count($obs[offid]);
	    for($i=0;$i<count($obs[offid]);$i++)
	    {
            $dateeval=date("m/d/y",$obs[dateeval][$i]);
            $date=explode("-",$obs[gamedate][$i]);
            echo "<tr align=left><td>$date[1]/$date[2]/$date[0]</td><td>".$obs[offfirst][$i]." ".$obs[offlast][$i]."</td><td>";

            if(!preg_match("/clinic/",$sportch))
            {
               if($sportch=='bb' && $obs[postseasongame][$i]=='1')
                  echo "<a href=\"".$sportch."observe.php?dbname=$database&session=$session&sport=$sportch&gameid=".$obs[gameid][$i]."&postseasongame=1&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">".$obs[home][$i]." vs. ".$obs[visitor][$i]." (Postseason Game)</a>";
               else
                  echo "<a target=\"_blank\" href=\"".$sportch."observe.php?dbname=$database&session=$session&sport=$sportch&gameid=".$obs[gameid][$i]."&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\">".$obs[home][$i]." vs. ".$obs[visitor][$i]."</a>";
     	    }
            else            
	    {
               echo "<a href=\"".$sportch."observe.php?dbname=$database&session=$session&sport=$sportch&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">Clinic at ".$obs[location][$i]."</a>";
            }
            echo "</td><td>".$obs[obsname][$i];
            if($obs[obsname][$i]!="NSAA")
               echo "<br><a href=\"mailto:".$obs[obsemail][$i]."\">".$obs[obsemail][$i]."</a><br>Phone: ".FormatPhone($obs[obsphone][$i]);
            echo "</td></tr>";
	    }
	 }
	 else	//ALL sports
	 {
            $sql="USE $curdb";
            $result=mysql_query($sql);
            $sql="SHOW TABLES LIKE '%observe'";
            $result=mysql_query($sql);
            while($row=mysql_fetch_array($result))
	    {
	       $temp=split("observe",$row[0]);
	       $sport=$temp[0];
	       echo "<tr align=left><td><b>".strtoupper(GetSportName($sport)).":</b></td></tr>";
               $obs=GetObservations($year,$sport,$lastch,$firstch,$sort,$obsid);
               //echo "$year,$sport,$lastch,$firstch: ".count($obs[offid]);
               for($i=0;$i<count($obs[offid]);$i++)
               {
            $dateeval=date("m/d/y",$obs[dateeval][$i]);
            $date=explode("-",$obs[gamedate][$i]);
            echo "<tr align=left><td>$date[1]/$date[2]/$date[0]</td><td>".$obs[offfirst][$i]." ".$obs[offlast][$i]."</td><td>";
            if($sport=='bb' && $obs[postseasongame][$i]=='1')
                echo "<a href=\"".$sport."observe.php?dbname=$database&session=$session&sport=$sport&gameid=".$obs[gameid][$i]."&postseasongame=1&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\" target=\"_blank\">".$obs[home][$i]." vs. ".$obs[visitor][$i]." (Postseason Game)</a>";
            else
                echo "<a target=\"_blank\" href=\"".$sport."observe.php?dbname=$database&session=$session&sport=$sport&gameid=".$obs[gameid][$i]."&offid=".$obs[offid][$i]."&obsid=".$obs[obsid][$i]."\">".$obs[home][$i]." vs. ".$obs[visitor][$i]."</a>";
            echo "</td><td>".$obs[obsname][$i];
            if($obs[obsname][$i]!="NSAA")
               echo "<br><a href=\"mailto:".$obs[obsemail][$i]."\">".$obs[obsemail][$i]."</a><br>Phone: ".FormatPhone($obs[obsphone][$i]);
            echo "</td></tr>";
	       }//end for each observation
            }//end for each sport
	 }//end if all sports
      }//end if all years
   }
   echo "</table>";
   echo "</form>";
   echo $end_html;
   exit();
}	//END IF SEARCH
else
{
echo "<form method=post action=\"obsadmin2.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=4 width=400><caption><b>";
if($level!=1) 
{
   if($sportch=="bbclinic") echo "Basketball CLINIC ";
   else echo GetSportName($sportch)." ";
}
echo "Observations Advanced Search:<br>";
if($level==1)
   echo "<font style=\"font-size:8pt;\">(Not to be confused with <a class=small href=\"obs_query.php?session=$session\">Observers Advanced Search</a></font>)</b>";
echo "<br><br></caption>";
echo "<tr align=left bgcolor=#E0E0E0><th align=left>Year(s):</th>";
echo "<td><select name=yearch><option value='this'>This Year</option>";
echo "<option value='all'>All (2005-Present)</option>";
for($i=$lastfall;$i>=2005;$i--)	//observations weren't online until 2005-2006 year
{
   $j=$i+1;
   echo "<option>$i-$j</option>";
}
echo "</select></td></tr>";
if($level==1)
{
   echo "<tr align=left><th align=left>Sport(s):</th>";
   echo "<td><select name=sportch><option value='all'>All Sports</option>";
   for($i=0;$i<count($activity);$i++)
   {
      $sql="SHOW TABLES LIKE '".$activity[$i]."%observe'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $temp=explode("observe",$row[0]);
         echo "<option value='$temp[0]'";
         if($sportch==$temp[0]) echo " selected";
         echo ">".GetSportName($activity[$i]);
         if(preg_match("/clinic/",$temp[0])) echo " CLINIC";
         echo "</option>";
      }
   }
   echo "</select></td></tr>";
}
else
   echo "<input type=hidden name=sportch value='$sportch'>";
echo "<tr valign=top align=left bgcolor=#E0E0E0><th align=left>Official's Name:</th>";
echo "<td><b>Last:</b>&nbsp;<input type=text name=lastch class=tiny size=25><br>";
echo "<b>First:</b>&nbsp;<input type=text name=firstch class=tiny size=25><br>";
echo "<i>(Leave both fields blank for \"All Officials\")</i></td></tr>";
echo "<tr align=center><td colspan=2><br><input type=submit name=search value=\"Search\"></td></tr>";
echo "</table>";
echo "</form>";
}
echo $end_html;

?>
