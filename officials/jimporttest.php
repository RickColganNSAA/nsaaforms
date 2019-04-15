<?php
exit();

//THIS ISN'T USED ANYMORE - USE importtest.php

require 'variables.php';
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//import new online judges tests
$file1="playtest0708.txt";
$sport="sp";
$categ=$sport."test_categ";
$test=$sport."test";

$open=fopen(citgf_fopen($file1),"r");
$line1=file(getbucketurl($file1));
fclose($open);

$curcateg=""; $categplace=1;

for($i=0;$i<count($line1);$i++)
{
   $ques=split("\t",$line1[$i]);
   //PLACE - QUESTION - ANSWER - REFERENCE
   $place=trim($ques[0]);
   if($place>0)
   {
   $question=trim($ques[1]);
   $question=ereg_replace("\"","",$question);
   $question=addslashes($question);
   if($place<=50) $categid=1;
   else $categid=2;
   if($ques[2]=="FALSE") $answer="f";
   else if($ques[2]=="TRUE") $answer="t";
   else $answer="b";
   $reference=trim($ques[3]);
   if($question!='')
   {
      $sql="INSERT INTO $test (question,place,category,answer,reference) VALUES ('$question','$place','$categid','$answer','$reference')";
      $result=mysql_query($sql);
      echo "$sql<br>";
   }
   }
}
echo "<br>DONE!";
exit();
?>
