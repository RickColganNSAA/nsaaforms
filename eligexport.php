<?php
require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=GetLevel($session);

if(!$dbname || $dbname=="" && $level!=1)
   $dbname=$db_name;

session_start();
if($level==1)	//LET THEM SELECT YEAR AND SCHOOL
{
   echo $init_html.GetHeader($session);
   echo "<br><h2>Export a School's Eligibility List:</h2><form method=post action=\"eligexport.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
   echo "<p><b>Select a School Year:</b> <select name=\"dbname\" onChange=\"submit();\">";
             $sql="SHOW DATABASES LIKE '".$db_name."2%2%'";
             $result=mysql_query($sql);
             while($row=mysql_fetch_array($result))
             {
                $temp=split("$db_name",$row[0]);
                $year1=substr($temp[1],0,4);
                $year2=substr($temp[1],4,4);
			if($row[0]!='nsaascores20162017_')	{
                echo "<option value=\"$row[0]\"";
	        if($dbname==$row[0]) echo " selected";
	        echo ">$year1-$year2</option>";}
             }
             echo "<option value=\"$db_name\"";
	     if($dbname==$db_name) echo " selected";
	     echo ">This Year</option>";
             echo "</select></p>";
   echo "<p><b>Select a School:</b> <select name=\"school\"><option value=\"\">Select a School</option>";
   if($dbname!='')
   {
      $sql="SELECT * FROM $dbname.headers ORDER BY school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[school]\"";
         if($row[school]==$school) echo " selected";
         echo ">$row[school]</option>";
      }
   }
   echo "</select></p><p><input type=submit name=\"go\" value=\"Export Eligibility List\"></p>";
}
else
{
   $school=GetSchool($session);
}

if(isset($_SESSION['query']))
   $sql=$_SESSION['query'];
else if($dbname && $dbname!="" && $school && $school!='')
{
   $school2=addslashes($school);
   $sql="SELECT * FROM $dbname.eligibility WHERE school='$school2'";
   $sql.=" ORDER BY last,first,middle";
}
else $sql="";
if($sql!='')
{
//echo $sql;
   $result=mysql_query($sql);
   $csv="\"First\",\"Middle\",\"Last\",\"Gender\",\"DOB\",\"Semester\",\"Eligible\",\"International Transfer\",\"FB 6/8\",\"FB 11\",\"VB\",\"SB\",\"CC\",\"TE\",\"BB\",\"WR\",\"SW\",\"GO\",\"TR\",\"BA\",\"SO\",\"CH\",\"SP\",\"PP\",\"DE\",\"IM\",\"VM\",\"JO\",\"Ubo\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[first]\",\"$row[middle]\",\"$row[last]\",\"$row[gender]\",\"$row[dob]\",\"$row[semesters]\",\"$row[eligible]\",\"$row[foreignx]\",\"$row[fb68]\",\"$row[fb11]\",\"$row[vb]\",\"$row[sb]\",\"$row[cc]\",\"$row[te]\",\"$row[bb]\",\"$row[wr]\",\"$row[sw]\",\"$row[go]\",\"$row[tr]\",\"$row[ba]\",\"$row[so]\",\"$row[ch]\",\"$row[sp]\",\"$row[pp]\",\"$row[de]\",\"$row[im]\",\"$row[vm]\",\"$row[jo]\",\"$row[ubo]\"\r\n";
   }
   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $filename=$sch."eligibility.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
   if($level!=1)	//JUST DOWNLOAD THE FILE
   {
      header("Content-type: text/css");
      header("Content-Disposition: attachment; filename=".urlencode($filename)."");
      readfile(getbucketurl("/home/nsaahome/attachments/".$filename.""));
      exit();
   }
   else
   {
                $temp=split("$db_name",$dbname);
                $year1=substr($temp[1],0,4);
                $year2=substr($temp[1],4,4);
      echo "<p><a href=\"attachments.php?session=$session&filename=$filename\">Download the $year1-$year2 $school Eligibility List</a></p>";
      echo "</form>";
	echo "<br><br><br><A class=\"small\" href=\"welcome.php?session=$session\">Return Home</a>";
      echo $end_html;
      exit();
   }
}//END IF sql!=''
?>
