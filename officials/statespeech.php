<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

if($delete=="spsuperior")
{
	//DELETE CURRENT STUDENTS IN spsuperior TABLE
	$sql="DELETE FROM spsuperior";
	$result=mysql_query($sql);
	header("Location:statespeech.php?session=$session");
}
if($upload)
{
   $uploadedfile=$_FILES['superiorfile']['tmp_name'];
   $uploaderror="";
   if(is_uploaded_file($uploadedfile))
   {
      if(!citgf_copy($uploadedfile,"superiorrecips.txt"))
         $uploaderror="Could not copy to superiorrecips.txt";
      else
      {
	 $open=fopen(citgf_fopen("superiorrecips.txt"),"r");
  	 $lines=file(getbucketurl("superiorrecips.txt"));
	 fclose($open);
	 $sql="DELETE FROM spsuperior";
	 $result=mysql_query($sql);
	 for($i=0;$i<count($lines);$i++)
	 {
	    $lines[$i]=ereg_replace("\"","",$lines[$i]);
	    $line=split("\t",$lines[$i]);
	    $students=trim(addslashes($line[0]));
	    $school=trim(addslashes($line[1]));
	    $event=trim(addslashes($line[2]));
	    if($school!="School")
	    {
	       $sql="INSERT INTO spsuperior (students,school,event) VALUES ('$students','$school','$event')";
	       $result=mysql_query($sql);
	    }
	 }
      }
   }
   else $uploaderror="No file indicated.";
   header("Location:statespeech.php?session=$session&uploaded=1&uploaderror=$uploaderror");
}

echo $init_html;
echo GetHeaderJ($session,"statespeech");

   echo "<br><br><table width=550><caption><b>State Speech: Main Menu<hr></b></caption>"; 
   echo "<tr align=center><td>";
   echo "<ul>";
   echo "<li><a href=\"../sp/distresults.php?session=$session&offadmin=1\" target=\"_blank\">View Submitted District Speech Results</a><br><br></li>";
   echo "<li><a href=\"spshuffle.php?session=$session\">View/Assign Judges & Contestants</a><br><br></li>";
   echo "<li><a href=\"sprooms.php?session=$session\">View/Edit Rounds & Rooms Information</a><br><br></li>";
   echo "<li class=bigger><b>JUDGES REPORTS:</b><ul>";
	echo "<li><a target=\"_blank\" href=\"spstatejudgesexport.php?session=$session\">ALL Rounds, TABLE Format</a></li>";
	echo "<li><a target=\"_blank\" href=\"spstatejudgesexport2.php?session=$session\">ALL Rounds, FOR LABELS</a></li>";
	echo "<li><a href=\"spfinaljudgesexport.php?session=$session\">FINAL Round Only, TEXT Format</a></li>";
	echo "<li><a href=\"spstatejudgesmileageexport.php?session=$session\">Judges Export for REIMBURSEMENT</a></li>";
   echo "</ul></li><br>";
   echo "<li class=bigger><b>STUDENT REPORTS:</b><ul>";
	echo "<li><a href=\"spstatestudentsexport.php?session=$session\">Participants & their Events for Each School (Excel Export, for LABELS)</a></li>";
   	echo "<li><a target=\"_blank\" href=\"spstatepartexport.php?session=$session\">EXCEL Participation Export (Student - School - Events)</a></li>";
	echo "<li><a target=\"_blank\" href=\"spstateentries.php?session=$session&exportuniqquals=1\">Number of Qualifiers by School (All Classes)</a></li>";
	echo "<li><form method=post action=\"statespeech.php\" enctype=\"multipart/form-data\"><input type=hidden name=\"session\" value=\"$session\"><p style='margin:3px;padding:3px;font-weight:normal;font-size:9pt;'><b>Upload Students Receiving \"Superior\" Certificate:</b><br>(Download the above Participation Export, remove students who scored <90, save as \"<b>TAB-DELIMITED (.TXT)</b>\" and upload below)</p>";
	if($uploaderror!='')
	   echo "<div class='error'>$uploaderror</div>";
	else if($uploaded)
	   echo "<div class='alert'>The file has been successfully uploaded!</div>";
	echo "<input type=file name=\"superiorfile\"> <input type=submit name=\"upload\" value=\"Upload\"></form>";
	$sql="SELECT * FROM spsuperior ORDER BY event,school";
	$result=mysql_query($sql);
        $csv="\"EVENT\",\"SCHOOL\",\"STUDENTS\"\r\n";
   	while($row=mysql_fetch_array($result))
    	{
	   $csv.="\"$row[event]\",\"$row[school]\",\"$row[students]\"\r\n";
	}
   	$open=fopen(citgf_fopen("/home/nsaahome/reports/spsuperior.csv"),"w");
   	fwrite($open,$csv);
   	fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spsuperior.csv");
	if(mysql_num_rows($result)>0)
	{
	   echo "<p><i>There are currently <a href=\"reports.php?session=$session&filename=spsuperior.csv\">".mysql_num_rows($result)." students</a> uploaded to receive a Superior certificate.</i></p>";
	   echo "<p><i>You can REMOVE these students and upload a new file by <a onClick=\"return confirm('Are you sure you want to delete all of these students from the Superior Certificate list?');\" href=\"statespeech.php?session=$session&delete=spsuperior\">Clicking Here</a>.</i></p>";
	}
	else
	   echo "<p><i>There are 0 students uploaded to receive a Superior certificate. The schools will not be able to generate State Speech certificates until you upload a list of students receiving this award.</i></p>";
	echo "</li>";
   echo "</ul></li>";
   echo "<li><a href=\"spstatecoachesexport.php?session=$session\">COACHES Export (School, Head Coach)</a></li><br>";
   echo "<li><a target=\"_blank\" href=\"spstateballotexport.php?session=$session\">BALLOT Export (Rounds 1 & 2)</a><br><br></li>";
   echo "<li><a target=\"_blank\" href=\"spstatefinalsexport.php?session=$session\">BALLOT Export (FINALS)</a><br><br></li>";
   echo "<li><a target=\"_blank\" href=\"spstatespeedballotexport.php?session=$session\">SPEED BALLOT Export (Rounds 1 & 2)</a><br><br></li>";
   echo "<li><a target=\"_blank\" href=\"spstatespeedfinalsexport.php?session=$session\">SPEED BALLOT Export (FINALS)</a><br><br></li>";
   echO "<li><a href=\"spstateentries.php?session=$session\">PRINTER'S Exports (State Entries/Room Assignments, Qualifiers by School)</a><br><br></li>";
   echO "<li><a href=\"spstateentries2.php?session=$session\">WEBSITE Exports (State Entries/Room Assignments)</a><br>This link will populate the links on the MAIN NSAA WEBSITE with the official room assignments. Do NOT click this link until you are ready for the assignments to be posted to the Speech Page on the NSAA website.<br><br></li>";
   echo "<li><a target=\"_blank\" href=\"spbinexport.php?session=$session\">BIN SHEET Export (School Names & Codes)</a><br><br></li>";
   echo "<li><a target=\"_blank\" href=\"sptabroomexport.php?session=$session\">TAB ROOM Export (Contestants & Schools)</a><br><br></li>";
   echo "</ul>";
   echo "</td></tr></table>";

echo $end_html;
?>
