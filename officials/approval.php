<?php
require 'functions.php';
if($ssl_invoice_number=='0' || $ssl_invoice_number=='' || !$ssl_invoice_number)
{
   echo "ERROR: No application id.";
   exit();
}
else    //check that invoice is not old (needs to equal today's date)
{
   $today=date("m/d/y");
   $invoicedate=date("m/d/y",$ssl_invoice_number);
   if($today!=$invoicedate && $secret!='46D5431FF61CD7EC47478FB32A52A')
   {
      echo "Expired session.  To complete your payment, you must start over.<br><br>";
      echo "<a href=\"application.php\">Click Here to complete the Officials Application Form</a>";
      exit();
   }
}
//approval.php: Page displayed if CC was approved

require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//insert transaction data into database
$sql="SELECT * FROM officialsapp WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   $sql2="INSERT INTO officialsapp (appid,approved) VALUES ('$ssl_invoice_number','yes')";
else
   $sql2="UPDATE officialsapp SET approved='yes' WHERE appid='$ssl_invoice_number'";
$result2=mysql_query($sql2);

//insert transaction data into database
$sql="SELECT * FROM pendingoffs WHERE appid='$ssl_invoice_number'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: No data for invoice #$ssl_invoice_number. (Date of invoice: ".date("m/d/y",$ssl_invoice_number).")";
   $attm=array("apps/app$ssl_invoice_number.html");
   SendMail("nsaa@nsaahome.org","NSAA","run7soccer@aim.com","Ann Gaffigan","Officials App Error","$ssl_invoice_number, ".date("m/d/y",$ssl_invoice_number),"$ssl_invoice_number, ".date("m/d/y",$ssl_invoice_number),$attm);
   exit();
}
else
{
   $sql2="UPDATE pendingoffs SET approved='yes' WHERE appid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
}

//UPDATE/INSERT INTO officials table the data from pendingoffs table for this official
$row=mysql_fetch_array($result);	//data from pendingoffs into $row
echo mysql_error();
if($row[offid]=="" || $row[offid]=='0')	//INSERT new entry
{
   $firstname=$row[first];
   $lastname=$row[last];   //might use for passcode generation below
   $row[first]=addslashes($row[first]);
   $row[last]=addslashes($row[last]);
   $row[address]=addslashes($row[address]);
   $row[city]=addslashes($row[city]);
   $row[convictionexplain]=addslashes($row[convictionexplain]);
   $sql1="SELECT * FROM officials WHERE appid='$ssl_invoice_number'";
   $result1=mysql_query($sql1);
   if(mysql_num_rows($result1)==0)
   {
      $sql2="INSERT INTO officials (appid,socsec,first,middle,last,address,city,state,zip,homeph,workph,cellph,email,nhsoa,gender,minority,conviction,convictionexplain) VALUES ('$ssl_invoice_number','$row[socsec]','$row[first]','$row[middle]','$row[last]','$row[address]','$row[city]','$row[state]','$row[zip]','$row[homeph]','$row[workph]','$row[cellph]','$row[email]','$row[nhsoa]','$row[gender]','$row[minority]','$row[conviction]','$row[convictionexplain]')";
   }
   else		//update entry (this will be used if they hit reload on approval page)
   {
      $sql2="UPDATE officials SET inactive='',socsec='$row[socsec]',first='$row[first]',middle='$row[middle]',last='$row[last]',address='$row[address]',city='$row[city]',state='$row[state]',zip='$row[zip]',homeph='$row[homeph]',workph='$row[workph]',cellph='$row[cellph]',email='$row[email]',";
      if($row[nhsoa]=='x') $sql2.="nhsoa='$row[nhsoa]',";
      $sql2.="gender='$row[gender]',minority='$row[minority]',conviction='$row[conviction]',convictionexplain='$row[convictionexplain]' WHERE appid='$ssl_invoice_number'";
   }
   $result2=mysql_query($sql2);
   if(mysql_error()) 
   {
      echo "We're sorry.  An unexpected error occurred.  Please contact the NSAA office and reference your application ID: $ssl_invoice_number.";
      echo "<div class='error'>ERROR in the query: $sql2<br><br>".mysql_error()."</div>";
      exit();
   }
   //get new offid
   $sql2="SELECT id FROM officials WHERE appid='$ssl_invoice_number'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $offid=$row2[0];
   $newoff=1;
}
else	//UPDATE entry
{
   $row[first]=addslashes($row[first]);
   $row[last]=addslashes($row[last]);
   $row[address]=addslashes($row[address]);
   $row[city]=addslashes($row[city]);
   $row[convictionexplain]=addslashes($row[convictionexplain]);
   $offid=$row[offid];
   $sql2="UPDATE officials SET inactive='',appid='$ssl_invoice_number',socsec='$row[socsec]',first='$row[first]',middle='$row[middle]',last='$row[last]',address='$row[address]',city='$row[city]',state='$row[state]',zip='$row[zip]',homeph='$row[homeph]',workph='$row[workph]',cellph='$row[cellph]',email='$row[email]',";
   if($row[nhsoa]=='x') $sql2.="nhsoa='$row[nhsoa]',";
   $sql2.="gender='$row[gender]',minority='$row[minority]',conviction='$row[conviction]',convictionexplain='$row[convictionexplain]' WHERE id='$offid'";
   $result2=mysql_query($sql2);
   $newoff=0;
   $firstname=$row[first]; $lastname=$row[last];
if(mysql_error()) { echo $sql2."<br>".mysql_error(); exit(); }
}
$savedql=$sql2;

