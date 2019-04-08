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
/***********************************************************
inhousedirexport.php
Created: 8/8/06
Produces CSV export of School Directory for NSAA Office Use
Fields mirror fields of In-House Access Database
Executed to run in background by direxportexec.php
************************************************************/
$session=$argv[1];

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   exit();
}

//Export Full School Directory:
$sql="SELECT * FROM headers WHERE inactive!='x' ORDER BY school";
$result=mysql_query($sql);
$dat="";
$dat.="Current Yr\tEnrollment\tSchool\tContact Name\tContact Person\tContact Person e-mail\tFB Type\tno rule-FB\tno rule-VB\tno rule-GTR\tno rule-BTR\tno rule-SB\tno rule-BB\tno rule-GB\tno rule-WR\tno rule-SW\tno rule-GSO\tno rule-BSO\tno rule-BA\tState FB\tState VB\tState WR\tState GBB\tState BBB\tState PP\tState SP\tState BSO\tState GSO\tState BA\tState SB\tState GGO\tState BGO\tState CC\tState TR\tAddress1\tAddress2\tCity, State\tZip\tNSAA District\tDistrict\tColors\tMascot\tConference\tPhone\tFax\t";
$dat.="Su\tSu phone\tSu e-mail\tPr\tPr phone\tPr e-mail\tAD\tAD Phone\tAD e-mail\tAD cell\tAct Dir\tAct Dir phone\tAct Dir e-mail\tAssist AD\tAssist AD phone\tAssist AD e-mail\tAct Sec\tAct Sec phone\tAct Sec e-mail\tFB\tFB Phone\tFB e-mail\tBB\tBB Phone\tBB e-mail\tGB\tGB Phone\tGB e-mail\tBT\tBT phone\tBT e-mail\tGT\tGT phone\tGT e-mail\tBCC\tBCC phone\tBCC e-mail\tGCC\tGCC phone\tGCC e-mail\tVB\tVB phone\tVB e-mail\tWR\tWR phone\tWR e-mail\tBGO\tBGO phone\tBGO e-mail\tGGO\tGGO phone\tGGO e-mail\t";
//$dat.="BGy\tBGy phone\tBGy e-mail\tGGy\tGGy phone\tGGy e-mail\t";
$dat.="BTen\tBTen phone\tBTen e-mail\tGTen\tGTen phone\tGTen e-mail\tBSw\tBSw phone\tBSw e-mail\tGSw\tGSw phone\tGSw e-mail\tBa\tBa phone\tBA e-mail\tBSo\tBSo phone\tBSo e-mail\tGSo\tGSo phone\tGSo e-mail\tSP\tSP phone\tSP e-mail\tPP\tPP phone\tPP e-mail\tDeb\tDeb phone\tDeb e-mail\tIM\tIM phone\tIM e-mail\tVM\tVM phone\tVM e-mail\tOrchestra\tO phone\tO e-mail\tSB\tSB phone\tSB e-mail\tBd Pres\tBd e-mail\tStud Coun\tStud Coun phone\tStud Coun e-mail\tHome Page\tSup Fax\tTrainer\tTrainer phone\tTrainer e-mail\tJ\tJ phone\tJ e-mail\tGuid Coun\tGuid Coun phone\tGuid Coun e-mail\r\n";

