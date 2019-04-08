<?php
//teamlist_g.php: list of students eligible to participate in track for school
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
if(!$session)
{
   $session=$argv[1]; $school_ch=$argv[1];
}
$sport="tr_g";
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

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
if((!$school_ch || $level==2 || $level==3) && $director!=1)
{
   $school=GetSchool($session);
}
else if($level==1)
{
   $school=$school_ch;
}
else if($director==1)
{
   $print=1;
   $school=$school_ch;
   $hostsch=GetSchool($session);
   $hostsch2=addslashes($hostsch);
   $sql="SELECT id FROM logins WHERE school='$hostsch2' AND level='$level'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[0];
   $sql="SELECT * FROM $db_name2.trgdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID2($school,$sport);
$team=GetSchoolName($sid,$sport);

$date=date("M d, Y");
$info=$init_html;
$info.="<body><table width=100%><tr><td align=center>";
$info.="<br><table cellspacing=0 cellpadding=5 class='nine' frame='all' rules='all' style=\"border:#808080 1px solid;\">";
$info.="<caption><b>$team Girls Track & Field District Roster:<br /></b>as of $date<br><br></caption>";
$csv="$team Girls Track & Field District Roster:\r\n";
$csv.=",Name (Grade)\r\n";
$info.="<tr align=center><th class=smaller colspan=2>Name (Grade)</th></tr>";
//get girls from this school (or co-op students) participating in track
$studs=explode("<result>",GetPlayers($sport,$school));
for($s=0;$s<count($studs);$s++)
{
   $stud=explode("<detail>",$studs[$s]);     //ID, name, school, eligible
   $x=$s+1;
   $info.="<tr align=left><td align=center>$x.</td>";
   $info.="<td>$stud[1]</td>";
   $name=ereg_replace(",","",$stud[1]);
   $csv.="$x,$name\r\n";
}
$info.="</table><br><b>Total: $s</b>";
$csv.="Total:,$s\r\n";

$info.="</td></tr></table></body></html>";
echo $info;

//write eligibility list to csv file to send along with dist entry form
$sch=strtolower($team);
$sch=ereg_replace(" ","",$sch);
$sch=ereg_replace("\'","",$sch);
$sch=ereg_replace("\.","",$sch);
$sch=ereg_replace("-","",$sch);
$filename="/home/nsaahome/attachments/";
$filename.=$sch."girlsdistroster";
$open=fopen(citgf_fopen("$filename.txt"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("$filename.txt");

$open=fopen(citgf_fopen("$filename.html"),"w");
fwrite($open,$info);
fclose($open); 
 citgf_makepublic("$filename.html");
?>
