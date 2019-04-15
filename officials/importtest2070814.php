<?php
/* IMPORT PART 2 ONLINE TESTS */
exit();
require 'variables.php';
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//import new online officials tests (PART 2 TESTS)
$file1="bapart2.txt"; 
$sport="ba";
$categ=$sport."test2_categ";
$test=$sport."test2";
$mchoices=$sport."test2_mchoices";

if(!$open=fopen(citgf_fopen($file1),"r")) echo "CAN'T OPEN";
$line1=file($file1);
fclose($open);

$curcateg=""; $categplace=1;
$orderby=1;	//FOR MCHOICE OPTIONS
$questionid=0;
for($i=0;$i<count($line1);$i++)
{
   $ques=split("\t",$line1[$i]);
   //0 - PLACE (IF QUESTION)
   if(trim($ques[0])=="")	//ROW WITH MULTIPLE CHOICES OTHER THAN TRUE/FALSE
   {
      //1 - CHOICE VALUE
      $choicevalue=ereg_replace("[^a-zA-Z0-9]","",$ques[1]);
      $choicevalue=strtolower($choicevalue);
      //2 - CHOICE LABEL
      $choicelabel=ereg_replace("\"","",$ques[2]);
      $choicelabel=trim(addslashes($choicelabel));
      if($choicelabel=="TRUE") $choicelabel="True";
      else if($choicelabel=="FALSE") $choicelabel="False";
      $sql="INSERT INTO $mchoices (questionid,orderby,choicevalue,choicelabel) VALUES ('$questionid','$orderby','$choicevalue','$choicelabel')";
      $result=mysql_query($sql);
      if(mysql_error()) {
        echo "<b>Error:</b> " . mysql_error() . "<br>";
      }
	echo "$sql<br>";
      $orderby++;
   }
   else
   {
      $orderby=1;	//RESET ORDER BY FOR MCHOICE OPTIONS
      //0 - PLACE
      $place=trim($ques[0]);
      //1 - QUESTION
      $question=trim(ereg_replace("\"","",$ques[1]));
      //2 - [BLANK]
      //3 - ANSWER
      $answer=trim(strtolower($ques[3]));
      //4 - REFERENCE
      $ques[4]=ereg_replace("\"","",$ques[4]);
      $reference=addslashes(trim($ques[4]));
      $question=ereg_replace("\"","",$question);
      $question=addslashes($question);
      if($question!='')
      {
         $sql="INSERT INTO $test (question,place,answer,reference) VALUES ('$question','$place','$answer','$reference')";
         $result=mysql_query($sql);
            if(mysql_error()) {
                echo "<b>Error:</b> " . mysql_error() . "<br>";
            }
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
      if(mysql_error()) {
        echo "<b>Error:</b> " . mysql_error() . "<br>";
      }
   }
}
echo "<br>DONE!";
exit();
?>
