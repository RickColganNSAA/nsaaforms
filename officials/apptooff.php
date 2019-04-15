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

$level=GetLevel($session);

$offtable=$sport."off";

if($sport=='bb')
{
   $bbdates=array();
   $sql2="SELECT DISTINCT tourndate FROM bbtourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $i=1;
   while($row2=mysql_fetch_array($result2))
   {
      $index="date".$i;
      $index2=$i-1;
      $date=explode("-",$row2[tourndate]);
      $bbdates[$index2]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sql="SHOW FULL COLUMNS FROM bbapply WHERE Field='$index'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         $sql="ALTER TABLE bbapply ADD `$index` VARCHAR(10) NOT NULL";
         $result=mysql_query($sql);
      }
      $i++;
   }
   $sql2="SELECT DISTINCT tourndate,girls,boys FROM bbtourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,girls DESC";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $index="date".$i;
      $index2=$i-1;
      $date=explode("-",$row2[tourndate]);
      $bbdates[$index2]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $sql="SHOW FULL COLUMNS FROM bbapply WHERE Field='$index'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         $sql="ALTER TABLE bbapply ADD `$index` VARCHAR(10) NOT NULL";
         $result=mysql_query($sql);
      }
      $i++;
   }
}
else if($sport=='wr')
{
   $sql="SELECT DISTINCT tourndate FROM wrtourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $wrdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $wrdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
}
else if($sport=='so')
{
   $sql="SELECT * FROM sotourndates WHERE offdate='x' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   $sodates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $sodates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $curlabel=preg_replace("/State - /","",$row[label]);
      $curlabel=preg_replace("/Substate - /","",$curlabel);
      $curlabel=preg_replace("/Districts - /","",$curlabel);
      $curlabel=preg_replace("/ - /","-",$curlabel);
      $curlabel=preg_replace("/pm/","",$curlabel);
      $curlabel=preg_replace("/am/","",$curlabel);
      $curlabel=preg_replace("/ and later/","+",$curlabel);
      $sodates[$i].=" (".$curlabel.")";
      $i++;
   }
}
else if($sport=='ba')
{
   $sql="SELECT tourndate FROM batourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $badates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $badates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
}
else if($sport=='sb')
{
   $sql="SELECT tourndate FROM sbtourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $sbdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $sbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
}
else if($sport=='vb')
{
   $sql="SELECT tourndate FROM vbtourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $vbdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $vbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
}
else if($sport=='fb')
{
   $sql="SELECT tourndate FROM fbtourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $fbdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $fbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
}
else if($sport=='sw')
{
   $sql="SELECT * FROM swtourndates WHERE offdate='x' ORDER BY tourndate";
   $result=mysql_query($sql);
   $swdates=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $swdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(trim($row[label])!='') $swdates[$i].=" ($row[label])";
      $i++;
   }
}

