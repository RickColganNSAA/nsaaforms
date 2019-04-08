<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);
if(!$sport || $sport=='')
{
   echo "You must select a sport.";
   exit();
}

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);
$sportname=GetActivityName($sport);

echo $init_html;
echo $header;

$districts=$sport."districts";
$disttimes=$sport."disttimes";
if($sport=="tr_b") $districts="trbdistricts";
else if($sport=="tr_g") $districts="trgdistricts";
switch($sport)
{
   case 'bbb':
      $sport='bb_b';
      break;
   case 'bbg':
      $sport='bb_g';
      break;
   case 'sob':
      $sport='so_b';
      break;
   case 'sog':
      $sport='so_g';
      break;
   case 'trb':
      $sport="tr_b";
      break;
   case 'trg':
      $sport="tr_g";
      break;
   default:
      $sport=$sport;
}

echo "<br>";
//make sure user is host of a district in this sport
$sql="SELECT id FROM logins WHERE school='$school2'";
if($sport!='sp') $sql.=" AND level='$level'";
else $sql.=" AND level='2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($disttimesid>0)
{
   if ($sport=='ba')
   $sql="SELECT t1.*,t2.type,t2.class,t2.district,t2.sids FROM $db_name2.$disttimes AS t1,$db_name2.$districts AS t2 WHERE t1.distid=t2.id AND t1.hostid='$row[0]' AND t1.id='$disttimesid'";
   else
   $sql="SELECT t1.*,t2.type,t2.class,t2.district FROM $db_name2.$disttimes AS t1,$db_name2.$districts AS t2 WHERE t1.distid=t2.id AND t1.hostid='$row[0]' AND t1.id='$disttimesid'";
}
else
{
   $sql="SELECT * FROM $db_name2.$districts WHERE (hostid='$row[0]'";
   if(ereg("bb",$sport)) $sql.=" OR hostid2='$row[0]'";
   $sql.=" OR hostschool='$school2'";
   $sql.=") AND id='$distid'";
}
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "<br>You are not the host of a $sportname District.";
   exit();
}
else if(!PastDue(GetDueDate($sport),0) && $sport!='pp' && $sport!='sp' && $secret!=1)
{
   $date=split("-",GetDueDate($sport));
   echo "<br>You will not be able to view the entry forms of the schools in your district until after the due date ($date[1]/$date[2]/$date[0] at midnight).";
   exit();
}
else if($sport=='pp' || $sport=='sp')	//allow district to view these forms on date indicated in database
{
   $showdate=GetDueDate($sport."showentries");
   if(!PastDue($showdate,0))
   {
      $date=split("-",$earlydate);
      echo "<br>You will be able to view the entry forms of the schools in your district after <b>$date[1]/$date[2]/$date[0]</b> at midnight.";
      exit();
   }
}

//else, school is a host and it is 2 days past due date of entry form...
$row=mysql_fetch_array($result);
echo "<table cellspacing=2 cellpadding=3 width=400><caption><b>$sportname District Entry Forms for $row[type] $row[class]-$row[district]";
/* if($disttimesid>0)
   echo ", Game $row[gamenum]"; */
