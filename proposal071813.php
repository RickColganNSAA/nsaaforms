<?php

require 'functions.php';
require 'variables.php';

$exhibits=array("A","B","C","D","E");

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
if($level!=1) echo $header;

if($submit)
{
   //check that all fields were filled out
   $error=0;
   if(trim($name)=="") 
   {
      $error=1; $errname=1;
   }
   if($yearbook=='x' && (trim($article)=="" || trim($section)=="" || trim($ybpage)==""))
   {
      //if yearbook is checked and other fields aren't filled out, show error
      $error=1; $erryearbook=1;
   }
   else if((trim($article)!="" || trim($section)!="" || trim($ybpage)!="") && (trim($article)=="" || trim($section)=="" || trim($ybpage)==""))
   {
      //if one of the yearbook fields is filled out, but the rest aren't, check 'yearbook' and show error
      $yearbook='x';
      $error=1;
      $erryearbook=2;
   }
   else if(trim($article)!="" && trim($section)!="" && trim($ybpage)!="")
   {
      //if all yearbook fields are filled out, make sure yearbook is checked
      $yearbook='x';
   }
   if($actman=='x' && (trim($actman2)=="" || trim($ampage)==""))
   {
      //if actman is checked and other fields aren't filled out, show error
      $error=1; $erractman=1;
   }
   else if((trim($actman2)!="" || trim($ampage)!="") && (trim($actman2)=="" || trim($ampage)==""))
   {
      //if one of the act man fields is filled out but the rest aren't, check 'actman' and show error
      $actman='x';
      $error=1;
      $erractman=2;
   }
   else if(trim($actman2)!="" && trim($ampage)!="")
   {
      //if all act man fields are filled out, make sure actman is checked
      $actman='x';
   }
   if(trim($current)=="")
   {
      $error=1; $errcurrent=1;
   }
   if(trim($changed)=="" && $type=="caucus")
   {
      $error=1; $errchanged=1;
   }
   if(!$travel && $type!="caucus")
   {
      $error=1; $errtravel=1;
   }
   if(!$instruction && $type!="caucus")
   {
      $error=1; $errinstruction=1;
   }
   if(trim($costanal)=="")
   {
      $error=1; $errcostanal=1;
   }
   if(trim($rationale)=="")
   {
      $error=1;
      $errrationale=1;
   }
   if(trim($class)=="" && $type=="caucus")
   {
      $error=1;
      $errclass=1;
   }
   
      //prepare text for database entry
      $name2=addslashes($name);
      $class=trim($class);
      $article2=addslashes($article);
      $section2=addslashes($section);
      $ybpage2=addslashes($ybpage);
      $actman22=addslashes($actman2);
      $ampage2=addslashes($ampage);
      $current2=addslashes($current);
      $current2=ereg_replace("\r\n","<br>",$current2);
      $current=ereg_replace("\r\n","<br>",$current);
      $changed2=addslashes($changed);
      $changed2=ereg_replace("\r\n","<br>",$changed2);
      $changed=ereg_replace("\r\n","<br>",$changed);
      $costanal2=addslashes($costanal);
      $costanal2=ereg_replace("\r\n","<br>",$costanal2);
      $costanal=ereg_replace("\r\n","<br>",$costanal);
      $rationale2=addslashes($rationale);
      $rationale2=ereg_replace("\r\n","<br>",$rationale2);
      $rationale=ereg_replace("\r\n","<br>",$rationale);

      //get AD name and NSAA district
      $sql="SELECT name FROM logins WHERE school='$school2' AND level=2";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $ADname=$row[0];
      if(trim($ADname)=="")	//no AD name, check Act Dir
      {
	 $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Activities Director'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $ADname=$row[0];
      }
      $sql="SELECT nsaadist FROM headers WHERE school='$school2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $nsaadist=trim($row[0]);

      if($error==0 && ($submit=="Submit Proposal" || $level==1))
      {
         $datesub=time();	//date submitted in seconds format
         if($givenid && $givenid!='')
     	 {
            $sql="SELECT filename FROM proposals WHERE id='$givenid'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $filename=$row[0];
         }
	 if(!$filename || $filename=="")
	 {
            $filename=ereg_replace("\'","",$school);
            $filename=ereg_replace(" ","",$filename);
            $filename=ereg_replace("-","",$filename);
            $filename=ereg_replace("[.]","",$filename);
            $filename.="_Dist".$nsaadist."_$datesub.html";
	 }
	 $locked='x';
      }
      else 
      {
         $datesub="";		//saved, not submitted yet
         $filename="";
	 $locked="";
      }
  
      if(!$givenid || $givenid=="")	//new form
      {
         $sql="INSERT INTO proposals (type,class,school,datesub,name,district,yearbook,article,section,ybpage,actman,actman2,ampage,current,changed,travel,instruction,costanal,rationale,filename,locked) VALUES ('$type','$class','$school2','$datesub','$name2','$nsaadist','$yearbook','$article2','$section2','$ybpage2','$actman','$actman22','$ampage2','$current2','$changed2','$travel','$instruction','$costanal2','$rationale2','$filename','$locked')";
	 $result=mysql_query($sql);
	 $givenid=mysql_insert_id();
      }
      else	//edited form
      {
	 $sql="UPDATE proposals SET type='$type',class='$class',school='$school2',name='$name2',district='$nsaadist',yearbook='$yearbook',article='$article2',section='$section2',ybpage='$ybpage2',actman='$actman',actman2='$actman22',ampage='$ampage2',current='$current2',changed='$changed2',travel='$travel',instruction='$instruction',costanal='$costanal2',rationale='$rationale2',filename='$filename',locked='$locked'";
         if($level!=1) $sql.=",datesub='$datesub'";
         $sql.=" WHERE id='$givenid'";
         $result=mysql_query($sql);
      }

      //Add/Change Exhibit Uploads
      $sql="SELECT * FROM proposals WHERE id='$givenid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      for($i=0;$i<count($exhibits);$i++)
      {
	 $letter=$exhibits[$i];
	 $title="ex".$letter."title";
	 $file="ex".$letter."file";
	 $delete="ex".$letter."delete";
         if($$delete=='x')	//delete exhibit
	 {
            citgf_unlink("attachments/".$row[$file]);
	    $sql2="UPDATE proposals SET $title='',$file='' WHERE id='$givenid'";
	    $result2=mysql_query($sql2);
         }
	 else
         {
	    //$$title has correct title
	    //upload NEW FILE AS LONG AS new file is not BLANK
	    if(citgf_file_exists($$file)) 
            {
	       $newfilename=$_FILES[$file]["name"];
	       citgf_copy($$file,"attachments/$newfilename");
	       $sql2="UPDATE proposals SET $title='".$$title."', $file='$newfilename' WHERE id='$givenid'";
	       $result2=mysql_query($sql2);
		//echo $sql2;
            }
	    else	//just update title
	    {
               $sql2="UPDATE proposals SET $title='".$$title."' WHERE id='$givenid'";
	       $result2=mysql_query($sql2);
	    }
         }
      } 

      //show what was entered and write to .html file to send to NSAA 
      if($submit=="Submit Proposal" && $error==0)
      {
         $open=fopen(citgf_fopen("attachments/$filename"),"w");
         $string="<html><body><table width='100%'><tr align=center><td>";
         echo "<br><font style=\"color:blue\"><b>The following proposal has been electronically sent to the NSAA:</b></font><br><br>";
         $info="";	//info will be both written to file and echoed to screen
         $info.="<table cellspacing=3 cellpadding=4>";
         $info.="<caption><b>";
   	 if($type=="caucus") $info.="CLASS CAUCUS";
         else $info.="LEGISLATIVE";
         $info.=" PROPOSAL FOR CHANGE IN NSAA REGULATIONS</b></caption>";
         $date=date("M d, Y",$datesub);
         $info.="<tr align=left><td><font style=\"color:red\"><b>Proposal Generated by</b> $ADname, $school<b>, School Access Code, District</b> $nsaadist</font></td></tr>";
         $info.="<tr align=left><td><b>This proposal was submitted on:</b>&nbsp;$date</td></tr>";
         $info.="<tr align=left><td><b>This proposal is submitted by:</b></td></tr>";
         $info.="<tr align=left><td><b>Name:</b>&nbsp;$name</td></tr>";
         $info.="<tr align=left><td><b>School:</b>&nbsp;$school</td></tr>";
         if($type=="caucus") $info.="<tr align=left><td><b>Class:</b>&nbsp;$class</td></tr>";
	 else $info.="<tr align=left><td><b>NSAA District:</b>&nbsp;$nsaadist</td></tr>";
         $info.="<tr align=left><td><b>The proposal deals with:</b></td></tr>";
         $info.="<tr align=left><td>";
         if($yearbook=='x')
         {
	    $info.="<b>Yearbook:&nbsp;&nbsp;Article</b> $article&nbsp;&nbsp;<b>Section:</b> $section&nbsp;&nbsp;<b>Page:</b> $ybpage<br>";
         }
         if($actman=='x')
         {
	    $info.="<b>Activities Manual:</b> $actman2&nbsp;&nbsp;<b>Page:</b> $ampage";
         }
         $info.="</td></tr>";
         $info.="<tr align=left><td><b>The section/paragraph/sentence indicates what is to be added/deleted/changed to the current Bylaw/Approved Ruling:<br>";
         $info.="ADDITIONS are in all capital letters. (Changes/Deletions are in parentheses.)</b><br>";
         if(ereg("\[Table #",$current))	//table(s) inserted in text
         {
	    $text=split("\[Table #",$current);
	    for($i=0;$i<count($text);$i++)
	    {
	       //$text[$i]=substr($text[$i],2,strlen($text[$i]));
	       $pos=strpos($text[$i],"]");
	       $tablenum=substr($text[$i],0,$pos+1);
	       if(ereg("[0-9]]",$tablenum))
	       {
	          $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	          $info.=GetTable($tablenum);
	          $info.=trim(substr($text[$i],$pos+1,strlen($text[$i])));
	          //update table in database (set proposalid to this proposal's id instead of session)
	          $sql="UPDATE proposaltables SET proposalid='$givenid' AND pending='x' WHERE id='$tablenum'";
	          $result=mysql_query($sql);
	       }
	       else	//no table (first text to show)
	       {
	          $info.=$text[$i];
	       }
	    }
         }
         else
	    $info.=$current;
         $info.="</td></tr>";
	 if($type=='caucus')
	 {
         $info.="<tr align=left><td><b>The section/paragraph/sentence that needs to be added/deleted/changed would read as follows:</b><br>";
         if(ereg("\[Table #",$changed))
         {
	    $text=split("\[Table #",$changed);
	    for($i=0;$i<count($text);$i++)
	    {
	       $pos=strpos($text[$i],"]");
	       $tablenum=substr($text[$i],0,$pos+1);
	       if(ereg("[0-9]]",$tablenum))
	       {
	          $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	          $info.=GetTable($tablenum);
	          $info.=trim(substr($text[$i],$pos+1,strlen($text[$i])));
	       }
	       else
	       {
	          $info.=$text[$i];
	       }
            }
         }
         else
	    $info.=$changed;
         $info.="</td></tr>";
	 }//end if CAUCUS
	 else
	 {
   	    	$info.="<tr align=left><td><br><b>Will this proposal increase travel for the participating schools?&nbsp;&nbsp;</b>";
   		if($travel=="Yes") $info.="YES";
   		else if($travel=="No") $info.="NO";
   		$info.="</td></tr>";
   		$info.="<tr align=left><td><b>Will this proposal impact a student or coach's loss of instruction time?&nbsp;&nbsp;</b>";
   		if($instruction=="Yes") $info.="YES";
   		if($instruction=="No") $info.="NO";
   		$info.="</td></tr>";
	}
         $info.="<tr align=left><td><b>Cost Analysis of Proposal:</b><br>";
         if(ereg("\[Table #",$costanal))
         {
            $text=split("\[Table #",$costanal);
            for($i=0;$i<count($text);$i++)
            {
               $pos=strpos($text[$i],"]");
	       $tablenum=substr($text[$i],0,$pos+1);
	       if(ereg("[0-9]]",$tablenum))
	       {
	          $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	          $info.=GetTable($tablenum);
	          $info.=trim(substr($text[$i],$pos+1,strlen($text[$i])));
	       }
	       else
	       {
	          $info.=$text[$i];
               }
	    }
         }
         else
	    $info.=$costanal;
         $info.="</td></tr>";
         $info.="<tr align=left><td><b>Rationale for the proposed change:</b><br>";
         if(ereg("\[Table #",$rationale))
         {
	    $text=split("\[Table #",$rationale);
	    for($i=0;$i<count($text);$i++)
	    {
	       $pos=strpos($text[$i],"]");
	       $tablenum=substr($text[$i],0,$pos+1);
	       if(ereg("[0-9]]",$tablenum))
	       {
	          $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	          $info.=GetTable($tablenum);
	          $info.=trim(substr($text[$i],$pos+1,strlen($text[$i])));
	       }
	       else
	       {
	          $info.=$text[$i];
	       }
	    }
         }
         else
	    $info.=$rationale;
         $info.="</td></tr></table>";

         //write to .html file
            //add exhibits at end:
	 $sql2="SELECT * FROM proposals WHERE id='$givenid'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $info.="<br><br><table>";
	 $attm[0]="attachments/$filename";
	 $a=1;
	 for($i=0;$i<count($exhibits);$i++)
    	 {
	    $letter=$exhibits[$i];
	    $title="ex".$letter."title"; $file="ex".$letter."file";
	    if($row2[$title]!='' || $row2[$file]!='')
	    {
	       $info.="<tr align=left><th align=left>EXHIBIT $letter:</th><td><a target=new2 href=\"https://secure.nsaahome.org/nsaaforms/attachments/".$row2[$file]."\">".$row2[$title]." (".$row2[$file].")</a></td></tr>";
	       $attm[$a]="attachments/".$row2[$file];
	       $a++;
	    }
	 }
         echo $info;
         echo "</table><br><br><br>";
         if($level!=1)
            echo "<a href=\"welcome.php?session=$session\">Home</a>";
         if($level==1 && $level!=5) echo "&nbsp;&nbsp;<a href=\"javascript:window.close();\">Close Window</a>";
	 $string.=$info."</table>";
         if(!fwrite($open,$string)) echo "COULD NOT WRITE";
         fclose($open); 
 citgf_makepublic("attachments/$filename");

         //e-mail .html file to nsaa 
         $text="Attached is a ";
	 if($type=="caucus") $text.="Class Caucus";
	 else $text.="Legislative";
	 $text.=" Proposal for Change in NSAA Regulations submitted by $school on $date for District $nsaadist.\r\n\r\nThank You!\r\n\r\n";
         $html="Attached is a ";
	 if($type=="caucus") $html.="Class Caucus";
	 else $html.="Legislative";
	 $html.=" Proposal for Change in NSAA Regulations submitted by $school on $date for District $nsaadist.<br><br>Thank You!<br><br>";
            $sql="SELECT email,name FROM logins WHERE school='$school2' AND level='2'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
         if($level!=1 && $school!="Test's School")
	 {
            SendMail($row[email],$row[name],"dvelder@nsaahome.org","Deb Velder","New Proposal for Change in NSAA Regulations Submitted",$text,$html,$attm);
	 }
	 else if($school=="Test's School")
            SendMail($row[email],$row[name],"agaffigan@gazelleincorporated.com","Ann Gaffigan","New Proposal for Change in NSAA Regulations Submitted",$text,$html,$attm);

         echo $end_html;
         exit();
      } 
      else if($error!=0)	//errors but tried to submit as final: show alert window
      {
?>
<script language="javascript">
alert("You have not completed all the required fields.  Please correct these errors and then submit your proposal again.\r\n\r\nThank You!");
</script>
<?php 
      }
}

