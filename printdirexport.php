<?php
/*******************************************************
printdirexport.php
Created: 08/09/2006
Export for use in printing School Directory
if($online==1) don't show e-mails
Revised 8/10/11 to not be such a mess
********************************************************/
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
$online=$argv[2];

require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   exit();
}
$year=date("Y");
$month=date("m");
if($month<6) $year--;

//FIRST, GET ALL ACTIVE SCHOOLS
$sql="SELECT * FROM headers WHERE inactive!='x' AND school!='Test\'s School' ORDER BY school";
$result=mysql_query($sql);
//DATA WILL BE IN FORM OF [stuff]<br>[more stuff]<br>
$deffont="<font style=\"font-family:arial;font-size:8pt;\">";	//DEFAULT FONT STYLE
$text="";
$ix=0;
$line=0; $col=0;
while($row=mysql_fetch_array($result))
{
	//SCHOOL INFO
   $cursch=$row[school]; $curschid=$row[id];
   $text.="<font style=\"font-family:arial;font-size:12pt;\"><b>$cursch</b></font><br>".$deffont;
   $text.="$row[address1]<br>";
   if(trim($row[address2])!='') 
   {
      $text.="$row[address2]<br>";
   }
   $text.="$row[city_state] $row[zip]<br>";
   $cursch2=ereg_replace("\'","\'",$cursch);
   $curphone=split("-",$row[phone]);
   $curfax=$row[fax];
   $curhomepage=ereg_replace("http://","",$row[website]);
   $text.="<b>$row[phone]</b><br>";
   $conf=ereg_replace("Conference","",$row[conference]);
   $conf=ereg_replace("conference","",$conf);
   $nsaadist=RomanNumeralize($row[nsaadist]);
   $text.="$conf-$nsaadist-$row[enrollment]<br>";
   $text.="$row[color_names]-<b>$row[mascot]</b><br>";
	//STAFF
   $adact=0; $pract=0;
   for($i=0;$i<count($staffs2);$i++)
   {
      if($staffs2[$i]=="Football")
      {
         $text.="<b>School Fax - $row[fax]</b><br>";
      }
      $phone2="";
      $phone3="";
      if($staffs2[$i]=="Athletic Director")
      {
	 //AD
	 $sql2="SELECT * FROM logins WHERE school='$cursch2' AND level=2";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $adname=ereg_replace("none","",trim($row2[name]));
         if($row2[phone]!='---' && $row2[phone]!=$curphone[0]."---")
	 {
	    $phone=split("-",$row2[phone]);
	    if($curphone[0]==$phone[0] || $phone[0]=="")
	       $adphone=$phone[1]."-".$phone[2];
	    else if($phone[0]!="")
	       $adphone=$phone[0]."-".$phone[1]."-".$phone[2];
	    if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($adphone=="$curphone[1]-$curphone[2]"))
	       $adphone="";
	    if($phone[3]!="")
	       $adphone.=" Ext. $phone[3]";
	 }
	 else
	    $adphone="";
	 if($row2[hours]!='' && $row2[hours]!='-' && $row2[hours]!='--')
	 {
            //cell phone
            $hours=$row2[hours];
            $hours=split("-",$hours);
            if(count($hours)==2)     //no area code
               $adcell=$curphone[0]."-".$hours[0]."-".$hours[1];
            else
               $adcell=$hours[0]."-".$hours[1]."-".$hours[2];
         }
         else $adcell="";
	 $adphone=trim($adphone);
	 $ademail=trim($row2[email]);
	 //Pr
	 $sql3="SELECT * FROM logins WHERE school='$cursch2' AND sport='Principal'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 $prname=ereg_replace("none","",trim($row3[name]));
	 if($row3[phone]!='---' && $row3[phone]!=$curphone[0]."---")
	 {
	    $phone=split("-",$row3[phone]);
	    if($curphone[0]==$phone[0] || $phone[0]=="")
	       $prphone=$phone[1]."-".$phone[2];
	    else if($phone[0]!="")
	       $prphone=$phone[0]."-".$phone[1]."-".$phone[2];
	    if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($prphone=="$curphone[1]-$curphone[2]"))
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
	 $actname=ereg_replace("none","",trim($row4[name]));
	 if($row4[phone]!='---' && $row4[phone]!=$curphone[0]."---")
	 {
	    $phone=split("-",$row4[phone]);
	    if($curphone[0]==$phone[0] || $phone[0]=="")
	       $actphone=$phone[1]."-".$phone[2];
	    else if($phone[0]!="")
	       $actphone=$phone[0]."-".$phone[1]."-".$phone[2];
	    if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($actphone=="$curphone[1]-$curhone[2]"))
	       $actphone="";
	    if($phone[3]!="")
	       $actphone.=" Ext. $phone[3]";
	 }
	 else 
	    $actphone="";
	/*	//NO AD CELL PHONE FOR NOW
         if($row2[hours]!='' && $row2[hours]!='-' && $row2[hours]!='--')
         {
            //cell phone
            $hours=$row2[hours];
            $hours=split("-",$hours);
            if(count($hours)==2)     //no area code
               $actcell=$curphone[0]."-".$hours[0]."-".$hours[1];
            else
               $actcell=$hours[0]."-".$hours[1]."-".$hours[2];
         }
         else $actcell="";
	*/
	 $actphone=trim($actphone);
	 $actemail=trim($row4[email]);

         if($adname==$prname && $adname==$actname)	//AD=Pr=Act Dir
	 {
	    //Pr/Act Dir:
	    if($adname!="")
	    {
	       $text.="Pr/Act Dir-$adname";
	       $linebreak="<br>";
	       if($adphone!="")
	       {  
	          $text.=", $adphone<br>"; $linebreak="";  
	       }
	       else if($prphone!="")
	       {  
	          $text.=", $prphone<br>"; $linebreak="";  
	       }
	       else if($actphone!="")
	       {  $text.=", $actphone<br>"; $linebreak=""; }
	       /*
	       if($adcell!="")
	       {  $text.="$linebreak&nbsp;&nbsp;(Cell) $adcell<br>"; $linebreak=""; }
               else if($actcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $actcell<br>"; $linebreak=""; }
	       */
	       if($ademail!="" && $online!=1)
	       {  $text.="$linebreak&nbsp;&nbsp;".$ademail."<br>"; $linebreak=""; }
	       else if($premail!="" && $online!=1)
	       {  $text.="$linebreak&nbsp;&nbsp;".$premail."<br>"; $linebreak=""; }
	       else if($actemail!="" && $online!=1)
	       {  $text.="$linebreak&nbsp;&nbsp;".$actemail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	 }
	 else if($adname==$prname)	//AD=PR!=Act Dir
	 {
	    //Pr/AD:
	    if($adname!="")
	    {
	       $text.="Pr/AD-$adname";
	       $linebreak="<br>";
	       if($adphone!="")
	       {  $text.=", $adphone<br>"; $linebreak="";  }
	       else if($prphone!="")
	       {  $text.=", $prphone<br>"; $linebreak="";  }
	       /*
               if($adcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $adcell<br>"; $linebreak=""; }
	       */
	       if($ademail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$ademail."<br>"; $linebreak=""; }
	       else if($premail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$premail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	    //Act Dir:
	    if($actname!="")
	    {
	       $text.="Act Dir-$actname";
	       $linebreak="<br>";
	       if($actphone!="")
	       {  $text.=", $actphone<br>"; $linebreak=""; }
	       /*
               if($actcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $actcell<br>"; $linebreak=""; }
	       */
	       if($actemail!="" && $online!=1)
	          { $text.="$linkbreak&nbsp;&nbsp;".$actemail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	 }
	 else if($adname==$actname)	//AD=Act Dir!=Pr
	 {
	    //Pr:
	    if($prname!="")
	    {
	       $text.="Pr-$prname";
	       $linebreak="<br>";
	       if($prphone!="")
	       {  $text.=", $prphone<br>"; $linebreak=""; }
	       if($premail!="" && $online!=1)
	       {  $text.="$linebreak&nbsp;&nbsp;".$premail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	    //Act Dir: (=AD)
	    if($adname!="")
	    {
	       $text.="Act Dir-$adname";
	       $linebreak="<br>";
	       if($adphone!="")
	       {  $text.=", $adphone<br>"; $linebreak=""; }
	       else if($actphone!="")
	       {  $text.=", $actphone<br>"; $linebreak=""; }
	       /*
               if($adcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $adcell<br>"; $linebreak=""; }
               else if($actcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $actcell<br>"; $linebreak=""; }
	       */
	       if($ademail!="" && $online!=1)
	       {  $text.="$linebreak&nbsp;&nbsp;".$ademail."<br>"; $linebreak=""; }
	       else if($actemail!="" && $online!=1)
	       {  $text.="$linebreak&nbsp;&nbsp;".$actemail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	 }
	 else if($prname==$actname)	//Pr=Act Dir!=AD
	 {
	    //AD:
	    if($adname!="")
	    {
	       $text.="AD-$adname";
	       $linebreak="<br>";
	       if($adphone!="")
		  { $text.=", $adphone<br>"; $linebreak=""; }
	       /*
               if($adcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $adcell<br>"; $linebreak=""; }
	       */
	       if($ademail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$ademail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	    //Pr/Act Dir:
	    if($prname!="")
	    {
	       $text.="Pr/Act Dir-$prname";
	       $linebreak="<br>";
	       if($prphone!="")
		  { $text.=", $prphone<br>"; $linebreak=""; }
	       else if($actphone!="")
		  { $text.=", $actphone<br>"; $linebreak=""; }
	       /*
               if($actcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $actcell<br>"; $linebreak=""; }
	       */
	       if($premail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$premail."<br>"; $linebreak=""; }
	       else if($actemail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$actemail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	 }
	 else	//Pr!=AD!=Act Dir (all different)
	 {
	    //Pr:
	    if($prname!="")
	    {
	       $text.="Pr-$prname";
	       $linebreak="<br>";
	       if($prphone!="")
		  { $text.=", $prphone<br>"; $linebreak=""; }
	       if($premail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$premail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	    //AD:
	    if($adname!="")
	    {
	       $text.="AD-$adname";
	       $linebreak="<br>";
	       if($adphone!="")
		  { $text.=", $adphone<br>"; $linebreak=""; }
	       /*
               if($adcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $adcell<br>"; $linebreak=""; }
	       */
	       if($ademail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$ademail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	    //Act Dir:
	    if($actname!="")
	    {
	       $text.="Act Dir-$actname";
	       $linebreak="<br>";
	       if($actphone!="")
		  { $text.=", $actphone<br>"; $linebreak=""; }
	       /*
               if($actcell!="")
               {  $text.="$linebreak&nbsp;&nbsp;(Cell) $actcell<br>"; $linebreak=""; }
	       */
	       if($actemail!="" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;".$actemail."<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	 }
      }	//END IF ATHLETIC DIRECTOR
      else if($staffs2[$i]=="Speech")	//SP, PP, Debate
      {
         $sport1="Speech"; $abbrev1="SP";
	 $sport2="Play Production"; $abbrev2="PP";
	 $sport3="Debate"; $abbrev3="Deb";
	 $sql2="SELECT * FROM logins WHERE sport='$sport1' AND school='$cursch2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $name1=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,"sp") && $name1=="")
            $name1="TBA";
	 //else if(!IsRegistered2011($curschid,"sp"))
	   // $name1="";
	 $phone1=$row2[phone];
	 $email1=$row2[email];
         $sql2="SELECT * FROM logins WHERE sport='$sport2' AND school='$cursch2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $name2=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,"pp") && $name2=="")
            $name2="TBA";
         //else if(!IsRegistered2011($curschid,"pp"))
           // $name2="";
         $phone2=$row2[phone];
         $email2=$row2[email];
         $sql2="SELECT * FROM logins WHERE sport='$sport3' AND school='$cursch2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $name3=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,"de") && $name3=="")
            $name3="TBA";
         //else if(!IsRegistered2011($curschid,"sp"))
           // $name3="";
         $phone3=$row2[phone];
         $email3=$row2[email];
         if($name1==$name2 && $name2==$name3 && $name1!='')	//PP/SP/Deb
	 {
            $phone4="";
            $text.="SP/PP/Deb-".$name1; //put in combo field
            $linebreak="<br>";
            if($phone1!='---' && $phone1!=$curphone[0]."---")
            {
               $phone=split("-",$phone1);
               if($curphone[0]==$phone[0] || $phone[0]=="")
                  $phone4=$phone[1]."-".$phone[2];
               else if($phone[0]!="")
                  $phone4=$phone[0]."-".$phone[1]."-".$phone[2];
               if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone4=="$curphone[1]-$curphone[2]"))
                  $phone4="";
               if($phone[3]!="")
                  $phone4.=" Ext. $phone[3]";
               if($phone4!="")
                  { $text.=", ".trim($phone4)."<br>"; $linebreak=""; }
            }
            else if($phone2!='---' && $phone2!=$curphone[0]."---")
            {
               $phone=split("-",$phone2);
               if($curphone[0]==$phone[0] || $phone[0]=="")
                  $phone4=$phone[1]."-".$phone[2];
               else if($phone[0]!="")
                  $phone4=$phone[0]."-".$phone[1]."-".$phone[2];
               if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone4=="$curphone[1]-$curphone[2]"))
                  $phone4="";
               if($phone[3]!="")
                  $phone4.=" Ext. $phone[3]";
               if($phone4!="")
                  { $text.=", ".trim($phone4)."<br>"; $linebreak=""; }
            }
            else if($phone3!='---' && $phone3!=$curphone[0]."---")
            {
               $phone=split("-",$phone2);
               if($curphone[0]==$phone[0] || $phone[0]=="")
                  $phone4=$phone[1]."-".$phone[2];
               else if($phone[0]!="")
                  $phone4=$phone[0]."-".$phone[1]."-".$phone[2];
               if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone4=="$curphone[1]-$curphone[2]"))
                  $phone4="";
               if($phone[3]!="")
                  $phone4.=" Ext. $phone[3]";
               if($phone4!="")
                  { $text.=", ".trim($phone4)."<br>"; $linebreak=""; }
            }
            if($email1!="" && $email1!="none" && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$email1<br>"; $linebreak=""; }
            else if($email2!="" && $email2!="none" && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$email2<br>"; $linebreak=""; }
            else if($email3!="" && $email3!="none" && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$email3<br>"; $linebreak=""; }
            $text.=$linebreak;
         }//end if SP=PP=Deb
	 else //divide into partner1, partner2, loner
	 {
	    if($name1==$name2 && $name1!=$name3)	//SP=PP!=Deb
	    {
	       $title="$abbrev1/$abbrev2";
	       $partner1=$name1; $partner1ph=$phone1; $partner1em=$email1;
	       $partner2=$name2; $partner2ph=$phone2; $partner2em=$email2;
	       $loner=$name3; $lonerph=$phone3; $lonerem=$email3; $lonertitle=$abbrev3;
	    }	
 	    else if($name1==$name3 && $name1!=$name2)	//SP=Deb!=PP
	    {
	       $title="$abbrev1/$abbrev3";
	       $partner1=$name1; $partner1ph=$phone1; $partner1em=$email1;
	       $partner2=$name3; $partner2ph=$phone3; $partner2em=$email3;
	       $loner=$name2; $lonerph=$phone2; $lonerem=$email2; $lonertitle=$abbrev2;
	    }
	    else if($name2==$name3 && $name1!=$name2)	//PP=Deb!=SP
	    {
	       $title="$abbrev2/$abbrev3";
	       $partner1=$name2; $partner1ph=$phone2; $partner1em=$email2;
	       $partner2=$name3; $partner2ph=$phone3; $partner2em=$email3;
	       $loner=$name1; $lonerph=$phone1; $lonerem=$email1; $lonertitle=$abbrev1;
	    }
  	    else	//PP!=SP!=Deb
	    {
	       $title="";
	       $partner1=$name1; $partner1ph=$phone1; $partner1em=$email1;
	       $partner2=$name2; $partner2ph=$phone2; $partner2em=$email2; 
	       $partner3=$name3; $partner3ph=$phone3; $partner3em=$email3;
	    }
	    if($partner1!='' && $title!='')
	    {
	       $phone3="";
               $text.="$title-".$partner1; //Skip $sport1 and $sport2 fields and put in combo field
               $linebreak="<br>";
               if($partner1ph!='---' && $partner1ph!=$curphone[0]."---")
               {
                  $phone=split("-",$partner1ph);
                  if($curphone[0]==$phone[0] || $phone[0]=="")
                     $phone3=$phone[1]."-".$phone[2];
                  else if($phone[0]!="")
                     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
                  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
                     $phone3="";
                  if($phone[3]!="")
                     $phone3.=" Ext. $phone[3]";
                  if($phone3!="")
                     { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
               }
               else if($partner2ph!='---' && $partner2ph!=$curphone[0]."---")
               {
                  $phone=split("-",$partner2ph);
                  if($curphone[0]==$phone[0] || $phone[0]=="")
                     $phone3=$phone[1]."-".$phone[2];
                  else if($phone[0]!="")
                     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
                  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
                     $phone3="";
                  if($phone[3]!="")
                     $phone3.=" Ext. $phone[3]";
                  if($phone3!="")
                     { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
               }
               if($partner1em!="" && $partner1em!="none" && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$partner1em<br>"; $linebreak=""; }
               else if($partner2em!="" && $partner2em!="none" && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$partner2em<br>"; $linebreak=""; }
               $text.=$linebreak;
            }//end if partner1!=''
	    if($loner!='' && $title!='')
	    {
               $phone3="";
               $text.="$lonertitle-".$loner; //Skip $sport1 and $sport2 fields and put in combo field
               $linebreak="<br>";
               if($lonerph!='---' && $lonerph!=$curphone[0]."---")
               {
                  $phone=split("-",$lonerph);
                  if($curphone[0]==$phone[0] || $phone[0]=="")
                     $phone3=$phone[1]."-".$phone[2];
                  else if($phone[0]!="")
                     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
                  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]
"))
                     $phone3="";
                  if($phone[3]!="")
                     $phone3.=" Ext. $phone[3]";
                  if($phone3!="")
                     { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
               }
	       if($lonerem!='' && $lonerem!='none' && $online!=1)
	       { $text.="$linebreak&nbsp;&nbsp;$lonerem<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }//end if loner!=''
	    else if($title=='')	//SP!=PP!=Deb
	    {
	       if($partner1!='')
	       {
               $phone3="";
               $text.="$abbrev1-".$partner1; 
               $linebreak="<br>";
               if($partner1ph!='---' && $partner1ph!=$curphone[0]."---")
               {
                  $phone=split("-",$partner1ph);
                  if($curphone[0]==$phone[0] || $phone[0]=="")
                     $phone3=$phone[1]."-".$phone[2];
                  else if($phone[0]!="")
                     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
                  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
                     $phone3="";
                  if($phone[3]!="")
                     $phone3.=" Ext. $phone[3]";
                  if($phone3!="")
                     { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
               }
	       if($partner1em!='' && $partner1em!='none' && $online!=1)
	       { $text.="$linebreak&nbsp;&nbsp;$partner1em<br>"; $linebreak=""; }
	       $text.=$linebreak;
	       }
               if($partner2!='')
               {
               $phone3="";
               $text.="$abbrev2-".$partner2; 
               $linebreak="<br>";
               if($partner2ph!='---' && $partner2ph!=$curphone[0]."---")
               {
                  $phone=split("-",$partner2ph);
                  if($curphone[0]==$phone[0] || $phone[0]=="")
                     $phone3=$phone[1]."-".$phone[2];
                  else if($phone[0]!="")
                     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
                  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
                     $phone3="";
                  if($phone[3]!="")
                     $phone3.=" Ext. $phone[3]";
                  if($phone3!="")
                     { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
               }
               if($partner2em!='' && $partner2em!='none' && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$partner2em<br>"; $linebreak=""; }
               $text.=$linebreak;
               }
               if($partner3!='')
               {
               $phone3="";
               $text.="$abbrev3-".$partner3;
               $linebreak="<br>";
               if($partner3ph!='---' && $partner3ph!=$curphone[0]."---")
               {
                  $phone=split("-",$partner3ph);
                  if($curphone[0]==$phone[0] || $phone[0]=="")
                     $phone3=$phone[1]."-".$phone[2];
                  else if($phone[0]!="")
                     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
                  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
                     $phone3="";
                  if($phone[3]!="")
                     $phone3.=" Ext. $phone[3]";
                  if($phone3!="")
                     { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
               }
               if($partner3em!='' && $partner3em!='none' && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$partner3em<br>"; $linebreak=""; }
               $text.=$linebreak;
               }
	    }//end if title==''
         }
      }
      else if($staffs2[$i]=="Cross-Country" || $staffs2[$i]=="Golf" || $staffs2[$i]=="Tennis" || $staffs2[$i]=="Basketball" || $staffs2[$i]=="Swimming" || $staffs2[$i]=="Track & Field" || $staffs2[$i]=="Soccer" || $staffs2[$i]=="Music")	//check if same for boys & girls
      {
	 if($staffs2[$i]=="Music")
	 {
	    $sport1="Instrumental Music";
	    $sport2="Vocal Music";
	    $abbrev1="IM";
	    $abbrev2="VM";
	    $sportreg1="mu"; $sportreg2="mu";
	 }
	 else
	 {
	    $sport1="Boys ".$staffs2[$i];
	    $sport2="Girls ".$staffs2[$i];
	    $abbrev1=$staffs_sm2[$i-2];
	    $abbrev2=$staffs_sm2[$i-1];
	    if($staffs2[$i]=="Golf")	//SWAP
	    {
	       $temp=$sport1; $sport1=$sport2; $sport2=$temp;
	    }
            $sportreg1=GetActivityAbbrev2($sport1);
            $sportreg2=GetActivityAbbrev2($sport2);
	 }
	 $sql2="SELECT * FROM logins WHERE sport='$sport1' AND school='$cursch2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
	 $name1=ereg_replace("none","",trim($row2[name]));
	 $phone1=$row2[phone];
	 $email1=$row2[email];
         if(IsRegistered2011($curschid,$sportreg1) && $name1=="") $name1="TBA";
	 $sql2="SELECT * FROM logins WHERE sport='$sport2' AND school='$cursch2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $name2=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,$sportreg2) && $name2=="") $name2="TBA";
	 $phone2=$row2[phone];
	 $email2=$row2[email];
	 if($name1==$name2 && $name1!='')
	 {
	    $phone3="";
	    $text.=$staffs_sm2[$i]."-".$name1; //Skip $sport1 and $sport2 fields and put in combo field
	    $linebreak="<br>";
	    if($phone1!='---' && $phone1!=$curphone[0]."---")
	    {
               $phone=split("-",$phone1);
  	       if($curphone[0]==$phone[0] || $phone[0]=="")
	  	  $phone3=$phone[1]."-".$phone[2];
	       else if($phone[0]!="") 
	          $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
	       if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
		  $phone3="";
	       if($phone[3]!="")
	          $phone3.=" Ext. $phone[3]";
	       if($phone3!="")
	          { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
	    }
	    else if($phone2!='---' && $phone2!=$curphone[0]."---")
	    {
               $phone=split("-",$phone2);
  	       if($curphone[0]==$phone[0] || $phone[0]=="")
	          $phone3=$phone[1]."-".$phone[2];
	       else if($phone[0]!="") 
	 	  $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
	       if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
	          $phone3="";
	       if($phone[3]!="")
		  $phone3.=" Ext. $phone[3]";
	       if($phone3!="")
	          { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
	    }
	    if($email1!="" && $email1!="none" && $online!=1)
	       { $text.="$linebreak&nbsp;&nbsp;$email1<br>"; $linebreak=""; }
	    else if($email2!="" && $email2!="none" && $online!=1)
	       { $text.="$linebreak&nbsp;&nbsp;$email2<br>"; $linebreak=""; }
	    $text.=$linebreak;
	 }
	 else
	 {
	    if($name1!='')
	    {
	       $text.=$abbrev1."-".$name1;
	       $linebreak="<br>";
	       if($phone1!='---' && $phone1!=$curphone[0]."---")
	       {
                  $phone=split("-",$phone1); $phone3="";
		  if($curphone[0]==$phone[0] || $phone[0]=="")
		     $phone3=$phone[1]."-".$phone[2];
		  else if($phone[0]!="") 
		     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
		  if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone3=="$curphone[1]-$curphone[2]"))
		     $phone3="";
		  if($phone[3]!="")
		     $phone3.=" Ext. $phone[3]";
		  if($phone3!="")
	             { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
	       }
	       if($email1!="" && $email1!="none" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;$email1<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	    if($name2!='')
	    {
	       $text.="$abbrev2-$name2";
	       $linebreak="<br>";
	       if($phone2!='---' && $phone2!=$curphone[0]."---")
	       {
                  $phone=split("-",$phone2); $phone3="";
		  if($curphone[0]==$phone[0] || $phone[0]=="")
	  	     $phone3=$phone[1]."-".$phone[2];
		  else if($phone[0]!="") 
		     $phone3=$phone[0]."-".$phone[1]."-".$phone[2];
		  if(($phone3=="$curphone[1]-$curphone[2]") || ($phone[0]=="" && $phone[1]=="" && $phone[2]==""))
		     $phone3="";
		  if($phone[3]!="")
		     $phone3.=" Ext. $phone[3]";
		  if($phone3!="")
	             { $text.=", ".trim($phone3)."<br>"; $linebreak=""; }
	       }
	       if($email2!="" && $email2!="none" && $online!=1)
		  { $text.="$linebreak&nbsp;&nbsp;$email2<br>"; $linebreak=""; }
	       $text.=$linebreak;
	    }
	 }	//END IF THEY DON'T MATCH
      }//end if a combo sport
      else if($staffs2[$i]=="Journalism")
      {
         $sql2="SELECT DISTINCT name,phone,email FROM logins WHERE sport LIKE 'Journalism' AND school='$cursch2' AND name!='' ORDER BY sport";
         $result2=mysql_query($sql2);
         $jct=0;
         while($row2=mysql_fetch_array($result2))
         {
            if($jct==0) $text.=$staffs_sm2[$i]."-";
	    else $text.="&nbsp;&nbsp;&nbsp;";
	    $text.="$row2[name]";
            $linebreak="<br>";
            if($row2[phone]!='---' && $row2[phone]!=$curphone[0]."---")
            {
               $phone=split("-",$row2[phone]); $phone2="";
               if($curphone[0]==$phone[0] || $phone[0]=="")
                  $phone2=$phone[1]."-".$phone[2];
               else if($phone[0]!="")
                  $phone2=$phone[0]."-".$phone[1]."-".$phone[2];
               if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone2=="$curphone[1]-$curphone[2]"))
                  $phone2="";
               if($phone[3]!="")
                  $phone2.=" Ext. $phone[3]";
               if($phone2!="")
                  { $text.=", ".trim($phone2)."<br>"; $linebreak=""; }
            }
            if($row2[email]!="" && $row2[email]!="none" && $online!=1)
               { $text.="$linebreak&nbsp;&nbsp;$row2[email]<br>"; $linebreak=""; }
            $text.=$linebreak;
	    $jct++;
	 }
      }
      else if($staffs2[$i]!="Principal" && !ereg("Boys",$staffs2[$i]) && !ereg("Girls",$staffs2[$i]))
      {
	 //Act Dir taken care of earlier 
	 //(Pr taken care of with AD and Boys & Girls sports taken care of above)
	 if($staffs2[$i]!="Activities Director")
	 {
	 $sql2="SELECT * FROM logins WHERE sport LIKE '$staffs2[$i]%' AND school='$cursch2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
  	 $row2[name]=ereg_replace("none","",trim($row2[name]));
         if($staffs2_regsp[$i]==1 && !IsRegistered2011($curschid,GetActivityAbbrev2($staffs2[$i])))
	 {
  	    //$row2[name]=="";
	 }
	 else if($staffs2_regsp[$i]==1 && $row2[name]=="")
	    $row2[name]="TBA";
	 if($row2[name]!="")
	 {
	    $text.=$staffs_sm2[$i]."-$row2[name]";
	    $linebreak="<br>";
	    if($row2[phone]!='---' && $row2[phone]!=$curphone[0]."---")
	    {
               $phone=split("-",$row2[phone]); $phone2="";
	       if($curphone[0]==$phone[0] || $phone[0]=="")
	  	  $phone2=$phone[1]."-".$phone[2];
	       else if($phone[0]!="") 
	 	  $phone2=$phone[0]."-".$phone[1]."-".$phone[2];
	       if(($phone[0]=="" && $phone[1]=="" && $phone[2]=="") || ($phone2=="$curphone[1]-$curphone[2]"))
		  $phone2="";
	       if($phone[3]!="")
	          $phone2.=" Ext. $phone[3]";
	       if($phone2!="")
	          { $text.=", ".trim($phone2)."<br>"; $linebreak=""; }
	    }
	    if($row2[email]!="" && $row2[email]!="none" && $online!=1)
	       { $text.="$linebreak&nbsp;&nbsp;$row2[email]<br>"; $linebreak=""; }
	    $text.=$linebreak;
	 }
	 }//end if not Act Dir
	 if($staffs2[$i]=="Trainer")
	 {
	    if($curhomepage!="")
	       $text.="Home Page: $curhomepage<br>";
	 }
      }
   }
   $ix++;
   $text.="<br></font><!--SCHOOL-->";
}
if($online!=1)
   echo "<table><tr align=left><td>".$text."</td></tr></table>";	//THIS IS ALL WE DO FOR THE PRINTED DIRECTORY EXPORT - ECHO IT TO SCREEN AS ONE BIG COLUMN FOR COPY AND PASTE
else if($online==1)
{
   $temp=split("<br>",$text);
   $line=0; $col=0;
   $text2=$init_html.="<table cellspacing=0 cellpadding=4><tr valign=top valign=left><td width='25%'>";
   for($i=0;$i<count($temp);$i++)
   {
      $text2.=$temp[$i];
     if($i<(count($temp)-1))
     {
      $line++;
      if($line>=98)
      {
	 $line=0;
	 $col++;
	 if($col>=4)
         {
	    $text2.="</td></tr><tr><td colspan=4><hr style=\"page-break-after:always;\"></td></tr><tr valign=top align=left><td width='25%'>";
            $col=0;
         }
         else
         {
	    $text2.="</td><td width='25%'>";
	 }
      }
      else
         $text2.="<br>";
     }
      $text2.="\r\n";
   }
   $text2.="</td></tr></table>".$end_html;
   $open=fopen(citgf_fopen("directory/schools$year.html"),"w");
   if(!fwrite($open,$text2)) echo " COULD NOT WRITE";
   fclose($open); 
 citgf_makepublic("directory/schools$year.html");
   $open=fopen(citgf_fopen("directory/schools.html"),"w");
   fwrite($open,$text);
   fclose($open); 
 citgf_makepublic("directory/schools.html");
   echo "<br><a class=small target='_blank' href=\"directory/schools$year.html\">Click to see All Schools (4 columns, website version)</a>";
   echo "<br><br><a class=small target='_blank' href=\"directory/schools.html\">Click to see All Schools (1 column)</a>";
   echo "<br><br><a class=small href=\"https://nsaahome.org/publications-order-forms\" target=\"_blank\">Preview Directory Links on NSAA Website (Publications & Order Forms Page)</a>";
}
   
echo "<br><br><b>END ($ix schools).</b><br><br>";

?>
