<?php
/*********************************
allstatenomletteradmin.php
NSAA can edit text NCPA Academic
All-State Nomination Letter
Author: Ann Gaffigan
Created: 11/10/14
*********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;
$springyear=$fallyear+1;

if($save)
{
   $letterbody=preg_replace("/\\r\\n/","<br>",$letterbody);
    $search = array(chr(145),
                    chr(146),
                    chr(147),
                    chr(148),
                    chr(133),
                    chr(150),
                    chr(151));

    $replace = array("'",
                     "'",
                     '"',
                     '"',
                     '...',
                     '-',
                     '-');

   $letterbody=str_replace($search, $replace, $letterbody);
   $letterbody=addslashes($letterbody);
   $sql="UPDATE allstatenomletter SET letterbody='$letterbody'";
   $result=mysql_query($sql);
   $saveerror="";
   if(mysql_error())
      $saveerror="There was an error with the query: <blockquote>$sql</blockquote><p><b>ERROR MESSAGE: </b>".mysql_error()."</p>";
}

echo $init_html;
echo GetHeader($session);

echo "<br /><p><a class=\"small\" href=\"allstatenomadmin.php?session=$session\">&larr; Return to NCPA Academic All-State Main Menu</a></p><h2>NCPA Academic All-State Letter:</h2>";
if($save && $saveerror!='')
   echo "<div class=\"alert\" style=\"width:600px;\">$saveerror</div>";
else if($save)
   echo "<div class=\"alert\" style=\"width:600px;\"><p><b><i>Your changes have been saved.</b></i></div>";
 
echo "<div class=\"alert\" style=\"width:600px;\"><p><b>INSTRUCTIONS:</b> Edit the body of the letter in the large text box below and click \"SAVE LETTER\" to save your changes. Changes to the design of the header and to the signatures at the bottom must be done by the programmer.</p><p><b>NOTE:</b> Use <b>[YEAR]</b> to indicate that you want the current school year to be inserted into the letter in that location. For example: \"Congratulations on being selected as a [YEAR] Academic All-Stater!\" would show on the generated letter as \"Congratulations on being selected as a $fallyear-$springyear All-Stater!\"</div>";
echo "<form method=post action=\"allstatenomletteradmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";

echo "<div style=\"text-align:center;width:700px;padding:20px 50px;\" class=\"normalwhite\">";

//HEADER (STATIC)
echo "<img src=\"../images/logofullsize.png\" style=\"width:200px;\"><br><br>";
echo "<b>NEBRASKA SCHOOL ACTIVITIES ASSOCIATION</b><br>
500 Charleston St.<br>
Suite 1 <br>
Lincoln, Nebraska 68508<br>
(402) 489-0386<br><br><br>";

//LETTER BODY (EDITABLE)
$sql="SELECT * FROM allstatenomletter";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$row[letterbody]=preg_replace("/\\r\\n/","",$row[letterbody]);
$row[letterbody]=preg_replace("/\<br\>/","\r\n",$row[letterbody]);
echo "<textarea style=\"width:100%;height:300px;\" name=\"letterbody\">$row[letterbody]</textarea>";

//SIGNATURES (STATIC)
echo "<br><br><p style=\"text-align:left;\">Sincerely,</p><br>";
echo "<div style=\"float:left;\"><img src=\"../images/jay.png\" style=\"height:50px;margin:0 0 15px 0;\"><br>Jay Bellar<br>
Executive Director<br>Nebraska School Activities Association</div>";
echo "<div style=\"float:right;\"><img src=\"../images/LouSignature.png\" style=\"height:50px;margin: 0 0 15px 0;\"><br>
Louis Andersen<br>
Executive Director<br>Nebraska Chiropractic Physicians Association</div>
<div style=\"clear:both;\"></div>";

echo "</div>";

//SAVE
echo "<br><input type=submit name=\"save\" value=\"SAVE LETTER\" class=\"fancybutton\">";


echo "</form>";

echo $end_html;
?>
