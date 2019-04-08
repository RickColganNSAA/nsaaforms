<?php
//post_message.php:  Level 1 can post messages to Level 2, and Level2 
//	can post ones to Level 3

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

//get level & school of user
$level=GetLevel($session);
$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);

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
      if(trim($email_text)=="") $email_text=$title;
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
   if($level==1 || $level==5)	//NSAA post to AD(s) OR Large School Post to schools
   {
      if(trim($replyto)=="") $replyto="nsaa@nsaahome.org";
      if($level==1) 
	 $poster="NSAA";
      else
	 $poster=$school2;
      $post_date=date("Y-m-d");
      if($sportreg && $sportreg!='')
      {
	 $school_array=array();
      }
      //if All Schools was chosen, make array with all schools in it:
      if($school_array[0]=="ALL Schools")
      {
	 $sportreg="";
	 $school_array[0]='All';
      }
      else if(count($school_array)>0)
      {
         $sportreg=""; 
      }

      $fileuploaded=0; 
      if(is_uploaded_file($_FILES['filename']['tmp_name']))
      {
         $newfile=preg_replace("/[^0-9a-zA-Z.]/","",$_FILES["filename"]["name"]);
         $i=2;
         while(citgf_file_exists("messagefiles/$newfile"))
         {
            $newfile=$i.$newfile;
            $i++;
         }
         if(citgf_copy($_FILES['filename']['tmp_name'],"messagefiles/$newfile"))
	 {
            if(trim($linkname)=="") $linkname=addslashes($newfile);
            else $linkname=addslashes($linkname);
            $email_text.="\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms/index.php to view the attachment to this message.";
            $email_html.="<br><br>Please login at <a href='https://secure.nsaahome.org/nsaaforms'>https://secure.nsaahome.org/nsaaforms/index.php</a> to view the attachment to this message.";
	    $fileuploaded=1;
         }
	 else
	 {
	    echo "ERROR: the file could not be uploaded. Please report this problem to the programmer.";
   	    exit();
	 }
      }
      else $linkname="";
      
      if($sportreg!='')	//REGISTERED FOR A SPECIFIC ACTIVITY
      {
	 $sportreg2=GetActivityAbbrev2($sportreg);
	 $schtable=GetSchoolsTable($sportreg2);
	 $sportreg3=preg_replace("/(school)/","",$schtable);
         if($alsoemail=='y')
         {
	    $sql="SELECT t1.id,t2.email,t2.name FROM headers AS t1,logins AS t2 WHERE t1.school=t2.school AND t2.level=2";
	    if($level==5)
	       $sql="SELECT t3.id,t1.email,t1.name FROM logins AS t1,largeschools AS t2,headers AS t3 WHERE t1.school=t3.school AND t1.school=t2.school AND t1.level=2 AND t2.schgroup='$school2'";
    	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       if(IsRegistered2011($row[id],$sportreg3))
	          $recipients.=$row[email].",";
	    }
	 }
	 $sql="SELECT * FROM messages WHERE title='$title' AND sportreg='$sportreg'";
	 if($level==5) $sql.=" AND school='$school2'";
	 $result=mysql_query($sql);
	 if(mysql_num_rows($result)>0) //UPDATE
	 {
	    $row=mysql_fetch_array($result);
	    $messageid=$row[id];
	    $sql="UPDATE messages SET fromemail='$replyto',sportreg='$sportreg', title='$title', message='$message_text', end_date='$end_date', filename='$newfile',linkname='$linkname',poster='$poster',post_date='$post_date' WHERE sportreg='$sportreg' AND title='$title'";
	    if($level==5) $sql.=" AND school='$school2'";
            $result=mysql_query($sql);
	 }
         else                           //INSERT (make new message)
         {
            $sql="INSERT INTO messages (fromemail, sportreg, title, message, end_date, filename,linkname,poster,post_date) VALUES ('$replyto', '$sportreg','$title','$message_text','$end_date', '$newfile','$linkname','$poster','$post_date')";
	    if($level==5)
               $sql="INSERT INTO messages (fromemail,school,sportreg, title, message, end_date, filename,linkname,poster,post_date) VALUES ('$replyto','$school2','$sportreg','$title','$message_text','$end_date', '$newfile','$linkname','$poster','$post_date')";
            $result=mysql_query($sql);
            $messageid=mysql_insert_id();
         }
      }
      else
      {
         for($i=0;$i<count($school_array);$i++)      
	 {         
	    $temp=ereg_replace("\'","\'",$school_array[$i]);         
	    //if box was checked, add recipient to email list         
	    if($alsoemail=='y')         
	    {            
	       $sql="SELECT email,name FROM logins WHERE";
	       if($temp!="All") $sql.=" school='$temp' AND";
	       $sql.=" level=2 and email LIKE '%@%'"; 
	       $result=mysql_query($sql);            
	       while($row=mysql_fetch_array($result))               
	       {
	          $recipients.=$row[0].",";         
	       }
   	    }         
	    $sql="SELECT * FROM messages WHERE title='$title' AND school='$temp'";         
	    $result=mysql_query($sql);         
 	    $school_array[$i]=ereg_replace("\'","\'",$school_array[$i]);         
	    if(mysql_num_rows($result)>0)  //UPDATE (replace)         
	    {            
	       $row=mysql_fetch_array($result);            
	       $messageid=$row[id];            
	       $sql="UPDATE messages SET fromemail='$replyto', school='$temp', title='$title', message='$message_text', end_date='$end_date', filename='$newfile',linkname='$linkname',poster='$poster',post_date='$post_date' WHERE school='$temp' AND title='$title'";
               $result=mysql_query($sql);
            }
            else                           //INSERT (make new message)
            {
               $sql="INSERT INTO messages (fromemail, school, title, message, end_date, filename,linkname,poster,post_date) VALUES ('$replyto','$temp','$title','$message_text','$end_date', '$newfile','$linkname','$poster','$post_date')";
               $result=mysql_query($sql);
               $messageid=mysql_insert_id();
            }
            //echo "$sql<br>".mysql_error();
         }
      }//end if sportreg not chosen
      //add any additional recipients to e-mail list
      $recipients.=$email;
      if($emailschoolgroups=='y') 
      {
        $sql2="SELECT email FROM logins WHERE level='5' AND email!=''";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
	   if(trim($row2[email])!='')
              $recipients.=",".trim($row2[email]);
 	}
      }
         
      //e-mail message if recipient list is not empty
      if(trim($recipients)!="")
      {
         $recips=ereg_replace(",","<recipient>",$recipients);
         //citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
		 //sendsemails($session,$messageid,$recips);
		 exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
      } 
   }
   else			//AD post to Coach(es)
   {
      //get AD e-mail, name
      $sql="SELECT email,name FROM logins WHERE school='$school2' AND level=2";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $replyto=$row[0];
      $replytoname=$row[1];
      $poster="AD";
      $post_date=date("Y-m-d");

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
	 $sql="SELECT * FROM messages WHERE title='$title' AND sport='$sport_array[$i]' AND school='$school2'";
	 $result=mysql_query($sql);

	 //if checked, add recipient to e-mail list
	 $sql2="SELECT email,name FROM logins WHERE school='$school2' AND sport LIKE '$sport_array[$i]%'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($alsoemail=='y') $recipients.=$row2[0].",";

	 $row=mysql_fetch_array($result);
	 if(mysql_num_rows($result)>0)	//UPDATE
	 {
	    $messageid=$row[id];
	    $sql="UPDATE messages SET school='$school2', sport='$sport_array[$i]', title='$title', message='$message_text', end_date='$end_date',poster='$poster',post_date='$post_date' WHERE id='$row[0]'";
	    $result=mysql_query($result);
	 }
	 else				//INSERT
	 {
	    $sql="INSERT INTO messages (school, sport, title, message, end_date,poster,post_date) VALUES ('$school2','$sport_array[$i]','$title','$message_text','$end_date','$poster','$post_date')";
	    $result=mysql_query($sql);
	    $messageid=mysql_insert_id();
	 }
      }

      //add any additional recipients to e-mail list
      $recipients.=$email;

      //e-mail message if recipient list is not empty
      if(trim($recipients)!="")
      {
         $recips=ereg_replace(",","<recipient>",$recipients);
         //citgf_exec("/usr/local/bin/php sendemails.php '$session' '$messageid' '$recips' > sendemailsoutput.html 2>&1 &");
		 //sendsemails($session,$messageid,$recips);
		 exec("/usr/bin/php sendemails.php '$session' '$messageid' '$recips' > /dev/null &");
      }
   }

   //display confirmation page:
   $header=GetHeader($session);
   echo $init_html;
   echo $header;
   echo "<br><br><table width=75%><tr align=left><th align=left>You have posted the following message:<br><br></th></tr>";
   echo "<tr align=left><th align=left><i>$title:</i></th></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><th align=left>";
   if($level==1 || $level==5) echo "...to the following school(s):";
   else echo "...to the coach(es) of the following sport(s):";
   echo "</th></tr>";
   if($level==1 || $level==5)
   {
      echo "<tr align=left><td><table>";
      for($i=0;$i<count($school_array);$i++)
      {
	 if($i%3==0) echo "<tr align=left>";
	 echo "<td>$school_array[$i]</td>";
	 if(($i+1)%3==0) echo "</tr>";
      }
      echo "</table></td></tr>";
      if($sportreg!='')
      {
	 for($i=0;$i<count($act_regi);$i++)
	 {
	    if($sportreg==$act_regi[$i])
	       echo "<tr align=left><td>Schools who are REGISTERED for $act_regi2[$i].</td></tr>";
         }
      }
   }
   else
   {
      for($i=0;$i<count($sport_array);$i++)
      {
	 echo "<tr align=left><td>$sport_array[$i]</td></tr>";
      }
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
         echo $temp3[$i].", ";
      }
	echo "<br><br>(The REPLY-TO address was $replyto.)";
      echo "</td></tr>";
   }
   echo "</table><br>";
   echo "<a href=\"post_message.php?session=$session\">Create Another Message</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"edit_message.php?session=$session\">Edit/Delete Messages</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"welcome.php?session=$session\">Home</a></td></tr></table>";
   echo $end_html;
   exit();
}