//LOGINS table: see if $offid has an entry
//If it does, get passcode OR if passcode is blank, generate passcode
//If it does NOT, generate passcode and add entry to logins table
$sql2="SELECT * FROM logins WHERE offid='$offid' AND offid!='0'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)	//official already has entry in logins table
{
   $row2=mysql_fetch_array($result2);
   $passcode=$row2[passcode];
   if($passcode=='' || $passcode=='0')
   {
      //generate new passcode for this official:
      $passcode=GeneratePasscode($lastname,1);
      //Update logins table
      $sql3="UPDATE logins SET passcode='$passcode' WHERE offid='$offid'";
      $result3=mysql_query($sql3);
   }
}
else	//Need NEW entry for this official in logins table
{
   //First, generate passcode, as done above
   $passcode=GeneratePasscode($lastname,1);
   //Then INSERT new entry into logins table
   $name=addslashes("$firstname $lastname");
   $sql2="INSERT INTO logins (name,level,passcode,offid) VALUES ('$name','2','$passcode','$offid')";
   $result2=mysql_query($sql2);
}
//Now $passcode has current passcode for this official
   
//record payment as "credit" in $db_name2.__off table 
$ccappsp=array("fb","vb","sb","bb","wr","sw","di","so","ba","tr");
$appdate=date("Y-m-d");
$year1=date("Y"); $mo=date("m");
$regyr=GetSchoolYear($year1,$mo);
for($i=0;$i<count($ccappsp);$i++)
{
   $field=$ccappsp[$i];
   $table=$ccappsp[$i]."off";
   $table2=$table."_hist";
   $field2=$field."contests";
   if($row[$field]=='x' || ($field=='tr' && $row[tr2]=='x'))
   {
      //put check in officials table
      $sql3="UPDATE officials SET $field='x' WHERE id='$offid'";
      $result3=mysql_query($sql3);

      $contests=$row[$field2];	//get contests for this sport from pendingoffs table

      //get current mailing number for this sport
      $sqlM="SELECT mailnum,mailnum2 FROM mailing WHERE sport='$ccappsp[$i]'";
      $resultM=mysql_query($sqlM);
      $rowM=mysql_fetch_array($resultM);
      $curmailnum=$rowM[0]; $curmailnum2=$rowM[1];

      //__off table:
      $sql3="SELECT * FROM $table WHERE offid='$offid'";
      $result3=mysql_query($sql3);
      $today=time();
      //First check if they already have a primary number for another sport before it in the list 
      $usemailnum=$curmailnum;
      for($j=0;$j<$i;$j++)
      {
	 $curtable=$ccappsp[$j]."off";
         $sql2="SELECT mailing FROM $curtable WHERE offid='$offid' AND payment!=''";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[0]<50 && $row2[0]>0 && mysql_num_rows($result2)>0)
	 {
	    $usemailnum=$curmailnum2;
	    $j=$i;
	 }
      }
      //If so, use secondary number
      if(mysql_num_rows($result3)>0)
         $sql2="UPDATE $table SET payment='credit',datepaid='$today',appid='$ssl_invoice_number',mailing='$usemailnum' WHERE offid='$offid'";
      else	//new off: INSERT
         $sql2="INSERT INTO $table (offid,payment,datepaid,appid,mailing) VALUES ('$offid','credit','$today','$ssl_invoice_number','$usemailnum')";
      $result2=mysql_query($sql2);

      //update __off_hist table
      $sql2="SELECT * FROM $table2 WHERE offid='$offid' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)	//INSERT
      {
         $sql3="INSERT INTO $table2 (offid,regyr,appdate,contest,nhsoa";
	 if($field=='tr') $sql3.=",position";
         $sql3.=") VALUES ('$offid','$regyr','$appdate','$contests','$row[nhsoa]'";
	 if($field=='tr' && $row[tr]=='x') $sql3.=",'starter'";
         else if($field=='tr') $sql3.=",'referee'";
    	 $sql3.=")";
      }
      else	//UPDATE
      {
	 $sql3="UPDATE $table2 SET appdate='$appdate',contest='$contests',";
         if($row[nhsoa]=='x') $sql3.="nhsoa='$row[nhsoa]'";
	 if($field=='tr' && $row[tr]=='x') $sql3.=",position='starter'";
	 else if($field=='tr') $sql3.=",position='referee'";
	 $sql3.=" WHERE offid='$offid' AND regyr='$regyr'";
      }
      $result3=mysql_query($sql3);

      UpdateRank($offid,$field);

      if($row[nhsoa]=='x')	//GO UPDATE THEIR OTHER SPORTS
      {
	 $sql4="SHOW TABLES LIKE '%off_hist'";
         $result4=mysql_query($sql4);
	 while($row4=mysql_fetch_array($result4))
	 {
	    $sql5="UPDATE $row4[0] SET nhsoa='x' WHERE offid='$offid' AND regyr='$regyr'";
	    $result5=mysql_query($sql5);
         }
      }
      else	//IF OTHER SPORT HAS NHSOA CHECKED, CHECK IT FOR THIS SPORT
      {
         $sql4="SHOW TABLES LIKE '%off_hist'";
         $result4=mysql_query($sql4);
         while($row4=mysql_fetch_array($result4))
         {
      	    $sql5="SELECT * FROM $row4[0] WHERE offid='$offid' AND regyr='$regyr'";
            $result5=mysql_query($sql5);
	    if($row5=mysql_fetch_array($result5))
	    {
	       if($row5[nhsoa]=='x')	//THEN UPDATE THIS YEAR
	       {
                  $sql6="UPDATE $table2 SET nhsoa='x' WHERE offid='$offid' AND regyr='$regyr'";
                  $result6=mysql_query($sql6);
                  //SendMail("nsaa@nsaahome.org","NSAA","agaffigan@gazelleincorporated.com","Ann Gaffigan","Official updated NHSOA check","Check on official $offid's NHSOA checkmarks","Check on official $offid's NHSOA checkmarks",array());
	       }
	    }
         }
      }
      if($field=='tr')
      {
	    $html=$sql3;
	    //SendMail("nsaa@nsaahome.org","NSAA","agaffigan@gazelleincorporated.com","Ann Gaffigan","Track Official Registered","$html","$html",array());
      }
   }
}