if($givenid && $givenid!="")
{
   $sql="SELECT * FROM proposals WHERE id='$givenid'";
   if($level!=1)
   {
      $sql.=" AND locked!='x'";
   }
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)	//locked
   {
      echo "This proposal has been locked.  You may not make any more changes to this proposal.  Thank You!";
      echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
      exit();
   }

      $name=$row[name];
      $class=$row['class'];
      $yearbook=$row[yearbook];
      $article=$row[article];
      $section=$row[section];
      $ybpage=$row[ybpage];
      $actman=$row[actman];
      $actman2=$row[actman2];
      $ampage=$row[ampage];
      $exAtitle=$row[exAtitle];
      $exAfile=$row[exAfile];
      $exBtitle=$row[exBtitle];
      $exBfile=$row[exBfile];  
      $exCtitle=$row[exCtitle];
      $exCfile=$row[exCfile];  
      $exDtitle=$row[exDtitle];
      $exDfile=$row[exDfile];  
      $exEtitle=$row[exEtitle];
      $exEfile=$row[exEfile];  
      $current=$row[current];
      $current=ereg_replace("<br>","\r\n",$current);
      $changed=$row[changed];
      $changed=ereg_replace("<br>","\r\n",$changed);
      $costanal=$row[costanal];
      $costanal=ereg_replace("<br>","\r\n",$costanal);
      $rationale=$row[rationale];
      $rationale=ereg_replace("<br>","\r\n",$rationale);
      $type=$row[type];
      $travel=$row[travel];
      $instruction=$row[instruction];
}

