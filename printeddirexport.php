<?php
/*******************************************************
Adapted from printdirexport.php 8/9/13
Created: 08/09/2006
Export for use in printing School Directory
if($online==1) don't show e-mails
Revised 8/10/11 to not be such a mess
Revised 8/9/13 to output in new format for printer
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
if(!$_REQUEST['session'])
{
   $session=$argv[1];
   $filename=$argv[2];
}

require 'functions.php';
require 'variables.php';
//require '/data/public_html/calculate/functions.php'; 
require '../calculate/functions.php'; 

$level=GetLevel($session);

   if($format=="html") $width="33%";
   else $width="210";

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

set_time_limit(0);                   // ignore php timeout

   //include PDF creation tool:
   //require_once('../tcpdf/config/lang/eng.php');
   require_once('../tcpdf/tcpdf.php');

   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   //$pdf = new TCPDF("P", PDF_UNIT, "LETTER", true, 'UTF-8', false);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(TRUE, 1); //FALSE, 1);
   $pdf->setLanguageArray($l);
   //$pdf->AliasNbPages();
   $pdf->AddPage();

//FIRST, GET ALL ACTIVE SCHOOLS
$sql="SELECT * FROM headers WHERE inactive!='x' AND school!='Test\'s School' ORDER BY school";
//school!='Maywood' AND school!='Nebraska Christian' AND school!='Millard West' AND school!='Mullen' AND school!='Mount Michael Benedictine' AND school!='Morrill' ORDER BY school LIMIT 100";
$result=mysql_query($sql);
//DATA WILL BE IN FORM OF [stuff]<br>[more stuff]<br>
$deffont="<font style=\"font-family:arial;font-size:8pt;\">";	//DEFAULT FONT STYLE
$ix=0;
$line=0; $col=0;
$x=0; $y=0;
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
while($row=mysql_fetch_array($result))
{
   $prepages = $pdf->getNumPages();
   $pdf->startTransaction();
	/*
   if($y>0)
   {
      $y=$pdf->GetY();
      if($y>240)
      {
	 $y=0;
	 $pdf->AddPage();
      }   
   }
	*/
   $x=0; 
   if($y>0) $y+=1;
   //TOP: Bold line, SCHOOL INFO
   $XRect=$x; 
   $pdf->Rect($x, $y, 216, 1, 'F', array(), array("0","0","0"));
   
   $pdf->SetFillColor(255,255,255);
   $y+=1;
   $cursch=$row[school]; $curschid=$row[id];
   $pdf->SetFont("times","BI","13");
	//SCHOOL NAME
   $XSch=$x;
   $pdf->writeHTMLCell("","",$x,$y,"$cursch",0,1,1,true,"L");

   $html="<table cellspacing=\"0\" cellpadding=\"0\">
	<tr align=left><td width=\"$width\">$row[address1]<br />";
   if(trim($row[address2])!='') 
      $html.="$row[address2]<br />";
   $html.="$row[city_state] $row[zip]<br />";
   $cursch2=addslashes($cursch);
   $curphone=split("-",$row[phone]);
   $curfax=$row[fax];
   $html.="<b>Phone: $row[phone]</b><br />Fax: $curfax";
   $sql2="SELECT phone,name FROM logins WHERE school='$cursch2' AND level=2";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $adphone=""; $adname=trim($row2[name]);
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
   if(trim($adphone)=="")	//IF ACT DIR IS SAME PERSON, PUT THAT NUMBER
   {
         $sql4="SELECT * FROM logins WHERE school='$cursch2' AND sport='Activities Director'";
         $result4=mysql_query($sql4);
         $row4=mysql_fetch_array($result4);
         $actname=ereg_replace("none","",trim($row4[name]));
         if($row4[phone]!='---' && $row4[phone]!=$curphone[0]."---" && $actname==$adname)
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
         $actphone=trim($actphone);
	 $adphone=$actphone;
   }
   $html.="</td><td width=\"$width\">";
   //if($adphone!='') $html.="AD Phone: $adphone<br />";
   $y=$pdf->GetY();
   $topy=$y;
   $pdf->SetFont("berthold","","9.5");
   //$pdf->SetFont("helvetica","","9.5");

   $conf=ereg_replace("Conference","",$row[conference]);
   $conf=ereg_replace("conference","",$conf);
   $nsaadist=RomanNumeralize($row[nsaadist]);
   $curhomepage=ereg_replace("http://","",$row[website]);
   if(strlen($curhomepage)>25)
   {
	$length=strlen($curhomepage);
      	$pieces=preg_split("/\//",$curhomepage);
	//echo $curhomepage."\r\n";
        $curhomepage="";
	$curpiece="";
	for($i=0;$i<count($pieces);$i++)
	{
	   if(strlen($curpiece)==0 || (strlen($curpiece)+strlen($pieces[$i]))<25)
	       $curpiece.=$pieces[$i]."/";
	   else
	   {
	       $curhomepage.=$curpiece."<br />&nbsp;&nbsp;";
	       $curpiece=$pieces[$i];
	   }
	}
        $curhomepage.=$curpiece;
	if(substr($curhomepage,strlen($curhomepage)-2,2)=="//")
	   $curhomepage=substr($curhomepage,0,strlen($curhomepage)-2);
	else if(substr($curhomepage,strlen($curhomepage)-1,1)=="/")
           $curhomepage=substr($curhomepage,0,strlen($curhomepage)-1);
	//echo $curhomepage."\r\n";
	//exit();
   }
   $html.="Conference: $conf<br />";
   $html.="NSAA District: $nsaadist<br />";
   $html.="Enrollment: $row[enrollment]</td><td>";
   $html.="Mascot: $row[mascot]<br />Colors: $row[color_names]<br />";
   if(trim($curhomepage)!='') $html.="Homepage: $curhomepage<br />";
    //coop
	 for($i=0;$i<count($staff);$i++)
	{ 
		if (($i>5 && $i<32) && ($i!=27 && $i!=28 && $i!=29)){
        $sql_coop="SELECT * FROM ".GetSchoolsTable(GetActivityAbbrev2($staff[$i]))." WHERE  othersch1=$row[id] OR othersch2=$row[id] OR othersch3=$row[id]";
		$result_coop=mysql_query($sql_coop);
		$row_coop=mysql_fetch_array($result_coop);
		//if (!empty($row_coop)) $co_op[]=GetActivityAbbrev2($staff[$i]);
		if (!empty($row_coop)) $co_op[]=$staff[$i];
		} 
		$coop_school_for = implode(",",$co_op);
	}   
  // $html.="Coop School For: $coop_school_for";unset($co_op);  
   $html.="</td></tr></table>";

        //CONFERENCE, MASCOT, ETC
   $y=$topy+2;
   $y2=$y;
   $x=0;
   if(trim($row[address2])!='') $height=.14;
   else $height=.11;
   $pdf->Image("../images/verticalline.png",70,$y2,"$height",'','','','',false,72,'',false,false,0,false,false,true);
   $pdf->Image("../images/verticalline.png",140,$y2,"$height",'','','','',false,72,'',false,false,0,false,false,true);
   $html=CleanForPDF($html);
   $X1=$x; $Y1=$y; $HTML1=$html;
   $pdf->writeHTMLCell("","",$x,$y,"$html",0,2,0,true,"L");

   $y2=$pdf->GetY();
   //$y2+=1;
   $x1=0; $x2=216; $y1=$y2; 
   $pdf->Line($x1, $y1, $x2, $y2);

	//STAFF
   $adact=0; $pract=0;
   $html="";
   $listings=array(); $l=0;
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
	 $adname=ereg_replace("none","",trim($row2[name]));
     /*     if($row2[phone]!='---' && $row2[phone]!=$curphone[0]."---")
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
	 $adphone=trim($adphone); */
	 $ademail=trim($row2[email]);
	 if(strlen($ademail)>38)
     	 {
	    $ademail=preg_replace("/\//",";",$ademail);
	    $tmp=explode(";",$ademail);
	    if(count($tmp)>1) $ademail=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
	 //Pr
	 $sql3="SELECT * FROM logins WHERE school='$cursch2' AND sport='Principal'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 $prname=ereg_replace("none","",trim($row3[name]));
/* 	 if($row3[phone]!='---' && $row3[phone]!=$curphone[0]."---")
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
	 $prphone=trim($prphone); */
	 $premail=trim($row3[email]);
         if(strlen($premail)>38)
	 {
            $premail=preg_replace("/\//",";",$premail);
            $tmp=explode(";",$premail);
            if(count($tmp)>1) $premail=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
	 //Act Dir
	 $sql4="SELECT * FROM logins WHERE school='$cursch2' AND sport='Activities Director'";
	 $result4=mysql_query($sql4);
	 $row4=mysql_fetch_array($result4);
	 $actname=ereg_replace("none","",trim($row4[name]));
/* 	 if($row4[phone]!='---' && $row4[phone]!=$curphone[0]."---")
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
	 $actphone=trim($actphone); */
	 $actemail=trim($row4[email]);
         if(strlen($actemail)>38)
	 {
            $actemail=preg_replace("/\//",";",$actemail);
            $tmp=explode(";",$actemail);
            if(count($tmp)>1) $actemail=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }

         if($adname==$prname && $adname==$actname)	//AD=Pr=Act Dir
	 {
	    //Pr/Act Dir:
	    if($adname!="")
	    {
	       $listings[$l]="Pr/Act Dir-$adname";
	       $linebreak="<br />";
	       if(strlen($adname)>=30) $delim="<br />&nbsp;&nbsp;";
	       else $delim=", ";
/* 	       if($adphone!="")
	       {  
	          $listings[$l].=$delim."$adphone<br />"; $linebreak="";  
	       }
	       else if($prphone!="")
	       {  
	          $listings[$l].=$delim."$prphone<br />"; $linebreak="";  
	       }
	       else if($actphone!="")
	       {  $listings[$l].=$delim."$actphone<br />"; $linebreak=""; } */
	       if($ademail!="")
	       {  $listings[$l].="$linebreak&nbsp;&nbsp;".$ademail."<br />"; $linebreak=""; }
	       else if($premail!="" && $online!=1)
	       {  $listings[$l].="$linebreak&nbsp;&nbsp;".$premail."<br />"; $linebreak=""; }
	       else if($actemail!="" && $online!=1)
	       {  $listings[$l].="$linebreak&nbsp;&nbsp;".$actemail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	 }
	 else if($adname==$prname)	//AD=PR!=Act Dir
	 {
	    //Pr/AD:
	    if($adname!="")
	    {
	       $listings[$l]="Pr/AD-$adname";
	       $linebreak="<br />";
               if(strlen($adname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
/* 	       if($adphone!="")
	       {  $listings[$l].=$delim."$adphone<br />"; $linebreak="";  }
	       else if($prphone!="")
	       {  $listings[$l].=$delim."$prphone<br />"; $linebreak="";  }
               if($adcell!="")
               {  $listings[$l].="$linebreak&nbsp;&nbsp;(Cell) $adcell<br />"; $linebreak=""; } */
	       if($ademail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$ademail."<br />"; $linebreak=""; }
	       else if($premail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$premail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	    //Act Dir:
	    if($actname!="")
	    {
	       $listings[$l]="Act Dir-$actname";
               if(strlen($actname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($actphone!="")
	       {  $listings[$l].=$delim."$actphone<br />"; $linebreak=""; } */
	       if($actemail!="" && $online!=1)
	          { $listings[$l].="$linkbreak&nbsp;&nbsp;".$actemail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	 }
	 else if($adname==$actname)	//AD=Act Dir!=Pr
	 {
	    //Pr:
	    if($prname!="")
	    {
	       $listings[$l]="Pr-$prname";
               if(strlen($prname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($prphone!="")
	       {  $listings[$l].=$delim."$prphone<br />"; $linebreak=""; } */
	       if($premail!="" && $online!=1)
	       {  $listings[$l].="$linebreak&nbsp;&nbsp;".$premail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	    //Act Dir: (=AD)
	    if($adname!="")
	    {
	       $listings[$l]="Act Dir-$adname";
               if(strlen($adname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($adphone!="")
	       {  $listings[$l].=$delim."$adphone<br />"; $linebreak=""; }
	       else if($actphone!="")
	       {  $listings[$l].=", $actphone<br />"; $linebreak=""; } */
	       if($ademail!="" && $online!=1)
	       {  $listings[$l].="$linebreak&nbsp;&nbsp;".$ademail."<br />"; $linebreak=""; }
	       else if($actemail!="" && $online!=1)
	       {  $listings[$l].="$linebreak&nbsp;&nbsp;".$actemail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	 }
	 else if($prname==$actname)	//Pr=Act Dir!=AD
	 {
	    //AD:
	    if($adname!="")
	    {
	       $listings[$l]="AD-$adname";
               if(strlen($adname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($adphone!="")
		  { $listings[$l].=$delim."$adphone<br />"; $linebreak=""; } */
	       if($ademail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$ademail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	    //Pr/Act Dir:
	    if($prname!="")
	    {
	       $listings[$l]="Pr/Act Dir-$prname";
               if(strlen($prname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($prphone!="")
		  { $listings[$l].=$delim."$prphone<br />"; $linebreak=""; }
	       else if($actphone!="")
		  { $listings[$l].=", $actphone<br />"; $linebreak=""; } */
	       if($premail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$premail."<br />"; $linebreak=""; }
	       else if($actemail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$actemail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	 }
	 else	//Pr!=AD!=Act Dir (all different)
	 {
	    //Pr:
	    if($prname!="")
	    {
	       $listings[$l]="Pr-$prname";
               if(strlen($prname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($prphone!="")
		  { $listings[$l].=$delim."$prphone<br />"; $linebreak=""; } */
	       if($premail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$premail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	    //AD:
	    if($adname!="")
	    {
	       $listings[$l]="AD-$adname";
               if(strlen($adname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
	       $linebreak="<br />";
/* 	       if($adphone!="")
		  { $listings[$l].=$delim."$adphone<br />"; $linebreak=""; } */
	       if($ademail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$ademail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	    //Act Dir:
	    if($actname!="")
	    {
	       $listings[$l]="Act Dir-$actname";
	       $linebreak="<br />";
               if(strlen($actname)>=30) $delim="<br />&nbsp;&nbsp;";
               else $delim=", ";
/* 	       if($actphone!="")
		  { $listings[$l].=$delim."$actphone<br />"; $linebreak=""; } */
	       if($actemail!="" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;".$actemail."<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
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
	 //$phone1=$row2[phone];
	 $email1=$row2[email];
         if(strlen($email1)>38)
	 {
            $email1=preg_replace("/\//",";",$email1);
            $tmp=explode(";",$email1);
            if(count($tmp)>1) $email1=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
         $sql2="SELECT * FROM logins WHERE sport='$sport2' AND school='$cursch2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $name2=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,"pp") && $name2=="")
            $name2="TBA";
         //else if(!IsRegistered2011($curschid,"pp"))
           // $name2="";
         //$phone2=$row2[phone];
         $email2=$row2[email];
         if(strlen($email2)>38)
	 {
            $email2=preg_replace("/\//",";",$email2);
            $tmp=explode(";",$email2);
            if(count($tmp)>1) $email2=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
         $sql2="SELECT * FROM logins WHERE sport='$sport3' AND school='$cursch2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $name3=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,"de") && $name3=="")
            $name3="TBA";
         //else if(!IsRegistered2011($curschid,"sp"))
           // $name3="";
        // $phone3=$row2[phone];
         $email3=$row2[email];
         if(strlen($email3)>38)
	 {
            $email3=preg_replace("/\//",";",$email3);
            $tmp=explode(";",$email3);
            if(count($tmp)>1) $email3=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
         if($name1==$name2 && $name2==$name3 && $name1!='')	//PP/SP/Deb
	 {
            $phone4="";
            $listings[$l]="SP/PP/Deb-".$name1; //put in combo field
            $linebreak="<br />";
/*             if($phone1!='---' && $phone1!=$curphone[0]."---")
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
                  { $listings[$l].=", ".trim($phone4)."<br />"; $linebreak=""; }
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
                  { $listings[$l].=", ".trim($phone4)."<br />"; $linebreak=""; }
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
                  { //$listings[$l].=", ".trim($phone4)."<br />"; $linebreak=""; 
				  }
            } */
            if($email1!="" && $email1!="none" && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$email1<br />"; $linebreak=""; }
            else if($email2!="" && $email2!="none" && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$email2<br />"; $linebreak=""; }
            else if($email3!="" && $email3!="none" && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$email3<br />"; $linebreak=""; }
            $listings[$l].=$linebreak;
	    $l++;
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
               $listings[$l]="$title-".$partner1; //Skip $sport1 and $sport2 fields and put in combo field
               $linebreak="<br />";
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
                     { $listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; }
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
                     { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
					 }
               }
               if($partner1em!="" && $partner1em!="none" && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$partner1em<br />"; $linebreak=""; }
               else if($partner2em!="" && $partner2em!="none" && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$partner2em<br />"; $linebreak=""; }
               $listings[$l].=$linebreak;
		$l++;
            }//end if partner1!=''
	    if($loner!='' && $title!='')
	    {
               $phone3="";
               $listings[$l]="$lonertitle-".$loner; //Skip $sport1 and $sport2 fields and put in combo field
               $linebreak="<br />";
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
                     { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
					 }
               }
	       if($lonerem!='' && $lonerem!='none' && $online!=1)
	       { $listings[$l].="$linebreak&nbsp;&nbsp;$lonerem<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
		$l++;
	    }//end if loner!=''
	    else if($title=='')	//SP!=PP!=Deb
	    {
	       if($partner1!='')
	       {
               $phone3="";
               $listings[$l]="$abbrev1-".$partner1; 
               $linebreak="<br />";
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
                     { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
					 }
               }
	       if($partner1em!='' && $partner1em!='none' && $online!=1)
	       { $listings[$l].="$linebreak&nbsp;&nbsp;$partner1em<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
		$l++;
	       }
               if($partner2!='')
               {
               $phone3="";
               $listings[$l]="$abbrev2-".$partner2; 
               $linebreak="<br />";
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
                     { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
					 }
               }
               if($partner2em!='' && $partner2em!='none' && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$partner2em<br />"; $linebreak=""; }
               $listings[$l].=$linebreak;
		$l++;
               }
               if($partner3!='')
               {
               $phone3="";
               $listings[$l]="$abbrev3-".$partner3;
               $linebreak="<br />";
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
                     { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
					 }
               }
               if($partner3em!='' && $partner3em!='none' && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$partner3em<br />"; $linebreak=""; }
               $listings[$l].=$linebreak;
		$l++;
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
         if(strlen($email1)>38)
	 {
            $email1=preg_replace("/\//",";",$email1);
            $tmp=explode(";",$email1);
            if(count($tmp)>1) $email1=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
         if(IsRegistered2011($curschid,$sportreg1) && $name1=="") $name1="TBA";
	 $sql2="SELECT * FROM logins WHERE sport='$sport2' AND school='$cursch2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $name2=ereg_replace("none","",trim($row2[name]));
         if(IsRegistered2011($curschid,$sportreg2) && $name2=="") $name2="TBA";
	 $phone2=$row2[phone];
	 $email2=$row2[email];
         if(strlen($email2)>38)
	 {
            $email2=preg_replace("/\//",";",$email2);
            $tmp=explode(";",$email2);
            if(count($tmp)>1) $email2=trim($tmp[0])."<br />&nbsp;&nbsp;".trim($tmp[1]);
	 }
	 if($name1==$name2 && $name1!='')
	 {
	    $phone3="";
	    $listings[$l]=$staffs_sm2[$i]."-".$name1; //Skip $sport1 and $sport2 fields and put in combo field
	    $linebreak="<br />";
	    if(preg_replace("/[^0-9]/","",$phone1)!='' && $phone1!=$curphone[0]."---")
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
	          { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
			  }
	    }
	    else if(preg_replace("/[^0-9]/","",$phone2)!='' && $phone2!=$curphone[0]."---")
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
	          { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
			  }
	    }
	    if($email1!="" && $email1!="none" && $online!=1)
	       { $listings[$l].="$linebreak&nbsp;&nbsp;$email1<br />"; $linebreak=""; }
	    else if($email2!="" && $email2!="none" && $online!=1)
	       { $listings[$l].="$linebreak&nbsp;&nbsp;$email2<br />"; $linebreak=""; }
	    $listings[$l].=$linebreak;
	    $l++;
	 }
	 else
	 {
	    if($name1!='')
	    {
	       $listings[$l]=$abbrev1."-".$name1;
	       $linebreak="<br />";
	       if(preg_replace("/[^0-9]/","",$phone1)!='' && $phone1!=$curphone[0]."---")
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
	             { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak="";
				 }
	       }
	       if($email1!="" && $email1!="none" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;$email1<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
		$l++;
	    }
	    if($name2!='')
	    {
	       $listings[$l]="$abbrev2-$name2";
	       $linebreak="<br />";
	       if(preg_replace("/[^0-9]/","",$phone2)!='' && $phone2!=$curphone[0]."---")
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
	             { //$listings[$l].=", ".trim($phone3)."<br />"; $linebreak=""; 
				 }
	       }
	       if($email2!="" && $email2!="none" && $online!=1)
		  { $listings[$l].="$linebreak&nbsp;&nbsp;$email2<br />"; $linebreak=""; }
	       $listings[$l].=$linebreak;
	       $l++;
	    }
	 }	//END IF THEY DON'T MATCH
      }//end if a combo sport
      else if($staffs2[$i]=="Journalism")	//attach news and year as well
      {
         $sql2="SELECT DISTINCT name,phone,email FROM logins WHERE sport LIKE 'Journalism' AND school='$cursch2' AND name!='' AND name!='none' ORDER BY sport";
         $result2=mysql_query($sql2);
         $jct=0; $listings[$l]="";
         while($row2=mysql_fetch_array($result2))
         {
            if($jct==0) $listings[$l].=$staffs_sm2[$i]."-";
	    else $listings[$l].="&nbsp;&nbsp;&nbsp;";
	    $listings[$l].="$row2[name]";
            $linebreak="<br pagebreak=\"false\"/>";
            if(preg_replace("/[^0-9]/","",$row2[phone])!='' && $row2[phone]!=$curphone[0]."---")
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
                  { //$listings[$l].=", ".trim($phone2)."<br pagebreak=\"false\"/>"; $linebreak=""; 
				  }
            }
            if($row2[email]!="" && $row2[email]!="none" && $online!=1)
               { $listings[$l].="$linebreak&nbsp;&nbsp;$row2[email]<br />"; $linebreak=""; }
            $listings[$l].=$linebreak;
	    $jct++;
         }
         $l++;
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
	    $listings[$l]=$staffs_sm2[$i]."-$row2[name]";
	    $linebreak="<br pagebreak=\"false\"/>";
	    if(preg_replace("/[^0-9]/","",$row2[phone])!='' && $row2[phone]!=$curphone[0]."---")
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
	          { //$listings[$l].=", ".trim($phone2)."<br pagebreak=\"false\"/>"; $linebreak=""; 
			  }
	    }
	    if($row2[email]!="" && $row2[email]!="none" && $online!=1)
	       { $listings[$l].="$linebreak&nbsp;&nbsp;$row2[email]<br />"; $linebreak=""; }
	    $listings[$l].=$linebreak;
		$l++;
	 }
	 }//end if not Act Dir
      }
   }
   $ix++;
	//STAFF CONTACT INFO
   if($l%3==0) $percol=$l/3;
   else $percol=ceil($l/3);
   $col=array("","",""); $c=0;
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr><td>";
   for($l=0;$l<count($listings);$l++)
   {
      if(($l%$percol)==0 && $l>0)
      {
	 $c++;
         $html.="</td><td>";
      }
      $col[$c].=$listings[$l]; //."<br />";
      $html.=$listings[$l];
   }
   $html.="</td></tr></table>";
   $x=0; $y=$pdf->GetY();
   $y++;
   $html=CleanForPDF($html);
   $html=ereg_replace("Kendra.Craven@agps.org;Kayla.Laune@agps.org","Kendra.Craven@agps.org<br />&nbsp;&nbsp;Kayla.Laune@agps.org",$html);
   $X2=$x; $Y2=$y; $HTML2=$html;
   $pdf->writeHTMLCell("","",$x,$y,"$html",0,1,1,true,"L");
   $postpages = $pdf->getNumPages();
   if($prepages<$postpages)	//WENT TO NEXT PAGE WITH THIS TABLE
   {
      $pdf->rollbackTransaction(true);
      $pdf->AddPage();
      $y=0;
      $pdf->Rect($XRect, $y, 216, 1, 'F', array(), array("0","0","0"));
      $pdf->SetFillColor(255,255,255);
      $y++;
      $pdf->SetFont("times","BI","13");
      $pdf->writeHTMLCell("","",$XSch,$y,"$cursch",0,1,1,true,"L");
      $y=$pdf->GetY();
      $pdf->Image("../images/verticalline.png",70,$y,"$height",'','','','',false,72,'',false,false,0,false,false,true);
      $pdf->Image("../images/verticalline.png",140,$y,"$height",'','','','',false,72,'',false,false,0,false,false,true);
      $pdf->SetFont("berthold","","9.5");
      $pdf->writeHTMLCell("","",$X1,$y,"$HTML1",0,2,0,true,"L");
      $y2=$pdf->GetY();
      $x1=0; $x2=216; $y1=$y2;
      $pdf->Line($x1, $y1, $x2, $y2);
      $y=$pdf->GetY();
      $pdf->writeHTMLCell("","",$X2,$y,"$HTML2",0,1,1,true,"L");
   }
   else
      $pdf->commitTransaction();
   $y=$pdf->GetY();

}
if($format=="html") echo $code."</body></html>";
else $pdf->Output("downloads/$filename", "F");
?>
