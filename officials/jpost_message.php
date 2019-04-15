<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//validate user
if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}
$level=GetLevelJ($session);

if($submit=="Cancel")
{
   header("Location:jwelcome.php?session=$session");
   exit();
}
else if($submit)	//store message in db
{
   //get end date in correct format
   $end_date="$year-$month-$day";

   //Check for " or ' in title and message_text:
   $title=ereg_replace("\'","",$title);
   $title=ereg_replace("\"","",$title);
      //keep on original format for e-mails:
      $email_text=$message_text;
      $email_html=ereg_replace("\r\n","<br>",$email_text);
   $message_text=ereg_replace("\'","",$message_text);
   $message_text=ereg_replace("\"","",$message_text);

   //trim whitespace from edges and get rid of multiple spaces:
   $title=trim($title);
   $title=preg_replace("/( +)/", " ", $title);
   $message_text=trim($message_text);
   $message_text=preg_replace("/( +)/", " ", $message_text);

   //Get variables ready for possible e-mails
   $attm=array();
   $recipients="";

   //if All Officials was chosen, make array with all officials in it:
 
   if(trim($from)=='') $from="ccallaway@nsaahome.org";
   $fromname="NSAA";

   if($filename)
   {
      $newfile=$_FILES["filename"]["name"];
      $newfile=ereg_replace(" ","",$newfile);
      $newfile=ereg_replace("\'","",$newfile);
      $newfile=ereg_replace("\"","",$newfile);
      $newfile=strtolower($newfile);
	 
      $i=2;
      while(citgf_file_exists("messagefiles/$newfile"))
      {
         $temp=split("[.]",$newfile);
         $newfile=$temp[0].$i.".".$temp[1];
         $i++;
      }
      citgf_copy($filename,"messagefiles/$newfile");
      $attm[0]="messagefiles/$newfile";
   }

   //if message with this title already exists, replace it:
   //if box was checked, add recipients to email list
   if($alsoemail=='y')
   {
      $sql="SELECT DISTINCT email,last,first FROM judges WHERE email!='' AND ";
      if($judgesch=="All Judges") $sql.="(speech='x' OR play='x') ";
      else if($judgesch=='sp') $sql.="speech='x' ";
      else $sql.="play='x' ";
      if($emailwho=="reg") $sql.="AND payment!='' ";
      $sql.="ORDER BY last,first";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $recipients.=$row[email].", ";
      }
      $recipients=substr($recipients,0,strlen($recipients)-2);
   }

      $sql="SELECT * FROM messages WHERE title='$title' AND end_date='$end_date' AND ";
      if($judgesch=="All Judges") $sql.="(sport='sp' || sport='pp')";
      else $sql.="sport='$judgesch'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         while($row=mysql_fetch_array($result))
         {
            $cursp=$row[sport];
            $sql2="UPDATE messages SET fromemail='$from',message='$message',filename='$newfile',linkname='$linkname' WHERE title='$title' AND end_date='$end_date' AND sport='$cursp'";
	    $result2=mysql_query($sql2);
	    $messageid=$row[id];
	 }
      }
      else
      {
         if($judgesch=="All Judges")
         {
            $sql="INSERT INTO messages (fromemail, sport, title, message, end_date, filename,linkname) VALUES ('$from','sp','$title','$message_text','$end_date', '$newfile','$linkname')";
            $result=mysql_query($sql);
	    $sql="INSERT INTO messages (fromemail, sport,title,message,end_date,filename,linkname) VALUES ('$from','pp','$title','$message_text','$end_date','$newfile','$linkname')";
	    $result=mysql_query($sql);
            $messageid=mysql_insert_id();
	 }
         else
	 {
	    $sql="INSERT INTO messages (fromemail,sport,title,message,end_date,filename,linkname) VALUES ('$from','$judgesch','$title','$message_text','$end_date','$newfile','$linkname')";
   	    $result=mysql_query($sql);
            $messageid=mysql_insert_id();
	 }
      }

   //e-mail message if recipient list is not empty
   if(trim($recipients)!="")
   {
      $recipients.=",$from";
      $recips=ereg_replace(",","<recipient>",$recipients);
      //citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
	 // sendsemails($session,$messageid,$recips);
	   exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
   }

   //display confirmation page:
   $header=GetHeaderJ($session,"jwelcome");
   echo $init_html;
   echo $header;
   echo "<br><br><table width=75%><tr align=left><td><b>You have posted the following message:<br><br></td></tr>";
   echo "<tr align=left><td><b>\"$title\"</b></td></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><td><b>...to the following judges:</b></td></tr>";
   echo "<tr align=left><td>";
   if($judgesch=='sp') echo "Speech Only";
   else if($judgesch=='pp') echo "Play Only";
   else echo "Speech AND Play";
   echo "</td></tr>";
   echo "<tr align=left><td><b><br>...to show until: $month/$day/$year.</b></td></tr>";
   if($filename)
   {
       echo "<tr align=left><td><b><br>";
       echo "You have also uploaded the following file:&nbsp;&nbsp;";
       echo "<a href=\"messagefiles/$newfile\" target=new>$linkname</a>";
       echo "</td></tr>";
   }
   if(trim($recipients)!="")
   {
      echo "<tr align=left><td><b><br>";
      echo "You have also e-mailed this message to the following recipients:</td></tr>";
      echo "<tr align=left><td><table width=600><tr align=left><td>$recipients</td></tr></table></td></tr>";
   }
   echO "</table><br><a href=\"jpost_message.php?session=$session\" class=small>Create Another Message</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"jedit_message.php?session=$session\">Edit/Delete Message</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"jwelcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}//end if post

