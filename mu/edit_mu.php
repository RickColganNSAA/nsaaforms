<?php
//edit_mu.php: Edit Page for Music Online Entry Form

require '../functions.php';
require '../../calculate/functions.php';
require 'mufunctions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch || $level>1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT id FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$year1=GetFallYear('mu');
$year2=$year1+1;

if($distid)
{
   $sql="SELECT * FROM muschools WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO muschools (school,distid) VALUES ('$school2','$distid')";
   }
   else
   {
      $sql2="UPDATE muschools SET distid='$distid' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
}

echo $init_html_ajax;
?>  
<script type="text/javascript" src="/javascript/Music.js"></script>
</head>
<body onload="Music.initialize('<?php echo $school2; ?>');">
<?php
echo GetHeader($session);

//Check if school is in muschools table yet:
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
if(mysql_error())
{
   echo mysql_error();
   exit();
}
if(mysql_num_rows($result)==0)
{
//echo "DEBUG: $sql<br>";
   echo "<table cellspacing=1 cellpadding=2 border=1 bordercolor=#000000>";
   echo "<caption><b>$year1-$year2 NSAA District Music Contests</b><br>";
   echo "<font style=\"font-size:9pt;\"><font style=\"color:blue\"><b>Please click on the district</b></font> in which your school will be participating this year.<br>(You will not have to select your district again once you have selected it the first time.)</font></caption>";
   echo "<tr align=center><td><b>District # -- Class</b></td>";
   echo "<td><b>Date(s)</b></td><td><b>Site</b></td><td><b>Director(s)</b></td></tr>";
   $sql="SELECT * FROM mudistricts ORDER BY distnum,classes";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left>";
      echo "<td><a class=small onclick=\"return confirm('Are you sure you want to select District $row[distnum] -- $row[classes]?  You will not be able to change your selection later.');\" href=\"view_mu.php?session=$session&school_ch=$school_ch&distid=$row[id]\">$row[distnum] -- $row[classes]</a></td>";
      $date=split("/",$row[dates]);
      $dates="";
      for($i=0;$i<count($date);$i++)
      {
         $cur=split("-",$date[$i]);
	 $dates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
      }
      $dates.=$cur[0];
      echo "<td>$dates</td>";
      echo "<td>$row[site]</td><td>$row[director]</td></tr>";
   }
   echo "</table>";
   echo "</form>";
   echo $end_html;
   exit();
}//end if no entry in muschools table
else	//show summary of school's current MU entry:
{
   $row=mysql_fetch_array($result);
   $distid=$row[distid];
   $submitted=$row[submitted];
   $supervisor=$row[supervisor];
   $phone=$row[phone]; $email=$row[email];
   $unlocked=$row[unlocked];

   echo "<form method=post action=\" edit_mu.php#top\" name=\"editmu\">";
   echo "<br><table width=95%><caption><b>$year1-$year2 NSAA DISTRICT MUSIC CONTEST ONLINE ENTRY:</b><br>";
   echo "<a class=small href=\"view_mu.php?session=$session&school_ch=$school_ch\">Return to Online Entry Home Page</a><br><br>";
   //Instructions:
   $duedate=$year2."-03-20";	//March 20 of this year
  if(($level!=1 && $unlocked!='x' && PastDue($duedate,0)) || $submitted!='')	//school has submitted this form
 //  if(($level!=1 && $unlocked!='x' && PastDue($duedate,0)) )	//school has submitted this form
   {
      echo "<table width=650><tr align=left><td>";
      if(PastDue($duedate,0))
         echo "<font style=\"color:red;font-size:9pt;\">This form was due on <b>March 20, $year2.</b></font><br>";
      if($submitted!='')
      {
         header("Location:view_mu.php?session=$session");
	 exit();
      }
      else	//Past due date AND no entry submitted (nothing to show them)
      {
         echo "<font style=\"font-size:9pt;\">You did NOT submit a $year1-$year2 NSAA District Music Contest Entry.</font>";
	 echo "</td></tr></table></caption></table>";
	 echo $end_html;
	 exit();
      }
   }
   else
   {
      echo "<table width=620 border=1 bordercolor=\"blue\" cellspacing=0 cellpadding=2><tr align=left><td>";
      echo "<font style=\"font-size:9pt;color:blue\"><b>You have NOT officially submitted this form yet.</font></b>";
      echo "<br><div id=\"entrystatus\" name=\"entrystatus\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".GetEntryStatus($schid)."</div>";
      if(GetCoopStatus($school)!="")
         echo "<br>".GetCoopStatus($school);
      echo "<br><font style=\"font-size:9pt;\"><font style=\"color:blue\">You must complete and submit this form to the NSAA by Midnight Central Time on <b>March 20, $year2.</font>";
      echo "<br><br><u>TO SUBMIT THIS FORM</u>, you must return to the <a href=\"view_mu.php?session=$session&school_ch=$school_ch\">NSAA District Music Contest Online Entry Home Page</a>.</b></font>";
      echO "</td></tr></table>";
   }
   echo "</caption>";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=school_ch value=\"$school_ch\">";
   echo "<tr align=center><td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3>";
   echo "<tr align=left bgcolor=#F0F0F0><td>";
   echo "<font style=\"font-size:9pt;\"><b><u>TO ENTER/EDIT YOUR NSAA DISTRICT MUSIC CONTEST ENTRY FORM</u>, follow the instructions in <font style=\"color:blue\">blue</font> below:</font><br><br>";
   echo "<font style=\"color:blue;font-size:9pt;\"><b><i>You are currently working on this category:&nbsp;</i></b></font>";
   echo "<select name=\"categ\" id=\"categ\" onchange=\"window.frames.contentframe.location.replace('edit_categ.php?session=$session&school_ch=".urlencode($school_ch)."&categ='+ this.value);\">";
   echo "<option value='0'";
   if(!$categ) echo " selected";
   echo ">Contact Info & Student Entry Count</option>";
   //get 6 main categories from database
   $sql="SELECT * FROM mucategories ORDER BY enterorder";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($categ==$row[id]) { echo " selected"; $curcatname=$row[category]; }
      echo ">$row[category]</option>";
   }
   echo "</select><input type=button name=go value=\"Go\" onclick=\"window.frames.contentframe.location.replace('edit_categ.php?session=$session&school_ch=".urlencode($school_ch)."&categ='+ categ.value +'');\"><br>";
   echo "<font style=\"color:blue;font-size:9pt\"><b><i>(To work on another category, select that category from the dropdown menu above. Make sure to SAVE your work below first!!)</i></b></font><br><br><a class=small href=\"view_mu.php?session=$session&school_ch=$school_ch\">Return to Online Entry Home Page</a></td></tr>";
   echo "</table></td></tr>";
   echo "<tr align=center><td>";
   echo "<iframe name=\"contentframe\" id=\"contentframe\" src=\"edit_categ.php?school_ch=$school_ch&session=$session&categ=$categ\" width=\"100%\" height=\"1000\" frameborder=0></iframe>";
   echo "</td></tr>";
}
echo "</table>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
