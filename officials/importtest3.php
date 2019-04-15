<?php
exit();
require 'variables.php';
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//import new online officials tests (PART 2 TESTS)
$file1="2012vbpart2.txt";
$sport="vb";
$categ=$sport."test2_categ";
$test=$sport."test2";
$mchoices=$sport."test2_mchoices";

if(!$open=fopen(citgf_fopen($file1),"r")) echo "CAN'T OPEN";
$line1=file(getbucketurl($file1));
fclose($open);

$curcateg=""; $categplace=1;
$orderby=1;     //FOR MCHOICE OPTIONS
$questionid=0; $curplace=0; $mid=1;
for($i=0;$i<count($line1);$i++)
{
   $ques=split("\t",$line1[$i]);
   if(trim($ques[3])!='')
   {
      $orderby=1;
      $correct=strtolower(trim($ques[3])); $reference=trim($ques[4]);
      $place=trim($ques[0]);
      $sql="UPDATE $test SET answer='$correct',reference='$reference' WHERE place='$place'";
      $result=mysql_query($sql);
     echo "$sql<br>";
      $sql="SELECT * FROM $test WHERE place='$place'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $quesid=$row[id];
   }
   else
   {
      //1 - CHOICE VALUE
      $choicevalue=ereg_replace("[^a-zA-Z0-9]","",$ques[1]);
      $choicevalue=strtolower($choicevalue);
      //2 - CHOICE LABEL
      $choicelabel=ereg_replace("\"","",$ques[2]);
      $choicelabel=trim(addslashes($choicelabel));
      if($choicelabel=="TRUE") $choicelabel="True";
      else if($choicelabel=="FALSE") $choicelabel="False";
      $sql="INSERT INTO $mchoices (questionid,orderby,choicevalue,choicelabel) VALUES ('$quesid','$orderby','$choicevalue','$choicelabel')";
      //$sql="UPDATE $mchoices SET choicevalue='$choicevalue',choicelabel='$choicelabel',orderby='$orderby' WHERE id='$mid'";
      $result=mysql_query($sql);
      if(mysql_error()) {
        echo "<b>Error:</b> " . mysql_error() . "<br>";
      }
        echo "$sql<br>";
      $orderby++; $mid++;
   }
}
echo "DONE!";
?>
