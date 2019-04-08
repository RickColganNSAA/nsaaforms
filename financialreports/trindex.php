<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../welcome.php?session=$session");
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

if($submit=='Delete Checked')	//delete checked schools' forms
{
   for($i=0;$i<count($trsch);$i++)
   {
      if($trdelete[$i]=='y' || $deleteall=='x')
      {
	 $sql="DELETE FROM finance_tr WHERE id='$trsch[$i]'";
	 $result=mysql_query($sql);
      }
      // else
      // {
	 // $sql="UPDATE finance_tr SET note='$trnote[$i]' WHERE id='$trsch[$i]'";
	 // $result=mysql_query($sql);
      // }
   }
}
elseif($submit=='Save Notes')
{
   for($i=0;$i<count($trsch);$i++)
   {
	 $sql="UPDATE finance_tr SET note='$trnote[$i]' WHERE id='$trsch[$i]'";
	 $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"trindex.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<table width=100% cellspacing=0 cellpadding=1><caption><br></caption>";

//show list of choices for each sport:
   echo "<tr align=center valign=top>";
   echo "<td>";
   $cursport=GetActivityAbbrev2($sports[$i]);
   $sql="SELECT DISTINCT class FROM $db_name2.trbdistricts WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   $classes=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $classes[$i]=$row['class']; $i++;
   }
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=1";
   echo "><caption align=left><b>Track & Field Financial Reports:</b><br><br><a class=small target='_blank' href='trfinanceexport.php?session=$session'>Export Track & Field Financial Reports</a></caption>";
      echo "<tr align=left>";
      if($level==1) echo "<td><b>Class</b></td>";
      echo "<td><b>District</b></td></tr>";
   $vix=0; $fix=0;
   for($j=0;$j<count($classes);$j++)
   {
	 if($level==1)
	 {
            echo "<tr align=left valign=top><th class=smaller>$classes[$j]</th>";
	    $rnd=2; 
	       echo "<td>";
	       $sql="SELECT t1.school,t1.id,t1.note,t1.distid,t2.class,t2.type,t2.district FROM finance_tr AS t1,$db_name2.trbdistricts AS t2 WHERE t1.distid=t2.id AND t2.class='$classes[$j]' AND t1.round='$rnd' ORDER BY t2.type,t2.district";
	       $result=mysql_query($sql);
	       while($row=mysql_fetch_array($result))
	       {
		  //checkbox to delete
		  echo "<input type=checkbox name=\"trdelete[$vix]\" value='y'>&nbsp;";
		  //link to form
		  echo "<a class=green href=\"trfinance.php?session=$session&distid=$row[distid]&school_ch=$row[0]\" class=small>$row[type] $row[class]-$row[district]: $row[0]</a><input type=hidden name=\"trsch[$vix]\" value=\"$row[1]\">";
		  //text box for note
		  echo "&nbsp;<input type=text class=tiny size=25 value=\"$row[2]\" name=\"trnote[$vix]\"><br>";
		  $vix++;
	       }
	       if(mysql_num_rows($result)==0) echo "&nbsp;";
	       echo "</td>";
	    echo "</tr>";
	 }
   }
   echo "</table>";
   echo "</td>";
   echo "</tr>";

echo "</table>";
if($level==1) echo "<p><input type=checkbox name=\"deleteall\" value=\"x\"> Check here to DELETE ALL Track & Field financial reports. Then click \"Delete Checked\" below.</p><input type=submit name=submit value=\"Delete Checked\">&nbsp&nbsp<input type=submit name=submit value=\"Save Notes\"></form>";
echo $end_html;
?>
