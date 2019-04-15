<?php
//edit_message.php:   Allow user to choose and edit/delete messages
//	they have posted

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

//get level of user
$level=GetLevel($session);

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

$header=GetHeader($session,"welcome");

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
else if($submit=="Delete")
{
   echo $init_html;
   echo $header;
   echo "<br><form method=post action=\"edit_message.php\">";
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
   header("Location:edit_message.php?session=$session&delete=1");
}
else if($submit=="No")	//return to edit_message.php
{
   header("Location:edit_message.php?session=$session");
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
   $message_text=ereg_replace("\r\n","<br>",$message_text);

   //replace multiple spaces with singles space:
   $title=preg_replace("/( +)/", " ", $title);
   $message_text=preg_replace("/( +)/", " ", $message_text);

   //get vars ready for possible e-mail
   $attm=array();
   $recipients="";

   $old_title=addslashes(trim($old_title));

   if(is_uploaded_file($_FILES['filename']['tmp_name']))
   {
      $newfile=$_FILES["filename"]["name"];
      $newfile=strtolower($newfile);
      $newfile=ereg_replace(" ","",$newfile);
      $newfile=ereg_replace("\'","",$newfile);
      $newfile=ereg_replace("\"","",$newfile);
      if(!citgf_copy($_FILES['filename']['tmp_name'], "messagefiles/$newfile")) echo "No Copy";
      $attm[0]="messagefiles/$newfile";
      if(trim($linkname)=="") $linkname=$newfile;
      else $linkname=addslashes($linkname);
      $filename=$newfile;
   }
   else $filename="";

   //insert new message in db table messages
   //if All Officials was chosen, make array with all sports in it:
   $empty=array();
   if($sport_array[0]=="All Officials")
   {
      $sport_array=array_merge($empty,$activity);
   }

   if(trim($from)=="") $from="nsaa@nsaahome.org";
   $fromname="NSAA";

   //if message with this title already exists, replace it:
   for($i=0;$i<count($sport_array);$i++)
   {
      $temp=ereg_replace("\'","\'",$sport_array[$i]);
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
         while($row=mysql_fetch_array($result))
         {
            $recipients.=$row[email].", ";  
         }
      }
      $sql="SELECT * FROM messages WHERE title='$old_title' AND sport='$temp'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $oldfilename=$row[filename];
      $sport_array[$i]=ereg_replace("\'","\'",$sport_array[$i]);
      if(mysql_num_rows($result)>0)	//UPDATE (replace)
      {
         $messageid=$row[id];
         if($filename!='')
	 {
	    $sql="UPDATE messages SET fromemail='$from',sport='$temp', title='$title', message='$message_text', end_date='$end_date', filename='$filename', linkname='$linkname' WHERE sport='$temp' AND title='$old_title'";
	 }
	 else
	 {
            $sql="UPDATE messages SET fromemail='$from',sport='$temp', title='$title', message='$message_text', end_date='$end_date' WHERE sport='$temp' AND title='$old_title'";
	 }
         $result=mysql_query($sql);
      }  
      else				//INSERT (make new message)
      {
         if($filename!='')
         {
            $sql="INSERT INTO messages (fromemail,sport, title, message, end_date, filename, linkname) VALUES ('$from','$temp','$title','$message_text','$end_date','$filename','$linkname')";
         }
	 else
	 {
            $sql="INSERT INTO messages (fromemail,sport, title, message, end_date) VALUES ('$from','$temp','$title','$message_text','$end_date')";
	 }
         $result=mysql_query($sql);
	 $messageid=mysql_insert_id();
      }
   }
   $recipients=substr($recipients,0,strlen($recipients)-2);
   //e-mail message if recipient list is not empty
   if(trim($recipients)!="")
   {
      $recipients.=",$from";
	//$recipients="agaffigan@gazelleincorporated.com,$from";	//TESTING
      $recips=ereg_replace(",","<recipient>",$recipients);
      //echo "MESSAGE ID: $messageid";
     // citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
	  //sendsemails($session,$messageid,$recips);
	   exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
      /*
      $temp2=split(",",$recipients);
      for($i=0;$i<count($temp2);$i++)
      {
         if(trim($temp2[$i]!=""))
         {
	    $temp2[$i]=trim($temp2[$i]);
            SendMail($from,$fromname,$temp2[$i],$temp2[$i],$title,$email_text,$email_html,$attm);
         }
      }
      */
   }

   //display confirmation page:
   echo $init_html;
   $header=GetHeader($session,"welcome");
   echo $header;
   echo "<br><br><table width=75%>";
   echo "<tr align=left><td><b>You have posted the following message:<br><br></b></td></tr>";
   echo "<tr align=left><td><b>\"$title:\"</b></td></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><td><b>...to the following officials:</b></td></tr>";
   echo "<tr align=left><td>";
   echo "<table frame=vsides bordercolor=#000000 cellspacing=0 cellpadding=5>";
   for($i=0;$i<count($sport_array);$i++)
   {
      for($j=0;$j<count($activity);$j++)
      {
         if($activity[$j]==$sport_array[$i]) 
            echo "<tr align=left><td>$act_long[$j]</td></tr>";
      }
   }
   echo "</table></td></tr>";
   echo "<tr align=left><td><b><br>...to show until $end_date.</b></td></tr>";
   if(!$filename && $oldfilename=="")
   {}
   else
   {
      echo "<tr align=left><td><b>You have also uploaded the following file:</b></td></tr>";
      echo "<tr align=left><td><b>";
      if($filename!='') echo "<a href=\"messagefiles/$filename\" target=new>$linkname</a>";
      else echo "<a href=\"messagefiles/$oldfilename\" target=new>$linkname</a>";
      echo "</b></td></tr>";
   }
   if(trim($recipients)!="")
   {
      echo "<tr align=left><td><b><br>";
      echo "You have also e-mailed this message to the following recipients:</b></td></tr>";
      echo "<tr align=left><td width=\"500px\">$recipients</td></tr>";
   }
   echo "</table><br>";
   echo "<a href=\"edit_message.php?session=$session\">Edit/Delete Messages</a>&nbsp;&nbsp;";
   echo "<a href=\"post_message.php?session=$session\">Post New Message</a>&nbsp;&nbsp;";
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}//end if(submit==Save) statement

