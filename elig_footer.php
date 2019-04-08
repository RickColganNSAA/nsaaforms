<html>
<head>
<link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
<body>
<b>

<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
require 'functions.php';
//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

session_start();

$level=GetLevel($session);
$school_ch2=addslashes($school_ch);
?>

<table width=100% cellspacing=0 cellpadding=0 height=100%>
<tr align=left>
<td width=18%>
   <form method=post action="submit_elig.php">
   &nbsp;&nbsp;<input type="button" name="save" value="Save" onClick="parent.list.document.elig_form.submit();">
   <br>
   &nbsp;&nbsp;<a class=small href="elig_query.php?session=<?php echo $session; ?>" target="_top">Advanced Search</a>
   <br>
   &nbsp;&nbsp;<a class=small href="eligibility.php?school_ch=<?php echo $school_ch; ?>&activity_ch=All&session=<?php echo $session; ?>&last=a" target="_top">View All</a>
   <?php
   if($level==1)	//NSAA user
   {
      echo "&nbsp;($school_ch)";
   }
   ?>
   <br>
<?php
   if($level==1) $toggle="menu2";
   else $toggle="menu1";
   echo "&nbsp;&nbsp;<a href=\"welcome.php?session=$session&toggle=$toggle\" target=\"_top\">Home</a>";
?>
   </form>
</td>
<td>
<b>Current Search:</b>
<?php
$school_str=ereg_replace(","," or ",$school_ch);
$activity_str=ereg_replace(","," or ",$activity_ch);
$activity_str=ereg_replace("All","Any",$activity_str);
$activity_str=ereg_replace("Only","",$activity_str);
if($school_str=="All") $school_str="All Schools";
if($activity_str=="Any") $activity_str="Any Activities";
if($activity_str=="Only") $activity_str="Sports Only";

if(ereg("Non",$activity_str)) $activity_str=ereg_replace("Athletic","Athletic Activities",$activity_str);
$string="Students from <i>$school_str</i>";

//gender: only relevant if they chose "All Activities, Sports Only, or Non-Athletic Only":
if($gender && $gender!="All")// && (ereg("Any",$activity_str) || ereg("Only", $activity_str)))
{
   if($gender=="M") $string.=" who are <i>males</i>,";
   else if($gender=="F") $string.=" who are <i>females</i>,";
}
if($grade && $grade!="All") $string.=" who are in grade <i>$grade</i>,";
if($transfer=="y") $string.=" who are <i>transfers</i> or";
if($ineligible=="y") $string.=" who are <i>ineligible</i> or";
if($foreign_x=="y") $string.=" who are <i>international transfer</i> students or";
if($enroll_option=="y") $string.=" who chose the <i>enrollment option</i> or";
if($transfer=="y" || $ineligible=="y" || $foreign_x=="y" || $enroll_option=="y")
{
   $string=substr($string,0,strlen($string)-3);
   $string.=",";
}
$string.=" and who are in <i>$activity_str</i>.";
echo $string;
?>
<br>
   <table cellspacing=0 cellpadding=2>
   <tr align=center>
   <td><b>Color Code: &nbsp;</b></td>
   <th class=small bgcolor=#FF0000><b>Ineligible</b></td>
   <!--<th class=small bgcolor=#00FF00><b>Enrollment Option</b></td>-->
   <th class=small bgcolor=#FFCC00><b>Will be ineligible by Sr year</b></td>
   </tr>
   </table>
<a class=small href="update_activity.php?session=<?php echo $session; ?>&activity_ch=<?php echo $activity_ch; ?>&school_ch=<?php echo $school_ch; ?>" target="_top">Update Participation by Sport/Activity</a>&nbsp;&nbsp;
<?php
   if(ereg("All",$activity_ch))
   {
      $sql="SELECT * FROM eligibility WHERE school='$school_ch2'";
   }
   else         //pull only specified activities
   {
      $sql="SELECT * FROM eligibility WHERE ";
      if(ereg("Non",$activity_ch))    //Non-Athletic Activities Only
      {
         $sql.="(sp='x' OR pp='x' OR de='x' OR im='x' OR vm='x' OR jo='x')";
      }
      else if(ereg("Sports",$activity_ch)) //Sports Only
      {
         $sql.="(fb68='x' OR fb11='x' OR vb='x' OR sb='x' OR cc='x' OR te='x' OR bb='x' OR wr='x' OR sw='x' OR go='x' OR tr='x' OR ba='x' OR so='x')";
      }
      else      //List of specific activities
      {
         $sql.="(";
         $activity_ch=split(",",$activity_ch);
         for($i=0;$i<count($activity_ch);$i++)
         {
            if($activity_ch[$i]=="Girls Track" || $activity_ch[$i]=="Boys Track")
               $activity_ch[$i].=" & Field";
            $string=GetActivityQuery($activity_ch[$i]);
            $sql.="$string";
         }
         $sql=substr($sql,0,strlen($sql)-3);
         $sql.=")";
      }
      $sql.=" AND school='$school_ch2'";
   }
   $sql.=" ORDER BY last,first,middle";
   $result=mysql_query($sql);
   $csv="\"First\",\"Middle\",\"Last\",\"Gender\",\"DOB\",\"Semester\",\"Eligible\",\"International Transfer\",\"FB 6/8\",\"FB 11\",\"VB\",\"SB\",\"CC\",\"TE\",\"BB\",\"WR\",\"SW\",\"GO\",\"TR\",\"BA\",\"SO\",\"CH\",\"SP\",\"PP\",\"DE\",\"IM\",\"VM\",\"JO\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[first]\",\"$row[middle]\",\"$row[last]\",\"$row[gender]\",\"$row[dob]\",\"$row[semesters]\",\"$row[eligible]\",\"$row[foreignx]\",\"$row[fb68]\",\"$row[fb11]\",\"$row[vb]\",\"$row[sb]\",\"$row[cc]\",\"$row[te]\",\"$row[bb]\",\"$row[wr]\",\"$row[sw]\",\"$row[go]\",\"$row[tr]\",\"$row[ba]\",\"$row[so]\",\"$row[ch]\",\"$row[sp]\",\"$row[pp]\",\"$row[de]\",\"$row[im]\",\"$row[vm]\",\"$row[jo]\"\r\n";
   }
   $sch=ereg_replace(" ","",$school_ch);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   /*
   $filename=$sch."eligibility.csv";
   $open=fopen(citgf_fopen("attachments/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("attachments/$filename");
   */
   echo "<a class=small target=new href=\"eligexport.php?school_ch=$school_ch&session=$session\">Export ALL Students as an Excel File</a>";
?>
</td>
<td align=left><b>Adding Students:</b><br>
<a class=small href="add_students.php?session=<?php echo $session; ?>&activity_ch=<?php echo $activity_ch; ?>&school_ch=<?php echo $school_ch; ?>" target="_top">Add Students Manually</a><br>
<a class=small href="import_students.php?session=<?php echo $session; ?>&activity_ch=<?php echo $activity_ch; ?>&school_ch=<?php echo $school_ch; ?>" target="_top">Import File of Students</a><br>
<a class=small href="export_students.php?session=<?php echo $session; ?>" target="_top">Export Last Year's Students</a>
</td>
</tr>
</table>

</center>
</body>
</html>
