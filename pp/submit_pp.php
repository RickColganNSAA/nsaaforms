<?php
//submit_pp.php: submits infor from edit_pp.php to database

require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session))
{
  // header("Location:../index.php");
   //exit();
}

if($submit=="Cancel")
{
   header("Location:../welcome.php?session=$session");
   exit();
}

if($school_ch && GetLevel($session)==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID2($school2,'pp');
   if(!empty($_FILES["imageUpload"]["name"])){
	$image = $_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	$sql="UPDATE ppschool SET filename='$image'WHERE sid='$sid'";
    $result=mysql_query($sql);		
    }
//check if this is state form submission
if($state==1)
{
   $table1="pp_state";
   $table2="pp_state_students";
}
else
{
   $table1="pp";
   $table2="pp_students";
}

//DELETE OLD ENTRY INFO
$sql="DELETE FROM $table2 WHERE school='$school2'";
$result=mysql_query($sql);

   if($reset)
   {
      header("Location:edit_pp.php?session=$session&school_ch=$school_ch");
      exit();
   }

//update school & play info
   //get playing time into correct format
   $time="$hrs:$min";
   //get rid of invalid chars
   $asst=addslashes($asst);
   $title=addslashes($title);
   $playwright=addslashes($playwright);
   $director=addslashes($director);
   $contest_site=addslashes($contest_site);

   $sql="UPDATE logins SET asst_coaches='$asst' WHERE school='$school2' AND sport='Play Production'";
   $result=mysql_query($sql);

$sql="SELECT * FROM $table1 WHERE school='$school2'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)	//UPDATE
{
   $sql="UPDATE $table1 SET time='$time', title='$title', short_title='$short_title', playwright='$playwright', director='$director', royalty='$royalty',permission='$permission',weapons='$weapons',adult='$adult',contest_site='$contest_site' WHERE school='$school2'";
}
else				//INSERT
{
   $sql="INSERT INTO $table1 (time, title, playwright, director, royalty,permission,weapons,adult, contest_site, school, short_title) VALUES ('$time','$title','$playwright','$director','$royalty','$permission','$weapons','$adult','$contest_site','$school2','$short_title')";
}
$result=mysql_query($sql);
//echo "$sql<br>".mysql_error();
//exit();

//update cast & crew info
$partorder=1;
for($i=0;$i<count($stud);$i++)
{
   if($stud[$i]!="Choose Student" && $part[$i]!="")
   {
   $part[$i]=ereg_replace("\"","\"",$part[$i]);
   $part[$i]=ereg_replace("\'","\'",$part[$i]);

   $sql="INSERT INTO $table2 (part, student_id, partorder,school) VALUES ('$part[$i]','$stud[$i]','$partorder','$school2')";
   $result=mysql_query($sql);

   $partorder++;
   }
}

//update crew members list
for($i=0;$i<count($crew);$i++)
{
   if($crew[$i]!="Choose Student")
   {
      $sql="INSERT INTO $table2 (student_id,crew,school) VALUES ('$crew[$i]','y','$school2')";
      $result=mysql_query($sql);
   }
}

if($send=='y')	//auto send to view_pp;php and e-mail file to NSAA
{
   header("Location:view_pp.php?session=$session&school_ch=$school_ch&send=$send");
   exit();
}
else if($submit=="Save & Keep Editing")
{
   header("Location:edit_pp.php?session=$session&school_ch=$school_ch");
   exit();
}
else if($submit=="Save & View Form")
{
   header("Location:view_pp.php?session=$session&school_ch=$school_ch");
   exit();
}
?>
