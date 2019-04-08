<html>
<head>
<link href="../../css/nsaaforms.css" rel="stylesheet" type="text/css">
<body style="background-color:#f0f0f0;">
<b>
<?php
require '../variables.php';
require '../functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level==8) $school_ch=GetSchool($session);
$school_ch2=addslashes($school_ch);
?>
<div class='eligfooter' style='float:left;'>
<b>CURRENT SEARCH:</b>
<?php
$school_str=ereg_replace(","," or ",$school_ch);
if($school_str=="All") $school_str="All Schools";

$string="Students from <i>$school_str</i>";

if($gender && $gender!="Any")
{
   if($gender=="M") $string.=" who are <i>males</i>,";
   else if($gender=="F") $string.=" who are <i>females</i>,";
}
if($grade && $grade!="Any") $string.=" who are in grade <i>$grade</i>,";
if($eligible=="y") $string.=" who are <i>eligible</i> or";
else if($eligible=="n") $string.=" who are <i>ineligible</i> or";
if($physical=="y") $string.=" who <i>have done a physical exam</i> or";
else if($physical=="n") $string.=" who <i>have not done a physical exam</i> or";
if($parent=="y") $string.=" who <i>have a parent consent form on file</i> or";
else if($parent=="n") $string.=" who <i>do not have a parent consent form on file</i> or";
echo "&nbsp;".$string;
?>
<br>
<b>Color Code:</b><br>
<div style="background-color:#ff0000;padding:3px;margin:0px;width:180px;float:left;">Ineligible</div>
<div style="background-color:#ffcc00;padding:3px;margin:0px;width:180px;float:right;">Will be ineligible by 8th grade year</div>
<div style='clear:both;'></div>
<div style="background-color:#00ff99;padding:3px;margin:0px;width:180px;float:left;">Physical Exam NOT DONE</div>
<div style="background-color:#6699ff;padding:3px;margin:0px;width:180px;float:right;">NO Parent Consent Form on file</div>
</div>
<div style='float:left;' class='eligfooter'>
   <b>Current Students:</b><br>
   <a class=small href="elig_query.php?session=<?php echo $session; ?>" target="_top">Advanced Search</a>
   <?php
   if($level==1)        //NSAA user
      echo "&nbsp;($school_ch)";
   ?>
   <br>
   <a class=small target=new href="eligexport.php?session=<?php echo $session; ?>">Export ALL Students as an Excel File</a>
</div>
<div class='eligfooter'>
<b>Adding Students:</b><br>
<a class=small href="add_students.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" target="_top">Add Students Manually</a><br>
<a class=small href="import_students.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" target="_top">Import File of Students</a><br>
<?php if(PastDue("2011-05-01",0)): ?>
<a class=small href="export_students.php?session=<?php echo $session; ?>" target="_top">Export Last Year's Students</a>
<?php endif; ?>
</div>
</div>
</center>
</body>
</html>