echo "<br>";
echo "<form name=proposalform method=post action=\"proposal.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"type\" value=\"$type\">";
echo "<input type=hidden name=givenid value=\"$givenid\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<table class='nine' cellspacing=0 cellpadding=7 style=\"width:750px;\"><caption><b><u>";
         if($type=="caucus") echo "CLASS CAUCUS";
         else echo "LEGISLATIVE";
echo "</u> PROPOSAL FOR CHANGE IN NSAA REGULATIONS</b></caption>";
echo "<tr align=left><td colspan=2>";
//get AD name for this school & nsaa district
$sql="SELECT name FROM logins WHERE school='$school2' AND level=2";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$ADname=$row[0];
if(trim($ADname)=="")	//check Act Dir
{
   $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Activities Director'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $ADname=$row[0];
}
$sql="SELECT nsaadist FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$nsaadist=$row[0];
echo "<br><font style=\"color:red\"><b>Proposal Generated by </b>$ADname, $school<b>, School Access Code, District</b> $nsaadist</font></td></tr>";
echo "<tr align=left><td colspan=2><p>";
echo "Follow this form to propose change in NSAA regulations at district meetings.  <b>Proposals must be submitted no later than November 1</b>.  Complete this form in its entirety.  You may save your work and come back later by clicking on the \"Save & Keep Editing\" button at the bottom of this screen.  When your proposal is completed, hit \"Submit Proposal\" at which time the form will be transmitted electronically to the NSAA for distribution to member schools.  Proposals <font style=\"color:red\"><u>must</u></font> explain the financial impact the proposal will have to schools and/or the NSAA if implemented.</p></td></tr>";
echo "<tr align=center><th colspan=2>-- ALL FIELDS ARE REQUIRED --</th></tr>";
if($error==1)
{
   echo "<tr align=center><td colspan=2><div class='error'>You have not completed all the fields in this form.  Please complete the fields noted with a (!).</div></td></tr>";
}
echo "<tr align=left><td colspan=2><b>This proposal is submitted by:</b></td></tr>";
echo "<tr align=left><td colspan=2>";
if($errname==1) echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>";
echo "NAME:&nbsp;&nbsp;<input type=text size=50 name=name value='$name'></td></tr>";
echo "<tr align=left><td colspan=2>SCHOOL:&nbsp;&nbsp;$school</td></tr>";
echo "<tr align=left><td colspan=2>";
if($type=="caucus")
{
   if($errclass==1) echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>&nbsp;&nbsp;";
   echo "<font style=\"color:red;\">CLASS:&nbsp;&nbsp;</font><input type=text size=3 name=\"class\" value=\"$class\"> (You may only submit a proposal in the class in which your school represents in that activity.)";
}
else
{
   echo "<font style=\"color:red\"><b>NSAA DISTRICT:&nbsp;&nbsp;</b>$nsaadist</font>";
   echo "&nbsp;(You may only submit a proposal in the NSAA district that your school resides in.)</td></tr>";
}
echo "<tr align=left><td colspan=2><b>The proposal deals with:</b> (you must complete the Yearbook OR Activities Manual fields)</td></tr>";
echo "<tr align=left><td colspan=2>";
if($erryearbook==1)	//yearbook checked but not all info filled out
{
   echo "<font style=\"color:red;font-size:9pt;\"><b>(!) If you check \"Yearbook\", you must complete the Article, Section and Page fields.</b></font><br>";
}
else if($erryearbook==2)  //yearbook not checked but other fields filled out
{
   echo "<font style=\"color:red;font-size:9pt;\"><b>(!) You completed some but not all of the Yearbook fields.  If your proposal deals with the Yearbook, please check \"Yearbook\" and complete the Article, Section and Page fields.  Otherwise, uncheck \"Yearbook\" and remove anything you've entered in the Article, Section, or Page fields.</b></font><br>";
}
echo "<input type=checkbox name=yearbook value='x'";
if($yearbook=='x') echo " checked";
echo ">Yearbook:&nbsp;&nbsp;";
echo "Article&nbsp;<input type=text size=15 name=article value='$article'>&nbsp;&nbsp;";
echo "Section&nbsp;<input type=text size=15 name=section value='$section'>&nbsp;&nbsp;";
echo "Page&nbsp;<input type=text size=15 name=ybpage value='$ybpage'></td></tr>";
echo "<tr align=left><td colspan=2>";
if($erractman==1)	//act man checked but not all info filled out
{
   echo "<font style=\"color:red;font-size:9pt;\"><b>(!) If you check \"Activities Manual\", you must complete BOTH text fields, for the Activities Manual and the Page, below.</b></font><br>";
}
else if($erractman==2) //actman not checked but other fields filled out
{
   echo "<font style=\"color:red;font-size:9pt;\"><b>(!) You completed some but not all of the Activities Manual fields.  If your proposal deals with the Activites Manual, please check \"Activites Manual\" and then complete the 2 text fields for which Activities Manual and which Page.  Otherwise, uncheck \"Activities Manual\" and remove anything you've entered in the text fields.</b></font><br>";
}
echo "AND/OR<br>";
echo "<input type=checkbox name=actman value='x'";
if($actman=='x') echo " checked";
echo ">Activities Manual&nbsp;";
echo "<input type=text name=actman2 value='$actman2' size=40>&nbsp;&nbsp;";
echo "Page&nbsp;<input type=text size=15 name=ampage value='$ampage'></td></tr>";

