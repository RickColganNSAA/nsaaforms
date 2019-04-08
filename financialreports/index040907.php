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

$curryear=date("Y",time());

if($submit)	//delete checked schools' forms
{
   for($i=0;$i<count($vbsch);$i++)
   {
      if($vbdelete[$i]=='y')
      {
	 $sql="DELETE FROM finance_vb WHERE id='$vbsch[$i]'";
	 $result=mysql_query($sql);
      }
      else
      {
	 $sql="UPDATE finance_vb SET note='$vbnote[$i]' WHERE id='$vbsch[$i]'";
	 $result=mysql_query($sql);
      }
   }
   for($i=0;$i<count($fbsch);$i++)
   {
      if($fbdelete[$i]=='y')
      {
	 $sql="DELETE FROM finance_fb WHERE id='$fbsch[$i]'";
	 $result=mysql_query($sql);
      }
      else 
      {
	 $sql="UPDATE finance_fb SET note='$fbnote[$i]' WHERE id='$fbsch[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"index.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=session value=\"$session\">";

echo "<br><font size=3><b>Financial Reports:</b></font>";

//show list of choices for each sport:
if($level!=4)
   $sports=array("Football","Volleyball","Boys Basketball","Girls Basketball");
else
   $sports=array("Volleyball");
/*
$sql="SELECT * FROM finance_hurr WHERE hostschool='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<tr align=left><td>Your Submitted Report(s):</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left><td><a class=small target=new href=\"hurrfinance.php?session=$session&gameid=$row[id]&print=1\">$row[hostschool] VS $row[oppschool] (".date("m/d/Y",$row[gamedate]).")</a></td></tr>";
   }
}
*/
echo "<table>";
for($i=0;$i<count($sports);$i++)
{
   //check if this sports' forms should be locked yet
   $curmo=date("m",time());
   if($sports[$i]=="Volleyball" || $sports[$i]=="Football")
   {
      if($curmo>=1 && $curmo<=6) 
	 $lockyr=$curryear-1;
      else
	 $lockyr=$curryear;
      $lockdate="Dec 1, $lockyr";
   }
   else	//Basketball
   {
      if($curmo>=1 && $curmo<=6)
	 $lockyr=$curryear;
      else
	 $lockyr=$curryear+1;
      $lockdate="Apr 1, $lockyr";
   }
   if(!PastDue($lockdate,0) || $secret==1)
   {
   echo "<tr align=center valign=top>";
   echo "<td>";
   $cursport=GetActivityAbbrev2($sports[$i]);
   $sql="SELECT choices FROM classes_districts WHERE sport='$cursport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $classes=split(",",$row[0]);
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=1";
   if($sports[$i]!="Football") echo " width=250";
   echo "><caption align=left><b>$sports[$i] Financial Reports:</b></caption>";
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
      echo "<th class=smaller>Sub-District</th><th class=smaller>District/District Final</th></tr>";
   }
   $vix=0; $fix=0;
   for($j=0;$j<count($classes);$j++)
   {
      if($cursport=="fb")
      {
	 if($level==1) 
	 {
	    echo "<tr align=left valign=top><th class=smaller align=left>$classes[$j]</th>";
	    for($rnd=1;$rnd<5;$rnd++)
	    {
	       echo "<td align=left>";
	       $sql="SELECT school,id,note FROM finance_fb WHERE classdist='$classes[$j]' AND round='$rnd' ORDER BY school";
	       $result=mysql_query($sql);
	       while($row=mysql_fetch_array($result))
	       {
		  //delete checkbox
		  echo "<input type=checkbox name=\"fbdelete[$fix]\" value='y'>&nbsp;";
		  //link to form
		  echo "<a href=\"fbfinance.php?session=$session&class=$classes[$j]&round=$rnd&school_ch=$row[0]\" class=small>$row[0]</a><input type=hidden name=\"fbsch[$fix]\" value=\"$row[1]\">";
		  //text box to put notes
		  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[2]\" name=\"fbnote[$fix]\"><br>";
		  $fix++;
	       }
	       if(mysql_num_rows($result)==0) echo "&nbsp;";
	       echo "</td>";
	    }
	    echo "</tr>";
	 }
	 else
	 {
	    echo "<tr align=left><td><a href=\"fbfinance.php?session=$session&class=$classes[$j]&round=1&school_ch=$school_ch\" class=small>Class $classes[$j]</a></td>"; 
	    if($classes[$j]=="A" || $classes[$j]=="B")
	    {
	       echo "<td>&nbsp;</td>";
	    }
	    else
	    {
	       echo "<td><a href=\"fbfinance.php?session=$session&school_ch=$school_ch&class=$classes[$j]&round=2\" class=small>Class $classes[$j]</a></td>";
	    }
	    echo "<td><a href=\"fbfinance.php?session=$session&school_ch=$school_ch&class=$classes[$j]&round=3\" class=small>Class $classes[$j]</a></td>";
	    echo "<td><a href=\"fbfinance.php?session=$session&school_ch=$school_ch&class=$classes[$j]&round=4\" class=small>Class $classes[$j]</a></td></tr>";
	 }
      }
      else
      {
	 if($level==1)
	 {
            echo "<tr align=left valign=top><th class=smaller>$classes[$j]</th>";
	    for($rnd=1;$rnd<3;$rnd++)
	    {
	       echo "<td>";
	       $sql="SELECT school,id,note FROM finance_$cursport WHERE class='$classes[$j]' AND round='$rnd' ORDER BY school";
	       $result=mysql_query($sql);
	       while($row=mysql_fetch_array($result))
	       {
		  //checkbox to delete
		  echo "<input type=checkbox name=\"".$cursport."delete[$vix]\" value='y'>&nbsp;";
		  //link to form
		  echo "<a href=\"".$cursport."finance.php?session=$session&class=$classes[$j]&round=$rnd&school_ch=$row[0]\" class=small>$row[0]</a><input type=hidden name=\"".$cursport."sch[$vix]\" value=\"$row[1]\">";
		  //text box for note
		  echo "&nbsp;<input type=text class=tiny size=10 value=\"$row[2]\" name=\"".$cursport."note[$vix]\"><br>";
		  $vix++;
	       }
	       if(mysql_num_rows($result)==0) echo "&nbsp;";
	       echo "</td>";
	    }
	    echo "</tr>";
	 }
	 else
	 {
	    if($classes[$j]!="A" && $classes[$j]!="B")
	    {
	       echo "<td><a href=\"".$cursport."finance.php?session=$session&class=$classes[$j]&round=1&school_ch=$school\" class=small>Class $classes[$j]</a></td>";
	    }
	    else echo "<td>&nbsp;</td>";
	    echo "<td><a href=\"".$cursport."finance.php?session=$session&class=$classes[$j]&round=2&school_ch=$school\" class=small>Class $classes[$j]</a></td>";
	    echo "</tr>";
         }
      }
   }
   echo "</table><br>";
   echo "</td>";
   echo "</tr>";
   }//end if not PastDue
   else	//put note that forms are locked
   {
      echo "<tr align=center><th align=center class=smaller><font style=\"color:blue\">The $sports[$i] Financial Reports are currently locked.</font></th></tr>";
   }
}
echo "</table>";
if($level==1) echo "<br><input type=submit name=submit value=\"Delete Checked & Save Notes\"></form>";
echo $end_html;
?>
