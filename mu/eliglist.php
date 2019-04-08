<?php
if($_REQUEST['get_argv']==1){
	$argv=array();
	if(isset($_GET['var1']))	$argv[1]=$_GET['var1'];
	if(isset($_GET['var2']))	$argv[2]=$_GET['var2'];
	if(isset($_GET['var3']))	$argv[3]=$_GET['var3'];
	if(isset($_GET['var4']))	$argv[4]=$_GET['var4'];
	if(isset($_GET['var5']))	$argv[5]=$_GET['var5'];
	if(isset($_GET['var6']))	$argv[6]=$_GET['var6'];
	if(isset($_GET['var7']))	$argv[7]=$_GET['var7'];
	if(isset($_GET['var8']))	$argv[8]=$_GET['var8'];
	if(isset($_GET['var9']))	$argv[9]=$_GET['var9'];
	if(isset($_GET['var10']))	$argv[10]=$_GET['var10'];
	if(isset($_GET['var11']))	$argv[11]=$_GET['var11'];
	if(isset($_GET['var12']))	$argv[12]=$_GET['var12'];
}
//eliglist.php: show list of eligible music students for this school
require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

if(!$session) $session=$argv[1];

$header=GetHeader($session);
$level=GetLevel($session);
if($level==1 && $argv[2]) $school_ch=ereg_replace("`","'",$argv[2]);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch || $level>1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo "<br>";
echo "<table width=100%><tr align=center><td>";
echo "<table width=90%><tr align=left><td><a class=small href=\"javascript:print();\">Print this Screen</a></td></tr></table>";
echo "<table cellspacing=5 cellpadding=5><caption><font style=\"font-size:9pt;\"><b>$school's Eligible Music Participants:</b></font></caption>";
$csv.="\"$school's Eligible Music Participants:\"\r\n\r\n";
echo "<tr align=center valign=top><td>";	//BOYS:
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption><font style=\"font-size:9pt;\"><b>BOYS:</b></font></caption>";
$csv.="\"BOYS:\"\r\n";
echo "<tr align=center><td><b>Student Name</b></td><td><b>Grade</b></td><td>VM</td><td>IM</td></tr>";
$csv.="\"Student Name\",\"Grade\",\"VM\",\"IM\"\r\n";
$sql="SELECT * FROM eligibility WHERE school='$school2' AND eligible='y' AND (vm='x' OR im='x') AND gender='M' ORDER BY last,first,middle";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>$row[first] $row[middle] $row[last]</td>";
   echo "<td align=center>".GetYear($row[semesters])."</td><td align=center>".strtoupper($row[vm])."</td>";
   echo "<td align=center>".strtoupper($row[im])."</td></tr>";
   $csv.="\"$row[first] $row[middle] $row[last]\",\"".GetYear($row[semesters])."\",\"$row[vm]\",\"$row[im]\"\r\n";
}
$csv.="\r\n";
echo "</table>";
echo "</td><td><table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption><font style=\"font-size:9pt;\"><b>GIRLS:</b></font></caption>";
$csv.="\"GIRLS:\"\r\n";
echo "<tr align-center><td><b>Student Name</b></td><td><b>Grade</b></td><td>VM</td><td>IM</td></tr>";
$csv.="\"Student Name\",\"Grade\",\"VM\",\"IM\"\r\n";
$sql="SELECT * FROM eligibility WHERE school='$school2' AND eligible='y' AND (vm='x' OR im='x') AND gender='F' ORDER BY last,first,middle";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=lert><td>$row[first] $row[middle] $row[last]</td>";
   echo "<td align=center>".GetYear($row[semesters])."</td><td align=center>".strtoupper($row[vm])."</td>";
   echo "<td align=center>".strtoupper($row[im])."</td></tr>";
   $csv.="\"$row[first] $row[middle] $row[last]\",\"".GetYear($row[semesters])."\"\r\n";
}
echo "</table></td></tr>";
echo "</table>";
//check if head coop for schools co-oping for V & I:
if(IsCooping($school,"Vocal") && IsCooping($school,"Instrumental") && IsHeadCoopSchool($school,"Vocal"))
{
   $schools=GetMusicCoopSchools($school,"Vocal");
   for($i=0;$i<count($schools);$i++)
   {
      if($schools[$i]!=$school)
      {
         $cursch=$schools[$i]; $cursch2=addslashes($cursch);
         echo "<table cellspacing=5 cellpadding=5><caption><font style=\"font-size:9pt;\"><b>$cursch's Eligible Music Participants:</b></font></caption>";
         $csv.="\"$cursch's Eligible Music Participants:\"\r\n\r\n";
         echo "<tr align=center valign=top><td>";        //BOYS:
         echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
         echo "<caption><font style=\"font-size:9pt;\"><b>BOYS:</b></font></caption>";
         $csv.="\"BOYS:\"\r\n";
         echo "<tr align=center><td><b>Student Name</b></td><td><b>Grade</b></td></tr>";
         $csv.="\"Student Name\",\"Grade\"\r\n";
         $sql="SELECT * FROM eligibility WHERE school='$cursch2' AND eligible='y' AND (vm='x' OR im='x') AND gender='M' ORDER BY last,first,middle";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<tr align=left><td>$row[first] $row[middle] $row[last]</td>";
            echo "<td align=center>".GetYear($row[semesters])."</td></tr>";
            $csv.="\"$row[first] $row[middle] $row[last]\",\"".GetYear($row[semesters])."\"\r\n";
         }
         $csv.="\r\n";
         echo "</table>";
         echo "</td><td><table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
         echo "<caption><font style=\"font-size:9pt;\"><b>GIRLS:</b></font></caption>";
         $csv.="\"GIRLS:\"\r\n";
         echo "<tr align-center><td><b>Student Name</b></td><td><b>Grade</b></td></tr>";
         $csv.="\"Student Name\",\"Grade\"\r\n";
         $sql="SELECT * FROM eligibility WHERE school='$cursch2' AND eligible='y' AND (vm='x' OR im='x') AND gender='F' ORDER BY last,first,middle";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<tr align=lert><td>$row[first] $row[middle] $row[last]</td>";
            echo "<td align=center>".GetYear($row[semesters])."</td></tr>";
            $csv.="\"$row[first] $row[middle] $row[last]\",\"".GetYear($row[semesters])."\"\r\n";
         }
         echo "</table></td></tr>";
         echo "</table>";
      }//end if not this school
   }//end for each co-op school
}//end if head of full co-op
//write to Excel (.csv) file
$file=strtolower($school);
$file=ereg_replace(" ","",$file);
$file=ereg_replace("[.]","",$file);
$file=ereg_replace("\'","",$file);
$file=ereg_replace("-","",$file);
$file.="eliglist";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$file.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$file.csv");
//echo "<a target=new2 href=\"/home/nsaahome/attachments/$file.csv\">$file.csv</a>";

echo $end_html;
?>
