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
   for($i=0;$i<count($sbsch);$i++)
   {
      if($sbdelete[$i]=='y' || $deleteall=='x')
      {
	 $sql="DELETE FROM finance_sb WHERE id='$sbsch[$i]'";
	 $result=mysql_query($sql);
      }
      // else
      // {
	 // $sql="UPDATE finance_sb SET note='$sbnote[$i]' WHERE id='$sbsch[$i]'";
	 // $result=mysql_query($sql);
      // }
   }
}
elseif($submit=='Save Notes')
{
   for($i=0;$i<count($sbsch);$i++)
   {
	 $sql="UPDATE finance_sb SET note='$sbnote[$i]' WHERE id='$sbsch[$i]'";
	 $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"sbindex.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<table><tr align=center valign=top>";

//show list of choices for each sport:
$sports=array("Softball");
for($i=0;$i<count($sports);$i++)
{
   echo "<td>";
   $cursport=GetActivityAbbrev2($sports[$i]);
   $districts="sbdistricts";
   $sql="SELECT DISTINCT class FROM $db_name2.$districts WHERE class!='' ORDER BY class";
   $result=mysql_query($sql);
   $classes=array(); $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $classes[$ix]=$row['class']; $ix++;
   }
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=1";
   echo "><caption align=left><b>$sports[$i] Financial Reports:</b><br><br><a class=small target='_blank' href='".$cursport."financeexport.php?session=$session'>Export $sports[$i] Financial Reports</a></caption>";
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
	       $sql="SELECT t1.school,t1.id,t1.note,t1.distid,t2.class,t2.type,t2.district FROM finance_".$cursport." AS t1,$db_name2.$districts AS t2 WHERE t1.distid=t2.id AND t2.class='$classes[$j]' AND t1.round='$rnd' ORDER BY t2.type,t2.district";
	       $result=mysql_query($sql);
	       while($row=mysql_fetch_array($result))
	       {
		  //checkbox to delete
		  echo "<input type=checkbox name=\"".$cursport."delete[$vix]\" value='y'>&nbsp;";
		  //link to form
		  echo "<a class=green href=\"".$cursport."finance.php?session=$session&distid=$row[distid]&school_ch=$row[0]\" class=small>$row[type] $row[class]-$row[district]: $row[0]</a><input type=hidden name=\"".$cursport."sch[$vix]\" value=\"$row[1]\">";
		  //text box for note
		  echo "&nbsp;<input type=text class=tiny size=25 value=\"$row[2]\" name=\"".$cursport."note[$vix]\"><br>";
		  $vix++;
	       }
	       if(mysql_num_rows($result)==0) echo "&nbsp;";
	       echo "</td>";
	    echo "</tr>";
	 }
   }
   echo "</table>";
   echo "</td>";
}//end for each sport
echo "</tr></table>";
if($level==1) echo "<p><input type=checkbox name=\"deleteall\" value=\"x\"> Check here to DELETE ALL Softball financial reports. Then click \"Delete Checked & Save Notes\" below.</p><input type=submit name=submit value=\"Delete Checked\">&nbsp&nbsp<input type=submit name=submit value=\"Save Notes\"></form>";
echo $end_html;
?>
