<?php
//post_message2.php:  Level 1 can post messages to Level 3 

require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
else if($submit)	//store message in db
{
   $end_date="$year-$month-$day";
   $title=addslashes($title);
      $email_text=$message_text;
      $email_html=ereg_replace("\r\n","<br>",$email_text);
   $message_text=addslashes($message_text);
   $message_text=ereg_replace("\r\n","<br>",$message_text);

   //trim whitespace from edges and get rid of multiple spaces:
   $title=trim($title);
   $title=preg_replace("/( +)/", " ", $title);
   $message_text=trim($message_text);
   $message_text=preg_replace("/( +)/", " ", $message_text);

   //Get variables ready for possible e-mails
   $attm=array();
   $recipients="";

   //insert new message in db table messages
   $poster="NSAA";
   $post_date=date("Y-m-d");

   //if All Activities was chosen, make array with all activities in it:
   if($sport_array[0]=="All Activities")
   {
      for($i=0;$i<count($act_long);$i++)
      {
         $sport_array[$i]=$act_long[$i];
      }
   }
   if(trim($from)=="") $from="nsaa@nsaahome.org";
   $fromname="NSAA";
   $fileuploaded=0;
   if(is_uploaded_file($_FILES['filename']['tmp_name']))
   {
	$fileuploaded=1;
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
   }
   else $linkname="";
   for($i=0;$i<count($sport_array);$i++)
   {
      $sql="SELECT * FROM messages WHERE title='$title' AND sport='$sport_array[$i]' AND school='All'";
      $result=mysql_query($sql);
      //if checked, add recipient to e-mail list
      if($alsoemail=='y')
      {
         $sql2="SELECT email,name FROM logins WHERE sport LIKE '$sport_array[$i]%' AND school!='Test\'s School'";
         $result2=mysql_query($sql2);
         while($row2=mysql_fetch_array($result2))
            $recipients.=$row2[0].",";
      }
      if(mysql_num_rows($result)>0)	//UPDATE (replace)
      {
	 $messageid=$row[id];
         $sql="UPDATE messages SET fromemail='$from', title='$title', message='$message_text', end_date='$end_date', filename='$newfile',linkname='$linkname',poster='$poster',post_date='$post_date' WHERE sport='$sport_array[$i]' AND school='All' AND title='$title'";
         $result=mysql_query($sql);
      }
      else				//INSERT (make new message)
      {
         $sql="INSERT INTO messages (fromemail,school, sport, title, message, end_date, filename,linkname,poster,post_date) VALUES ('$from','All','$sport_array[$i]','$title','$message_text','$end_date', '$newfile','$linkname','$poster','$post_date')";
         $result=mysql_query($sql);
         $messageid=mysql_insert_id();
      }
      //echo "$sql<br>".mysql_error();
   }

   //add any additional recipients to e-mail list
   $recipients.=$email;
   //e-mail message if recipient list is not empty
   if(trim($recipients)!="")
   {
         $recips=ereg_replace(",","<recipient>",$recipients);
         //citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
		// sendsemails($session,$messageid,$recips);
		 exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
   }

   //display confirmation page:
   $header=GetHeader($session);
   echo $init_html;
   echo $header;
   echo "<br><br><table width=75%><tr align=left><th align=left>You have posted the following message:<br><br></th></tr>";
   echo "<tr align=left><th align=left><i>$title:</i></th></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><th align=left>";
   echo "...to the coach(es) of the following sport(s):";
   echo "</th></tr>";
   for($i=0;$i<count($sport_array);$i++)
   {
      echo "<tr align=left><td>$sport_array[$i]</td></tr>";
   }
   echo "<tr align=left><th align=left><br>...to show until: $month/$day/$year.</th></tr>";
   if($fileuploaded)
   {
      echo "<tr align=left><th align=left><br>";
      echo "You have also uploaded the following file:&nbsp;&nbsp;";
      echo "<a href=\"messagefiles/$newfile\" target=new>$linkname</a>";
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
   echo "</table><br>";
   echo "<a href=\"post_message2.php?session=$session\">Create Another Message</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"edit_message2.php?session=$session\">Edit/Delete Messages</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"welcome.php?session=$session\">Home</a></td></tr></table>";
   echo $end_html;
   exit();
}

//If you get here, need to show blank message form
echo $init_html;
echo GetHeader($session);
echo "<br>";
echo "<form method=post action=\"post_message2.php\" enctype=\"multipart/form-data\" name=emailform>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>Post a New Message to COACHES:</b><br>";
echo "<a class=small href=\"post_message.php?session=$session\">Post a Message to AD's</a></caption>";
echo "<tr><td colspan=2><hr></td></tr>";
$from="nsaa@nsaahome.org";
echo "<tr align=left><th align=left class=smaller>Reply-to E-mail:</th><td><input type=text size=30 name=\"from\" value=\"$from\">";
echo "<tr align=left><th align=left class=smaller>Enter a Title:</th>";
echo "<td><input type=text name=title size=43></td></tr>";
echo "<tr align=left><th align=left class=smaller>Display Message Until:</th>";
echo "<td><select name=month>";
$year=date(Y); $month=date("m"); $day=date("d");
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($m==$month) echo " selected";
   echO ">$m</option>";
}
echo "</select>";
echo "/<select name=day>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($m==$day) echo " selected";
   echO ">$m</option>";
}
echo "</select>/";
echo "<input type=text name=year size=4 class=tiny value=\"$year\">";
echo "</td></tr>";
echo "<tr align=left valign=top><th align=left class=smaller><br>";
echo "Recipient(s): Select Coach(es) of Activiti(es):";
echo "</th><td><br>";
echo "<select name=sport_array[] MULTIPLE size=4>";
echo "<option>All Activities</option>";
for($i=0;$i<count($act_long);$i++)
{
   echo "<option>$act_long[$i]</option>";
   if($act_long[$i]=="Vocal Music")
   {
      echo "<option";
      echo ">Orchestra</option>";
   }
}
echo "</select>";
echo "<br><font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>";
echo "</td></tr>";
echo "<tr align=left><th align=left colspan=2 class=smaller>";
echo "<input type=checkbox name=alsoemail value='y'>&nbsp;Check here if you would also like to e-mail the recipient(s) you have selected <u>above</u>.</th></tr>";
echo "<tr align=left><th align=left colspan=2 class=smaller>Select additional recipient(s) to receive this message via e-mail by using our";
echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
echo "</th></tr>";
echo "<tr align=left><td width=600 colspan=2>(Recipient(s) selected using the Address Book will appear in the box below. You may also type e-mail addresses into this box. Make sure to separate multiple e-mail addresses with a comma.)<br></td></tr>";
echo "<tr align=center><td colspan=2><b>Additional Recipient(s) via E-mail:<br>";
echo "<textarea name=email cols=50 rows=5>$recipients</textarea></td></tr>";
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
echo "<textarea cols=100 rows=10 name=message_text></textarea>";
echo "</td></tr>";
if($level==1)
{
   echo "<tr align=left><th align=left class=smaller>Upload a File:</th>";
   echo "<td><input type=file name=filename></td></tr>";
   echo "<tr align=left>";
   echo "<th align=left class=smaller>Give your File a Title (leave blank to use filename as title):</th>";
   echo "<td><input type=text name=linkname size=20</td></tr>";
}
echo "<tr align=center><td colspan=2><br><br>";
echo "<input type=submit name=submit value=\"Post\">";
echo "<input type=submit name=submit value=\"Cancel\">";
echo "</td></tr></table></form>";

echo $end_html;
?>
