<?php
//swadmin.php: NSAA SW Admin Page: advanced search on swimming season bests

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

if($search=="Search" && $schch!="Choose School")
{
   echo $init_html;
   echo $header;

   echo "<center><br><a href=\"swadmin.php?session=$session\" class=small>Return to Swimming Advanced Search</a><br><br><table>";

   //create query:
   $cursch=split(",",$schch);
   $schname=$cursch[0];
   $schname2=ereg_replace("\'","\'",$schname);
 
   if(!ereg("All",$studch))
   {
      $sql="SELECT first,last FROM eligibility WHERE id='$studch'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $student="$row[0] $row[1]";
   }
   else
   {
      $student=$studch;
   }
   echo "<caption>Search Results for School: <b>$schname</b>, Student(s): <b>$student</b>, Event(s): <b>$eventch</b><hr></caption>";

   $qual=array();
   $qix=0;
   if($eventch=="All Events")
   {
      $cur_sw_events=$sw_events;
   }
   else
   {
      $cur_sw_events[0]=$eventch;
   }

   for($i=0;$i<count($cur_sw_events);$i++)
   {
      $tables=array();
      if($studch=="All Students")
      {
         $tables[0]="sw_verify_perf_g";
         $tables[1]="sw_verify_perf_b";
      }
      else if($studch=="All Girls")
      {
         $tables[0]="sw_verify_perf_g";
      }
      else if($studch=="All Boys")
      {
         $tables[0]="sw_verify_perf_b";
      }
      else
      {
         //get gender of selected student
         $sql="SELECT gender FROM eligibility WHERE id='$studch'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if($row[0]=="M")
            $tables[0]="sw_verify_perf_b";
         else
            $tables[0]="sw_verify_perf_g";
      }
      for($x=0;$x<count($tables);$x++)
      {
         if(ereg("_b",$tables[$x])) $gender="m";
         else $gender="f";
         $sql="SELECT * FROM $tables[$x] WHERE school='$schname2' AND event='$cur_sw_events[$i]'";
         if(!ereg("All",$studch))
         {
            $sql.="AND (studentid='$studch' OR studentid LIKE '$studch/%' OR studentid LIKE '%/$studch')";
	 }
	 $sql.=" ORDER BY performance";
	 if(ereg("Diving",$cur_sw_events[$i]))
	    $sql.=" DESC";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    $qual[id][$qix]=$row[0];
	    $qual[event][$qix]=$cur_sw_events[$i];
	    $qual[sch][$qix]=$schname;
	    if($cur_sw_events[$i]!="Diving")
	    {
	       $qual[mark][$qix]=ConvertFromSec($row[5]);
	       $qual[marksec][$qix]=$row[5];
	    }
	    else
	    {
	       $qual[mark][$qix]=$row[5];
	       $qual[marksec][$qix]=$row[5];
	    }

            //get name(s)
	    if(!ereg("Relay",$cur_sw_events[$i]))
	    {
	       $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[4]'";
               $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $qual[name][$qix]="$row2[0] $row2[1] (".GetYear($row2[2]).")";
	    }
	    else
	    {
	       $temp=split("/",$row[4]);
	       $qual[name][$qix]="";
	       for($j=0;$j<count($temp);$j++)
	       {
	          $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$temp[$j]'";
	          $result2=mysql_query($sql2);
	          $row2=mysql_fetch_array($result2);
	          $qual[name][$qix].="$row2[0] $row2[1] (".GetYear($row2[2])."), ";
	       }
	       $qual[name][$qix]=substr($qual[name][$qix],0,strlen($qual[name][$qix])-2);
	    }

            //get meet
	    $newtable=ereg_replace("_perf","",$tables[$x]);
	    if($row[1]==0)
	    {
	       $qual[meet][$qix]=$row["meet"];
	    }
	    else
	    {
	       $sql2="SELECT meet FROM $newtable WHERE id='$row[1]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $qual[meet][$qix]=$row2[0];
	    }

            //get type of qualfication, if any
	    if($gender=="f") $curevent="Girls ".$cur_sw_events[$i];
	    else $curevent="Boys ".$cur_sw_events[$i];
	    $qualtype=DoesQualify($curevent,$qual[mark][$qix]);
	    if($qualtype=="no") $qualtype="&nbsp;";
	    $qual[qualtype][$qix]=$qualtype;

            $qix++;
         }   //end while loop
      }  //end for (tables) loop

      //show this event's qualifiers:

      $curct=1;
      echo "<tr align=left><th class=smaller align=left colspan=6><br><i>$cur_sw_events[$i]</i>:</th></tr>";
      echo "<tr align=left valign=bottom><th colspan=2 class=smaller align=left>Name (Grade)</th>";
      echo "<th class=smaller align=left>School</th>";
      echo "<th class=smaller align=left>Mark</th>";
      echo "<th class=smaller align=left>Meet</th>";
      echo "<th class=smaller align=left>Automatic/<br>Secondary</th></tr>";
      $usednames=array();
      $uix=0;
         for($l=0;$l<$qix;$l++)
         {
	       $used=0;
	       /*
	       for($k=0;$k<count($usednames);$k++)    //check that this student isn't already listed
	       {
	          if(!ereg("Relay",$cur_sw_events[$i]) && $usednames[$k]==$qual[name][$l])
	 	  $used=1;
		  else if(ereg("Relay",$cur_sw_events[$i]) && $usednames[$k]==$qual[sch][$l])
		  $used=1;
	       }
	       */
	       if($used==0) // || (ereg("Relay",$cur_sw_events[$i]) && $qual[qualtype][$l]=="Automatic"))
	       {
	          //non-relays: only show fastest time
	          //relays: show all auto-qualifiers but only one secondary if no auto is made
	          echo "<tr align=left><td>$curct.</td><td>".$qual[name][$l]."</td><td>".$qual[sch][$l]."</td><td>".$qual[mark][$l]."</td><td>";
	          echo $qual[meet][$l]."</td><td>".$qual[qualtype][$l]."</tr>";
	          $usednames[$uix]=$qual[name][$l];
	          if(ereg("Relay",$cur_sw_events[$i]))
	          {
	             $usednames[$uix]=$qual[sch][$l];
	   	  }
		  $uix++;
		  $curct++;
	       } 
	       $qual[marksec][$l]="";
	 }
      unset($usednames);
      $qix=0;
   } //end for each event
   echo "</table><br><a href=\"swadmin.php?session=$session\" class=small>Return to Swimming Advanced Search</a>&nbsp;&nbsp;&nbsp;<a href=\"welcome.php?session=$session\" class=small>Return Home</a>";

   echo $end_html;
   exit();
}

