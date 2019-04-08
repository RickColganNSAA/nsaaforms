<?php
//edit_message2.php:   Allow Level 1 user to choose and edit/delete messages posted to coaches

require 'functions.php';
require 'variables.php';
$level=GetLevel($session);
//validate user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//erase old messages
$today=time();
$sql="SELECT id, end_date FROM messages";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $id=$row[0];
   $end=split("-",$row[1]);
   $monthnum=$end[1];
   $daynum=$end[2];
   $yearnum=$end[0];
   $end=mktime(23,59,59,$monthnum,$daynum,$yearnum);
   if($end<$today)
   {
      $sql2="DELETE FROM messages WHERE id='$id'";
      $result2=mysql_query($sql2);
   }
}

$header=GetHeader($session);

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
else if($submit=="Delete")
{
   echo $init_html;
   echo $header;
?>
   <br>
   <form method=post action="edit_message2.php">
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=title value="<?php echo $title; ?>">
   <font size=2>Are you sure you want to delete <b>"<?php echo $title; ?>"</b>?<br></font><br><br>
   <input type=submit name=submit value="Yes">&nbsp;&nbsp;
   <input type=submit name=submit value="No">
   </form>
<?php
   exit();
}
else if($submit=="Yes")	//confirmation on deleting message
{
   $sql="DELETE FROM messages WHERE title='$title' AND poster='NSAA' AND school='All'";
   $result=mysql_query($sql);
   header("Location:edit_message2.php?session=$session");
}
else if($submit=="No")	//return to edit_message2.php
{
   header("Location:edit_message2.php?session=$session");
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

   //line breaks
   $message_text=ereg_replace("\r\n","<br>",$message_text);

   //get vars ready for possible e-mail
   $attm=array();
   $recipients="";

   $old_title=trim($old_title);
   $fileuploaded=0;
   if(is_uploaded_file($_FILES['filename']['tmp_name']) && $deletefile!='x')
   {
      $newfile=ereg_replace(" ","",$_FILES["filename"]["name"]);
      $newfile=ereg_replace("\'","",$newfile);
      $newfile=ereg_replace("\"","",$newfile);
      $i=2;
      while(citgf_file_exists("messagefiles/$newfile"))
      {
         $newfile=$i.$newfile;
         $i++;
      }
      if(!citgf_copy($_FILES['filename']['tmp_name'],"messagefiles/$newfile"))
      {
	 echo "ERROR: $newfile could not be uploaded. Please report this problem to the programmer.";
         exit();
      }
      $attm[0]="messagefiles/$newfile";
      if(trim($linkname)=="") $linkname=addslashes($newfile);
      else $linkname=addslashes($linkname);
	$fileuploaded=1;
   }
   else if($deletefile=='x')
   {
      $filename=""; $linkname=""; $newfile="";
   }

   //if All Activities was chosen, make array with all activities in it:
   if($sport_array[0]=="All Activities")
   {
      for($i=0;$i<count($act_long);$i++)
      {
         $sport_array[$i]=$act_long[$i];
      }
   }
   for($i=0;$i<count($sport_array);$i++)
   {
      $sql="SELECT * FROM messages WHERE title='$old_title' AND sport='$sport_array[$i]' AND school='All' AND poster='NSAA'";
      $result=mysql_query($sql);

      //if checked, add recipient to email list
      $sql2="SELECT email, name FROM logins WHERE sport LIKE '$sport_array[$i]%'";
      $result2=mysql_query($sql2);
      if($alsoemail=='y') 
      {
         while($row2=mysql_fetch_array($result2))
	    $recipients.=$row2[0].",";
      }

      if(trim($from)=="") $from="nsaa@nsaahome.org";
      $fromname="NSAA";

      $row=mysql_fetch_array($result);
      $oldfilename=$row[6];
      if(!$fileuploaded && $oldfilename!='' && trim($linkname)=="" && $deletefile!='x') 
         $linkname=addslashes($oldfilename);
      else if(!$fileuploaded && $oldfilename!='' && $deletefile!='x') 
      $linkname=addslashes($linkname);
      if(mysql_num_rows($result)>0)	//UPDATE (replace)
      {
            $messageid=$row[id];
         if($fileuploaded || $deletefile=='x')
         {
            $sql="UPDATE messages SET fromemail='$from', title='$title', message='$message_text', end_date='$end_date', filename='$newfile', linkname='$linkname' WHERE sport='$sport_array[$i]' AND school='All' AND title='$old_title' AND poster='NSAA'";
	 }
	 else
	 {
            $sql="UPDATE messages SET fromemail='$from', title='$title', message='$message_text', end_date='$end_date',linkname='$linkname' WHERE sport='$sport_array[$i]' AND school='All' AND title='$old_title' AND poster='NSAA'";
	 }
         $result=mysql_query($sql);
      }
      else				//INSERT (make new message)
      {
	 $poster="NSAA";
	 if($fileuploaded || $deletefile=='x')
	 {
	    $sql="INSERT INTO messages (fromemail, sport,school, title, message, end_date, filename, linkname,poster) VALUES ('$from','$sport_array[$i]','All','$title','$message_text','$end_date','$newfile','$linkname','$poster')";
	 }
	 else
	 {
            $sql="INSERT INTO messages (fromemail, sport, school, title, message, end_date,poster) VALUES ('$from','$sport_array[$i]','All','$title','$message_text','$end_date','$poster')";
	 }
         $result=mysql_query($sql);
	 $messageid=mysql_insert_id();
      }
   }
   if($deletefile=='x' && citgf_file_exists("messagefiles/$oldfilename"))
   {
      citgf_unlink("messagefiles/$oldfilename");
   }

   //add any additional recipients to e-mail list
   $recipients.=$email;
   //e-mail message if recipient list is not empty
   if(trim($recipients)!="")
   {
         $recips=ereg_replace(",","<recipient>",$recipients);
         //echo "MESSAGE ID: $messageid";
         //citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
		 //sendsemails($session,$messageid,$recips);
		 exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
   }
   //display confirmation page:
   echo $init_html;
   echo $header;
   echo "<table width=75%>";
   echo "<tr align=left><th align=left>You have posted the following message:<br><br></th></tr>";
   echo "<tr align=left><th align=left><i>$title:</i></th></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><th align=left>";
   echo "...to the coach(es) of the following sport(s):";
   echo "</th></tr>";
   echo "<tr align=left><td><table cellspacing=1 cellpadding=2>";
   for($i=0;$i<count($sport_array);$i++)
   {
      if($i%3==0) echo "<tr align=left>";
      echo "<td>$sport_array[$i]</td>";
      if(($i+1)%3==0) echo "</tr>";
   }
   echo "</table></td></tr>";
   $date=split("-",$end_date);
   $enddate="$date[1]/$date[2]/$date[0]";
   echo "<tr align=left><th align=left><br>...to show until: $enddate.</th></tr>";
   if((!$fileuploaded && $oldfilename=="") || $deletefile=='x')
   {}
   else
   {
      echo "<tr align=left><th align=left>You have also uploaded the following file:</th></tr>";
      echo "<tr align=left><th align=left>";
      if($fileuploaded) echo "<a href=\"messagefiles/$newfile\" target=new>$linkname</a>";
      else echo "<a href=\"messagefiles/$oldfilename\" target=new>$linkname</a>";
      echo "</th></tr>";
   }
   if(trim($recipients)!="")
   {
      echo "<tr align=left><th align=left><br>";
      echo "You have also e-mailed this message to the following recipients:</th></tr>";
      echo "<tr align=left><td>";
      $temp3=split(",",$recipients);
      for($i=0;$i<count($temp3);$i++)
      {
         echo $temp3[$i]."<br>";
      }
      echo "</td></tr>";
   }
   echo "</table>";
   echo "<br><a href=\"edit_message2.php?session=$session\">Edit/Delete Messages to Coaches</a>&nbsp;&nbsp;";
   echo "<a href=\"post_message2.php?session=$session\">Post New Message to Coaches</a>&nbsp;&nbsp;";
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo "</td></tr></table>";
   echo $end_html;
   exit();
}//end if(submit==Save) statement

