<?php
//NSAA Admin View for Class D CC Survey (cc/cc_survey.php)

require 'functions.php';
require 'variables.php';

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

echo $init_html;
echo $header;

//show 3 different lists, for boys and girls each
echo "<center><br><br>";
?>
<h2>Class D  Cross-Country  Survey Result</h2>
<div style="float:left; width:25%;">
<h3>Class D  That Have full Team competing </h3>

<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>
<tr><th>School Name </th><th>Number of Boys </th><th>Number of Girls </th> </tr>
<?php 
	$sql="SELECT school,how_many_boys,how_many_girls FROM cc_classd WHERE full_b='y' and  full_g='y'  ORDER BY school";
	
	$result=mysql_query($sql);

while($row=mysql_fetch_array($result))
{
   ?><tr><td> <?php echo $row[0]."";?></td><td> <?php echo $row[1]."";?></td><td> <?php echo $row[2]."";?></td> </tr><?php }?>

<tr><td><a target="blank" href="cc_survey_export.php?type=fullteam"> Export</a></td> </tr>
</table>
</div>
<div style="float:left; width:25%;">
<h3>Class D  that have Boys full team competing and do not have Girls full Team competing </h3>

<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>
<tr><th>School Name </th> <td> Number of Girls</td><td> Number of Boys</td></tr>
<?php 
	$sql="SELECT school,how_many_girls,how_many_boys FROM cc_classd WHERE full_b='y' and  full_g='n'  ORDER BY school";
	
	$result=mysql_query($sql);

while($row=mysql_fetch_array($result))
{
   ?><tr><td> <?php echo $row[0]."";?></td><td> <?php echo $row[1]."";?></td><td> <?php echo $row[2]."";?></td> </tr><?php }?>

<tr><td><a target="blank" href="cc_survey_export.php?type=numberofgirls"> Export</a></td> </tr>
</table>
</div>
<div style="float:left; width:25%;">
<h3>Class D  that do not have Boys full team competing and  Girls full Team competing </h3>

<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>
<tr><th>School Name </th> <td> Number of Boys</td><td> Number of Girls</td></tr>
<?php 
	$sql="SELECT school,how_many_boys,how_many_girls FROM cc_classd WHERE full_b='n' and  full_g='y'  ORDER BY school";
	
	$result=mysql_query($sql);

while($row=mysql_fetch_array($result))
{
   ?><tr><td> <?php echo $row[0]."";?></td><td> <?php echo $row[1]."";?></td><td> <?php echo $row[2]."";?></td> </tr><?php }?>

<tr><td><a target="blank" href="cc_survey_export.php?type=numberofboys"> Export</a></td> </tr>
</table>
</div>
<div style="float:left; width:25%;">
<h3>Class D  that do not have Boys full team competing and  Do not have Girls full Team competing </h3>

<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>
<tr><th>School Name </th> <td> Number of Boys</td> <td> Number of Girls</td></tr>
<?php 
	$sql="SELECT school,how_many_boys,how_many_girls FROM cc_classd WHERE full_b='n' and  full_g='n'  ORDER BY school";
	
	$result=mysql_query($sql);

while($row=mysql_fetch_array($result))
{
   ?><tr><td> <?php echo $row[0]."";?></td><td> <?php echo $row[1]."";?></td> <td> <?php echo $row[2]."";?></td> </tr><?php }?>

<tr><td><a target="blank" href="cc_survey_export.php?type=noone"> Export</a></td> </tr>
</table>
</div>
<div style="float:left; width:90%; padding-top:20px;">
<?php 
//show schools who have not finished their survey:
echo "<table width=400 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption align=left><b>BOYS Class D Schools that have NOT completed the Class D Cross-Country Survey:</b> 
<br /><a target='blank' href='cc_survey_export.php?type=notcomplitedboys'> Export</a></caption>";
//echo "<tr align=center><th width=\"50%\" class=smaller>Partially<br>Completed</th><th width=\"50%\" class=smaller>No Questions<br>Completed</th></tr>";
$sql="SELECT * FROM ccbschool WHERE class='D' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $school=addslashes($row2[school]);
   $sql3="SELECT * FROM cc_classd WHERE school='$school'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   //if(mysql_num_rows($result3)>0 && ($row3[reg_b]=='' || $row3[reg_g]=='' || $row3[full_b]=='' || $row3[full_g]==''))	//PARTIAL
   if(mysql_num_rows($result3)>0 && ( $row3[full_b]=='' || $row3[full_g]==''))	//PARTIAL
      ;
   //else if(mysql_num_rows($result3)==0 || ($row3[reg_b]=='' && $row3[reg_g]=='' && $row3[full_b]=='' && $row3[full_g]==''))
   else if(mysql_num_rows($result3)==0 || ( $row3[full_b]=='' && $row3[full_g]==''))
   
     echo "<tr align=center><td>$row2[school]</td></tr>";
}
echo "</table>";
echo "<br><table width=400 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption align=left><b>GIRLS Class D Schools that have NOT completed the Class D Cross-Country Survey:</b>
<br /><a target='blank' href='cc_survey_export.php?type=notcomplitedgirls'> Export</a></caption>";
//echo "<tr align=center><th width=\"50%\" class=smaller>Partially<br>Completed</th><th width=\"50%\" class=smaller>No Questions<br>Completed</th></tr>";
$sql="SELECT * FROM ccgschool WHERE class='D' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $school=addslashes($row2[school]);
   $sql3="SELECT * FROM cc_classd WHERE school='$school'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   //if(mysql_num_rows($result3)>0 && ($row3[reg_b]=='' || $row3[reg_g]=='' || $row3[full_b]=='' || $row3[full_g]==''))   //PARTIAL
   if(mysql_num_rows($result3)>0 && ( $row3[full_b]=='' || $row3[full_g]==''))   //PARTIAL
      ;
   //else if(mysql_num_rows($result3)==0 || ($row3[reg_b]=='' && $row3[reg_g]=='' && $row3[full_b]=='' && $row3[full_g]==''))
   else if(mysql_num_rows($result3)==0 || ( $row3[full_b]=='' && $row3[full_g]==''))
     echo "<tr align=center><td>$row2[school]</td></tr>";
}
echo "</table>";
echo "<br><a href=\"welcome.php?session=$session\">Home</a></div>";

echo $end_html;
?>
