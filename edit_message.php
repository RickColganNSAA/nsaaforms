<?php
//edit_message.php:   Allow user to choose and edit/delete messages
//	they have posted
require '../calculate/functions.php';
require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get level of user
$level=GetLevel($session);

//get school of user
$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);

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
   $end=mktime(0,0,0,$monthnum,$daynum,$yearnum);
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
   <form method=post action="edit_message.php">
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
   $title=addslashes($title);
   if($level==1)
   {
      $sql="DELETE FROM messages WHERE title='$title' AND poster='NSAA'";
   }
   else if($level==5)
   {
      $sql="DELETE FROM messages WHERE title='$title' AND poster='$school2'";
   }
   else
   {
      $sql="DELETE FROM messages WHERE title='$title' AND school='$school2' AND sport IS NOT NULL";
   }
   $result=mysql_query($sql);
   header("Location:edit_message.php?session=$session");
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

   //replace multiple spaces with singles space:
   $title=preg_replace("/( +)/", " ", $title);
   $message_text=preg_replace("/( +)/", " ", $message_text);

   //line breaks
   $message_text=ereg_replace("\r\n","<br>",$message_text);

   //get vars ready for possible e-mail
   $attm=array();
   $recipients="";

   $old_title=trim($old_title);

   if(is_uploaded_file($_FILES['filename']['tmp_name']) && $deletefile!='x')
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
         if(trim($linkname)=="") $linkname=addslashes($filename);
         else $linkname=addslashes($linkname);
         $email_text.="\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms/index.php to view the attachment to this message.";
         $email_html.="<br><br>Please login at <a href='https://secure.nsaahome.org/nsaaforms'>https://secure.nsaahome.org/nsaaforms/index.php</a> to view the attachment to this message.";
         $fileuploaded=1;
      }
      else
      {
	 $fileuploaded=0; $linkname="";
	 echo "ERROR: ".$_FILES['filename']['name']." couldn't be copied to messagefiles/$newfile. Please <a href=\"javascript:history.go(-1);\">Go Back</a> and try again or report this issue to the programmer.";
	 exit();
      }
   }
   else if($deletefile=='x')
   {
      $filename=""; $linkname=""; $newfile=""; $fileuploaded=0;
   }
   else $fileuploaded=0;

   //insert new message in db table messages
   if($level==1 || $level==5)	//NSAA post to AD(s) or large school post to AD(s)
   {
      //if All Schools was chosen, make array with all schools in it:
      if($sportreg!='')
      {
	 $school_array=array();
      }
      else if($school_array[0]=="All Schools")
      {
	 $school_array[0]="All";
      }
      if(count($school_array)>0) $sportreg="";

      if(trim($from)=="") $from="nsaa@nsaahome.org";
      $fromname="NSAA";

      //if message with this title already exists, replace it:
      for($i=0;$i<count($school_array);$i++)
      {
	 $temp=ereg_replace("\'","\'",$school_array[$i]);
	 //if box was checked, add recipients to email list
	 if($alsoemail=='y')
	 {
            $sql="SELECT email,name FROM logins WHERE school='$temp' AND level=2";
            if($school_array[$i]=='All' || $school_array[$i]=="All Schools")
               $sql="SELECT email,name FROM logins WHERE level=2";
            $result=mysql_query($sql);
            while($row=mysql_fetch_array($result))
               $recipients.=$row[0].",";
	 }
         $sql="SELECT * FROM messages WHERE title='$old_title' AND ";
         if(ereg("All",$temp)) $sql.="(school='All' OR school='All Schools')";
	 else $sql.="school='$temp'";
	 $sql.=" AND sport IS NULL";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $oldfilename=$row[6];
         if(!$filename && $oldfilename!='' && trim($linkname)=="" && $deletefile!='x') 
	    $linkname=addslashes($oldfilename);
         else if(!$filename && $oldfilename!='' && $deletefile!='x') 
	    $linkname=addslashes($linkname);
	 $school_array[$i]=ereg_replace("\'","\'",$school_array[$i]);
         if(mysql_num_rows($result)>0)	//UPDATE (replace)
         {
	    $messageid=$row[id];
	    if($fileuploaded || $deletefile=='x')
	    {
	       $sql="UPDATE messages SET fromemail='$from',school='$temp', title='$title', message='$message_text', end_date='$end_date', filename='$newfile', linkname='$linkname' WHERE school LIKE '$school_array[$i]%' AND title='$old_title' AND poster=";
	    }
	    else
	    {
               $sql="UPDATE messages SET fromemail='$from',school='$temp', title='$title', message='$message_text', end_date='$end_date',linkname='$linkname' WHERE school LIKE '$school_array[$i]%' AND title='$old_title' AND poster=";
	    }
	    if($level==1) $sql.="'NSAA'";
	    else $sql.="'$school2'";
            $result=mysql_query($sql);
//echo "$sql<br>";
//echo mysql_error();
         }
         else				//INSERT (make new message)
         {
	    if($level==1) $poster="NSAA";
	    else $poster=$school2;
	    if($fileuploaded || $deletefile=='x')
	    {
	       $sql="INSERT INTO messages (fromemail,school, title, message, post_date,end_date, filename, linkname,poster) VALUES ('$from','$temp','$title','$message_text','".date("Y-m-d")."','$end_date','$newfile','$linkname','$poster')";
	    }
	    else
	    {
               $sql="INSERT INTO messages (fromemail, school, title, message, post_date,end_date,poster) VALUES ('$from','$temp','$title','$message_text','".date("Y-m-d")."','$end_date','$poster')";
	    }
            $result=mysql_query($sql);
	    $messageid=mysql_insert_id();
         }
      }
      if($sportreg!='')
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
         $sql="SELECT * FROM messages WHERE title='$old_title' AND sport IS NULL";         
         $result=mysql_query($sql);         
         $row=mysql_fetch_array($result);         
         $oldfilename=$row[6];         
         if(!$fileuploaded && $oldfilename!='' && trim($linkname)=="" && $deletefile!='x')            
            $linkname=addslashes($oldfilename);         
         else if(!$fileuploaded && $oldfilename!='' && $deletefile!='x')            
	    $linkname=addslashes($linkname);         
         if(mysql_num_rows($result)>0)  //UPDATE (replace)         
 	 {            
	    $messageid=$row[id];            
	    if($fileuploaded || $deletefile=='x')            
	    {               
	       $sql="UPDATE messages SET fromemail='$from', sportreg='$sportreg', title='$title', message='$message_text', end_date='$end_date', filename='$newfile', linkname='$linkname' WHERE title='$old_title' AND poster=";            
            }            
            else            
            {               
               $sql="UPDATE messages SET fromemail='$from', sportreg='$sportreg', title='$title', message='$message_text', end_date='$end_date',linkname='$linkname' WHERE title='$old_title' AND poster=";            
 	    }            
	    if($level==1) $sql.="'NSAA'";            
	    else $sql.="'$school2'";            
	    $result=mysql_query($sql);       
	 }         
	 else                           //INSERT (make new message)         
	 {            
	    if($level==1) { $poster="NSAA"; $temp=''; }
	    else { $poster=$school2;  $temp=$school2; }
	    if($fileuploaded || $deletefile=='x')            
	    {               
	       $sql="INSERT INTO messages (fromemail,sportreg,school, title, message, end_date, filename, linkname,poster) VALUES ('$from','$sportreg','$temp','$title','$message_text','$end_date','$newfile','$linkname','$poster')";
            }
            else
            {
               $sql="INSERT INTO messages (fromemail,sportreg,school, title, message, end_date,poster) VALUES ('$from','$sportreg','$temp','$title','$message_text','$end_date','$poster')";
            }
            $result=mysql_query($sql);
            $messageid=mysql_insert_id();
         }
      }
      if($deletefile=='x' && citgf_file_exists("messagefiles/$oldfilename"))
      {
         citgf_unlink("messagefiles/$oldfilename");
      }
   }//end if NSAA posting to schools
   else			//AD post to Coach(es)
   {
      //get AD email, name
      $sql="SELECT email, name FROM logins WHERE school='$school2' AND level=2";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $from=$row[0];
      $fromname=$row[1];

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
	 $sql="SELECT * FROM messages WHERE title='$old_title' AND sport='$sport_array[$i]' AND school='$school2'";
	 $result=mysql_query($sql);

	 //if checked, add recipient to email list
	 $sql2="SELECT email, name FROM logins WHERE school='$school2' AND sport LIKE '$sport_array[$i]%'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($alsoemail=='y') $recipients.=$row2[0].",";

	 $row=mysql_fetch_array($result);
	 if(mysql_num_rows($result)>0)	//UPDATE
	 {
	    $sql="UPDATE messages SET title='$title', message='$message_text', end_date='$end_date' WHERE title='$old_title' AND sport='$sport_array[$i]' AND school='$school2'";
	    $result=mysql_query($sql);
	    $messageid=$row[id];
	 }
	 else				//INSERT
	 {
	    $sql="INSERT INTO messages (school, sport, title, message, end_date) VALUES ('$school2','$sport_array[$i]','$title','$message_text','$end_date')";
	    $result=mysql_query($sql);
	    $messageid=mysql_insert_id();
	 }
      }
   }//end if AD posting to coaches

   //add any additional recipients to e-mail list
   $recipients.=$email;
   if($emailschoolgroups=='y')         
      $recipients.=",khand@lps.org,margaret.naylon@ops.org,bob.danenahauer@ops.org,ctwhaley@mpsomaha.org,seversonr@hotmail.com";
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
   echo "<tr align=left><th align=left><br>You have posted the following message:<br><br></th></tr>";
   echo "<tr align=left><th align=left><i>$title:</i></th></tr>";
   echo "<tr align=left><td><p>$message_text</p></td></tr>";
   echo "<tr align=left><th align=left>";
   if($level==1 || $level==5) echo "...to the following school(s):";
   else echo "...to the coach(es) of the following sport(s):";
   echo "</th></tr>";
   if($level==1 || $level==5)
   {
      echo "<tr align=left><td>";
      echo "<table frame=vsides bordercolor=#000000 cellspacing=0 cellpadding=5>";
      for($i=0;$i<count($school_array);$i++)
      {
	 if($i%3==0) echo "<tr align=left>";
	 echo "<td>$school_array[$i]</td>";
	 if(($i+1)%3==0) echo "</tr>";
      }
      if($sportreg!='')
      {
         echo "<tr align=left><td>Schools who have REGISTERED for ";
	 for($i=0;$i<count($act_regi);$i++)
	 {
	    if($sportreg==$act_regi[$i]) { echo $act_regi2[$i]; $i=count($act_regi); }
         }
	 echo "</td></tr>";
      }
      echo "</table></td></tr>";
   }
   else
   {
      echo "<tr align=left><td><table cellspacing=1 cellpadding=2>";
      for($i=0;$i<count($sport_array);$i++)
      {
	 if($i%3==0) echo "<tr align=left>";
	 echo "<td>$sport_array[$i]</td>";
	 if(($i+1)%3==0) echo "</tr>";
      }
      echo "</table></td></tr>";
   }
   $date=split("-",$end_date);
   $enddate="$date[1]/$date[2]/$date[0]";
   echo "<tr align=left><th align=left><br>...to show until: $enddate.</th></tr>";
   if($deletefile!='x' && ($fileuploaded || ($oldfilename!='' && citgf_file_exists("messagefiles/$oldfilename"))))
   {
      echo "<tr align=left><th align=left>You have also uploaded the following file:</th></tr>";
      echo "<tr align=left><th align=left>";
      if($filename) echo "<a href=\"messagefiles/$newfile\" target=new>$linkname</a>";
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
   echo "<br><a href=\"edit_message.php?session=$session\">Edit/Delete Messages</a>&nbsp;&nbsp;";
   echo "<a href=\"post_message.php?session=$session\">Post New Message</a>&nbsp;&nbsp;";
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
   echo "<a class=small href=\"post_message.php?session=$session\">Post New Message to Schools</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"post_message2.php?session=$session\">Post New Message to Coaches</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"edit_message2.php?session=$session\">Edit Messages to Coaches</a><br>";
}
echo "<br><form method=post action=\"edit_message.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=80%>";
echo "<tr><th colspan=2><u>Edit Messages Posted to ";
if($level==1 || $level==5) echo "Schools";
else echo "Coaches";
echo ":</u><br><br>";
echo "Choose a message and then choose \"Edit\" or \"Delete\":<br></td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<select name=title>";
if($level==1)	//NSAA user
{
   $sql="SELECT DISTINCT title FROM messages WHERE poster='NSAA' AND sport IS NULL";
}
else if($level==5)	//Large School
{
   $sql="SELECT DISTINCT title FROM messages WHERE poster='$school2'";
}
else	//AD
{
   $sql="SELECT DISTINCT title FROM messages WHERE school='$school2' AND sport IS NOT NULL";
}
$sql.=" ORDER BY title";
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
   if($level==1)
   {
      $sql="SELECT * FROM messages WHERE title='$title' AND poster='NSAA' ORDER BY school";
   }
   else if($level==5)
   {
      $sql="SELECT * FROM messages WHERE title='$title' AND poster='$school2' ORDER BY school";
   }
   else
   {
      $sql="SELECT * FROM messages WHERE title='$title' AND school='$school2' AND sport IS NOT NULL ORDER BY sport";
   }
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($level==1 || $level==5)
      {
	 if($row[sportreg]!='') $sportreg=$row[sportreg];
         $school_array[$ix]=$row[2];
      }
      else
      {
	 $sport_array[$ix]=$row[3];
      }
      $ix++;
      $end_date=$row[4];
      $message_text=$row[5];
      $filename=$row[filename]; $linkname=$row[linkname];
      if(!citgf_file_exists("messagefiles/$filename")) { $filename=""; $linkname=""; }
      $from=$row[fromemail];
   }
   if(trim($from)=="") $from="nsaa@nsaahome.org";
   //get end_date into three parts:
   $end_date=split("-",$end_date);
   $month=$end_date[1];
   $day=$end_date[2];
   $year=$end_date[0];

   echo "<form method=post action=\"edit_message.php\" enctype=\"multipart/form-data\" name=\"emailform\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=old_title value=\"$title\">";
   echo "<table width='650px'>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   if($level==1) echo "<tr align=left><th align=left>Reply-To E-mail:</th><td><input type=text name=from value=\"$from\" size=30></td></tr>";
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
   if($level==1 || $level==5)	//NSAA user
   {
      echo "School(s):";
   }
   else	//AD
   {
      echo "Sport(s):";
   }
   echo "</th>";
   echo "<td><br>";
   if($level==1 || $level==5)
   {
      echo "Schools Registered for:&nbsp;";
      echo "<select id=\"sportreg\" name=\"sportreg\" onchange=\"if(this.options.selectedIndex!=0) { schoolch.options.selectedIndex=0; }\"><option value=''>Select Sport</option>";
      for($i=0;$i<count($act_regi);$i++)
      {
         echo "<option value=\"$act_regi[$i]\"";
         if($sportreg==$act_regi[$i]) echo " selected";
         echo ">$act_regi2[$i]</option>";
      }
      echo "</select><br><b>OR</b><br>";

      echo "<select name=school_array[] MULTIPLE size=5>";
      echo "<option selected>All Schools</option>";
      if($level==1)
         $sql2="SELECT school FROM headers ORDER BY school";
      else
         $sql2="SELECT school FROM largeschools WHERE schgroup='$school2' ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option";
	 for($i=0;$i<count($school_array);$i++)
         {
	    if($school_array[$i]==$row2[school])
	    {
	       echo " selected"; $i=count($school_array);
	    }
	 }
         echo ">$row2[school]</option>";
      }
      echo "</select><br>";
      echo "<font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>";
   }
   else	//AD
   {
      echo "<select name=\"sport_array[]\" MULTIPLE size=5>";
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
	       $select=1;
	       $j=count($sport_array);
	    }
         }
         if($select==1)
         {  
	    echo " selected";
         }
         echo ">$act_long[$i]</option>";
      }
      echo "</select>";
   }
   echo "</td></tr>";
   echo "<tr align=left><th align=left colspan=2><br>";
   echo "<input type=checkbox name=alsoemail value='y'>&nbsp;Check here if you would also like to e-mail the <u>RECIPIENTS YOU HAVE SELECTED ABOVE</u>.</th></tr>";
   echo "<tr align=left><th align=left colspan=2 width='600px'><br>";
   echo "<input type=checkbox name=\"emailschoolgroups\" value='y'>&nbsp;<b>Check here to also e-mail the <u>LARGE SCHOOL GROUPS</u>:</b><br>(khand@lps.org, margaret.naylon@ops.org, bob.danenahauer@ops.org, ctwhaley@mpsomaha.org and seversonr@hotmail.com)</th></tr>";
   echo "<tr align=left><th align=left colspan=2 class=smaller><br>Select additional recipients to received this message via e-mail by using our ";
   echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
   echo "</th></tr>";
   echo "<tr align=left><td width='600px' colspan=2>(Recipient(s) selected using the Address Book will appear in the box below.  You may also type e-mail address into this box.  Make sure to separate multiple e-mail addresses with a comma.)</td></tr>";
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
      if($filename!="" && $filename!=NULL)
      {
         echo "<tr align=left><td align=left><b>You have uploaded the following file to accompany this message:</b></td></tr>";
         echo "<tr align=left><td colspan=2><a target=\"_blank\" href=\"messagefiles/$filename\">$filename ($linkname)</a></td></tr>";
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
