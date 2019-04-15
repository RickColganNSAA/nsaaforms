<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   if($sport=='pp' || $sport=='sp')
      header("Location:jindex.php?error=1");
   else
      header("Location:index.php?error=1");
   exit();
}

$contracts=$sport."contracts";
$districts=$sport."districts";
if($sport=='fb')
   $districts=$sport."brackets";
$disttimes=$sport."disttimes";

if($sport=='pp' || $sport=='sp')
{
   $sportname=GetSportName($sport);
   //first get contracts that have not been posted yet:
   $sql="SELECT offid,distid FROM $contracts WHERE post!='y'";
   $result=mysql_query($sql);
   $nopost=array(); $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $nopost[offid][$ix]=$row[0]; $nopost[distid][$ix]=$row[1];
      $ix++;
   }
   if($all==1 || !$distid || $distid==0 || $distid=='State')
      $sql="UPDATE $contracts SET post='y'";
   else
      $sql="UPDATE $contracts SET post='y' WHERE distid='$distid'";
   $result=mysql_query($sql);
   //now go through $nopost and send e-mail if their contract is now posted
   for($i=0;$i<count($nopost[offid]);$i++)
   {
      $sql="SELECT t1.type,t1.class,t1.district FROM $districts AS t1, $contracts AS t2 WHERE t1.id=t2.distid AND t2.offid='".$nopost[offid][$i]."' AND t2.post='y' AND t1.id='".$nopost[distid][$i]."'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
 	 $row=mysql_fetch_array($result);
         $From="nsaa@nsaahome.org"; $FromName="NSAA";
	 $sql2="SELECT first,last,email FROM judges WHERE id='".$nopost[offid][$i]."'";
         $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $To=$row2[email]; $ToName="$row2[first] $row2[last]";
         $Subject="Selection to Judge the";
         if($row[type]=='State') $Subject.=" State ";
	 else $Subject.=" $row[type] $row[class]-$row[district] ";
	 $Subject.="$sportname Contest.";
         $Html="You have been selected to Judge the";
         if($row[type]=='State') $Html.=" State "; 
         else $Html.=" $row[type] $row[class]-$row[district] ";
         $Html.="$sportname Contest.<br><br>";
         $Html.="Please login to your account at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/jindex.php\">https://secure.nsaahome.org/nsaaforms/officials/jindex.php</a> to view and respond to your contract.<br><br>Thank You!";
	 $Text=ereg_replace("<br>","\r\n",$Html);
	 $Html2=$Html."<br><br>($To, $ToName)";
	 $Text2=ereg_replace("<br>","\r\n",$Html2);
	 $Attm=array();
         if(($distid=="State" && $row[type]=="State") || $distid!="State")
	 {
	    //SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
		 if(SendMail("nsaa@nsaahome.org","NSAA",$To,$ToName,$Subject,$Html,$Html,$Attm)){
	   
			writefile('sendemailsoutput.html', "Sent to $To\r\n"."DONE!");
		}
		else writefile('sendemailsoutput.html', "Could not send to $To\r\n"."DONE!");
		
            $Html=ereg_replace("\'","`",$Html);
            //citgf_exec("/usr/local/bin/php sendemail.php '$session' '$To' '$ToName' '$Subject' '$Html' '$Attm' > sendemailsoutput.html 2>&1 &");
	 }
      }
   }
}//end play or speech
else
{
   $sportname=GetSportName($sport);
   //first get contracts that have not been posted yet:
   if($sport=='wr')
      $sql="SELECT offid,distid FROM $contracts WHERE post!='y'";
   else if($sport=='fb')
      $sql="SELECT offid,gameid FROM $contracts WHERE post!='y'";
   else
      $sql="SELECT offid,disttimesid FROM $contracts WHERE post!='y'";
   $result=mysql_query($sql);
   $nopost=array(); $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $nopost[offid][$ix]=$row[0]; $nopost[disttimesid][$ix]=$row[1];
      $ix++;
   }
   if($type!='' && $sport!='wr' && $sport!='fb')
   {
      $sql="SELECT t1.id FROM $disttimes AS t1, $districts AS t2 WHERE t1.distid=t2.id AND ";
      if($type=='State' || $type=='state') $sql.="t2.type='State'";
      else $sql.="t2.type!='State'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $sql2="UPDATE $contracts SET post='y' WHERE disttimesid='$row[0]'";
         $result2=mysql_query($sql2);
      }
   }
   else if($type!='' && $sport=='wr')
   {
      $sql="SELECT id FROM $districts WHERE ";
      if($type=='State' || $type=='state') $sql.="t2.type='State'";
      else $sql.="t2.type!='State'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $sql2="UPDATE $contracts SET post='y' WHERE distid='$row[0]'";
         $result2=mysql_query($sql2);
      }
   }
   else
   {
      $sql="UPDATE $contracts SET post='y'";
      $result=mysql_query($sql);
   }
   for($i=0;$i<count($nopost[offid]);$i++)
   {
      if($sport=='wr')
         $sql="SELECT t1.type,t1.class,t1.district FROM $districts AS t1, $contracts AS t2 WHERE t1.id='".$nopost[disttimesid][$i]."' AND t1.id=t2.distid AND t2.offid='".$nopost[offid][$i]."' AND t2.post='y'";
      else if($sport=='fb')
         $sql="SELECT t1.class,t1.round FROM $districts AS t1, $contracts AS t2 WHERE t1.id='".$nopost[disttimesid][$i]."' AND t1.id=t2.gameid AND t2.offid='".$nopost[offid][$i]."' AND t2.post='y'";
      else
         $sql="SELECT t1.type,t1.class,t1.district FROM $districts AS t1, $contracts AS t2, $disttimes AS t3 WHERE t1.id=t3.distid AND t2.disttimesid=t3.id AND t2.offid='".$nopost[offid][$i]."' AND t2.post='y' AND t3.id='".$nopost[disttimesid][$i]."'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $row=mysql_fetch_array($result);
         $From="nsaa@nsaahome.org"; $FromName="NSAA";
         $sql2="SELECT first,last,email FROM officials WHERE id='".$nopost[offid][$i]."'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $To="sk118420@gmail.com"; $ToName="$row2[first] $row2[last]";
         $Subject="Selection to Officiate";
	 if($sport=='fb')
	    $Subject.=" Class $row[class] $row[round] Football Playoffs";
	 else
	 {
            if($row[type]=='State') $Subject.=" State ";
            else $Subject.=" $row[type] $row[class]-$row[district] ";
            $Subject.="$sportname.";
         }
         $Html="You have been selected to Officiate";
	 if($sport=='fb')
	    $Html.=" a Class $row[class] $row[round] Football Playoffs game.<br><br>";
	 else
	 {
            if($row[type]=='State') $Html.=" State ";
            else $Html.=" $row[type] $row[class]-$row[district] ";
            $Html.="$sportname.<br><br>";
	 }
         $Html.="Please login to your account at <a href=\"https://secure.nsaahome.org/nsaaforms/officials/index.php\">https://secure.nsaahome.org/nsaaforms/officials/index.php</a> to view and respond to your contract.<br><br>Thank You!";
         $Text=ereg_replace("<br>","\r\n",$Html);
         $Html2=$Html."<br><br>($To, $ToName)";
         $Text2=ereg_replace("<br>","\r\n",$Html2);
         $Attm=array();
	 if($To!='')
	 {
            //SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
			if(SendMail("nsaa@nsaahome.org","NSAA",$To,$ToName,$Subject,$Html,$Html,$Attm)){
	   
					writefile('sendemailsoutput.html', "Sent to $cur_to\r\n"."DONE!");
			 }
			   else writefile('sendemailsoutput.html', "Could not send to $cur_to\r\n"."DONE!");
		
            $Html=ereg_replace("\'","`",$Html);
            //citgf_exec("/usr/local/bin/php sendemail.php '$session' '$To' '$ToName' '$Subject' '$Html' '$Attm' > sendemailsoutput.html 2>&1 &");
         }
      }
   }
}

if($return && $return!="")
   header("Location:$return.php?sport=$sport&posted=yes&session=$session&type=$type&distid=$distid&confirmed=$confirmed");
else if($sport=='sp' || $sport=='pp')
   header("Location:assignreportplay.php?session=$session&posted=yes&sport=$sport&distid=$distid");
else
   header("Location:assignreport.php?sport=$sport&posted=yes&session=$session&type=$type");

exit();
?>