//Upload Exhibits - Hide this on Caucus Proposals - AS OF 8/14/12, HIDE COMPLETELY
/*
if($type!='caucus')
{
   echo "<tr align=left><td colspan=2><b>Exhibit Upload:</b> <font style=\"color:blue\"><b>Please read these instructions!!</b></font><br>";
   echo "You may upload up to <b><u>5</u></b> \"exhibits\", images or other documents that are pertinent to your proposal but are impossible to portray using the text boxes provided below.  Please indicate the location of your exhibit files below and <u>give each exhibit a descriptive title</u>.  They will be uploaded to your proposal when you click \"Save & Keep Editing\" or \"Submit Proposal\" below.  Make sure to refer to the exhibits in your text below as Exhibit A, Exhibit B, etc.</td></tr>";

   echo "<tr align=left><td colspan=2><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#d0d0d0 1px solid;\">";
   for($i=0;$i<count($exhibits);$i++)
   {
      $letter=$exhibits[$i];
      $title="ex".$letter."title";
      $file="ex".$letter."file";
      $delete="ex".$letter."delete";
      echo "<tr align=left valign=top><td><b>Exhibit $letter:</b></td>";
      echo "<td>Title of Exhibit:&nbsp;<input type=text class=tiny size=40 name=\"$title\" value=\"".$$title."\"></td><td>";
      if($$file && $$file!='')
      {
         echo "<a target=new href=\"attachments/".$$file."\" class=small>Preview Current Exhibit $letter</a><br>";
         echo "Upload a New Exhibit $letter: <input type=file name=\"$file\"><br>";
         echo "OR <input type=checkbox name=\"$delete\" value='x'>Check here to DELETE Exhibit $letter";
      }
      else
      {
         echo "Choose File:&nbsp;<input type=file name=\"$file\">";
      }
      echo "</td></tr>";
   }
   echo "</table></td></tr>";
}//END IF NOT CAUCUS, SHOW EXHIBITS
*/

