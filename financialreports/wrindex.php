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
   for($i=0;$i<count($wrsch);$i++)
   {
      if($wrdelete[$i]=='y' || $deleteall=='x')
      {
	 $sql="DELETE FROM finance_wr WHERE id='$wrsch[$i]'";
	 $result=mysql_query($sql);
      }
      // else
      // {
	 // $sql="UPDATE finance_wr SET note='$wrnote[$i]' WHERE id='$wrsch[$i]'";
	 // $result=mysql_query($sql);
      // }
   }
   for($i=0;$i<count($fbsch);$i++)
   {
      if($fbdelete[$i]=='y')
      {
	 $sql="DELETE FROM finance_fb WHERE id='$fbsch[$i]'";
	 $result=mysql_query($sql);
      }
      // else 
      // {
	 // $sql="UPDATE finance_fb SET note='$fbnote[$i]' WHERE id='$fbsch[$i]'";
	 // $result=mysql_query($sql);
      // }
   }
}
elseif($submit=='Save Notes')
{
   for($i=0;$i<count($wrsch);$i++)
   {
	 $sql="UPDATE finance_wr SET note='$wrnote[$i]' WHERE id='$wrsch[$i]'";
	 $result=mysql_query($sql);
   }
   for($i=0;$i<count($fbsch);$i++)
   {
	 $sql="UPDATE finance_fb SET note='$fbnote[$i]' WHERE id='$fbsch[$i]'";
	 $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"wrindex.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<center><br><font size=3><b>Financial Reports:</b></font>";
echo "<table width=100% cellspacing=0 cellpadding=1><caption><br></caption>";

//show list of choices for each sport:
$sports=array("Wrestling");
for($i=0;$i<count($sports);$i++)
{
   echo "<tr align=center valign=top>";
   echo "<td>";
   $cursport=GetActivityAbbrev2($sports[$i]);
   $sql="SELECT choices FROM classes_districts WHERE sport='$cursport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $classes=split(",",$row[0]);
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=1";
   echo "><caption align=left><b>$sports[$i] Financial Reports:</b>";
   echo "<br><a class=small href=\"wrfinanceexport.php?session=$session\" target=new>Export Wrestling Reports</a><br><br></caption>";
   if($cursport=="fb")
   {
      echo "<tr align=left>";
      if($level==1) echo "<th class=smaller>Class</th>";
      echo "<th class=smaller>1st Round</th><th class=smaller>2nd Round</th><th class=smaller>Quarterfinals</th><th class=smaller>Semifinals</th></tr>";
   }
   else
   {
      echo "<tr align=left>";
      if($level==1) echo "<th class=smaller>Class</th>";
      echo "<th class=smaller>District (Host)</th></tr>";
   }
   $vix=0; $fix=0;
   for($j=0;$j<count($classes);$j++)
   {
      if($level==1)
      {
         echo "<tr align=left valign=top><th class=smaller>$classes[$j]</th>";
	 $sql0="SELECT * FROM $db_name2.wrdistricts WHERE class='$classes[$j]' AND district!='' ORDER BY type,district";
	 $result0=mysql_query($sql0);
	 $classct=0;
	 while($row0=mysql_fetch_array($result0))
	 {
	    $distid=$row0[id];
	    $sql="SELECT school,id,note FROM finance_wr WHERE distid='$distid'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    if(mysql_num_rows($result)>0)
	    {
               if($classct==0) echo "<td>";
	       //checkbox to delete
	       echo "<input type=checkbox name=\"wrdelete[$vix]\" value='y'>&nbsp;";
	       //link to form
	       echo "<a class=green href=\"wrfinance.php?session=$session&distid=$distid\" class=small>$row0[type] $row0[class]-$row0[district] ($row[school])</a><input type=hidden name=\"wrsch[$vix]\" value=\"$row[1]\">";
	       //text box for note
	       echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[2]\" name=\"wrnote[$vix]\"><br>";
	       $vix++;	
	       $classct++;
	    }
	 }
	 if($classct==0)
	    echo "<td>&nbsp;</td></tr>";
      }
   }
   echo "</table>";
   echo "</td>";
   echo "</tr>";
}
echo "</table>";
if($level==1) echo "<p><input type=checkbox name=\"deleteall\" value=\"x\"> Check here to DELETE ALL Wrestling financial reports. Then click \"Delete Checked\" below.</p><input type=submit name=submit value=\"Delete Checked\">&nbsp&nbsp<input type=submit name=submit value=\"Save Notes\"></form>";
echo $end_html;
?>
