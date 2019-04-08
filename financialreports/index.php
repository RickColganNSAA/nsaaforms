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
$sql="SELECT id FROM logins WHERE school='$school2' AND (level='2' OR level='4' OR level='6')";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$hostid=$row[id];

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

if($level==1)
{
   echo "<form method=post action=\"index.php\">";
   echo "<input type=hidden name=school_ch value=\"$school_ch\">";
   echo "<input type=hidden name=session value=\"$session\">";
}

echo "<br><font size=3><b>Financial Reports:</b></font>";

//show list of choices for each sport:
if($level!=4)
{
   $sports=array("Football","Volleyball","Boys Basketball","Girls Basketball");
   $sports_sm=array("fb","vb","bb_b","bb_g");
}
else
{
   $sports=array("Volleyball");
   $sports_sm=array("vb");
}
echo "<table>";
if($school!="Test's School")
{
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
   $cursport=$sports_sm[$i];
   $sql="SELECT choices FROM classes_districts WHERE sport='$cursport'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $classes=split(",",$row[0]);
   echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=4";
   if($level!=1) echo " width=300";
   echo "><caption><b>$sports[$i] Financial Reports:</b></caption>";
   if($level==1)
   {
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
         }//end if sport==fb
         else
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
         }//end if sport!=fb
      }
   }//end if level=1
   else	//level!=1
   {
      $tempsp=ereg_replace("_","",$cursport);
      $table=$tempsp."districts";
      if($cursport=='fb') $table="fbbrackets";
      $sql2="SELECT * FROM $db_name2.$table WHERE";
      if($cursport=='fb') $sql2.=" hostschool='$school2' AND round!='Finals'";
      else $sql2.=" hostid='$hostid' AND (type='Subdistrict' OR type='District Final')";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         echo "<tr align=center><td>";
         while($row2=mysql_fetch_array($result2))
         {
            if($cursport=='fb')
            {
	       if($row2[round]=="First Round") $rnd=1;
	       else if($row2[round]=="Second Round") $rnd=2;
	       else if($row2[round]=="Quarterfinals") $rnd=3;
	       else $rnd=4;
               echo "<a href=\"fbfinance.php?session=$session&class=$row2[class]&round=$rnd\">Class $row2[class] ".strtoupper($row2[round])."</a><br><br>";
	    }
	    else
	    {
	       if($row[type]=="Subdistrict") $rnd=1;
	       else $rnd=2;
	       if(ereg("Boys",$sports[$i])) $gender="BOYS";
	       else if(ereg("Girls",$sports[$i])) $gender="GIRLS";
	       else $gender='';
	       echo "<a href=\"".$cursport."finance.php?session=$session&distid=$row2[id]\">";
	       if($gender!='') echo "$gender ";
	       echo "$row2[type] $row2[class]-$row2[district]</a><br><br>";
	    }
	 }
	 echo "</td></tr>";
      }
      else
	 echo "<tr align=center><td>Your school has not hosted any $sports[$i] Tournaments</td></tr>";
   } //end if level!=1
   echo "</table><br>";
   echo "</td>";
   echo "</tr>";
   }//end if not PastDue
   else	//put note that forms are locked
   {
      echo "<tr align=center><th align=center class=smaller><font style=\"color:blue\">The $sports[$i] Financial Reports are currently locked.</font></th></tr>";
   }
}
}
echo "</table>";
if($level==1) echo "<br><input type=submit name=submit value=\"Delete Checked & Save Notes\"></form>";

if($school=="Test's School")
{
   echo "<br><br><table>";
   echo "<tr align=left><td><form method=post action=\"sbfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Softball Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.sbdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
/*
   echo "<tr align=left><td><form method=post action=\"vbfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Volleyball Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.vbdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
*/
   echo "<tr align=left><td><form method=post action=\"bafinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Baseball Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.badistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "<tr align=left><td><form method=post action=\"wrfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Wrestling Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.wrdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "<tr align=left><td><form method=post action=\"bb_bfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Boys Basketball Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.bbbdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "<tr align=left><td><form method=post action=\"bb_gfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Girls Basketball Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.bbgdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "<tr align=left><td><form method=post action=\"trfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Track & Field Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.trdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "<tr align=left><td><form method=post action=\"so_bfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Boys Soccer Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.sobdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "<tr align=left><td><form method=post action=\"so_gfinance.php\">";
   echo "<input type=hidden name=session value=\"$session\"><b>Testing Girls Soccer Financial Reports:</b>";
   echo "<select name=distid><option value=\"\">Choose District</option>";
   $sql="SELECT * FROM $db_name2.sogdistricts WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[type] $row[class]-$row[district]</option>";
   }
   echo "</select><input type=submit name=go value=\"Go\"></form></td></tr>";
   echo "</table>";
}
echo $end_html;
?>
