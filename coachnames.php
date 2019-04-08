<?php
//read in coaches' names from Melissa's file

require 'functions.php';
require 'variables.php';

$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//$open=fopen(citgf_fopen("coaches.csv"),"r");
//$data=file("coaches.csv");
$open=fopen(citgf_fopen("journalismnames.csv"),"r");
$data=file(getbucketurl("journalismnames.csv"));
fclose($open);
$num=count($data);
for($i=1;$i<$num;$i++)
{
   $data[$i]=split(",",$data[$i]);
   $school=$data[$i][0];
   $ix=0;
   $school=ereg_replace("\'","\'",$school);
   //while($ix<count($data[$i]))
   //{
      //$ix=38;
      //$x=$ix-1;
      $ix=1;
      $sport="Journalism"; //trim($data[$i][$x]);
      $name=trim($data[$i][$ix]);
      /*
      if($x==43)	//combine musics
      {
	 $name.="/";
	 $name.=$data[$i][46];
	 $name.="/";
	 $name.=$data[$i][48];
	 $ix=48;
	 $sport="Music";
      }
      if($x==49)	//combine newspaper and yearbook
      {
	 $name.="/";
	 $name.=$data[$i][52];
	 $ix=52;
	 $sport="Journalism";
      }
      if($sport=="Football")
      {
	 $sport.=" 6/8";
      }
      if(ereg("Cross",$sport))
      {
	 if(ereg("Boys",$sport)) $sport="Boys Cross-Country";
	 else $sport="Girls Cross-Country";
      }
      if(ereg("Track",$sport))
      {
	 $sport.=" & Field";
      }
      */
      $name=ereg_replace("\'","\'",$name);
      if($sport!="")
      {
         $sql="UPDATE logins SET name='$name' WHERE school='$school' AND level='3' AND sport='$sport'";
	 $result=mysql_query($sql);
	 /*
	 $ct=1;
	 while($ct>0)
	 {
	 $passcode=rand(100000,999999);
	 $sql="SELECT * FROM logins WHERE passcode='$passcode'";
	 $result=mysql_query($sql);
	 $ct=mysql_num_rows($result);
	 }
	 if($sport=="Speech")
	 {
	    $sql="INSERT INTO logins (name, school, level, passcode, sport) values ('$name', '$school', '3', '$passcode', '$sport')";
	    echo "$school $passcode<br>";
	 }
	 else
	 {
	    echo "$sport<br>";
	 }
	 $result=mysql_query($sql);
	 echo mysql_error();
	 if($sport=="Football 6/8")
	 {
	    $sport="Football 11";
	    $sql="UPDATE logins SET name='$name' WHERE school='$school' AND level='3' and sport='$sport'";
	    $result=mysql_query($sql);
	    echo mysql_error();
	 }
	 */
      }
   //}
}
?>