echo "<tr align=center><td colspan=2>";
if($errcurrent==1)
{
   echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>";
}
echo "<br><h3>The section/paragraph/sentence indicates what is to be added/deleted/changed to the current Bylaw/Approved Ruling:</h3>";
echo "<p><font style=\"color:blue\">[Indicate ADDITIONS in all capital letters.  Indicate (changes/deletions) in parenthesis.]</font></p>";
echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=current','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Insert a Table</a></td></tr>";
echo "<tr valign=top align=center><td>";
//check for tables inserted and show links to edit them
if(ereg("\[Table #",$current))    //table(s) inserted in text
{
   $text=split("\[Table #",$current);
   for($i=0;$i<count($text);$i++)
   {
      //$text[$i]=substr($text[$i],2,strlen($text[$i]));
      $pos=strpos($text[$i],"]");
      $tablenum=substr($text[$i],0,$pos+1);
      if(ereg("[0-9]]",$tablenum))
      {
         $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	 echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=current&tableid=$tablenum','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Edit Table #$tablenum</a><br>";
      }	
   }
}
echo "</td><td><textarea name=current rows=10 cols=70>$current</textarea></td></tr>";
if($type=='caucus')
{
echo "<tr align=center><td colspan=2>";
if($errchanged==1)
{
   echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>";
}
echo "<br><h3>The section/paragraph/sentence that needs to be added/deleted/changed would read as follows:</h3>";
echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=changed','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Insert a Table</a></td></tr>";
echo "<tr valign=top align=center><td>";
//check for tables inserted and show links to edit them
if(ereg("\[Table #",$changed))    //table(s) inserted in text
{
   $text=split("\[Table #",$changed);
   for($i=0;$i<count($text);$i++)
   {
      //$text[$i]=substr($text[$i],2,strlen($text[$i]));
      $pos=strpos($text[$i],"]");
      $tablenum=substr($text[$i],0,$pos+1);
      if(ereg("[0-9]]",$tablenum))
      {
         $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	 echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=changed&tableid=$tablenum','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Edit Table #$tablenum</a><br>";
      }
   }
}
echo "</td><td><textarea name=changed rows=10 cols=70>$changed</textarea></td></tr>";
}//end if CAUCUS
else
{
   echo "<tr align=left><td colspan=2><br>";
   if($errtravel==1)
   {
      echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>&nbsp;";
   }
   echo "<b>Will this proposal increase travel for the participating schools?&nbsp;&nbsp;</b>";
   echo "<input type=radio name=\"travel\" value=\"Yes\"";
   if($travel=="Yes") echo " checked";
   echo ">Yes&nbsp;&nbsp;&nbsp;<input type=radio name=\"travel\" value=\"No\"";
   if($travel=="No") echo " checked";
   echo ">No</td></tr>";
   echo "<tr align=left><td colspan=2>";
   if($errinstruction==1)
   {
      echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>&nbsp;";
   }
   echo "<b>Will this proposal impact a student or coach's loss of instruction time?&nbsp;&nbsp;</b>";
   echo "<input type=radio name=\"instruction\" value=\"Yes\"";
   if($instruction=="Yes") echo " checked";
   echo ">Yes&nbsp;&nbsp;&nbsp;<input type=radio name=\"instruction\" value=\"No\"";
   if($instruction=="No") echo " checked";
   echo ">No</td></tr>";
}
echo "<tr align=center><td colspan=2>";
if($errcostanal==1)
{
   echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>";
}
echo "<br><h3>Cost Analysis of Proposal: <font style=\"color:red\">Financial Impact of Proposal Required.";
if($type!='caucus') echo "<br>Indicate impact to NSAA and/or the local school district(s).";
echo "</font></h3>";
echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=costanal','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Insert a Table</a></td></tr>";
echo "<tr valign=top align=center><td>";
//check for tables inserted and show links to edit them
if(ereg("\[Table #",$costanal))    //table(s) inserted in text
{
   $text=split("\[Table #",$costanal);
   for($i=0;$i<count($text);$i++)
   {
      //$text[$i]=substr($text[$i],2,strlen($text[$i]));
      $pos=strpos($text[$i],"]");
      $tablenum=substr($text[$i],0,$pos+1);
      if(ereg("[0-9]]",$tablenum))
      {
         $tablenum=substr($tablenum,0,strlen($tablenum)-1);
	 echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=costanal&tableid=$tablenum','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Edit Table #$tablenum</a><br>";
      }
   }
}
echo "</td><td><textarea name=costanal rows=10 cols=70>$costanal</textarea></td></tr>";
echo "<tr align=center><td colspan=2>";
if($errrationale==1)
{
   echo "<font style=\"color:red;font-size:10pt;\"><b>(!)</b></font>";
}
echo "<br><h3>Rationale for the proposed change:</h3>";
echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=rationale','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Insert a Table</a></td></tr>";
echo "<tr valign=top align=center><td>";
//check for tables inserted and show links to edit them
if(ereg("\[Table #",$rationale))    //table(s) inserted in text
{
   $text=split("\[Table #",$rationale);
   for($i=0;$i<count($text);$i++)
   {
      //$text[$i]=substr($text[$i],2,strlen($text[$i]));
      $pos=strpos($text[$i],"]");
      $tablenum=substr($text[$i],0,$pos+1);
      if(ereg("[0-9]]",$tablenum))
      {
         $tablenum=substr($tablenum,0,strlen($tablenum)-1);
         echo "<a href=\"#\" onclick=\"window.open('inserttable.php?session=$session&field=rationale&tableid=$tablenum','insert_table','width=500,height=500,menubar=no,resizable=yes,scrollbars=yes,location=no');\">Edit Table #$tablenum</a><br>";
      }
   }
}
echo "</td><td><textarea name=rationale rows=10 cols=70>$rationale</textarea></td></tr>";
echo "<tr align=center><th colspan=2>-- ALL FIELDS ARE REQUIRED --</th></tr>";
echo "<tr align=center><td colspan=2>";
echo "<b><font style=\"color:red\">";
echo "<p>If you would like to save this proposal and come back to work on it later, please click:</p>";
echo "<input type=submit name=submit class='fancybutton' value=\"Save & Keep Editing\"><br><br><br>";
echo "<p>To submit this as your <u>FINAL PROPOSAL</u>, please make sure all the information you have entered is correct and then click:</p><input class='fancybutton2' type=submit name=submit onclick=\"return confirm('Are you sure you want to submit this proposal?  You will not be able to make changes to this proposal once you have submitted it.');\" value=\"Submit Proposal\"></td></tr>";

echo "</table></form>";

echo $end_html;

function GetTable($tableid)
{
   //get table from proposaltables
   $sql="SELECT * FROM proposaltables WHERE id='$tableid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $entries=split("<entry>",$row[entries]);
   $boldrow=split("/",$row[boldrow]);
   $boldcol=split("/",$row[boldcol]);
   $table="<br><br><table";
   if($row[gridlines]=='x') 
   $table.=" border=1 bordercolor=#000000";
   $table.=" cellspacing=1 cellpadding=2>";
   if($row[title]!="")
   $table.="<caption class=small><b>$row[title]</b></caption>";
   $ix=0;
   for($j=0;$j<$row[rows];$j++)
   {
      $table.="<tr align=left>";
      for($k=0;$k<$row[cols];$k++)
      {
         $table.="<td>";
         if($boldrow[$j]=='x' || $boldcol[$k]=='x')
            $table.="<b>";
         $table.=$entries[$ix];
 	 if($boldrow[$j]=='x' || $boldcol[$k]=='x')
	    $table.="</b>";
	 $table.="</td>";
	 $ix++;
      }
      $table.="</tr>";
   }
   $table.="</table><br>";
   return $table;
}
?>