echo $init_html;
$header=GetHeaderJ($session,"jwelcome");
echo $header;

echo "<br><form method=post action=\"jpost_message.php\" enctype=\"multipart/form-data\" name=emailform>";
echo "<input type=hidden name=session value=$session>";
echo "<table><caption><b>Create a New Message:</b><hr></caption>";
echo "<tr align=left><td><b>Reply-To E-mail:</b></td><td><input type=text name=\"from\" size=30 value=\"ccallaway@nsaahome.org\"></td></tr>";
echo "<tr align=left><td><b>Subject of Message:</b></td>";
echo "<td><input type=text name=title size=50></td></tr>";
echo "<tr align=left><td><b>Display Message Until:</b></td>";
echo "<td><select name=month>";
$year=date("Y"); $year1=$year+1;
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($m==date("m")) echo " selected";
   echo ">$m</option>";
}
echo "</select>&nbsp;/&nbsp;<select name=day>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($d==date("d")) echo " selected";
   echo ">$d</option>";
}
echo "</select>&nbsp;/&nbsp;<select name=year>";
for($i=$year;$i<=$year1;$i++)
{
   echo "<option>$i</option>";
}
echo "</select></td></tr>";
echo "<tr align=left valign=top><td><b>";
echo "Recipients:";
echO "</b></td><td>";
echo "<select name=judgesch>";
echo "<option>All Judges</option>";
echo "<option value='pp'>Play Judges ONLY</option>";
echo "<option value='sp'>Speech Judges ONLY</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr align=left><td colspan=2>";
echo "<input type=checkbox name=alsoemail value='y'>&nbsp;<b>Check here if you would also like to E-MAIL the recipients you have selected above.</b></td></tr>";
echo "<tr align=left><td colspan=2>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=emailwho value='all' checked>&nbsp;";
echo "E-mail <b>ALL</b> judges in the database<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=emailwho value='reg'>&nbsp;";
echo "E-mail only the <b>REGISTERED</b> judges in the database</td></tr>";
echo "<tr align=left><td colspan=2><b>Message:</b></td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<textarea cols=90 rows=10 name=message_text></textarea></td></tr>";
echo "<tr align=left><td><b>Upload a File:</b></td>";
echo "<td><input type=file name=filename></td></tr>";
echo "<tr align=left><td><b>Name of Link (Description of File):</b></td>";
echo "<td><input type=text name=linkname size=40></td></tr>";
echo "<tr align=center><td colspan=2><br><br><input type=submit name=submit value=\"Post\">&nbsp;";
echo "<input type=submit name=submit value=\"Cancel\">";
echo "</td></tr></table></form>";

echo $end_html;
?>
