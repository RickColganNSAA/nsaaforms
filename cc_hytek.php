<?php

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

if($submit)
{
   citgf_copy($userfile,"/home/nsaahome/reports/cc_hytek.csv");
   $open=fopen(citgf_fopen("cc_hytek.csv"),"r");
   $line=file(getbucketurl("cc_hytek.csv"));
   $i=1;
   $cursch="";
   $csv="";
   while($i<count($line))
   {
      $line[$i]=split(",",trim($line[$i]));
      if($cursch!=$line[$i][0])
      {
	 //new line: put school and coach
	 $csv.="\r\n".$line[$i][0].",".$line[$i][1].",";
	 $cursch=$line[$i][0];
      }
      //same line: add next contestant
      $csv.=$line[$i][2].",".$line[$i][3].",".$line[$i][4].",";
      $i++;
   }
   fclose($open);
   citgf_unlink("/home/nsaahome/reports/cc_hytek.csv");
   $open=fopen(citgf_fopen("/home/nsaahome/reports/cc_export.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/cc_export.csv");
   echo $init_html; echo $header;
   echo "<br><br><a href=\"exports.php?session=$session&filename=cc_export.csv\" target=new>Click Here to Download File</a>";
   echo $end_html;
   exit();
}

echo $init_html;
echo $header;
echo "<br><br>";
echo "<form method=post action=\"cc_hytek.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=$session>";
echo "<table><caption><b>Upload Hytek Export File:</b><br>";
echo "<table width=500><tr align=left><td><b>INSTRUCTIONS:</b><i> The file you upload below should be an export from your Hytek program and should be of the following format:<br><br></i>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;School Name, Coach Name, Athlete Name, Bib #, Grade<br><br>";
echo "<i>There should be ONE ROW PER ATHLETE.  The output file will have the following format:<br><br></i>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;School Name, Coach Name, Athlete 1 Name, Bib #, Grade, Athlete 2 Name, Bib #, Grade, ...<br><br>";
echo "<i>There will be ONE ROW PER SCHOOL in the output file.</i>";
echo "</td></tr></table>";
echo "<hr></caption>";
echo "<tr align=center><th>Select File:&nbsp;&nbsp;";
echo "<input type=file name=userfile></th></tr>";
echo "<tr align=center><td><input type=submit name=submit value=\"Upload\"></td></tr>";
echo "</table></form><br>";
echo "<a class=small href=\"cc_main.php?session=$session\">Cross-Country District Results & State Qualifiers Main Menu</a>";
?>