echo $init_html;
echo "<table><tr align=left><td colspan=5>";
echo "<b>(Please click the Reload button on your browser to view the progress of this export.<br>The export is complete when you see the links to download the export files at the bottom of this screen.)</b>";
echo "<br><br>Directory Export started at ".date("r",time())."...<br><br></td></tr>";
$ix=0;
$currentyr=date("Y");
while($row=mysql_fetch_array($result))
{
   $cursch=$row[school];
   if($ix%5==0) echo "<tr align=left>";
   echo "<td>$cursch...</td>";
   //EXPORT 1:
   //take "conference" out of row 13 
   $row[conference]=ereg_replace("Conference","",$row[conference]);
   $row[conference]=ereg_replace("conference","",$row[conference]);
   $school2=ereg_replace("\'","\'",$row[school]);
   $sql2="SELECT * FROM logins WHERE school='$school2' AND maincontact='y'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[level]==2)	//AD
      $row2[sport]="Ath Dir";
   $dat.="$currentyr\t".trim($row[enrollment])."\t$row[school]\t$row2[name]\t$row2[sport]\t$row2[email]\t\t\t\t\t\t\t\t\t\t\t\t\t\t$row[statefb]\t$row[statevb]\t$row[statewr]\t$row[statebbg]\t$row[statebbb]\t$row[statepp]\t$row[statesp]\t$row[statesob]\t$row[statesog]\t$row[stateba]\t$row[statesb]\t$row[stategog]\t$row[stategob]\t$row[statecc]\t$row[statetr]\t$row[address1]\t$row[address2]\t$row[city_state]\t$row[zip]\t".trim($row[nsaadist])."\t\t$row[color_names]\t$row[mascot]\t$row[conference]\t$row[phone]\t$row[fax]\t";
   $tempph=split("-",$row[phone]);
   $mainareacode=$tempph[0];
   $mainphone=$tempph[1]."-".$tempph[2];
   for($i=0;$i<count($staffs);$i++)
   {
      if($staffs[$i]!="Home Page" && $staffs[$i]!="Sup Fax") // && $staffs[$i]!="AD Secretary")
      {
	 if($staffs[$i]!="Athletic Director") 
	    $sql2="SELECT * FROM logins WHERE school='$school2' AND sport LIKE '$staffs[$i]%'";
	 else
	    $sql2="SELECT * FROM logins WHERE school='$school2' AND level=2";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($staffs[$i]!="Orchestra" && trim($row2[name])=="" && $staffs[$i]!="Superintendent" && $staffs[$i]!="Principal" && $staffs[$i]!="AD Secretary" && !ereg("Director",$staffs[$i])&& !ereg("Gymnastics",$staffs[$i]) && !ereg("Cheer",$staffs[$i]) && !ereg("Board",$staffs[$i]) && !ereg("Student Council",$staffs[$i]) && !ereg("Trainer",$staffs[$i]))	//if no name given but registered for this activity, put TBA
	 {
	    $abbrev=GetActivityAbbrev2($staffs[$i]);
	    //echo "$cursch $abbrev<br>";
	    if(IsRegistered($cursch,$abbrev))
	    {
	       $row2[name]="TBA";
	    }
	    //echo IsRegistered($cursch,$abbrev)."<br>";
	 }
	 $dat.=trim($row2[name])."\t";
	 if($staffs[$i]!="Board President") //no phone for board pres
	 {
	    $curphone=split("-",$row2[phone]);
	    $curphonebase=trim($curphone[1])."-".trim($curphone[2]);
	    if($curphonebase=="-" || $curphonebase==$mainphone) //coach's phone same as school phone
	    {
	       if(trim($curphone[3])!="") //an ext is given
		  $newphone="Ext. $curphone[3]";
	       else //no ext given
		  $newphone="";
	    }
	    else	//coach's phone diff from school
	    {
	       if(trim($curphone[3])!="") //an ext is given
	       {
		  if($curphone[0]==$mainareacode || trim($curphone[0])=="")	//same area code as school
		     $newphone=$curphonebase." Ext. ".$curphone[3];
		  else	//diff area code from school
		     $newphone=$curphone[0]."-".$curphonebase." Ext. ".$curphone[3];
	       }
	       else //no ext given
	       {
		  if($curphone[0]==$mainareacode || trim($curphone[0])=="")	//same area code as school
		     $newphone=$curphonebase;
		  else	//diff area code from school
		     $newphone=$curphone[0]."-".$curphonebase;
	       }
	    }
	    $dat.=trim($newphone)."\t";
	 }
	 if(trim($row2[email])=="none")	//if no e-mail, put "" instead of "none"
	    $row2[email]="";
	 $dat.=trim($row2[email])."\t";
	 if($staffs[$i]=="Athletic Director") 
	 {
            $cell=trim($row2[hours]);
            $cell=split("-",$cell);
            if($cell[0]==$mainareacode || trim($cell[0])=="")     //same area code as school
               $newphone="$cell[1]-$cell[2]";
            else  //diff area code from school
               $newphone="$cell[0]-$cell[1]-$cell[2]";
	    if($newphone=="-" || $newphone=="--") $newphone="";
	    //$dat.="$newphone\t";
	    $dat.="\t";
	 }
      }	//end if not Home Page and not Sup Fax 
      else 
      {
	 if($staffs[$i]=="Home Page")
	 {
	    $sql3="SELECT website FROM headers WHERE school='$school2'";
	    $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	    $row3[website]=ereg_replace("http://","",$row3[website]);
	    $dat.=$row3[website]."\t\t"; //(leave blank for Sup Fax)
	 }
      }
   }
   $dat.="\r\n";	//new line for each school

   if(($ix+1)%5==0) echo "</tr>";
   $ix++;
}

//write to .dat file
$open=fopen(citgf_fopen("inhousedirectory.dat"),"w");
fwrite($open,$dat);
fclose($open); 
 citgf_makepublic("inhousedirectory.dat");

echo "<tr align=left><td colspan=5><a href=\"inhousedirectory.dat\" target=new2>Download Full Directory Export Here (.dat file)</a><br>";
copy("inhousedirectory.dat","inhousedirectory.txt");
echo "<a href=\"inhousedirectory.txt\" target=new2>Or Here (.txt file)</a>";
echo "<br>(Both files are tab-delimited)";

echo $end_html;
exit();
?>