echo $init_html;
echo $header;

echo "<br><br><a class=small href=\"welcome.php?session=$session&toggle=menu3&menu3sport=Swimming\">Return to Home-->Swimming</a><br><br>";
echo "<form method=post action=\"swadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=10><caption><b>Swimming Season Bests Advanced Search:<br><br></b></caption>";
echo "<tr align=\"left\" bgcolor=#E0E0E0><th align=left>School:</th>";
echo "<td><select name=schch onchange='submit();'><option>Choose School";

//get list of swimming schools
$sql="SELECT school, hytekabbr,sid FROM swschool ORDER BY school";
$result=mysql_query($sql);
$i=0;
$sch=array();
while($row=mysql_fetch_array($result))
{
   $sch[name][$i]=$row[0];
   $sch[abbr][$i]=$row[1];
   $sch[sid][$i]=$row[2];
   $i++;
}

for($i=0;$i<count($sch[name]);$i++)
{
   echo "<option value=\"".$sch[sid][$i]."\"";
   if($schch==$sch[sid][$i]) echo " selected";
   echo ">".$sch[name][$i];
}
echo "</select></td></tr>";
echo "<tr align=left><th align=left>Students:</th>";
echo "<td><select name=studch><option selected>All Students<option>All Boys<option>All Girls";

//get list of eligible swimmers for this school/co-op
$sql="SELECT * FROM swschool WHERE sid='$schch'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql="SELECT t1.* FROM eligibility AS t1, headers AS t2 WHERE t1.school=t2.school AND (t2.id='$row[mainsch]'";
if($row[othersch1]>0) 
   $sql.=" OR t2.id='$row[othersch1]'";
if($row[othersch2]>0)
   $sql.=" OR t2.id='$row[othersch2]'";
if($row[othersch3]>0)
   $sql.=" OR t2.id='$row[othersch3]'";
$sql.=") AND t1.sw='x' AND t1.eligible='y' ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\">$row[last], $row[first] $row[middle] (".GetYear($row[semesters]).")";
}
echo "</select></td></tr>";

echo "<tr align=left bgcolor=#E0E0E0><th align=left>Event:</th>";
echo "<td><select name=eventch><option selected>All Events";
for($i=0;$i<count($sw_events);$i++)
{
   echo "<option>$sw_events[$i]";
}
echo "</select></td></tr>";

echo "<tr align=center><td colspan=2>";
echo "<input type=submit name=search value=\"Search\">&nbsp;&nbsp;";
echo "</td></tr>";
echo "</table></form></center>";

echo $end_html;
?>
