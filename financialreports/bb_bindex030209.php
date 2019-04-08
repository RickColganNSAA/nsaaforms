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

if($submit)	//delete checked schools' forms
{
   for($i=0;$i<count($bbbsch);$i++)
   {
      if($bbbdelete[$i]=='y')
      {
	 $sql="DELETE FROM finance_bb_b WHERE id='$bbbsch[$i]'";
	 $result=mysql_query($sql);
      }
      else
      {
	 $sql="UPDATE finance_bb_b SET note='$bbbnote[$i]' WHERE id='$bbbsch[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"bb_bindex.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<center><br><font size=3><b>Financial Reports:</b></font>";
echo "<table width=100% cellspacing=0 cellpadding=1><caption><br></caption>";

//show list of choices for each sport:
   echo "<tr align=center valign=top>";
   echo "<td>";

   $sql="SELECT DISTINCT class FROM $db_name2.bbbdistricts ORDER BY class";
   $result=mysql_query($sql);
   $i=0;
   while($row=mysql_fetch_array($result))
   {
      $classes[$i]=$row['class']; $i++;
   }
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=1";
   echo "><caption align=left><b>Boys Basketball Financial Reports:</b>";
   echo "<br><a class=small href=\"bb_bfinanceexport.php?session=$session\" target=new>Export Boys Basketball Reports</a><br><br></caption>";
   echo "<tr align=left>";
   if($level==1) echo "<th class=smaller>Class</th>";
   echo "<th class=smaller>District (Host)</th></tr>";
   $vix=0; $fix=0;
   for($j=0;$j<count($classes);$j++)
   {
      if($level==1)
      {
         echo "<tr align=left valign=top><th class=smaller>$classes[$j]</th>";
	 $sql0="SELECT * FROM $db_name2.bbbdistricts WHERE class='$classes[$j]' AND district!='' ORDER BY type,district";
	 $result0=mysql_query($sql0);
	 $classct=0;
	 while($row0=mysql_fetch_array($result0))
	 {
	    $distid=$row0[id];
	    $sql="SELECT school,id,note FROM finance_bb_b WHERE distid='$distid'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    if(mysql_num_rows($result)>0)
	    {
               if($classct==0) echo "<td>";
	       //checkbox to delete
	       echo "<input type=checkbox name=\"bbbdelete[$vix]\" value='y'>&nbsp;";
	       //link to form
	       echo "<a class=green href=\"bb_bfinance.php?session=$session&distid=$distid\" class=small>$row0[type] $row0[class]-$row0[district] ($row[school])</a><input type=hidden name=\"bbbsch[$vix]\" value=\"$row[1]\">";
	       //text box for note
	       echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[2]\" name=\"bbbnote[$vix]\"><br>";
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
echo "</table>";
if($level==1) echo "<br><input type=submit name=submit value=\"Delete Checked & Save Notes\"></form>";
echo $end_html;
?>