//If you get here, need to prompt user to choose message and Edit or Delete
echo $init_html;
echo $header."<br>";
if($level==1)
{
   echo "<a class=small href=\"post_message2.php?session=$session\">Post New Message to Coaches</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"post_message.php?session=$session\">Post New Message to Schools</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"edit_message.php?session=$session\">Edit Messages to Schools</a><br>";
}
echo "<br><form method=post action=\"edit_message2.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=80%>";
echo "<tr><th colspan=2><u>Edit Messages Posted to ";
echo "Coaches";
echo ":</u><br><br>";
echo "Choose a message and then choose \"Edit\" or \"Delete\":<br></td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<select name=title>";
$sql="SELECT DISTINCT title FROM messages WHERE poster='NSAA' AND school='All' ";
$sql.="ORDER BY title";
$result=mysql_query($sql);
$messages=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $messages[$ix]=$row[0];
   echo "<option";
   if($title==$messages[$ix]) echo " selected";
   echo ">$messages[$ix]</option>";
    $ix++;
}
echo "</select></td></tr>";
echo "<tr align=center>";
echo "<td colspan=2><input type=submit name=submit value=\"Edit\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Delete\"></td></tr></table></form>";
if($submit=="Edit")	//Display that message, editable
{
   //get information for the chosen message
   $sql="SELECT * FROM messages WHERE title='$title' AND poster='NSAA' AND school='All' ORDER BY sport";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $sport_array[$ix]=$row[3];
      $ix++;
      $end_date=$row[4];
      $message_text=$row[5];
      $filename=$row[6];
      $linkname=$row[7];
      $from=$row[from];
   }
   if(trim($from)=="") $from="nsaa@nsaahome.org";
   //get end_date into three parts:
   $end_date=split("-",$end_date);
   $month=$end_date[1];
   $day=$end_date[2];
   $year=$end_date[0];

   echo "<form method=post action=\"edit_message2.php\" enctype=\"multipart/form-data\" name=\"emailform\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=old_title value=\"$title\">";
   echo "<table width=80%>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   echo "<tr align=left><th align=left>Reply-to E-mail:</th><td><input type=text size=30 name=\"from\" value=\"$from\"></td></tr>";
   echo "<tr align=left><th align=left>Title:</th>";
   echo "<td><input type=text name=title size=50 value=\"$title\">";
   echo "</td></tr>";
   echo "<tr align=left><th align=left>Display Message Until:</th>";
   echo "<td><select name=month>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($month==$m) echo " selected";
      echo ">$m</option>";
   } 
   echo "</select>/<select name=day>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option";
      if($day==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/";
   echo "<input type=text name=year size=4 class=tiny value=\"$year\"></td></tr>";
   echo "<tr align=left valign=top><th align=left><br>";
   echo "Activitie(s):";
   echo "<br><font size=1>(hold down CTRL(PC) or Apple(Mac) to make<br>multiple selections)</font></th>";
   echo "<td><br>";
   echo "<select name=sport_array[] MULTIPLE size=4>";
   echo "<option>All Activities</option>";
   $ix=0;
   for($i=0;$i<count($act_long);$i++)
   {
      echo "<option";
      $select=0;
      for($j=0;$j<count($sport_array);$j++)
      {
         if($sport_array[$j]==$act_long[$i])
         {
            $select=1; $j=count($sport_array);
         }
      }
      if($select==1) echo " selected";
      echo ">$act_long[$i]</option>";
      if($act_long[$i]=="Vocal Music")
      {
         echo "<option";
	 $select=0;
	 for($j=0;$j<count($sport_array);$j++)
	 {
	    if($sport_array[$j]=="Orchestra")
	    {
	       $select=1; $j=count($sport_array);
	    }
	 }
	 if($select==1) echo " selected";
	 echo ">Orchestra</option>";
      }   
   }
   echo "</select>";
   echo "</td></tr>";
   echo "<tr align=left><th align=left colspan=2 class=smaller>";
   echo "<input type=checkbox name=alsoemail value='y'>&nbsp;Check here if you would also like to e-mail the recipients you have selected above.</th></tr>";
   echo "<tr align=left><th align=left colspan=2 class=smaller>Select additional recipients to received this message via e-mail by using our ";
   echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
   echo "</th></tr>";
   echo "<tr align=left><td width=600 colspan=2>(Recipient(s) selected using the Address Book will appear in the box below.  You may also type e-mail address into this box.  Make sure to separate multiple e-mail addresses with a comma.)</td></tr>";
   echo "<tr align=center><td colspan=2><b>Additional Recipient(s) via E-mail:</b><br>";
   echo "<textarea name=\"email\" cols=50 rows=5>$recipients</textarea></td></tr>";
   $message_text=ereg_replace("<br>","\r\n",$message_text);
   echo "<tr align=left><th align=left colspan=2>Message Body:</th></tr>";
   echo "<tr align=left><td colspan=2><b>HTML Help:</b><br><table border=1 cellspacing=0 cellpadding=3>";
   echo "<tr align=center><td><b>To...</b></td><td><b>Type this:</b></td><td><b>To see this:</b></td></tr>";
   echo "<tr align=left><td>Add a LINK:</td><td>&lt;a target=&quot;_blank&quot; href=&quot;http://www.google.com&quot;&gt;Google&lt;/a&gt;</td><td><a target=\"_blank\" href=\"http://www.google.com\">Google</a></td></tr>";
   echo "<tr align=left><td>Link to an E-MAIL:</td><td>&lt;a href=&quot;mailto:nsaa@nsaahome.org&quot;&gt;nsaa@nsaahome.org&lt;/a&gt;</td><td><a href=\"mailto:nsaa@nsaahome.org\">nsaa@nsaahome.org</a></td></tr>";
   echo "<tr align=left><td>BOLD words:</td><td>&lt;b&gt;August 1&lt;/b&gt;</td><td><b>August 1</b></td></tr>";
   echo "<tr align=left><td>ITALICIZE words:</td><td>&lt;i&gt;August 1&lt;/i&gt;</td><td><i>August 1</i></td></tr>";
   echo "<tr align=left><td>UNDERLINE words:</td><td>&lt;u&gt;August 1&lt;/u&gt;</td><td><u>August 1</u></td></tr></table>";
   echo "</td></tr>";
   echo "<tr align=center><td colspan=2>";
   echo "<textarea cols=100 rows=10 name=message_text>$message_text</textarea></td></tr>";
   if($level==1)
   {
      echo "<tr align=left><th align=left colspan=2>File Attachment:</th></tr>";
      if($fileuploaded)
      {
         echo "<tr align=left><td align=left><b>You have uploaded the following file to accompany this message:</b></td></tr>";
         echo "<tr align=left><td colspan=2><a target=\"_blank\" href=\"messagefiles/$newfile\">$newfile ($linkname)</a></td></tr>";
         echo "<tr align=left><td width=600 align=left colspan=2><b>To upload a DIFFERENT file in place of the file shown above, indicate the location and edit the title of your file (optional) below.<br>To KEEP the file you have already uploaded (shown above), you do not need to do anything except edit your file's title if you wish:</b></td></tr>";
         $fileuploaded=1;
      }
      else
      {
         $fileuploaded=0;
         echo "<tr align=left><td colspan=2><b>You have not uploaded a file to accompany this message.  If you wish to attach a file, please indicate its location and give your file a title (optional) below:</b></td></tr>";
      }
      echo "<tr align=left><td><b>Location of File:</b></td><td><input type=file name=filename></td></tr>";
      echo "<tr align=left><td><b>Your File's Title (leave blank to use filename as title):</b></td><td><input type=text name=linkname value=\"$linkname\"></td></tr>";
      if($fileuploaded==1)
      {
         echo "<tr align=left><td colspan=2><input type=checkbox name=\"deletefile\" value='x'>&nbsp;<b>Check here to REMOVE the file you've uploaded (<a target=\"_blank\" href=\"messagefiles/$filename\" class=small>$filename</a>) from this message.  This means NO FILE will be attached to this message.</b></td></tr>";
      }
   }  
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Save\">";
   echo "<input type=submit name=submit value=\"Cancel\"></td></tr></table></form>";
}//end if submit==Edit

echo $end_html;
?>
