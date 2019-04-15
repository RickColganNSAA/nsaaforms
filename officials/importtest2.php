<?php
/******************************
importtest2.php: 
NSAA user can import CSV file of test questions/answers/references
for Part 2 Online Tests
Created 7/8/14 by Ann Gaffigan
*******************************/
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

if($sport=='' || !$sport) exit();
$sportname=GetSportName($sport);

//get tables for this sport's test
$test=$sport."test2";
$answers=$test."_answers";
$mchoices=$test."_mchoices";
$results=$test."_results";
$letters=array("a","b","c","d","e");	//The assumption is that there are at most 5 multiple choice options

$uploadedfile=$_FILES['importfile']['tmp_name'];
$errors="";
if($sport && $sport!='' && is_uploaded_file($uploadedfile))
{
   //before importing, empty the following tables:
   $sql="DELETE FROM $test";
   $result=mysql_query($sql);
   $sql="DELETE FROM $mchoices";
   $result=mysql_query($sql);
   $sql="DELETE FROM $results";
   $result=mysql_query($sql);
   $sql="DELETE FROM $answers";
   $result=mysql_query($sql);

   //import new online officials test
   $file1="/home/nsaahome/attachments/".$sport."test.txt";
   if(!citgf_copy($uploadedfile,$file1))  
      $errors.="<p>Could not copy uploaded file.</p>";
   
   else
   {
      if(!$open=fopen(citgf_fopen($file1),"r")) 
         $errors.="<p>Error opening the uploaded file.</p>";
      else
      {
         $line1=file(getbucketurl($file1));
         fclose($open);
	 for($i=0;$i<count($line1);$i++)
	 {
   	    $ques=split("\t",$line1[$i]);
   	    $place=trim($ques[0]);
    	    $place=preg_replace("/[^0-9]/","",$place);
   	    if(trim($place)!="")	//ROW WITH MULTIPLE CHOICES OTHER THAN TRUE/FALSE
   	    {
	       $question=trim($ques[1]);
               $question=trim(addslashes($question));
	       $answernum=trim($ques[7]);
               $answernum=preg_replace("/[^0-9]/","",$answernum);
	       $answernum--;
	       $correct=$letters[$answernum];
	       $reference=trim($ques[8]);
               $reference=ereg_replace("\"","",$reference);
               $reference=trim(addslashes($reference));
     	       $sql="INSERT INTO $test (question,place,answer,reference) VALUES ('$question','$place','$correct','$reference')";
	       $result=mysql_query($sql);
	       $questionid=mysql_insert_id();
	       for($j=0;$j<5;$j++)
	       {
	          $ix=2+$j;
      		  $choicevalue=$letters[$j];
      	          $choicelabel=ereg_replace("\"","",$ques[$ix]);
      		  $choicelabel=trim(addslashes($choicelabel));
		  if($choicelabel=="TRUE") $choicelabel="True";
		  else if($choicelabel=="FALSE") $choicelabel="False";
		  if($choicelabel!='')
	          {
      	             $sql="INSERT INTO $mchoices (questionid,orderby,choicevalue,choicelabel) VALUES ('$questionid','$j','$choicevalue','$choicelabel')";
      	             $result=mysql_query($sql);
	          }
	       }//FOR EACH MC
	    }//END IF place!=''
	 }// END FOR EACH LINE IN FILE
      } //END IF FILE WAS OPENED OK
   } //END IF FILE WAS COPIED OK
} //END IF FILE WAS UPLOADED

echo $init_html;
echo GetHeader($session,"test2report");
echo "<br>";
echo "<a href=\"test2report.php?session=$session&sport=$sport\" class=small>Return to $sportname Online Test Admin</a><br><br>";
echo "<form method=post action=\"importtest2.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<div style=\"width:800px;\">";
if($import && $errors!='')
   echo "<div clas=\"error\">$errors</div>";
else if($import && is_uploaded_file($uploadedfile))
   echo "<div class=alert>The test has been successfully imported! <a href=\"edittest2.php?session=$session&sport=$sport\">View and edit the test questions HERE</a></div><br><br>";
echo "<h1>Import PART 2 $sportname Test Questions/Answers:</h1>";
echo "<p><i>Please upload the $sportname Part 2 Test questions, answers and rule references by following the instructions below.</i></p>
	<ol>
	<li>Make sure the information is in the <b>appropriate columns</b> in your file. The columns should be in the following order:<br /><br />
	<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">
	<tr align=center><td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td><td>G</td><td>H</td><td>I</td></tr>
	<tr align=center><td>Place</td><td>Question</td><td>Answer<br />Choice<br />1</td><td>Answer<br />Choice<br />2</td><td>Answer<br />Choice<br />3</td><td>Answer<br />Choice<br />4</td><td>Answer<br />Choice<br />5</td><td>Correct<br />Answer</td><td>Rule<br />Reference</td></tr>
	<tr align=left><td>1</td><td>The penalty for both offensive and defensive<br />pass interference fouls is 15 yards.</td><td>True</td><td>False</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>1</td><td>7-5-10</td></tr>
	<tr align=center><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td></tr>
	</table></li><br />
	<li>Make sure your file is in the correct <b>FORMAT</b>. It must be an Excel file that was saved as a <b>Tab-Delimited (.txt)</b> file (on a Mac, be sure to select \"Windows Formatted Text (.txt)\").</li><br />
	<li>Finally, make sure to there are no <b>merged</b> or <b>extra blank</b> columns in your file.</li><br />
	<li><b>PLEASE NOTE:</b> Uploading a file below will ERASE all EXISTING test questions in the database.</li><br />
	<li><b>Upload the file HERE:</b> <input type=file name=\"importfile\"></li><br />
	<li><b>Click HERE:</b> <input type=submit name=\"import\" value=\"Import\"></li>
	</ul>";
echo "</div>";
echo "</form>";

?>
