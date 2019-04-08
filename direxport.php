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
$session=$argv[1];

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

//Export Full School Directory:
$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
$dat="";
$dat.="School\tContact Name\tContact Person\tContact Person e-mail\tFB Type\tno rule-FB\tno rule-VB\tno rule-GTR\tno rule-BTR\tno rule-SB\tno rule-BB\tno rule-GB\tno rule-WR\tno rule-SW\tno rule-GSO\tno rule-BSO\tno rule-BA\tno rule-SP\tno rule-PP\tState FB\tState VB\tState WR\tState GBB\tState BBB\tState PP\tState SP\tState BSO\tState GSO\tState BA\tState SB\tState GGO\tState BGO\tState CC\tState TR\tAddress1\tAddress2\tCity State\tZip\tNSAA District\tDistrict\tColors\tMascot\tConference\tPhone\tFax\tSu\tSu phone\tSu e-mail\tPr\tPr phone\tPr e-mail\tAD\tAD Phone\tAD e-mail\tAD times\tAct Dir\tAct Dir phone\tAct Dir e-mail\tAD Sec\tAD Sec phone\tAD Sec e-mail\tAssist AD\tAssist AD phone\tAssist AD e-mail\tFB\tFB Phone\tFB e-mail\tBB\tBB Phone\tBB e-mail\tGB\tGB Phone\tGB e-mail\tBT\tBT phone\tBT e-mail\tGT\tGT phone\tGT e-mail\tBCC\tBCC phone\tBCC e-mail\tGCC\tGCC phone\tGCC e-mail\tVB\tVB phone\tVB e-mail\tWR\tWR phone\tWR e-mail\tBGO\tBGO phone\tBGO e-mail\tGGO\tGGO phone\tGGO e-mail\tBGy\tBGy phone\tBGy e-mail\tGGy\tGGy phone\tGGy e-mail\tBTen\tBTen phone\tBTen e-mail\tGTen\tGTen phone\tGTen e-mail\tBSw\tBSw phone\tBSw e-mail\tGSw\tGSw phone\tGSw e-mail\tBa\tBa phone\tBA e-mail\tBSo\tBSo phone\tBSo e-mail\tGSo\tGSo phone\tGSo e-mail\tSP\tSP phone\tSP e-mail\tPP\tPP phone\tPP e-mail\tDeb\tDeb phone\tDeb e-mail\tIM\tIM phone\tIM e-mail\tVM\tVM phone\tVM e-mail\tOrchestra\tO phone\tO e-mail\tSB\tSB phone\tSB e-mail\tBd Pres\tBd e-mail\tStud Coun\tStud Coun phone\tStud Coun e-mail\tHome Page\tSup Fax\tTrainer\tTrainer phone\tTrainer e-mail\tNews\tNews phone\tNews e-mail\tYear\tYear phone\tYear e-mail\r\n";
//echo "<textarea>$dat</textarea>";

//2nd export (for mail merge)
$dat2="";
$dat2.="School\tAddress 1\tAddress 2\tCity, State\tZip\tPhone\tDistrict\tConference\tColors\tMascot\tSu\tSu E-mail\tPr\tPr E-mail\tAD\tAD E-mail\tPr/AD\tPr/AD E-mail\tAct Dir\tAct Dir E-mail\tPr/Act. Dir.\tPr/Act. Dir. E-mail\tBest Time to Contact AD\tADSec\tADSec E-mail\tAAD\tAAD E-mail\tSchool Fax\tFB\tFB E-mail\tVB\tVB E-mail\tBCC\tBCC E-mail\tGCC\tGCC E-mail\tCC\tCC E-mail\tSB\tSB E-mail\tBGo\tBGo E-mail\tGGo\tGGo E-mail\tGO\tGO E-mail\tBTe\tBTe E-mail\tGTe\tGTe E-mail\tTEN\tTEN E-mail\tBa\tBa E-mail\tBBB\tBBB E-mail\tGBB\tGBB E-mail\tBB\tBB E-mail\tWR\tWR E-mail\tBSw\tBSw E-mail\tGSw\tGSw E-mail\tSW\tSW E-mail\tBT\tBT E-mail\tGT\tGT E-mail\tTR\tTR E-mail\tBSo\tBSo E-mail\tGSo\tGSo E-mail\tSO\tSO E-mail\tSP\tSP E-mail\tPP\tPP E-mail\tSP/PP\tSP/PP E-mail\tDeb\tDeb E-mail\tIM\tIM E-mail\tVM\tVM E-mail\tMU\tMU E-mail\tOR\tOR E-mail\tNews\tNews E-mail\tYear\tYear E-mail\tJ\tJ E-mail\tSC\tSC E-mail\tBd Pres\tBd Pres E-mail\tTrainer\tTrainer E-mail\tHome Page\r\n";
echo "<textarea rows=10 cols=50>$dat2</textarea>";