//If you get here, need to prompt user to choose message and Edit or Delete
echo $init_html;
echo GetHeader($session,"welcome");
echo "<br><form method=post action=\"edit_message.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><tr align=center><td><b>Choose a message and then choose \"Edit\" or \"Delete\":<br><br></b></td></tr>";
echo "<tr align=center><td colspan=2><select name=title>";
$sql="SELECT DISTINCT title,end_date FROM messages WHERE sport!='sp' AND sport!='pp' ORDER BY id DESC";
$result=mysql_query($sql);
$messages=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $messages[$ix]=$row[0];
   echo "<option value=\"$messages[$ix]\">$messages[$ix] (show until $row[1])</option>";
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
      $from=$row[from];
   }
   if(trim($from)=="") $from="nsaa@nsaahome.org";
   
   //get end_date into three parts:
   $end_date=split("-",$end_date);
   $month=$end_date[1];
   $day=$end_date[2];
   $year=$end_date[0];

   echo "<form method=post action=\"edit_message.php\" enctype=\"multipart/form-data\" name=emailform>";
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
   echo "<tr align=left valign=top><td><b>Officials:</b><br>";
   echo "(hold down CTRL(PC) or OPT(Mac) to make<br>multiple selections)</td>";
   echo "<td><br><select name=sport_array[] MULTIPLE size=5>";
   echo "<option>All Officials</option>";
   for($i=0;$i<count($activity);$i++)
   {
      echo "<option value='$activity[$i]'";
      for($j=0;$j<count($sport_array);$j++)
      {
	 if($sport_array[$j]==$activity[$i])
	 {
	    echo " selected";
	    $j=count($sport_array);
	 }
      }
      echo ">$act_long[$i]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td colspan=2><b>";
   echo "<input type=checkbox name=alsoemail value='y'>&nbsp;Check here if you would also like to e-mail the recipients you have selected above.</b></td></tr>";
   echo "<tr align=left><td colspan=2><b>Message:</b></th></tr>";
   echo "<tr align=center><td colspan=2><textarea cols=90 rows=10 name=\"message_text\">".preg_replace("/<br>/","\r\n",$message_text)."</textarea></td></tr>";
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
