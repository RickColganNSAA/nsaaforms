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
   for($i=0;$i<count($bbbsch);$i++)
   {
      if($bbbdelete[$i]=='y' || $deleteall=='x')
      {
	 $sql="DELETE FROM finance_bb_b WHERE id='$bbbsch[$i]'";
	 $result=mysql_query($sql);
      }
      // else
      // {
	 // $sql="UPDATE finance_bb_b SET note='$bbbnote[$i]' WHERE id='$bbbsch[$i]'";
	 // $result=mysql_query($sql);
      // }
   }
}
elseif($submit=='Save Notes')
{
   for($i=0;$i<count($bbbsch);$i++)
   {
	 $sql="UPDATE finance_bb_b SET note='$bbbnote[$i]' WHERE id='$bbbsch[$i]'";
	 $result=mysql_query($sql);
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
$sports=array("Boys Basketball");
for($i=0;$i<count($sports);$i++)
{
   echo "<tr align=center valign=top>";
   echo "<td>";
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=1";
   echo "><caption align=left><b>$sports[$i] Financial Reports:</b></caption>";
      echo "<tr align=left>";
      if($level==1) echo "<th class=smaller>Class</th>";
      echo "<th class=smaller>Sub-District</th><th class=smaller>District/District Final</th></tr>";
   $vix=0; $fix=0;
   $sql0="SELECT DISTINCT class FROM $db_name2.bbbdistricts WHERE class!='' ORDER BY class";
   $result0=mysql_query($sql0);
   while($row0=mysql_fetch_array($result0))
   {
	 if($level==1)
	 {
            echo "<tr align=left valign=top><th class=smaller>$row0[class]</th>";
	       echo "<td>";
	       $sql="SELECT t1.id,t1.school,t1.disttimesid,t1.distid,t1.note,t2.type,t2.district FROM finance_bb_b AS t1,$db_name2.bbbdistricts AS t2 WHERE t1.distid=t2.id AND t2.class='$row0[class]' AND t2.type='Subdistrict' ORDER BY class,type,district";
	       $result=mysql_query($sql);
	       while($row=mysql_fetch_array($result))
	       {
		  //checkbox to delete
		  echo "<input type=checkbox name=\"bbbdelete[$vix]\" value='y'>&nbsp;";
		  //link to form
		  echo "<a class=green href=\"bb_bfinance.php?session=$session&school_ch=$row[school]&disttimesid=$row[disttimesid]&distid=$row[distid]\" class=small>$row0[class]-$row[district]: $row[school]</a><input type=hidden name=\"bbbsch[$vix]\" value=\"$row[id]\">";
		  //text box for note
		  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[note]\" name=\"bbbnote[$vix]\"><br>";
		  $vix++;
	       }
	       if(mysql_num_rows($result)==0) echo "&nbsp;";
	       echo "</td>";
               echo "<td>";
               $sql="SELECT t1.id,t1.school,t1.disttimesid,t1.distid,t1.note,t2.type,t2.district FROM finance_bb_b AS t1,$db_name2.bbbdistricts AS t2 WHERE t1.distid=t2.id AND t2.class='$row0[class]' AND t2.type LIKE 'District%' ORDER BY class,type,district";
               $result=mysql_query($sql);
               while($row=mysql_fetch_array($result))
               {
                  //checkbox to delete
                  echo "<input type=checkbox name=\"bbbdelete[$vix]\" value='y'>&nbsp;";
                  //link to form
                  echo "<a class=green href=\"bb_bfinance.php?session=$session&school_ch=$row[school]&disttimesid=$row[disttimesid]&distid=$row[distid]\" class=small>$row0[class]-$row[district]: $row[school]</a><input type=hidden name=\"bbbsch[$vix]\" value=\"$row[id]\">";
                  //text box for note
                  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[note]\" name=\"bbbnote[$vix]\"><br>";
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
}
echo "</table>";
if($level==1) echo "<p><input type=checkbox name=\"deleteall\" value=\"x\"> Check here to DELETE ALL Boys Basketball financial reports. Then click \"Delete Checked\" below.</p><input type=submit name=submit value=\"Delete Checked\">&nbsp&nbsp<input type=submit name=submit value=\"Save Notes\"></form>";
echo $end_html;
?>