if($level==2)	//official
{
   $page=$appsport."app.php";
   header("Location:".$page."?session=$session");
   exit();
}
else if($level==1 || $level==4)	//NSAA: Apps to Off Admin Page
{
   if($submit=="Delete")
   {
      $table=$sport."apply";
      for($i=0;$i<count($deloffid);$i++)
      {
	 if($delete[$i]=='x')
	 {
	    $sql="DELETE FROM $table WHERE offid='$deloffid[$i]'"; 
	    $result=mysql_query($sql);
	    //echo "$sql<br>";
	 }
      }
   }
   if($print==1)	//show printable report of all of this sport's offs by zone
   {
      echo $init_html;
      //get zones
      $table=$sport."_zones";
      $table2=$sport."apply";
      $table3=$sport."off";
      echo "<table>";
      for($i=0;$i<count($activity);$i++)
      {
	 if($activity[$i]==$sport)
	    $sportname=$act_long[$i];
      }
      echo "<caption><b>Officials Eligible for District $sportname Assignments:</b></caption>";
      $sql="SELECT zone,cities FROM $table ORDER BY zone";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $cities=split(",",$row[1]);
	 $curzone=$row[0];
	 $sql2="SELECT DISTINCT t1.*,t2.*,t3.class,t3.years FROM $table2 AS t1, officials AS t2,$table3 AS t3 WHERE t1.offid=t2.id AND t3.offid=t2.id AND (";
	 for($i=0;$i<count($cities);$i++)
	 {
	    $cities2[$i]=ereg_replace("\'","\'",trim($cities[$i]));
	    $sql2.="TRIM(t2.city)='$cities2[$i]' OR ";
	 }
	 $sql2=substr($sql2,0,strlen($sql2)-4);
	 $sql2.=") ORDER BY t2.last";
	 $result2=mysql_query($sql2);
	 while($row2=mysql_fetch_array($result2))
	 {
	    echo "<tr align=left><td><table>";
	    echo "<tr align=left><td>Zone:</td><td colspan=6>$curzone</td></tr>";
	    echo "<tr align=left><td>ID:</td><td>$row2[socsec]</td>";
	    echo "<td>$row2[first] $row2[last],</td><td>$row2[city]</td><td>Class: $row2[class]</td>";
	    echo "<td>Years: $row2[years]</td></tr>";
            if($sport=="sb")
	    {
	       for($i=1;$i<=count($sbdates);$i++)
	       {
		  $index="date".$i;
		  $index2=$i-1;
		  echo "<tr align=left><td>$sbdates[$index2]: </td><td colspan=5>$row2[$index]</td></tr>";
	       }
	       echo "<tr align=left><td colspan=6>Available at 8 AM:&nbsp;$row2[early]</td></tr>";
	       echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }
	    else if($sport=="ba")
	    {
	       for($i=0;$i<count($badates);$i++)
	       {
		  if($i%2==0) echo "<tr align=left>";
		  $index2=$i+1;
		  $index="date".$index2;
		  echo "<td>$badates[$i]: </td><td>$row2[$index]</td>";
		  if(($i+1)%2==0) echo "<td colspan=2>&nbsp;</td></tr>";
	       }
	       echo "<tr align=left><td colspan=6>Available at 11 AM:&nbsp;$row2[early]</td></tr>";
	       echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }
	    else if($sport=="bb")
	    {
	       for($i=0;$i<count($bbdates);$i++)
	       {
		  if($i%3==0) echo "<tr align=left>";
		  $index2=$i+1;
		  $index="date".$index2;
		  echo "<td>$bbdates[$i]: </td><td>$row2[$index]</td>";
		  if(($i+1)%3==0) echo "</tr>";
	       }
	       echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }
	    else if($sport=="fb")
	    {
	       //get crew names
	       $sql3="SELECT first,last,socsec FROM officials WHERE id='$row2[referee]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       echo "<tr align=left><td>Referee:</td>";
	       echo "<td>$row3[first] $row3[last]</td><td colspan=4>#$row3[socsec]</td></tr>";
	       $sql3="SELECT first,last,socsec FROM officials WHERE id='$row2[umpire]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       echo "<tr align=left><td>Umpire:</td>";
	       echo "<td>$row3[first] $row3[last]</td><td colspan=4>#$row3[socsec]</td></tr>";
	       $sql3="SELECT first,last,socsec FROM officials WHERE id='$row2[linesman]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       echo "<tr align=left><td>Linesman:</td>";
	       echo "<td>$row3[first] $row3[last]</td><td colspan=4>#$row3[socsec]</td></tr>";
	       $sql3="SELECT first,last,socsec FROM officials WHERE id='$row2[linejudge]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       echo "<tr align=left><td>Line Judge:</td>";
	       echo "<td>$row3[first] $row3[last]</td><td colspan=4>#$row3[socsec]</td></tr>";
	       $sql3="SELECT first,last,socsec FROM officials WHERE id='$row2[backjudge]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       echo "<tr align=left><td>Back Judge:</td>";
	       echo "<td>$row3[first] $row3[last]</td><td colspan=4>#$row3[socsec]</td></tr>";

	       for($i=0;$i<count($fbdates);$i++)
	       {
		  if($i%2==0) echo "<tr align=left>";
		  $index2=$i+1;
		  $index="date".$index2;
		  echo "<td>$fbdates[$i]: </td><td>$row2[$index]</td>";
		  if(($i+1)%2==0) echo "<td colspan=2>&nbsp;</td></tr>";
	       }
	       echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }
	    else if(ereg("so",$sport))
	    {
               for($i=0;$i<count($sodates);$i++)
               {
                  if($i%2==0) echo "<tr align=left>";
                  $index2=$i+1;
                  $index="date".$index2;
                  echo "<td>$sodates[$i]: </td><td>$row2[$index]</td>";
                  if(($i+1)%2==0) echo "<td colspan=2>&nbsp;</td></tr>";
               }
               echo "<tr align=left><td colspan=2>Preferred Partners:</td>";
               echo "<td colspan=2>$row2[partner1] ($row2[city1]), </td>";
               echo "<td colspan=2>$row2[partner2] ($row2[city2])</td></tr>";
               echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }
	    else if($sport=="vb")
	    {
	       for($i=0;$i<count($vbdates);$i++)
	       {
		  if($i%2==0) echo "<tr align=left>";
		  $index2=$i+1;
		  $index="date".$index2;
		  echo "<td>$vbdates[$i]: </td><td>$row2[$index]</td>";
		  if(($i+1)%2==0) echo "<td colspan=2>&nbsp;</td></tr>";
	       }
	       echo "<tr align=left><td colspan=6>Available at 4:30PM:&nbsp;$row2[available]</td></tr>";
	       echo "<tr align=left><td colspan=2>Preferred Partners:</td>";
	       echo "<td colspan=2>$row2[partner1] ($row2[city1]), </td>";
	       echo "<td colspan=2>$row2[partner2] ($row2[city2])</td></tr>";
	       echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }
	    else if($sport=="wr")
	    {
               for($i=0;$i<count($wrdates);$i++)
               {
                  if($i%3==0) echo "<tr align=left>";
		  $index2=$i+1;
		  $index="date".$index2;
		  echo "<tr align=left><td>$wrdates[$i]: </td><td colspan=5>$row2[$index]</td></tr>";
	       }
	       echo "<tr align=left><td colspan=6>Conflicts:&nbsp;$row2[conflict]</td></tr>";
	    }

	    echo "</table><hr></td></tr>";
	 }
      }
      echo "</table>";
      echo $end_html;
      exit();
   }
   if($search!="Search" && !$searchquery)	//sport selected, no adv search options selected yet
   {
      echo $init_html;
      echo GetHeader($session,"apptooff");
      echo "<br><br>";
      echo "<a href=\"tourndates.php?session=$session\">&larr; Edit Postseason Dates for Apps to Host, Apps to Officiate, Lodging</a><br><br>";
      echo "<form name=advsearch method=post action=\"apptooff.php\">";
      echo "<input type=hidden name=session value=$session>";
      echo "<table><caption><b>Applications to Officiate Admin:</b></caption>";
      echo "<tr align=center><th class=smaller>Choose a Sport:&nbsp;";
      echo "<select name=sport><option>~</option>";
      for($i=0;$i<count($activity);$i++)
      {
	 if($activity[$i]!="di")
	 {
            echo "<option value='$activity[$i]'";
            if($sport==$activity[$i]) echo " selected";
  	    echo ">$act_long[$i]</option>";
	 }
      }
      echo "</select>&nbsp;<input type=submit name=go value=\"Go\"><hr>";
      if($sport && $sport!="~")
      {
      echo "<tr align=center><td><ul>";
      if($sport=='fb')
	 echo "<li><a class=small target=new href=\"fbcontacts.php?session=$session\">Football Contact Information</a><br><br></li>";
      echo "<li><a href=\"apptooffzones.php?session=$session&sport=$sport\" class=small>Manage Zones</a><br><br></li>";
      echo "<li><a href=\"apptooff.php?session=$session&sport=$sport&print=1\" target=new class=small>Print Report (in Zone Order)</a><br><br></li>";
      echo "<li><a target=new class=small href=\"".$sport."app.php?print=1&session=$session\">Preview App to Officiate</a><br><br></li>";
      if($sport=='sb')
      {
	 echo "<li><b>Blank Softball District Assignment Forms:</b><br>";
	 echo "<table>";
	 $sql="SELECT id,class,district FROM sbdistricts ORDER BY class,district";
	 $result=mysql_query($sql);
	 $ix=0;
	 while($row=mysql_fetch_array($result))
	 {
	    if($ix%6==0) echo "<tr align=left>";
	    echo "<td><a class=small target=new href=\"sbassign.php?session=$session&distid=$row[id]\">$row[class]-$row[district]</a></td>";
	    if(($ix+1)%6==0) echo "</tr>";
	    $ix++;
	 }
	 echo "</table><br>";
	 echo "</li>";
      }
      echo "<li><b>Advanced Search Options:</b><br><br>";
      echo "<table>";
      echo "<tr align=left><td><b>Zone(s):</b></td>";
      echo "<td><select multiple size=4 name=zones[]><option selected>All Zones</option>";
      //get zones
      $zonetable=$sport."_zones";
      $sql="SELECT zone FROM $zonetable ORDER BY zone";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<option>$row[0]</option>";
      }
      echo "</select></td></tr>";
      echo "<tr align=left valign=top>";
      echo "<th align=left class=smaller>Official's Name:<br>(Click to E-mail)</th>";
      echo "<td>";
      echo "Last: <input type=text size=20 class=tiny name=last><br>";
      echo "First: <input type=text size=20 class=tiny name=first></td></tr>";
      if($sport!='tr')
      {
      echo "<tr align=left valign=top><th align=left class=smaller>Dates Available:</th>";
      echo "<td><table>";
      switch($sport)
      {
	 case "sb":
	    $datearray=$sbdates;
	    break;
	 case "ba":
	    $datearray=$badates;
	    break;
	 case "bb":
	    $datearray=$bbdates;
	    break;
	 case "fb":
	    $datearray=$fbdates;
	    break;
	 case "so":
	    $datearray=$sodates;
	    break;
	 case "vb":
	    $datearray=$vbdates;
	    break;
	 case "wr":
	    $datearray=$wrdates;
	    break;
         case "sw":
	    $datearray=$swdates;
	    break;
	 default:
	    $datearray=array();
      }
      for($i=0;$i<count($datearray);$i++)
      {
	    if($i%3==0) echo "<tr align=left>";
	    $index2=$i+1;
	    $index="date".$index2;
	    echo "<td><input type=checkbox name=$index value='x'>".$datearray[$i]."</td>";
	    if(($i+1)%3==0) echo "</tr>";
      }
      echo "</table></td></tr>";
      echo "<tr align=left><th align=left class=smaller>Applied to Officiate Yet:</th>";
      echo "<td><input type=radio name=applied value='y'>Yes&nbsp;&nbsp;";
      echo "<input type=radio name=assigned value='n'>No</td></tr>";
      }
      echo "<tr align=left><th align=left class=smaller>Assigned Yet:</th>";
      echo "<td><input type=radio name=assigned value='y'>Yes&nbsp;&nbsp;";
      echo "<input type=radio name=assigned value='n'>No</td></tr>";
      echo "<tr align=left><th align=left class=smaller>Completed FULL Registration:</th>";
      echo "<td><input type=radio name=fullyreg value='y'>Yes&nbsp;&nbsp;";
      echo "<input type=radio name=fullyreg value='n'>No</td></tr>";
      if($sport=='tr')
      {
         echo "<tr align=left><td><b>Wants to be a State Track Starter:</b></td>";
	 echo "<td><input type=checkbox name=starter value='x'></td></tr>";
      }
      echo "<tr align=center><td colspan=2><input type=submit name=search value=\"Search\"></td></tr>";
      echo "</table></li>";
      }//end if sport selected
   }
   else if($sport && ($search=="Search" || $searchquery))	//advanced search submitted...
   {
      echo $init_html;
      echo GetHeader($session,"apptooff");
      echo "<br>";
      echo "<form method=post action=\"apptooff.php\">";
      echo "<input type=hidden name=session value=$session>";
      echo "<input type=hidden name=sport value=$sport>";
      //echo "<input type=hidden name=search value=$search>";
      //echo "<input type=hidden name=hiddensql value=\"$sql\">";
      echo "<a href=\"apptooff.php?session=$session&sport=$sport\" class=small>Return to App to Off Admin</a><br><br>";
      echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\" class='nine'>";
      echo "<caption><b>Advanced Search Results:</b><br>";
      echo "<div class='alert' style=\"width:500px;text-align:left;\">The <b>\"Observations\" column</b> contains links to any observations filled out by the NSAA or another observer as well as a link for the NSAA to \"Fill out Evaluation\".  Once you fill out a new evaluation and close that window, click the \"Refresh\" button on your browser to see the link to that new evaluation.</div><br>";
      //show search criteria
      for($i=0;$i<count($activity);$i++)
      {
	 if($activity[$i]==$sport)
	    $sportname=$act_long[$i];
      }
      $criteria="<div class='normalwhite' style='width:400px;'><b>CURRENT SEARCH:</b><br><br>Sport: $sportname, ";
      $criteria.="Zone(s): ";
      for($i=0;$i<count($zones);$i++)
      {
	 $criteria.=$zones[$i].", ";
	 if($zones[$i]=="All Zones")
	    $i=count($zones);
      }
      if($last!="")
	 $criteria.="Last Name: $last*, ";
      if($first!="")
	 $criteria.="First Name: $first*, ";

      //create query based on search criteria
      $table=$sport."apply";
      $offtable=$sport."off";
      $zonetable=$sport."_zones";
      $obstable=$sport."observe";
      if($zones[0]!="All Zones")	//zone(s) specified
      {
	 $cities="";
	 for($i=0;$i<count($zones);$i++)
	 {
	    $sql="SELECT cities FROM $zonetable WHERE zone='$zones[$i]'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $cities.=$row[0];
	 }
	 $cities=ereg_replace("\'","\'",$cities);
	 $cities=split(",",$cities);
	 $sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t1.email AS emailaddress,t2.* FROM officials AS t1, $table AS t2, $offtable AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t1.$sport='x' AND (";
	 $sqlAPP="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t1.socsec,t1.email,t1.homeph,t1.cellph,t1.workph FROM officials AS t1,$offtable AS t3 WHERE t1.id=t3.offid AND t1.$sport='x' AND (";
	 for($i=0;$i<count($cities);$i++)
	 {
	    $cities[$i]=trim($cities[$i]);
	    $sql.="t1.city='$cities[$i]' OR ";
	    $sqlAPP.="t1.city='$cities[$i]' OR ";
	 }
	 $sql=substr($sql,0,strlen($sql)-4);
	 $sqlAPP=substr($sql,0,strlen($sql)-4);
	 $sql.=")";
	 $sqlAPP.=")";
      }
      else	//all zones
      {
	 $sql="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t1.email AS emailaddress,t1.homeph,t1.cellph,t1.workph,t2.*,t3.mailing FROM officials AS t1,$table AS t2,$offtable AS t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t1.$sport='x'";
	 $sqlAPP="SELECT DISTINCT t1.id,t1.first,t1.last,t1.city,t1.email,t1.homeph,t1.cellph,t1.workph,t3.mailing,t1.socsec FROM officials AS t1,$offtable AS t3 WHERE t1.id=t3.offid AND t1.$sport='x'";
      }
      //check for name search
      $last2=addslashes($last);
      $first2=addslashes($first);
      if(trim($last)!="")
      {
         $sql.=" AND t1.last LIKE '$last2%'";
	 $sqlAPP.=" AND t1.last LIKE '$last2%'";
      }
      if(trim($first)!="")
      {
         $sql.=" AND t1.first LIKE '$first2%'";
	 $sqlAPP.=" AND t1.first LIKE '$first2%'";
      }
      if($sport=='tr' && $starter=='x')
      {
         $sql.=" AND t2.starter='x'";
	 $sqlAPP.=" AND t2.starter='x'";
      }
      //check for dates available search
      switch($sport)
      {
	 case "ba":
	    $ct=count($badates);
	    $datearray=$badates;
	    break;
	 case "bb":
	    $ct=count($bbdates);
	    $datearray=$bbdates;
	    break;
	 case "fb":
	    $ct=count($fbdates);
	    $datearray=$fbdates;
	    break;
	 case "sb":
	    $ct=count($sbdates);
	    $datearray=$sbdates;
	    break;
	 case "so":
	    $ct=count($sodates);
	    $datearray=$sodates;
	    break;
	 case "tr":
	    $ct=0;
	    $datearray=array();
	    break;
	 case "vb":
	    $ct=count($vbdates);
	    $datearray=$vbdates;
	    break;
	 case "wr":
	    $ct=count($wrdates);
	    $datearray=$wrdates;
	    break;
	 case "sw":
	    $ct=count($swdates);
	    $datearray=$swdates;
	    break;
	 default:
	    $ct=0;
      }
      $sql.=" AND (";
      $dateschecked=0;
      $string="";
      for($i=0;$i<$ct;$i++)
      {
	    $index2=$i+1;
	    $index="date".$index2;
            if($$index=='x')
	    {
	       $sql.="t2.$index='x' OR ";
	       $dateschecked=1;
	       $string.="$datearray[$i], ";
	    }
      }
      if($datescheckedsent)
	 $dateschecked=$datescheckedsent;
      if($dateschecked==1)
	 $criteria.="Dates Available: $string";
      if($applied=='y' || $applied=='n')
      {
	 $criteria.="Applied to Officiate: ";
	 if($applied=='y') $criteria.="Y, ";
	 else $criteria.="N, ";
      }
      if($assigned=='y' || $assigned=='n')
      {
	 $criteria.="Assigned: ";
	 if($assigned=='y') $criteria.="Y, ";
	 else $criteria.="N, ";
      }
      if($fullyreg=='y' || $fullyreg=='n')
      {
	 $criteria.="Fully Registered: ";
	 if($fullyreg=='y') $criteria.="Y, ";
	 else $criteria.="N, ";
      }
      if($starter=='x') $criteria.=" Track Starter: YES, ";
      $criteria=substr($criteria,0,strlen($criteria)-2);
      if($criteriasent)
	 $criteria=$criteriasent;
      if(substr($criteria,strlen($criteria)-1,1)!=".")
	 $criteria.=".";
      echo $criteria."<br><br><a href=\"apptooff.php?session=$session&sport=$sport\">Search Again</a></div><br>";
      echo "<div class=alert id='exportdiv' style='text-align:center;width:300px;margin:5px;'><a href=\"reports.php?session=$session&filename=apptooff".$sport."export.csv\">Download Export of this Table</a><br>(Wait until page has finished loading.)</div>";
      echo "</caption>";
      $sql=substr($sql,0,strlen($sql)-4);
      if(substr($sql,strlen($sql)-2,2)==" A")
      {
	 $sql=substr($sql,0,strlen($sql)-2);
	 $datesavail=0;
      }
      else	//dates were chosen
      {
	 $sql.=")";
	 $datesavail=1;
      }
      //check sort parameter
      if(!$sort || $sort=="") $sort="last";
      if($sort!="zone")
      {
         $sql.=" ORDER BY t1.$sort";
	 $sqlAPP.=" ORDER BY t1.$sort";
      }
      if($searchquery)
      {
	 $searchquery=ereg_replace("%20"," ",$searchquery);
         $temp=split(" ORDER BY ",$searchquery);
         if($sort!="zone") 
	    $sql=$temp[0]." ORDER BY t1.".$sort;
	 else $sql=$temp[0];
      }
      /*
      if($hiddensql && $hiddensql!="")
      {
	 $sql=$hiddensql;
      }
      */
	  //echo $sql;
      $result=mysql_query($sql);
      if($sport=='fb' || $sport=='sb' || $sport=='vb') $cols=12;
      else $cols=11;
      echo "<tr><td colspan=$cols><b>".mysql_num_rows($result)."</b> Results</td></tr>";
      //echo "<tr><td colspan=8>$sql</td></tr>";
      echo "<tr align=center>";
      echo "<th align=center>Delete</th>";
      echo "<th><a href=\"apptooff.php?session=$session&searchquery=$sql&sort=last&sport=$sport\">Official's Name";
      if($sort=="last") echo " <img src=\"../arrowdown.png\" width=\"15px\">";
      echo "</a><br>(FB: Crew Chief)<br>(Click to E-MAIL)</th>";
      if($sport=='fb')	//have column for contact info
         echo "<td><b>Preferred Contact Info</b></td>";
      else if($sport=='vb')	//show preferred partners
         echO "<th>Preferred<br>Partners</th>";
      echo "<th>Class</th><th>Years</th>";
      echo "<td><a href=\"apptooff.php?session=$session&searchquery=$sql&sort=zone&sport=$sport&datescheckedsent=$dateschecked&criteriasent=$criteria\">Zone";
      if($sort=="zone") echo " <img src=\"../arrowdown.png\" width=\"15px\">";
      echo "</a></td>";
      echo "<td><a href=\"apptooff.php?session=$session&searchquery=$sql&sort=city&datescheckedsent=$dateschecked&criteriasent=$criteria&sport=$sport\">City";
      if($sort=="city") echo " <img src=\"../arrowdown.png\" width=\"15px\">";
      echo "</a></td>";
      if($sport!='tr')
      {
      echo "<td width=150><b>Dates Available</b></td>";
      if($sport=='sb')	//column for available for 8am games
	 echo "<td><b>8:00AM</b></td>";
      if($sport=='vb')
	 echo "<td><b>4:30PM</b></td>";
      if($sport=='sw')
	 echo "<td><b>Positions<br>Preferred</b></td>";
      else
	 echo "<td><b>Conflicts</b></td>";
      }
      else
      {
         echo "<td><b>Wants to be a<br>State Track Starter</b></td>";
      }
      echo "<td><b>Observations</b></td></tr>";
      $csv="\"Official's Name\",\"Email\",";
      if($sport=='vb') $csv.="\"Preferred Partners\",";
      $csv.="\"Class\",\"Year\",\"City\",";
      if($sport=='tr') $csv.="\"Wants to be a State Track Starter\"\r\n";
      else 
      {
	 $csv.="\"Dates Available\",";
	 for($d=0;$d<count($datearray);$d++)
  	 {
	    $csv.="\"".$datearray[$d]."\",";
	 }
	 $csv.="\"Conflicts\"\r\n";
      }
      $ix=0;
      $zones=array(); $names=array(); $offids=array(); $citys=array(); $early=array(); $avail=array();
      $conflicts=array();
      //echo $sql;
      $d=0;
      //assignment tables:
      $contracts=$sport."contracts";
      while($row=mysql_fetch_array($result))
      {
         $sql2="SELECT mailing,class,years FROM $offtable WHERE offid='$row[0]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $curclass=$row2['class']; $curyears=$row2[years]; $curmailing=$row2[mailing];

	 //if $assigned, make sure current official has been assigned
         $sql2="SELECT id FROM $contracts WHERE offid='$row[0]' AND times LIKE '%x%'";
	 $result2=mysql_query($sql2);
	 $show=1;
         if($assigned=='y' && mysql_num_rows($result2)==0)
	    $show=0;
	 else if($assigned=='n' && mysql_num_rows($result2)>0)
	    $show=0;
	 //if $fullyreg, check if official has fully registered
	 $show=1;
	 if($fullyreg=='y' && $curmailing<100) $show=0;
	 else if($fullyreg=='n' && $curmailing>=100) $show=0;
         //show results in a table
            //get current zone
            $sql2="SELECT zone FROM $zonetable WHERE (cities LIKE '".trim(addslashes($row[city])).",%' OR cities LIKE '%, ".trim(addslashes($row[city]))."' OR cities LIKE '%, ".trim(addslashes($row[city])).",%')";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $curzone=$row2[0];
	 if($sort=="zone" && $show==1)	//put in array to sort later
	 {
	    $zones[$ix]=$curzone;
	    $names[$ix]="<a href=\"mailto:$row[emailaddress]\">$row[last], $row[first]</a>";
	    $offids[$ix]=$row[0];
	    if($sport=='fb')
	    {
               $sql2="SELECT t1.last,t1.first,t1.city,t1.email AS emailaddress,t1.homeph,t1.cellph,t1.workph,t2.* FROM officials AS t1,fbapply AS t2 WHERE t1.id=t2.contact AND t2.offid='$row[0]'";
	       $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
	       $contacts[$ix]="";
	       $contacts[$ix].="<b>$row2[first] $row2[last]</b><br>City: $row2[city]<br>";
	       $sql3="SELECT * FROM officials WHERE id='$row2[contact]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
               $contactmethod=0;
               if($row2[homeph]=='x')
               {
                  $contacts[$ix].="Home Ph: (".substr($row3[homeph],0,3).")".substr($row3[homeph],3,3)."-".substr($row3[homeph],6,4)."<br>";
                  $contactmethod=1;
               }
               if($row2[workph]=='x')
               {
                  $contacts[$ix].="Work Ph: (".substr($row3[workph],0,3).")".substr($row3[workph],3,3)."-".substr($row3[workph],6,4)."<br>";
                  $contactmethod=1;
               }
               if($row2[cellph]=='x')
               {
                  $contacts[$ix].="Cell Ph: (".substr($row3[cellph],0,3).")".substr($row3[cellph],3,3)."-".substr($row3[cellph],6,4)."<br>";
                  $contactmethod=1;
               }
               if(trim($row2[otherph])!="")
               {
                  $contacts[$ix].="Other Ph: (".substr($row2[otherph],0,3).")".substr($row2[otherph],3,3)."-".substr($row2[otherph],6,4)."<br>";
                  $contactmethod=1;
               }
               if($row2[email]=='x')
               {
                  $contacts[$ix].="E-mail: <a class=small href=\"mailto:$row3[email]\">$row3[email]</a><br>";
                  $contactmethod=1;
               }
               if(trim($row2[otheremail])!="")
               {
                  $contacts[$ix].="Other E-mail: <a class=small href=\"mailto:$row2[otheremail]\">$row2[otheremail]</a>";
                  $contactmethod=1;
               }
               if($contactmethod==0)
               {
                  $contacts[$ix].="<i>No Preferred Contact Method Indicated.</i><br>Contact Info in Database:<br>";
                  $contacts[$ix].="Home Ph: (".substr($row3[homeph],0,3).")".substr($row3[homeph],3,3)."-".substr($row3[homeph],6,4)."<br>";
                  $contacts[$ix].="Work Ph: (".substr($row3[workph],0,3).")".substr($row3[workph],3,3)."-".substr($row3[workph],6,4)."<br>";
                  $contacts[$ix].="Cell Ph: (".substr($row3[cellph],0,3).")".substr($row3[cellph],3,3)."-".substr($row3[cellph],6,4)."<br>";
                  $contacts[$ix].="E-mail: <a class=small href=\"mailto:$row3[email]\">$row3[email]</a><br>";
               }
	    }
            else if($sport=='vb')
	    {
	       $sql2="SELECT * FROM ".$sport."apply WHERE offid='$row[0]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $partners="";
	       if($row2[partner1]!='' || $row2[partner2]!='') 
	       {
	          if($row2[partner1]!='')  $partners.="$row2[partner1], ";
	          if($row2[partner2]!='')  $partners.="$row2[partner2], ";
	          $partners=substr($partners,0,strlen($partners)-2);
	       }
	       $prefpartners[$ix]=$partners;
	    }
	       
	    $citys[$ix]=$row[city];
            $classes[$ix]=$curclass; $yearses[$ix]=$curyears;
	    $dateavails[$ix]=""; $csv.="\"\",";
	    for($i=0;$i<$ct;$i++)
	    {
	       $index2=$i+1;
	       $index="date".$index2;
	       //if($row[$index]=='x' && (ereg($datearray[$i].",",$criteria) || ereg($datearray[$i].".",$criteria) || $$index=='x' || $dateschecked==0))
	       if($row[$index]=='x')
	       {
	          $dateavails[$ix].=$datearray[$i].", ";
	          $csv.="\"X\",";
	       }
	       else $csv.="\"\",";
	    }
	    if($sport=='sb')
	    {
	       if($row[early]=='y') $early[$ix]="Yes";
	       else $early[$ix]="No";
	    }
	    if($sport=='vb')
	    {
	       if($row[available]=='y') $avail[$ix]="YES";
	       else $avail[$ix]="No";
	    }
	    if($sport=='sw') 
	    {
	       $conflicts[$ix]=$row[position1];
               if($row[position2]!='') $conflicts[$ix].=", $row[position2]";
	       if($row[position3]!='') $conflicts[$ix].=", $row[position2]";
	    }
            else $conflicts[$ix]=$row[conflict];
	    $dateavails[$ix]=substr($dateavails[$ix],0,strlen($dateavails[$ix])-2);
	    $ix++;
	 }
	 else if($show==1)
	 {
            echo "<tr valign=top align=left>";
	    echo "<input type=hidden name=\"deloffid[$d]\" value=\"$row[0]\">";
	    echo "<td align=center><input type=checkbox name=\"delete[$d]\" value='x'>";
	    if($sport!="fb")
	    {
               echo "<td><a href=\"mailto:$row[emailaddress]\">".trim($row[last]).", ".trim($row[first])."</a>";
	       $hphone=FormatPhone($row[homeph]);
	       if($hphone!='') echo "<br>(H) $hphone";
               $wphone=FormatPhone($row[workph]);
               if($wphone!='') echo "<br>(W) $wphone";
               $cphone=FormatPhone($row[cellph]);
               if($cphone!='') echo "<br>(C) $cphone";
	       echo "<br>&nbsp;&nbsp;<a target=\"_blank\" class=small href=\"edit_sport.php?session=$session&id=$row[0]&sport=$sport\">".strtoupper($sport)." Screen</a>";
	       $searchquery2=ereg_replace("\'","\'",$sql);
               echo "<br>&nbsp;&nbsp;<a class=small href=\"".$sport."app.php?session=$session&givenoffid=$row[0]&searchquery=$searchquery2&sort=$sort\" target=\"_blank\">Application</a><br>";
               echo "&nbsp;&nbsp;<a class=small href=\"schedule.php?session=$session&sport=$sport&givenoffid=$row[0]\" target=\"_blank\">Schedule</a>";
               echo "</td>";
		$csv.="\"".trim($row[last]).", ".trim($row[first])."\",";
	    }
	    else	
	    {
	       $searchquery2=ereg_replace("\'","\'",$sql);
	       echo "<td>";
	       if($row[offid]!=$row[chief])
	       {
	          echo "<b>CREW CHIEF:</b>".GetOffName($row[chief])."<br>";
		  echo "<b>Submitted by:</b>".GetOffName($row[offid]);
	       }
	       else echo "<b>".GetOffName($row[chief])."</b>";
               $hphone=FormatPhone($row[homeph]);
               if($hphone!='') echo "<br>(H) $hphone";
               $wphone=FormatPhone($row[workph]);
               if($wphone!='') echo "<br>(W) $wphone";
               $cphone=FormatPhone($row[cellph]);
               if($cphone!='') echo "<br>(C) $cphone";
               echo "<br>&nbsp;&nbsp;<a target=\"_blank\" class=small href=\"edit_sport.php?session=$session&id=$row[chief]&sport=$sport\">".strtoupper($sport)." Screen</a>";
	       echo "<br><a class=small href=\"fbapp.php?session=$session&givenoffid=$row[offid]&header=no\" target=\"_blank\">Application</a><br>";
	       echo "<a class=small href=\"schedule.php?session=$session&sport=$sport&givenoffid=$row[0]\" target=\"_blank\">Schedule</a><br>";
	       echo "</td>";
	 	$csv.="\"".GetOffName($row[chief])."\",";
	    }
	    if($sport=='fb')	//show contact info
	    {
	       $sql2="SELECT t1.last,t1.first,t1.city,t1.email as emailaddress,t1.homeph,t1.cellph,t1.workph,t2.* FROM officials AS t1,fbapply AS t2 WHERE t1.id=t2.contact AND t2.offid='$row[offid]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       echo "<td><b>$row2[first] $row2[last]</b><br>City: $row2[city]<br>";
	       $sql3="SELECT * FROM officials WHERE id='$row2[contact]'";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       $contactmethod=0;
	       if($row2[homeph]=='x')
	       {
		  echo "Home Ph: (".substr($row3[homeph],0,3).")".substr($row3[homeph],3,3)."-".substr($row3[homeph],6,4)."<br>";
	   	  $contactmethod=1;
	       }
	       if($row2[workph]=='x')
	       {
		  echo "Work Ph: (".substr($row3[workph],0,3).")".substr($row3[workph],3,3)."-".substr($row3[workph],6,4)."<br>";
                  $contactmethod=1;
               }
	       if($row2[cellph]=='x')
	       {
		  echo "Cell Ph: (".substr($row3[cellph],0,3).")".substr($row3[cellph],3,3)."-".substr($row3[cellph],6,4)."<br>";
                  $contactmethod=1;
               }
	       if(trim($row2[otherph])!="")
	       {
		  echo "Other Ph: (".substr($row2[otherph],0,3).")".substr($row2[otherph],3,3)."-".substr($row2[otherph],6,4)."<br>";
                  $contactmethod=1;
               }
	       if($row2[email]=='x')
	       {
		  echo "E-mail: <a class=small href=\"mailto:$row3[email]\">$row3[email]</a><br>";
                  $contactmethod=1;
               }
	       if(trim($row2[otheremail])!="")
	       {
		  echo "Other E-mail: <a class=small href=\"mailto:$row2[otheremail]\">$row2[otheremail]</a>";
                  $contactmethod=1;
               }
	       if($contactmethod==0)
	       {
		  echo "<i>No Preferred Contact Method Indicated.</i><br>Contact Info in Database:<br>";
                  echo "Home Ph: (".substr($row3[homeph],0,3).")".substr($row3[homeph],3,3)."-".substr($row3[homeph],6,4)."<br>";
                  echo "Work Ph: (".substr($row3[workph],0,3).")".substr($row3[workph],3,3)."-".substr($row3[workph],6,4)."<br>";
                  echo "Cell Ph: (".substr($row3[cellph],0,3).")".substr($row3[cellph],3,3)."-".substr($row3[cellph],6,4)."<br>";
                  echo "E-mail: <a class=small href=\"mailto:$row3[email]\">$row3[email]</a><br>";
	       }
	       echo "</td>";
	    }
	    else
	    {
	       $csv.="\"$row[emailaddress]\",";
	    }
            if($sport=='vb')
            { 
               $sql2="SELECT * FROM ".$sport."apply WHERE offid='$row[0]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $partners="";
               if($row2[partner1]!='' || $row2[partner2]!='')
	       {
                  if($row2[partner1]!='')  $partners.="$row2[partner1], ";
                  if($row2[partner2]!='')  $partners.="$row2[partner2], ";
                  $partners=substr($partners,0,strlen($partners)-2);
	       }
	       echo "<td>$partners</td>"; 
	       $csv.="\"$partners\",";
            }
            echo "<td align=center>$curclass&nbsp;</td>";
            echo "<td align=center>$curyears&nbsp;</td>";
            echo "<td>$curzone&nbsp;</td>";
            echo "<td>$row[city]</td>";
	    $csv.="\"$curclass\",\"$curyears\",\"$row[city]\",";
	    if($sport!='tr')
	    {
	    $dateavail=""; $csv.="\"\",";
	    for($i=0;$i<$ct;$i++)
	    {
	       $index2=$i+1; $index="date".$index2;
	       //if($row[$index]=='x' && (ereg($datearray[$i].",",$criteria) || ereg($datearray[$i].".",$criteria) || $dateschecked==0 || $$index=='x'))
	       if($row[$index]=='x') 
	       {
	          $dateavail.=$datearray[$i].", ";
	   	  $csv.="\"X\",";
	       }
	       else $csv.="\"\",";
	    }
	    $dateavail=substr($dateavail,0,strlen($dateavail)-2);
            echo "<td width=150>$dateavail&nbsp;</td>";
	    //$csv.="\"$dateavail\",";
	    if($sport=='sb')	//show if available for 8am games
	    {
	       echo "<td>";
	       if($row[early]=='y')
		  echo "YES";
	       else echo "No";
	       echo "</td>";
	    }
	    else if($sport=='vb')	//show if available for 4:30pm games
	    {
	        if($row[available]=='y')
	           echo "<td>YES</td>";
	        else echo "<td>No</td>";
	    }

	    //conflicts column:
	    echo "<td width=200>";
            if($sport=='sw')
	    {
	       $conflict=$row[position1];
	       if($row[position2]!='') $conflict.=", $row[position2]";
	       if($row[position3]!='') $conflict.=", $row[position3]";
     	    }
	    else $conflict="$row[conflict]";
	    echo "$conflict</td>";
	    $csv.="\"$conflict\",";
            }
	    else	//TRACK
	    {
	       echo "<th align=center>".strtoupper($row[starter])."</th>";
		$csv.="\"$row[starter]\",";
	    }
	    $csv.="\r\n";

	    //observed field
	    $schtable=$sport."sched";
	    //get link to evaluation forms already submitted and a link to fill out new eval
                  //get submitted observations and fill out new ones                  $schtable=$sport."sched";                  
            $obstable=$sport."observe";                  
            $sql2="SELECT t1.obsid,t1.gameid,t2.first,t2.last,t3.offdate,t1.dateeval FROM $obstable AS t1, observers AS t2,$schtable AS t3 WHERE t1.obsid=t2.id AND t1.gameid=t3.id AND t1.offid='$row[offid]' ORDER by t1.dateeval";                  
            $result2=mysql_query($sql2);                  
	    echo "<td>";
	    while($row2=mysql_fetch_array($result2))
	    {
	       echo "<a class=small href=\"".$sport."observe.php?session=$session&offid=$row[0]&obsid=$row2[0]&gameid=$row2[1]\" target=\"_blank\">$row2[offdate] ($row2[first] $row2[last])";
	       if($row2[dateeval]!='')
		  echo " (Submitted)";
	       else
		  echo " (Saved)";
	       echo "</a><br>";
	    }
	    echo "<a class=small href=\"".$sport."observe.php?session=$session&gameid=new&offid=$row[0]&obsid=1\" target=\"_blank\">Fill out Evaluation</a>";
            echo "</td></tr>";
	    $d++;
	 }
      }
      if($sort=="zone")	//show in zone order
      {
	 $sql="SELECT DISTINCT zone FROM $zonetable ORDER BY zone";
	 $result=mysql_query($sql);
	 $d=0;
	 while($row=mysql_fetch_array($result))
	 {
	    for($i=0;$i<$ix;$i++)
	    {
	       if($zones[$i]==$row[0])	//show this result
	       {
		  echo "<tr valign=top align=left>";
		  echo "<input type=hidden name=\"deloffid[$d]\" value=\"$offids[$i]\">";
		  echo "<td align=center><input type=checkbox name=\"delete[$d]\" value='x'></td>";
		  echo "<td>$names[$i]";
	          $sql2="SELECT * FROM officials WHERE id='$offids[$i]'";
	          $result2=mysql_query($sql2);
	          $row2=mysql_fetch_array($result2);
               $hphone=FormatPhone($row2[homeph]);
               if($hphone!='') echo "<br>(H) $hphone";
               $wphone=FormatPhone($row2[workph]);
               if($wphone!='') echo "<br>(W) $wphone";
               $cphone=FormatPhone($row2[cellph]);
               if($cphone!='') echo "<br>(C) $cphone";
               echo "<br>&nbsp;&nbsp;<a target=\"_blank\" class=small href=\"edit_sport.php?session=$session&id=$row2[id]&sport=$sport\">".strtoupper($sport)." Screen</a>";
	          echo "<br>&nbsp;&nbsp;";
		  echo "<a class=small href=\"".$sport."app.php?session=$session&givenoffid=$offids[$i]\" target=\"_blank\">Application</a><br>";
		  echo "&nbsp;&nbsp;<a class=small href=\"schedule.php?session=$session&sport=$sport&givenoffid=$offids[$i]\" target=\"_blank\">Schedule</a>";
		  if($sport=='fb')
		  {
		     echo "</td><td>$contacts[$i]";
		  }
		  echo "</td>";
		  $csv.="\"$names[$i]\",";
	          if($sport=='vb')
	             echo "<td>$prefpartners[$i]</td>";
	          echo "<td align=center>$classes[$i]</td>";
                  echo "<td align=center>$yearses[$i]</td><td>$zones[$i]</td><td>$citys[$i]</td>";
		  echo "<td width=200>$dateavails[$i]</td>";
		  if($sport=='sb')
		     echo "<td>$early[$i]</td>";
	          else if($sport=='vb')
	   	     echo "<td>$avail[$i]</td>";
		  echo "<td width=200>$conflicts[$i]</td>";
		  $csv.="\"$classes[$i]\",\"$yearses[$i]\",\"$citys[$i]\",\"$dateavails[$i]\",\"$conflicts[$i]\"\r\n";
                  //observed field
		  $schtable=$sport."sched";
		  //get link to evaluation forms already submitted and a link to fill out new eval
		  $sql2="SELECT t1.obsid,t1.gameid,t2.name,t3.offdate,t1.dateeval FROM $obstable AS t1, observers AS t2,$schtable AS t3 WHERE t1.obsid=t2.id AND t1.gameid=t3.id AND t1.offid='$offids[$i]' ORDER BY t1.obsid,t1.dateeval";
	          $result2=mysql_query($sql2);
		  echo "<td>";
	          while($row2=mysql_fetch_array($result2))
	          {
		      echo "<a class=small href=\"".$sport."observe.php?session=$session&offid=$offids[$i]&obsid=$row2[0]&gameid=$row2[1]\" target=\"_blank\">$row2[offdate] ($row2[name])";
		      if($row2[dateeval]!='')
 			 echo " (Submitted)";
		      else
			 echo " (Saved)";
		      echo "</a><br>";
		  }
		  echo "<a class=small href=\"".$sport."observe.php?session=$session&gameid=new&offid=$row[0]&obsid=1\" target=\"_blank\">Fill out Evaluation</a>";
		  echo "</td></tr>";
		  $d++;
	       }
	    }
	 }
      }
      echo "<tr align=left><td colspan=$cols><input type=submit name=submit value=\"Delete\">";
      echo "&nbsp;(Click this button to delete the applications in the rows you have checked.)";
      echo "</td></tr>";
      echo "</table>";
      echo "</form>";
      //}
      if($applied!='y')
      {
	 $resultAPP=mysql_query($sqlAPP);
	 //echo $sqlAPP;
	 echo "<br><br>";
	 echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;width:500px;\" class='nine'>";
	 echo "<caption><b>Officials who have NOT submitted an Application (Check Registration Status):</b></caption>";
	 echo "<tr align=center><td><b>Name</b></td><td><b>SSN#</b></td><td><b>City</b></td>";
	 echo "</tr>";
	 while($rowAPP=mysql_fetch_array($resultAPP))
	 {
	    $sql="SELECT * FROM $table WHERE offid='$rowAPP[id]'";
	    $result=mysql_query($sql);
	    if(mysql_num_rows($result)==0)
	    {
	    echo "<tr valign=top align=left>";
	    echo "<td><a href=\"mailto:$rowAPP[email]\">$rowAPP[first] $rowAPP[last]</a>";
               $hphone=FormatPhone($rowAPP[homeph]);
               if($hphone!='') echo "<br>(H) $hphone";
               $wphone=FormatPhone($rowAPP[workph]);
               if($wphone!='') echo "<br>(W) $wphone";
               $cphone=FormatPhone($rowAPP[cellph]);
               if($cphone!='') echo "<br>(C) $cphone";
               echo "<br>&nbsp;&nbsp;<a target=\"_blank\" class=small href=\"edit_sport.php?session=$session&id=$rowAPP[id]&sport=$sport\">".strtoupper($sport)." Screen</a>";
	    echo "<br><a class=small href=\"".$sport."app.php?session=$session&givenoffid=$rowAPP[id]\" target=\"_blank\">Application</a><br>";
	    echo "<a class=small href=\"schedule.php?session=$session&sport=$sport&givenoffid=$rowAPP[id]&edit=yes\" target=\"_blank\">Schedule</a>";
	    echo "</td>";
	    echo "<td>$rowAPP[socsec]</td>";
	    echo "<td>$rowAPP[city]";
	    echo "</td>";
	    echo "</tr>";
	    }
	 }
	 echo "</table>";
      }
   }
 
   if($csv!='')
   {
   $open=fopen(citgf_fopen("/home/nsaahome/reports/apptooff".$sport."export.csv"),"w");
   if(!fwrite($open,$csv)) { echo "ERROR: COULD NOT WRITE TO EXPORT FILE.<br>"; }
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/apptooff".$sport."export.csv");
   }
}//end if($level==1)
echo "<br><a href=\"apptooff.php?sport=$sport&session=$session\" class=small>Return to App to Off Admin</a>";
echo "&nbsp;&nbsp;&nbsp;<a class=small href=\"welcome.php?session=$session\">Return Home</a>";
echo $end_html;
?>
