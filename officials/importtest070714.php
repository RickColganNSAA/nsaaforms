<?php
//IMPORT PART 1 TESTS
exit();
require 'variables.php';
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//before running this script, empty the following tables:
//__test (Where the test questions go)
//__test_categ (Categories, ex: "Questions 1-10")
//_test_mchoices (The multiple choice options for each question)
//__test_results (The test results)

//import new online officials test
$file1="batest.txt"; 	//File containing properly-formatted test
$sport="ba";			//The sport, abbreviated
$categ=$sport."test_categ";	//Categories table
$test=$sport."test";		//Questions, correct answers and rule references table
$mchoices=$test."_mchoices";	//Multiple choice options table

if(!$open=fopen(citgf_fopen($file1),"r")) echo "CAN'T OPEN";
$line1=file(getbucketurl($file1));
fclose($open);

$curcateg=""; $categplace=1;
$orderby=1;	//FOR MCHOICE OPTIONS
$questionid=0;
for($i=0;$i<count($line1);$i++)
{
   $ques=split("\t",$line1[$i]);
   //0 - CATEGORY
   $category=trim($ques[0]);
   $category=ereg_replace("\"","",$category);
   $category=addslashes(ereg_replace(":","",$category));
   //1 - PLACE (IF QUESTION)
   if(trim($ques[1])=="")	//ROW WITH MULTIPLE CHOICES OTHER THAN TRUE/FALSE
   {
      if(trim($ques[2])!='')
      {
      //2 - CHOICE VALUE
      $choicevalue=ereg_replace("[^a-zA-Z0-9]","",$ques[2]);
      $choicevalue=strtolower($choicevalue);
      //3 - CHOICE LABEL
      $choicelabel=ereg_replace("\"","",$ques[3]);
      $choicelabel=trim(addslashes($choicelabel));
	if($choicelabel=="TRUE") $choicelabel="True";
	else if($choicelabel=="FALSE") $choicelabel="False";
      $sql="INSERT INTO $mchoices (questionid,orderby,choicevalue,choicelabel) VALUES ('$questionid','$orderby','$choicevalue','$choicelabel')";
      $result=mysql_query($sql);
      $orderby++;
      }
   }
   else
   {
      $orderby=1;	//RESET ORDER BY FOR MCHOICE OPTIONS
      $place=trim($ques[1]);
      //2 - QUESTION
      $question=trim(ereg_replace("\"","",$ques[2]));
      //3 - [BLANK]
      //4 - ANSWER
      $answer=trim(strtolower($ques[4]));
      if($answer=="true") $answer="a";
      else if($answer=="false") $answer="b";
      //5 - REFERENCE
      $ques[5]=ereg_replace("\"","",$ques[5]);
      $reference=addslashes(trim($ques[5]));
      if($curcateg!=$category && $category!='')	//NEW CATEGORY?
      {
         $sql="INSERT INTO $categ (category,place) VALUES ('$category','$categplace')";
         $result=mysql_query($sql);
         echo "$sql<br>";
         $categplace++; $curcateg=$category;
         $sql="SELECT id FROM $categ WHERE category='$category'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $categid=$row[0]; 
      }
      $question=ereg_replace("\"","",$question);
      $question=addslashes($question);
      if($question!='')
      {
         $sql="INSERT INTO $test (question,place,category,answer,reference) VALUES ('$question','$place','$categid','$answer','$reference')";
         $result=mysql_query($sql);
         echo "$sql<br>";
         $questionid=mysql_insert_id();
      }
   }//end if row with question/answer
}
//NOW, for any question that doesn't have entry in $mchoices yet, add true/false options
$sql="SELECT * FROM $test";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $questionid=$row[id];
   $sql2="SELECT * FROM $mchoices WHERE questionid='$questionid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql3="INSERT INTO $mchoices (questionid,orderby,choicevalue,choicelabel) VALUES ('$questionid','0','a','True')";
      $result3=mysql_query($sql3);
      $sql3="INSERT INTO $mchoices (questionid,orderby,choicevalue,choicelabel) VALUES ('$questionid','1','b','False')";
      $result3=mysql_query($sql3);
   }
}
echo "<br>DONE!";
exit();
?>