$appid=$ssl_invoice_number;
//send me e-mail so I can check up on how this is working
$Text="Official Approved:\r\noffid=$offid\r\nappid=$appid\r\nMailnum: $usemailnum\r\n$savedsql";
$Html="Official Approved:<br>offid=$offid<br>appid=$appid<br>Mailnum: $usemailnum<br>$savedsql";
$Attm=array();
//SendMail("nsaa@nsaahome.org","NSAA","run7soccer@aim.com","Ann Gaffigan","Official's CC Approved",$Text,$Html,$Attm);
?>
<html>
<head>
   <title>NSAA | Official's Application Form</title>
   <link href="/css/nsaaforms.css" rel=stylesheet type="text/css">
</head>
<body>
<table><tr align=center><td>
<table width=500>
<caption><b>Transaction Complete!<hr></b></caption>
<?php
echo "<tr align=left><th colspan=2>Please print this page for your records:<br><br></th></tr>";
$date=date("M d, Y",$ssl_invoice_number);
echo "<tr align=left><th class=smaller>Transaction #:</th><td>$appid</td></tr>";

$string="<tr><td colspan=2><table>";
$string.="<tr align=left><td><br>Billing Name:</td><td>$ssl_first_name $ssl_last_name</td></tr>";
$string.="<tr align=left><td><br>Billing Address:</td><td>$ssl_avs_address<br>$ssl_avs_zip</td></tr>";
$string.="<tr align=left><td><br>Credit Card Number:</td><td>$ssl_card_number</td></tr>";

$sql="SELECT * FROM officialsapp WHERE appid='$appid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$html=$row[html]."$string</table></body></html>";
$html=addslashes($html);
$sql="UPDATE officialsapp SET html='$html' WHERE appid='$appid'";
$result=mysql_query($sql);

$showhtml=ereg_replace("<html><body>","",$html);
$showhtml=ereg_replace("</body></html>","",$showhtml);
echo "<tr align=center><td colspan=2>".$showhtml."</td></tr></table><br>";

?>
<table width=500>
<tr align=left><td colspan=2><br><b><i>I have read the NSAA regulations governing officials.  I further understand and accept that officials are considered independent contractors and not employees of the Nebraska School Activities Association.</i></b></th></tr>
<tr align=center><th align=left colspan=2><br><i>Your passcode is</i>: <?php echo $passcode; ?><br>
You may login with this passcode at: <a class=small target=new href="index.php">https://secure.nsaahome.org/nsaaforms/officials</a></th></tr>
<tr align=left><td colspan=2><font style=\"color:red\"><b>PLEASE WRITE THIS PASSCODE DOWN IN A SAFE, EASY-TO-REMEMBER PLACE.  You will NOT be able to return to this screen to retrieve your passcode.</b></font></td></tr>
<tr align=center><td colspan=2><br><br>Thank You!<br><br><a href="/">nsaahome.org</a></td></tr>
</table>
</td></tr></table>
</body>
</html>