//If you get here, need to show blank message form
echo $init_html;
echo GetHeader($session);
echo "<br>";
echo "<form method=post action=\"post_message.php\" enctype=\"multipart/form-data\" name=emailform>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table class='nine'><caption><b>Create a New Message:</b>";
if($level==2)
   echo "<br>NOTE: This message will post only to coaches in YOUR SCHOOL";
echo "</caption>";
echo "<tr><td colspan=2><hr></td></tr>";
if($level==1)
   echo "<tr align=left><th width='200px' align=left>Reply-to Email:</th><td><input type=text size=30 name=\"replyto\" value=\"nsaa@nsaahome.org\"></td></tr>";
echo "<tr align=left><th width='200px' align=left>Enter the Subject:</th>";
echo "<td><input type=text name=title size=43></td></tr>";
echo "<tr align=left><th align=left>Display Message Until:</th>";
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
echo "<input type=text name=year size=4 class=tiny value=\"$year\"> (The message will show until midnight on this date.)";
echo "</td></tr>";
echo "<tr align=left valign=top><th align=left><br>";
if($level==1 || $level==5)	//NSAA user or large school user
{
   echo "Recipient(s):</th><td><br>";
}
else	//AD
{
   echo "Recipient(s):</th><td><br>";
}
if($level==1 || $level==5)
{
   echo "AD's of Schools Registered for:&nbsp;";
   echo "<select id=\"sportreg\" name=\"sportreg\"><option value=''>Select Sport</option>";
   for($i=0;$i<count($act_regi);$i++)
   {
      echo "<option value=\"$act_regi[$i]\"";
      if($sport==$act_regi[$i]) echo " selected";
      echo ">$act_regi2[$i]</option>";
   }
   echo "</select><br><br><b>OR</b><br><br>";

   echo "<select name=school_array[] MULTIPLE size=8>";
   echo "<option selected>All Schools</option>";
   if($level==1)
      $sql2="SELECT school FROM headers ORDER BY school";
   else
      $sql2="SELECT school FROM largeschools WHERE schgroup='$school2' ORDER BY school";
   $result2=mysql_query($sql2);
   while($schools=mysql_fetch_array($result2))
   {
      echo "<option";
      if($row[1]==$schools[0]) echo " selected";
      echo ">$schools[0]</option>";
   }
   echo "</select><br>";
   echo "(Hold down CTRL(PC) or Apple(Mac) to make multiple selections)";
   /*
   echo "Select a Specific School or ALL Schools:&nbsp;";
   echo "<select name=\"schoolch\" id=\"schoolch\" onchange=\"if(this.options.selectedIndex!=0) { sportreg.options.selectedIndex=0; }\"><option value=''>~</option><option value='all'>ALL Schools</option>";
   $sql="SELECT * FROM headers ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[school]\"";
      if($schools==$row[school]) echo " selected";
      echo ">$row[school]</option>";
   }
   echo "</select>";
   */
}
else	//AD
{
   echo "<select name=sport_array[] MULTIPLE size=4>";
   echo "<option>All Activities</option>";
   for($i=0;$i<count($act_long);$i++)
   {
      echo "<option>$act_long[$i]</option>";
   }
   echo "</select>";
   echo "<br><font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>";
}
echo "</td></tr>";
echo "<tr align=left><th align=left colspan=2><br>";
echo "<input type=checkbox name=alsoemail value='y'>&nbsp;Check here if you would also like to e-mail this message to the <u>RECIPIENTS YOU HAVE SELECTED ABOVE</u>.</th></tr>";
echo "<tr align=left><td align=left colspan=2 width='600px'><br>";
echo "<input type=checkbox name=\"emailschoolgroups\" value='y'>&nbsp;<b>Check here to also e-mail the <u>LARGE SCHOOL GROUPS</u>:</b><br>";
	//GET LARGE SCHOOL GROUP EMAILS
	$sql2="SELECT * FROM logins WHERE level='5' ORDER BY school,name";
	$result2=mysql_query($sql2);
	while($row2=mysql_fetch_array($result2))
	{
	    if(trim($row2[email])=="") $row2[email]="<label style=\"color:red\">[no email]</label>";
	    else $row2[email]="<a href=\"mailto:$row2[email]\" class=small>$row2[email]</a>";
	    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row2[school]: $row2[name], $row2[email]<br>";
	    $i++;
	}
