<?php

require '../functions.php';
require '../variables.php';

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

if($submit=='Delete Checked')	//delete checked schools' forms
{
   for($i=0;$i<count($vbsch);$i++)
   {
      if($vbdelete[$i]=='y' || $deleteall=='x')
      {
	 $sql="DELETE FROM finance_vb WHERE id='$vbsch[$i]'";
	 $result=mysql_query($sql);
      }
      // else
      // {
	 // $sql="UPDATE finance_vb SET note='$vbnote[$i]' WHERE id='$vbsch[$i]'";
	 // $result=mysql_query($sql);
      // }
   }
}
elseif($submit=='Save Notes')
{
   for($i=0;$i<count($vbsch);$i++)
   {
	 $sql="UPDATE finance_vb SET note='$vbnote[$i]' WHERE id='$vbsch[$i]'";
	 $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"vbindex.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<br><h2>Volleyball Financial Reports:</h2>";
echo "<table width=100% cellspacing=0 cellpadding=1><caption><p><a href=\"vbfinanceexport.php?session=$session\" target=\"_blank\">Export Financial Reports</a></p><br></caption>";

//show list of choices for each sport:
$sports=array("Volleyball");
for($i=0;$i<count($sports);$i++)
{
   echo "<tr align=center valign=top>";
   echo "<td>";
   echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
      echo "<tr align=left>";
      if($level==1) echo "<th class=smaller>Class</th>";
      echo "<th class=smaller>Sub-District</th><th class=smaller>District Final</th></tr>";
   $vix=0; $fix=0;
   $sql0="SELECT DISTINCT class FROM $db_name2.vbdistricts WHERE class!='' ORDER BY class";
   $result0=mysql_query($sql0);
   while($row0=mysql_fetch_array($result0))
   {
	 if($level==1)
	 {
            echo "<tr align=left valign=top><th class=smaller>$row0[class]</th>";
	       echo "<td>";
	       $sql="SELECT t1.id,t1.school,t1.distid,t1.note,t2.type,t2.district FROM finance_vb AS t1,$db_name2.vbdistricts AS t2 WHERE t1.distid=t2.id AND t2.class='$row0[class]' AND t2.type='Subdistrict' ORDER BY class,type,district";
	       $result=mysql_query($sql);
	       while($row=mysql_fetch_array($result))
	       {
		  //checkbox to delete
		  echo "<input type=checkbox name=\"vbdelete[$vix]\" value='y'>&nbsp;";
		  //link to form
		  echo "<a class=green href=\"vbfinance.php?session=$session&school_ch=$row[school]&distid=$row[distid]\" class=small>$row[type] $row0[class]-$row[district]: $row[school]</a><input type=hidden name=\"vbsch[$vix]\" value=\"$row[id]\">";
		  //text box for note
		  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[note]\" name=\"vbnote[$vix]\"><br>";
		  $vix++;
	       }
	       if(mysql_num_rows($result)==0) echo "&nbsp;";
	       echo "</td>";
               echo "<td>";
               $sql="SELECT t1.id,t1.school,t1.distid,t1.note,t2.type,t2.district FROM finance_vb AS t1,$db_name2.vbdistricts AS t2 WHERE t1.distid=t2.id AND t2.class='$row0[class]' AND t2.type LIKE 'District%' ORDER BY class,type,district";
               $result=mysql_query($sql);
               while($row=mysql_fetch_array($result))
               {
                  //checkbox to delete
                  echo "<input type=checkbox name=\"vbdelete[$vix]\" value='y'>&nbsp;";
                  //link to form
                  echo "<a class=green href=\"vbfinance.php?session=$session&school_ch=$row[school]&distid=$row[distid]\" class=small>$row[type] $row0[class]-$row[district]: $row[school]</a><input type=hidden name=\"vbsch[$vix]\" value=\"$row[id]\">";
                  //text box for note
                  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[note]\" name=\"vbnote[$vix]\"><br>";
                  $vix++;
               }
               if(mysql_num_rows($result)==0) echo "&nbsp;";
               echo "</td>";
               /*echo "<td>";
               $sql="SELECT t1.id,t1.school,t1.distid,t1.note,t2.type,t2.district FROM finance_vb AS t1,$db_name2.vbdistricts AS t2 WHERE t1.distid=t2.id AND t2.class='$row0[class]' AND t2.type LIKE 'Substate%' ORDER BY class,type,district";
               $result=mysql_query($sql);
               while($row=mysql_fetch_array($result))
               {
                  //checkbox to delete
                  echo "<input type=checkbox name=\"vbdelete[$vix]\" value='y'>&nbsp;";
                  //link to form
                  echo "<a class=green href=\"vbfinance.php?session=$session&school_ch=$row[school]&distid=$row[distid]\" class=small>$row[type] $row0[class]-$row[district]: $row[school]</a><input type=hidden name=\"vbsch[$vix]\" value=\"$row[id]\">";
                  //text box for note
                  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[note]\" name=\"vbnote[$vix]\"><br>";
                  $vix++;
               }
               if(mysql_num_rows($result)==0) echo "&nbsp;";
               echo "</td>";*/
	    echo "</tr>";
	 }
   }
   echo "</table>";
   echo "</td>";
   echo "</tr>";
}
echo "</table>";
if($level==1) echo "<p><input type=checkbox name=\"deleteall\" value=\"x\"> Check here to DELETE ALL Volleyball financial reports. Then click \"Delete Checked\" below.</p><input type=submit name=submit value=\"Delete Checked\">&nbsp&nbsp<input type=submit name=submit value=\"Save Notes\"></form>";
echo $end_html;
?>
