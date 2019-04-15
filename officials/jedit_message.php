<?php
require 'functions.php';
require 'variables.php';

//validate user
$level=GetLevelJ($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:jindex.php?error=1");
   exit();
}

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//erase old messages
$today=time();
$sql="SELECT id, end_date FROM messages";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $id=$row[0];
   $end=split("-",$row[1]);
   $monthnum=$end[1];
   $curday=$end[2];
   $year=$end[0];
   $end=mktime(0,0,0,$monthnum,$curday,$year);
   if($end<$today)
   {
      $sql2="DELETE FROM messages WHERE id='$id'";
      $result2=mysql_query($sql2);
   }
}

$header=GetHeaderJ($session,"jwelcome");

if($submit=="Cancel")
{
   header("Location:jwelcome.php?session=$session");
   exit();
}
else if($submit=="Delete")
{
   echo $init_html;
   echo $header;
   echo "<br><form method=post action=\"jedit_message.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=title value=\"$title\">";
   echo "<font size=2>Are you sure you want to delete <b>\"$title\"</b>?<br></font><br><br>";
   echo "<input type=submit name=submit value=\"Yes\">&nbsp;&nbsp;";
   echo "<input type=submit name=submit value=\"No\"></form>";
   exit();
}
else if($submit=="Yes")	//confirmation on deleting message
{
   $sql="DELETE FROM messages WHERE title='$title'";
   $result=mysql_query($sql);
   header("Location:jedit_message.php?session=$session&delete=1");
}
else if($submit=="No")	//return to jedit_message.php
{
   header("Location:jedit_message.php?session=$session");
}
else if($submit=="Save")	//update message in db
{
   //get end date in correct format
   $end_date="$year-$month-$day";

   //Check for " or ' in title and message_text:
   $title=ereg_replace("\'","",$title);
   $title=ereg_replace("\"","",$title);
   $title=ereg_replace("!","",$title);
   $title=trim($title);
      //keep in original format for e-mails:
      $email_text=$message_text;
      $email_html=ereg_replace("\r\n","<br>",$email_text);
   $message_text=ereg_replace("\'","",$message_text);
   $message_text=ereg_replace("\"","",$message_text);
   $message_text=ereg_replace("!",".",$message_text);
   $message_text=trim($message_text);

   //replace multiple spaces with singles space:
   $title=preg_replace("/( +)/", " ", $title);
   $message_text=preg_replace("/( +)/", " ", $message_text);

   //get vars ready for possible e-mail
   $attm=array();
   $recipients="";

   $old_title=addslashes(trim($old_title));

   if($filename)
   {
      $linkname=addslashes($linkname);
      $newfile=$_FILES["filename"]["name"];
      $newfile=strtolower($newfile);
      $newfile=ereg_replace(" ","",$newfile);
      $newfile=ereg_replace("\'","",$newfile);
      $newfile=ereg_replace("\"","",$newfile);
      if(!citgf_copy($filename, "messagefiles/$newfile")) echo "No Copy";
      $attm[0]="messagefiles/$newfile";
   }

   //insert new message in db table messages
   if(trim($from)=="") $from="ccallaway@nsaahome.org";
   $fromname="NSAA";

   //if message with this title already exists, replace it:
      //if box was checked, add recipients to email list
      if($alsoemail=='y')
      {
	 $sql="SELECT * FROM judges WHERE email!='' AND ";
	 if($judgesch=="All Judges") $sql.="(speech='x' OR play='x')";
	 else if($judgesch=="speech") $sql.="speech='x'";
	 else $sql.="play='x'";
	 if($emailwho=='reg') $sql.=" AND payment!=''";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            $recipients.=$row[email].", ";  
         }
         $recipients=substr($recipients,0,strlen($recipients)-2);
      }
      $sql="SELECT * FROM messages WHERE title='$old_title' AND ";
      if($judgesch=="All Judges") $sql.="(sport='sp' OR sport='pp')";
      else $sql.="sport='$judgescg'";   
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $oldfilename=$row[filename];
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)	//UPDATE (replace)
      {
         if($filename)
	 {
	    while($row=mysql_fetch_array($result))
	    {
	       $cursp=$row[sport];
	       $sql2="UPDATE messages SET fromemail='$from', title='$title', message='$message_text', end_date='$end_date', filename='$newfile', linkname='$linkname' WHERE sport='$cursp' AND title='$old_title'";
	       $result2=mysql_query($sql2);
	       $messageid=$row[id];
	    }
	 }
	 else
	 {
            while($row=mysql_fetch_array($result))
            {
               $cursp=$row[sport];
               $sql2="UPDATE messages SET fromemail='$from',title='$title', message='$message_text', end_date='$end_date',linkname='$linkname' WHERE sport='$cursp' AND title='$old_title'";
	       $result2=mysql_query($sql2);
	       $messageid=$row[id];
	    }
	 }
      }  
      else				//INSERT (make new message)
      {
         if($filename)
         {
	    if($judgesch=="All Judges") $cursp="sp";
	    else $cursp=$judgesch;
            $sql="INSERT INTO messages (fromemail, sport, title, message, end_date, filename, linkname) VALUES ('$from','$cursp','$title','$message_text','$end_date','$filename','$linkname')";
	    $result=mysql_query($sql);
	    if($judgesch=="All Judges")
	    {
	       $sql="INSERT INTO messages (fromemail,sport,title,message,end_date,filename,linkname) VALUES ('$from','pp','$title','$message_text','$end_date','$filename','$linkname')";
	       $result=mysql_query($sql);
	    }
	    $messageid=mysql_insert_id();
         }
	 else
	 {
            if($judgesch=="All Judges") $cursp="sp";
            else $cursp=$judgesch;
            $sql="INSERT INTO messages (fromemail,sport, title, message, end_date) VALUES ('$sport','$cursp','$title','$message_text','$end_date')";
            $result=mysql_query($sql);
            if($judgesch=="All Judges")
            {
               $sql="INSERT INTO messages (fromemail,sport,title,message,end_date,filename,linkname) VALUES ('$sport','pp','$title','$message_text','$end_date','$filename','$linkname')";
               $result=mysql_query($sql);
            }
	    $messageid=mysql_insert_id();
	 }
      }

   //e-mail message if recipient list is not empty
   if(trim($recipients)!="")
   {
      $recipients.=",$from";
      $recips=ereg_replace(",","<recipient>",$recipients);
      //citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
	  //sendsemails($session,$messageid,$recips);
	   exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
   }

   //display confirmation page:
   echo $init_html;
   echo $header;
   echo "<br><br><table width=75%>";
   echo "<tr align=left><td><b>You have posted the following message:<br><br></b></td></tr>";
   echo "<tr align=left><td><b>\"$title:\"</b></td></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><td><b>...to the following judges:</b></td></tr>";
   echo "<tr align=left><td>";
   if($judgesch=="All Judges") echo "Speech AND Play";
   else if($judgesch=="sp") echo "Speech ONLY";
   else echo "Play ONLY";
   echo "</td></tr>";
   $date=split("-",$end_date);
   echo "<tr align=left><td><b><br>...to show until $date[1]/$date[2]/$date[0].</b></td></tr>";
   if(!$filename && $oldfilename=="")
   {}
   else
   {
      echo "<tr align=left><td><b>You have also uploaded the following file:</b></td></tr>";
      echo "<tr align=left><td><b>";
      if($filename) echo "<a href=\"messagefiles/$newfile\" target=new>$linkname</a>";
      else echo "<a href=\"messagefiles/$oldfilename\" target=new>$linkname</a>";
      echo "</b></td></tr>";
   }
   if(trim($recipients)!="")
   {
      echo "<tr align=left><td><b><br>";
      echo "You have also e-mailed this message to the following recipients:</b></td></tr>";
      echo "<tr align=left><td><table width=600><tr align=left><td>$recipients</td></tr></table></td></tr>";
   }
   echo "</table><br>";
   echo "<a href=\"jedit_message.php?session=$session\">Edit/Delete Messages</a>&nbsp;&nbsp;";
   echo "<a href=\"jpost_message.php?session=$session\">Post New Message</a>&nbsp;&nbsp;";
   echo "<a href=\"jwelcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}//end if(submit==Save) statement