echo "</th></tr>";
echo "<tr align=left><th align=left colspan=2><br>Select <u>additional recipients</u> to receive this message VIA E-MAIL by using our&nbsp;";
echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
echo "</th></tr>";
echo "<tr align=left><td width=600 colspan=2>(Recipients selected using the Address Book will appear in the box below. You may also type e-mail addresses into this box. Make sure to separate multiple e-mail addresses with a <u>comma</u>.)<br></td></tr>";
echo "<tr align=center><td colspan=2><b>Additional Recipient(s) via E-mail:<br>";
echo "<textarea name=email cols=70 rows=5>$recipients</textarea></td></tr>";
echo "<tr align=left><th align=left colspan=2>Message Body:</th></tr>";
echo "<tr align=center><td colspan=2><b>HTML Help:</b><br><table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<tr align=center><td><b>To...</b></td><td><b>Type this:</b></td><td><b>To see this:</b></td></tr>";
echo "<tr align=left><td>Add a LINK:</td><td>&lt;a target=&quot;_blank&quot; href=&quot;http://www.google.com&quot;&gt;Google&lt;/a&gt;</td><td><a target=\"_blank\" href=\"http://www.google.com\">Google</a></td></tr>";
echo "<tr align=left><td>Link to an E-MAIL:</td><td>&lt;a href=&quot;mailto:nsaa@nsaahome.org&quot;&gt;nsaa@nsaahome.org&lt;/a&gt;</td><td><a href=\"mailto:nsaa@nsaahome.org\">nsaa@nsaahome.org</a></td></tr>";
echo "<tr align=left><td>BOLD words:</td><td>&lt;b&gt;August 1&lt;/b&gt;</td><td><b>August 1</b></td></tr>";
echo "<tr align=left><td>ITALICIZE words:</td><td>&lt;i&gt;August 1&lt;/i&gt;</td><td><i>August 1</i></td></tr>";
echo "<tr align=left><td>UNDERLINE words:</td><td>&lt;u&gt;August 1&lt;/u&gt;</td><td><u>August 1</u></td></tr></table>";
echo "</td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<textarea cols=110 rows=10 name=message_text></textarea>";
echo "</td></tr>";
if($level==1)
{
   echo "<tr align=left><th colspan=2 align=left>Add an Attachment:</th></tr>";
   echo "<tr align=left><td><b>Upload File:</b></td>";
   echo "<td><input type=file name=filename></td></tr>";
   echo "<tr align=left>";
   echo "<td><b>Give your File a Title</b><br>(leave blank to use filename as title):</td>";
   echo "<td><input type=text name=linkname size=20</td></tr>";
}
echo "<tr align=center><td colspan=2><br><br>";
echo "<input type=submit name=submit value=\"Post\">";
echo "<input type=submit name=submit value=\"Cancel\">";
echo "</td></tr></table></form>";

echo $end_html;
?>