echo $init_html;
echo "<table><tr align=left><td colspan=5>";
echo "<b>(Please click the Reload button on your browser to view the progress of this export.<br>The export is complete when you see the links to download the export files at the bottom of this screen.)</b>";
echo "<br><br>Directory Export started at ".date("r",time())."...<br><br></td></tr>";
$ix=0;
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
   $dat.="$row[school]\t$row2[name]\t$row2[sport]\t$row2[email]\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t$row[address1]\t$row[address2]\t$row[city_state]\t$row[zip]\t".trim($row[nsaadist])."\t\t$row[color_names]\t$row[mascot]\t$row[conference]\t$row[phone]\t$row[fax]\t";
   $tempph=split("-",$row[phone]);
   $mainareacode=$tempph[0];
   $mainphone=$tempph[1]."-".$tempph[2];
   for($i=0;$i<count($staffs);$i++)
   {
      if($staffs[$i]!="Home Page" && $staffs[$i]!="Sup Fax")
      {
	 $school2=ereg_replace("\'","\'",$row[school]);
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
	    $cell=split(",",$cell);
	    $dat.="(".$cell[0].")".$cell[1]."-".$cell[2]."\t";
	 }
      }	//end if not Home Page and not Sup Fax
      else 
      {
	 if($staffs[$i]=="Home Page")
	 {
	    $sql3="SELECT website FROM headers WHERE school='$school2'";
	    $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	    $dat.=$row3[website]."\t\t"; //(leave blank for Sup Fax)
	 }
      }
   }
   $dat.="\r\n";	//new line for each school

   //EXPORT 2:
   $dat2.="$cursch\t$row[address1]\t$row[address2]\t$row[city_state]\t$row[zip]\t$row[phone]\t".trim($row[nsaadist])."\t".trim($row[conference])."\t$row[color_names]\t$row[mascot]\t";
   $cursch2=ereg_replace("\'","\'",$cursch);
   $curphone=split("-",$row[phone]);
   $curfax=$row[fax];
   $curhomepage=$row[website];
   $adact=0; $pract=0;
   for($i=0;$i<count($staffs2);$i++)
   {
      $phone2="";
      $phone3="";
      if($staffs2[$i]=="Athletic Director")
      {
	 //AD
	 $sql2="SELECT * FROM logins WHERE school='$cursch2' AND level=2";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $adname=$row2[name];
         if($row2[phone]!='---' && $row2[phone]!=$curphone[0]."---")
	 {
	    $phone=split("-",$row2[phone]);
	    if($curphone[0]==$phone[0] || $phone[0]=="")
	       $adphone=$phone[1]."-".$phone[2];
	    else if($phone[0]!="")
	       $adphone=$phone[0]."-".$phone[1]."-".$phone[2];
	    if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
	       $adphone="";
	    if($phone[3]!="")
	       $adphone.=" Ext. $phone[3]";
	 }
	 else
	    $adphone="";
	 $adphone=trim($adphone);
	 $ademail=trim($row2[email]);
	 //Pr
	 $sql3="SELECT * FROM logins WHERE school='$cursch2' AND sport='Principal'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 $prname=$row3[name];
	 if($row3[phone]!='---' && $row3[phone]!=$curphone[0]."---")
	 {
	    $phone=split("-",$row3[phone]);
	    if($curphone[0]==$phone[0] || $phone[0]=="")
	       $prphone=$phone[1]."-".$phone[2];
	    else if($phone[0]!="")
	       $prphone=$phone[0]."-".$phone[1]."-".$phone[2];
	    if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
	       $prphone="";
	    if($phone[3]!="")
	       $prphone.=" Ext. $phone[3]";
	 }
	 else
	    $prphone="";
	 $prphone=trim($prphone);
	 $premail=trim($row3[email]);
	 //Act Dir
	 $sql4="SELECT * FROM logins WHERE school='$cursch2' AND sport='Activities Director'";
	 $result4=mysql_query($sql4);
	 $row4=mysql_fetch_array($result4);
	 $actname=$row4[name];
	 if($row4[phone]!='---' && $row4[phone]!=$curphone[0]."---")
	 {
	    $phone=split("-",$row4[phone]);
	    if($curphone[0]==$phone[0] || $phone[0]=="")
	       $actphone=$phone[1]."-".$phone[2];
	    else if($phone[0]!="")
	       $actphone=$phone[0]."-".$phone[1]."-".$phone[2];
	    if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
	       $actphone="";
	    if($phone[3]!="")
	       $actphone.=" Ext. $phone[3]";
	 }
	 else 
	    $actphone="";
	 $actphone=trim($actphone);
	 $actemail=trim($row4[email]);

         if($adname==$prname && $adname==$actname)	//AD=Pr=Act Dir
	 {
	    $dat2.="\t\t\t\t\t\t\t\t";	//skip Pr, AD, Pr/AD, Act Dir
	    //Pr/Act Dir:
	    if($adname!="")
	    {
	       $dat2.="Pr/Act Dir-$adname";
	       if($adphone!="")
		  $dat2.=", $adphone";
	       else if($prphone!="")
		  $dat2.=", $prphone";
	       else if($actphone!="")
		  $dat2.=", $actphone";
	       $dat2.="\t";
	       if($ademail!="")
		  $dat2.=$ademail;
	       else if($premail!="")
		  $dat2.=$premail;
	       else if($actemail!="")
		  $dat2.=$actemail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	 }
	 else if($adname==$prname)	//AD=PR!=Act Dir
	 {
	    $dat2.="\t\t\t\t";	//skip Pr, AD
	    //Pr/AD:
	    if($adname!="")
	    {
	       $dat2.="Pr/AD-$adname";
	       if($adphone!="")
		  $dat2.=", $adphone";
	       else if($prphone!="")
		  $dat2.=", $prphone";
	       $dat2.="\t";
	       if($ademail!="")
		  $dat2.=$ademail;
	       else if($premail!="")
		  $dat2.=$premail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    //Act Dir:
	    if($actname!="")
	    {
	       $dat2.="Act Dir-$actname";
	       if($actphone!="")
		  $dat2.=", $actphone";
	       $dat2.="\t";
	       if($actemail!="")
		  $dat2.=$actemail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    $dat2.="\t\t";	//skip Pr/Act Dir
	 }
	 else if($adname==$actname)	//AD=Act Dir!=Pr
	 {
	    //Pr:
	    if($prname!="")
	    {
	       $dat2.="Pr-$prname";
	       if($prphone!="")
		  $dat2.=", $prphone";
	       $dat2.="\t";
	       if($premail!="")
		  $dat2.=$premail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    $dat2.="\t\t\t\t";	//skip AD, Pr/AD
	    //Act Dir: (=AD)
	    if($adname!="")
	    {
	       $dat2.="Act Dir-$adname";
	       if($adphone!="")
		  $dat2.=", $adphone";
	       else if($actphone!="")
		  $dat2.=", $actphone";
	       $dat2.="\t";
	       if($ademail!="")
		  $dat2.=$ademail;
	       else if($actemail!="")
		  $dat2.=$actemail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    $dat2.="\t\t";	//skip Pr/Act Dir
	 }
	 else if($prname==$actname)	//Pr=Act Dir!=AD
	 {
	    $dat2.="\t\t";	//skip Pr
	    //AD:
	    if($adname!="")
	    {
	       $dat2.="AD-$adname";
	       if($adphone!="")
		  $dat2.=", $adphone";
	       $dat2.="\t";
	       if($ademail!="")
		  $dat2.=$ademail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    $dat2.="\t\t\t\t";	//skip Pr/AD, Act Dir
	    //Pr/Act Dir:
	    if($prname!="")
	    {
	       $dat2.="Pr/Act-$prname";
	       if($prphone!="")
		  $dat2.=", $prphone";
	       else if($actphone!="")
		  $dat2.=", $actphone";
	       $dat2.="\t";
	       if($premail!="")
		  $dat2.=$premail;
	       else if($actemail!="")
		  $dat2.=$actemail;
	       $dat2.="\t";
	    }
	    else 
	       $dat2.="\t\t";
	 }
	 else	//Pr!=AD!=Act Dir (all different)
	 {
	    //Pr:
	    if($prname!="")
	    {
	       $dat2.="Pr-$prname";
	       if($prphone!="")
		  $dat2.=", $prphone";
	       $dat2.="\t";
	       if($premail!="")
		  $dat2.=$premail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    //AD:
	    if($adname!="")
	    {
	       $dat2.="AD-$adname";
	       if($adphone!="")
		  $dat2.=", $adphone";
	       $dat2.="\t";
	       if($admail!="")
		  $dat2.=$ademail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    $dat2.="\t\t";	//skip Pr/AD
	    //Act Dir:
	    if($actname!="")
	    {
	       $dat2.="Act Dir-$actname";
	       if($actphone!="")
		  $dat2.=", $actphone";
	       $dat2.="\t";
	       if($actemail!="")
		  $dat2.=$actemail;
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    $dat2.="\t\t";	//skip Pr/Act Dir
	 }
      }
      else if($staffs2[$i]=="Speech" || $staffs2[$i]=="Cross-Country" || $staffs2[$i]=="Golf" || $staffs2[$i]=="Tennis" || $staffs2[$i]=="Basketball" || $staffs2[$i]=="Swimming" || $staffs2[$i]=="Track & Field" || $staffs2[$i]=="Soccer" || $staffs2[$i]=="Music")	//check if same for boys & girls
      {
	 if($staffs2[$i]=="Speech")
	 {
	    $sport1="Speech";
	    $sport2="Play Production";
	    $abbrev1="SP";
	    $abbrev2="PP";
	 }
	 else if($staffs2[$i]=="Music")
	 {
	    $sport1="Instrumental Music";
	    $sport2="Vocal Music";
	    $abbrev1="IM";
	    $abbrev2="VM";
	 }
	 else
	 {
	    $sport1="Boys ".$staffs2[$i];
	    $sport2="Girls ".$staffs2[$i];
	    $abbrev1=$staffs_sm2[$i-2];
	    $abbrev2=$staffs_sm2[$i-1];
	 }
	 $sql2="SELECT * FROM logins WHERE sport='$sport1' AND school='$cursch2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
	 $name1=$row2[name];
	 $phone1=$row2[phone];
	 $email1=$row2[email];
	 $sql2="SELECT * FROM logins WHERE sport='$sport2' AND school='$cursch2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $name2=$row2[name];
	 $phone2=$row2[phone];
	 $email2=$row2[email];
	 if($name1==$name2 && $name1!='')
	 {
	    $phone3="";
	    $dat2.="\t\t\t\t".$staffs_sm2[$i]."-".$name1; //Skip $sport1 and $sport2 fields and put in combo field
	    if($phone1!='---' && $phone1!=$curphone[0]."---")
	    {
               $phone=split("-",$phone1);
  	       if($curphone[0]==$phone[0] || $phone[0]=="")
	  	  $phone3=$phone[1]."-".$phone[2];
	       else if($phone[0]!="") 
	          $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
	       if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
		  $phone3="";
	       if($phone[3]!="")
	          $phone3.=" Ext. $phone[3]";
	       if($phone3!="")
	          $dat2.=", ".trim($phone3);
	    }
	    else if($phone2!='---' && $phone2!=$curphone[0]."---")
	    {
               $phone=split("-",$phone2);
  	       if($curphone[0]==$phone[0] || $phone[0]=="")
	          $phone3=$phone[1]."-".$phone[2];
	       else if($phone[0]!="") 
	 	  $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
	       if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
	          $phone3="";
	       if($phone[3]!="")
		  $phone3.=" Ext. $phone[3]";
	       if($phone3!="")
	          $dat2.=", ".trim($phone3);
	    }
	    $dat2.="\t";
	    if($email1!="" && $email1!="none")
	       $dat2.="$email1";
	    else if($email2!="" && $email2!="none")
	       $dat2.="$email2";
	    $dat2.="\t";
	 }
	 else
	 {
	    if($name1!='')
	    {
	       $dat2.=$abbrev1."-".$name1;
	       if($phone1!='---' && $phone1!=$curphone[0]."---")
	       {
                  $phone=split("-",$phone1); $phone3="";
		  if($curphone[0]==$phone[0] || $phone[0]=="")
		     $phone3=$phone[1]."-".$phone[2];
		  else if($phone[0]!="") 
		     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
		  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
		     $phone3="";
		  if($phone[3]!="")
		     $phone3.=" Ext. $phone[3]";
		  if($phone3!="")
	             $dat2.=", ".trim($phone3);
	       }
	       $dat2.="\t";
	       if($email1!="" && $email1!="none")
		  $dat2.="$email1";
	       $dat2.="\t";
	    }
	    else
	       $dat2.="\t\t";
	    if($name2!='')
	    {
	       $dat2.="$abbrev2-$name2";
	       if($phone2!='---' && $phone2!=$curphone[0]."---")
	       {
                  $phone=split("-",$phone2); $phone3="";
		  if($curphone[0]==$phone[0] || $phone[0]=="")
	  	     $phone3=$phone[1]."-".$phone[2];
		  else if($phone[0]!="") 
		     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
		  if(($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]) || ($phone[0]=="" && $phone[1]=="" && $phone[2]==""))
		     $phone3="";
		  if($phone[3]!="")
		     $phone3.=" Ext. $phone[3]";
		  if($phone3!="")
	             $dat2.=", ".trim($phone3);
	       }
	       $dat2.="\t";
	       if($email2!="" && $email2!="none")
		  $dat2.="$email2";
	       $dat2.="\t\t\t";	//skip combo field
	    }
	    else
	       $dat2.="\t\t\t\t";
	 }
      }//end if a combo sport
      else if($staffs2[$i]!="Principal" && !ereg("Boys",$staffs2[$i]) && !ereg("Girls",$staffs2[$i]))
      {
	 //Act Dir taken care of earlier 
	 //(Pr taken care of with AD and Boys & Girls sports taken care of above)
	 if($staffs2[$i]!="Activities Director")
	 {
	 $sql2="SELECT * FROM logins WHERE sport LIKE '$staffs2[$i]%' AND school='$cursch2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[name]!="")
	 {
	    $dat2.=$staffs_sm2[$i]."-$row2[name]";
	    if($row2[phone]!='---' && $row2[phone]!=$curphone[0]."---")
	    {
               $phone=split("-",$row2[phone]); $phone2="";
	       if($curphone[0]==$phone[0] || $phone[0]=="")
	  	  $phone2=$phone[1]."-".$phone[2];
	       else if($phone[0]!="") 
	 	  $phone2=$phone[0]."-".$phone[1]."-".$phone[2];
	       if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($curphone[1]==$phone[1] && $curphone[2]==$phone[2] && $curphone[0]==$phone[0]))
		  $phone2="";
	       if($phone[3]!="")
	          $phone2.=" Ext. $phone[3]";
	       if($phone2!="")
	          $dat2.=", ".trim($phone2);
	    }
	    $dat2.="\t";
	    if($row2[email]!="" && $row2[email]!="none")
	       $dat2.="$row2[email]";
	    $dat2.="\t";
	 }
	 else
	    $dat2.="\t\t";
	 }//end if not Act Dir
	 if($staffs2[$i]=="Activities Director")
	 {
	    //Best time to contact AD field
	    $sql2="SELECT hours FROM logins WHERE school='$cursch2' AND level=2";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $hours=split(",",$row2[0]);
	    if(trim($hours[0])!=":-:" && trim($hours[0])!=": - :" && trim($hours[0])!="," && trim($hours[1])!=":-:" && trim($hours[1])!=": - :" && trim($hours[1])!=",")
	       $dat2.="Hours Available: ";
	    if(trim($hours[0])!=":-:" && trim($hours[0])!=": - :" && trim($hours[0])!=",")
	       $dat2.=trim($hours[0]);
	    if(trim($hours[0])!=":-:" && trim($hours[0])!=": - :" && trim($hours[0])!="," && trim($hours[1])!=":-:" && trim($hours[1])!=": - :" && trim($hours[1])!=",")
	       $dat2.=", ";
	    if(trim($hours[1])!=":-:" && trim($hours[1])!=": - :" && trim($hours[1])!=",")
	       $dat2.=trim($hours[1]);
	    $dat2.="\t";
	 }
	 else if($staffs2[$i]=="Assistant Athletic Director")
	    $dat2.="$curfax\t";
	 else if($staffs2[$i]=="Trainer")
	 {
	    if($curhomepage!="")
	       $dat2.="Home Page: $curhomepage";
	 }
      }
   }
   $dat2.="\r\n";
   if(($ix+1)%5==0) echo "</tr>";
   $ix++;
}

//write to .dat file
$open=fopen(citgf_fopen("directory.dat"),"w");
fwrite($open,$dat);
fclose($open); 
 citgf_makepublic("directory.dat");
$open=fopen(citgf_fopen("directory2.dat"),"w");
fwrite($open,$dat2);
fclose($open); 
 citgf_makepublic("directory2.dat");

echo "<tr align=left><td colspan=5><a href=\"directory.dat\" target=new2>Download Full Directory Export Here (.dat file)</a><br>";
copy("directory.dat","directory.txt");
echo "<a href=\"directory.txt\" target=new2>Or Here (.txt file)</a>";
echo "<br>(Both files are tab-delimited)";
echo "<br><br>";
echo "<a href=\"directory2.dat\" target=new2>Download Mail Merge Export (.dat)</a></td></tr></table>";
echo $end_html;
exit();
?>
