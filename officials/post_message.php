<?php
//post_message.php:  Level 1 can post messages to Level 2, and Level2 
//	can post ones to Level 3

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=Getlevel($session);

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
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
      if(trim($email_text)=="") $email_text=$title;
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
   $empty=array();
   if($sport_array[0]=="All Officials")
   {
      $sport_array=array_merge($empty,$activity);
   }
 
   if(trim($from)=='') $from="nsaa@nsaahome.org";
   $fromname="NSAA";

   if(is_uploaded_file($_FILES['filename']['tmp_name']))
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
      citgf_copy($_FILES['filename']['tmp_name'],"messagefiles/$newfile");
      $attm[0]="messagefiles/$newfile";   
      if(trim($linkname)=="") $linkname=$newfile;
      else $linkname=addslashes($linkname);
   }

   //if message with this title already exists, replace it:
   for($i=0;$i<count($sport_array);$i++)
   {
      //if box was checked, add recipients to email list
      if($alsoemail=='y')
      {
         $temp=$sport_array[$i]; $histtbl=$temp."off_hist";
         //Only e-mail officials with registration record from this year or last year
         $thismo=date("n"); $thisyr=date("Y"); $lastyr=$thisyr-1;
         $thisyear=GetSchoolYear($thisyr,$thismo); 
         $lastyear=GetSchoolYear($lastyr,$thismo);
         $sql="SELECT DISTINCT t1.email,t1.last,t1.first FROM officials AS t1, $histtbl AS t2 WHERE t1.inactive!='x' AND t1.id=t2.offid AND t1.email!='' AND (t2.regyr='$lastyear' OR t2.regyr='$thisyear') ORDER BY t1.last,t1.first";
         $result=mysql_query($sql);
         if(mysql_error()) echo "$sql<br>".mysql_error();
         while($row=mysql_fetch_array($result))
	 {
	    $row[email]=trim($row[email]);
	    $recipients.=$row[email].",";
         }
      }

      $sql="SELECT * FROM messages WHERE title='$title' AND end_date='$end_date' AND sport='$sport_array[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $row=mysql_fetch_array($result);
         $sql="UPDATE messages SET fromemail='$from',message='$message',filename='$newfile',linkname='$linkname' WHERE title='$title' AND end_date='$end_date' AND sport='$sport_array[$i]'";
	 $result=mysql_query($sql);
	 $messageid=$row[id];
      }
      else
      {
         $sql="INSERT INTO messages (fromemail,sport, title, message, end_date, filename,linkname) VALUES ('$from','$sport_array[$i]','$title','$message_text','$end_date', '$newfile','$linkname')";
         $result=mysql_query($sql);
         $messageid=mysql_insert_id();
      }
   }
   $recipients=substr($recipients,0,strlen($recipients)-1);
   //e-mail message if recipient list is not empty
   if(trim($recipients)!="")
   {
      $recipients.=",$from";
      $temp2=split(",",$recipients);
      $recips=ereg_replace(",","<recipient>",$recipients);
     // citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
	 // sendsemails($session,$messageid,$recips);
	  exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
   }

   //display confirmation page:
   $header=GetHeader($session,"welcome");
   echo $init_html;
   echo $header;
   if(mysql_error())
   {
      echo "<br><br><div class=error>UNEXPECTED ERROR FOR THE QUERY:<br>$sql<br><br>".mysql_error()."<br><br><i>Please notify the programmer of this exact error.</div><br><br>";
      echo $end_html;
      exit();
   }
   echo "<br><br><table width=75%><tr align=left><td><b>You have posted the following message:<br><br></td></tr>";
   echo "<tr align=left><td><b>\"$title\"</b></td></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><td><b>...to the following officials:</b></td></tr>";
   echo "<tr align=left><td><table>";
   for($i=0;$i<count($sport_array);$i++)
   {
      echo "<tr align=left>";
      for($j=0;$j<count($activity);$j++)
      {
         if($sport_array[$i]==$activity[$j])
            echo "<td>$act_long[$j]</td>";
      }
      echo "</tr>";
   }
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
      echo "<tr align=left><td width=\"500px\">$recipients</td></tr>";
   }
   echO "</table><br><a href=\"post_message.php?session=$session\" class=small>Create Another Message</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"edit_message.php?session=$session\">Edit/Delete Message</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}//end if post

echo $init_html;
if($level==1)
   $header=GetHeader($session,"welcome");
else 
   $header=GetHeader($session);
echo $header;

echo "<br><form method=post action=\"post_message.php\" enctype=\"multipart/form-data\" name=emailform>";
echo "<input type=hidden name=session value=$session>";
echo "<table class=nine><caption><b>Create a New Message:</b><hr></caption>";
echo "<tr align=left><td><b>Reply-To E-mail:</b></td><td><input type=text name=\"from\" size=30 value=\"nsaa@nsaahome.org\"></td></tr>";
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
echo "Officials:";
echO "</b></td><td>";
echo "<select name=sport_array[] MULTIPLE size=5>";
echo "<option>All Officials</option>";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value='$activity[$i]'>$act_long[$i]</option>";
}
echo "</select>";
echo "<br><font size=1>Hold down CTRL(PC) or OPT(Mac) to make multiple selections</font>";
echo "</td></tr>";
echo "<tr align=left><td colspan=2>";
echo "<input type=checkbox name=alsoemail value='y'>&nbsp;<b>Check here if you would also like to E-MAIL the recipients you have selected above.</b></td></tr>";

//ADDRESS BOOK
/*
echo "<tr align=left><th align=left colspan=2><br>Select additional recipient(s) to receive this message via e-mail by using our";
echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
echo "</th></tr>";
echo "<tr align=left><td width=600 colspan=2>(Recipient(s) selected using the Address Book will appear in the box below. You may also type e-mail addresses into this box. Make sure to separate multiple e-mail addresses with a comma.)<br></td></tr>";
echo "<tr align=center><td colspan=2><b>Additional Recipient(s) via E-mail:<br>";
echo "<textarea name=email cols=50 rows=5>$recipients</textarea></td></tr>";
*/
//MESSAGE
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