echo ":</b><br>(In Printer-Friendly/E-mail Version)</caption>";
if($row[type]=="District Final") 
{
   $schools=split("VS",$row[schools]);
   if(count($schools)==1)
      $schools=split("vs.",$row[schools]);
   $sids=array(); 
   
   for($i=0;$i<count($schools);$i++)
   {
      $schools[$i]=(explode("(#",$schools[$i]));
      $schools[$i]=$schools[$i][0];
	  $schools[$i]=trim($schools[$i]);
      $sids[$i]=GetSID2($schools[$i],$sport);
   }
}
else if($disttimesid>0 && $sport!='ba')
{
/*    $sql2="SELECT * FROM ".preg_replace("/_/","",$sport)."sched WHERE distid='$row[distid]' AND gamenum='$row[gamenum]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sids=array($row2[sid],$row2[oppid]);
   $schools=array(GetMainSchoolName($row2[sid],$sport),GetMainSchoolName($row2[oppid],$sport)); */
    
    $sql2="SELECT * FROM ".preg_replace("/_/","",$sport)."sched WHERE distid='$row[distid]' AND (gamenum=1 OR gamenum=2)"; 
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2)){
   $sids[]=$row2[sid];
   $sids[]=$row2[oppid];
   $schools[]=GetMainSchoolName($row2[sid],$sport);
   $schools[]=GetMainSchoolName($row2[oppid],$sport);
   }
}
else
{
   $schools=split(",",$row[schools]);
   if($row[sids]!='')
   {
     $sids=split(",",$row[sids]);
      for($i=0;$i<count($sids);$i++)
      {
	 $schools[$i]=GetMainSchoolName($sids[$i],$sport);
      }
   }
}
if($sport=='pp' || $sport=='sp')
{
   $duedate=split("-",GetDueDate($sport));
   echo "<tr align=center><td><table class=nine width=400><tr align=left><td>";
   echo "<b>PLEASE NOTE:</b> <i>These entry forms are not locked until $duedate[1]/$duedate[2]/$duedate[0] at midnight.  Therefore, the forms below can be edited by the schools in your district until that date.  If you print off these forms before that date, you may want to check back to see if any changes have been made.</i></td></tr></table>";
   if($sport=='sp')	//SHOW LINK TO EXPORT
   {
      echo "<div class=alert style='width:550px;text-align:center;'><a href=\"sp/exportdistrictspeech.php?session=$session&distid=$distid\">Download a Comma-Delimited (Excel) File of all Entry Forms for $row[type] $row[class]-$row[district]</a></div>";
   }
   echo "</td></tr>";
}
echo "<tr align=center><td><table class=nine frame=all rules=all style=\"border:#d0d0d0 1px solid;width:600px;\" cellspacing=0 cellpadding=5>";

for($i=0;$i<count($schools);$i++)
{
   $cursch=trim($schools[$i]); 
   $cursch=(explode("(#",$cursch));
   $cursch=$cursch[0];
   if(count($sids)>0)
   {    
      if($sids[$i]>0)
         $school_ch=GetMainSchoolName($sids[$i],$sport);
      else $error="Team TBD";
   }
   else
   {
      if(trim($cursch)!='')
      {
         $cursch2=addslashes($cursch);
         $table=GetSchoolsTable($sport);
         $sql="SELECT * FROM $table WHERE school='$cursch2'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if(mysql_num_rows($result)>0)
	    $school_ch=GetMainSchoolName($row[sid],$sport);
         else
            $school_ch=$cursch;
      }
      else $error="Team TBD";
   }
   $dir=GetActivityAbbrev($sportname);
   echo "<tr align=left><td>";
   if($error!='')
   {
      echo "$error";
   }
   else if(trim($school_ch)=="")
   {
      echo "Error: $cursch $sportname Team doesn't have a head school associated with it in the system. Please contact the NSAA. Either the team is missing from or incomplete in the list of $sportname schools, or the team is improperly entered on the list of teams for this tournment.";
   }
   else if(ereg("tr",$sport))
   {
      echo "<a target=new href=\"tr/view_tr_g.php?school_ch=$school_ch&session=$session&director=1&print=1\">$cursch (Girls)</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
      echo "<a target=\"_blank\" href=\"tr/teamlist_g.php?school_ch=$school_ch&session=$session&director=1\">Eligibility List (Girls)</a><br>";
      echO "<a target=new href=\"tr/view_tr_b.php?school_ch=$school_ch&session=$session&director=1&print=1\">$cursch (Boys)</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
      echo "<a target=\"_blank\" href=\"tr/teamlist_b.php?school_ch=$school_ch&session=$session&director=1\">Eligibility List (Boys)</a><br>";
   }
   else
   { 
   $qry1="SELECT name,email FROM logins WHERE school='".addslashes($school_ch)."' AND level=2";
   $res1=mysql_query($qry1);
   $row1=mysql_fetch_array($res1);
   $qry2="SELECT name,email FROM logins WHERE school='".addslashes($school_ch)."' AND sport='$sportname'";
   $res2=mysql_query($qry2);
   $row2=mysql_fetch_array($res2);
	if($sport=='ccb') $sport="cc_b";
        if($sport=='ccg') $sport="cc_g";

   echo "<a target=new href=\"$dir/view_$sport.php?school_ch=$school_ch&session=$session&director=1&print=1\">$cursch</a></td>
	   <td>AD: ".$row1[name]."<br><a class=small href='mailto:$row1[email]'>$row1[email]</a>&nbsp;";
   echo "</td><td>Coach: $row2[name]<br><a class=small href=\"mailto:$row2[email]\">$row2[email]</a>&nbsp;";
   }
      
   echo "</td></tr>";
}
echo "</table></td></tr></table><br><br>";

echo "<a href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