//If you get here, need to prompt user to choose message and Edit or Delete
echo $init_html;
echo GetHeaderJ($session,"jwelcome");
echo "<br><form method=post action=\"jedit_message.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><tr align=center><td><b>Choose a message and then choose \"Edit\" or \"Delete\":<br><br></b></td></tr>";
echo "<tr align=center><td colspan=2><select name=title>";
$sql="SELECT DISTINCT title,end_date FROM messages WHERE (sport='sp' OR sport='pp') ORDER BY id DESC";
$result=mysql_query($sql);
$messages=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $messages[$ix]=$row[0];
   $date=split("-",$row[1]);
   echo "<option value=\"$messages[$ix]\">$messages[$ix] (show until $date[1]/$date[2]/$date[0])</option>";
   $ix++;
} 
echo "</select></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Edit\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Delete\"></td></tr>";
echo "</table></form>";
if($submit=="Edit")	//Display that message, editable
{
   //get information for the chosen message
   $sql="SELECT * FROM messages WHERE title='$title'";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $sport_array[$ix]=$row[sport];
      $ix++;
      $end_date=$row[end_date];
      $message_text=$row[message];
      $filename=$row[filename];
      $linkname=$row[linkname];
      $from=$row[fromemail];
   }
   
   //get end_date into three parts:
   $end_date=split("-",$end_date);
   $month=$end_date[1];
   $day=$end_date[2];
   $year=$end_date[0];

   echo "<form method=post action=\"jedit_message.php\" enctype=\"multipart/form-data\" name=emailform>";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=old_title value=\"$title\">";
   echo "<table width=80%><tr align=center><td colspan=2><hr></td></tr>";
   echo "<tr align=left><th align=left>Reply-to E-mail:</th><td><input type=text size=30 name=\"from\" value=\"$from\"></td></tr>";
   echo "<tr align=left><th align=left>Title:</th>";
   echo "<td><input type=text name=title size=50 value=\"$title\"></td></tr>";
   echo "<tr align=left><th align=left>Display Message Until:</th><td><select name=month>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($month==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>&nbsp;/&nbsp;<select name=day>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option";
      if($day==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>&nbsp;/&nbsp;<select name=year>";
   $year0=$year-1; $year1=$year+1;
   for($i=$year0;$i<=$year1;$i++)
   {
      echo "<option";
      if($year==$i) echo " selected";
      echo ">$i</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left valign=top><td><b>Judges:</b></td>";
   echo "<td><br><select name=judgesch>";
   echo "<option";
   if(count($sport_array)>1) echo " selected";
   echo ">All Judges</option><option value='pp'";
   if($sport_array[0]=='pp' && count($sport_array)==1) echo " selected";
   echo ">Speech ONLY</option><option value='sp'";
   if($sport_array[0]=='sp' && count($sport_array)==1) echo " selected";
   echo ">Play ONLY</option>";
   echo "</select></td></tr>";
   echo "<tr align=left><td colspan=2><b>";
   echo "<input type=checkbox name=alsoemail value='y'>&nbsp;Check here if you would also like to e-mail the recipients you have selected above.</b></td></tr>";
   echo "<tr align=left><td>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<input type=radio name=emailwho value='all' checked>&nbsp;";
   echo "E-mail <b>ALL</b> judges in the database<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<input type=radio name=emailwho value='reg'>&nbsp;";
   echo "E-mail only the <b>REGISTERED</b> judges in the database</td></tr>";
   echo "<tr align=left><td colspan=2><b>Message:</b></th></tr>";
   echo "<tr align=center><td colspan=2><textarea cols=90 rows=10 name=\"message_text\">$message_text</textarea></td></tr>";
   if($filename!="" && $filename!=NULL)
   {
      echo "<tr align=left><td colspan=2><b>You have uploaded the following file to accompany this message:</b></td></tr>";
      echo "<tr align=left><td colspan=2>$filename ($linkname)</td></tr>";
      echo "<tr align=left><td colspan=2><b>To upload a different file, indicate the location, link name, and file type below:</b></td></tr>";
   }
   else
   {
      echo "<tr align=left><td colspan=2><b>You have not uploaded a file to accompany this message.  If you wish to attach a file, please indicate its location, link name, and file type below:</b></td></tr>";
   }
   echo "<tr align=left><td><b>Location:</b></td><td><input type=file name=filename></td></tr>";
   echo "<tr align=left><td><b>Link Name:</b></td><td><input type=text name=linkname value=\"$linkname\"></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Save\">&nbsp;";
   echo "<input type=submit name=submit value=\"Cancel\"></td></tr>";
   echO "</table></form>";
}//end if submit==Edit

echo $end_html;
exit();
?>
