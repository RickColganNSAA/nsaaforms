<?php
/*********************************
cupspreadsheet.php
NSAA can preview and print public link
for website showing full cup standings,
spreadsheet style,
and also edit the header for
these pages
Author: Ann Gaffigan
Created: 9/10/15
*********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
/* $db_name='nsaascores20172018';
$db=mysql_connect($db_host,$db_user,$db_pass); */
/****** SAVE SETTINGS ******/


/****** GENERATE A FILE FOR A CLASS ******/
if($class && $class!='')
{
   if($edit==1)		/****** EDIT HEADER FOR A CLASS ******/
   {
      if($saveheader)
      {
         $sql="UPDATE cupclasssettings SET ssheadertext='".addslashes($headertext)."' WHERE class='$class'";
         $result=mysql_query($sql);
         echo mysql_error();
      }
      echo $init_htmlNEWTINYMCE.GetHeader($session);
?>
<script type="text/javascript">
tinyMCE.init({
        mode : 'textareas',
        toolbar: [
                "undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright"
        ],
        plugins: [ "link image" ]
        });
</script>
<?php

      echo "<p style=\"text-align:left;\"><a href=\"cuppublic.php?session=$session\">&larr; Return to Manage NSAA Cup Public Links</a></p>";

      echo "<h1>NSAA Cup: Edit Header for CLASS $class Public Spreadsheet Link</h1>";

      echo "<div style=\"width:100%;max-width:1000px;text-align:left;\">";

      echo "<form method='post' action='cupspreadsheet.php'>	
		<input type=hidden name='session' value='$session'>
		<input type=hidden name='class' value='$class'>
		<input type=hidden name='edit' value='$edit'>";

      $header=GetCupHeader($class,TRUE);
      if($saveheader) echo "<div class='alert'>Your changes have been saved. <a href=\"cupspreadsheet.php?session=$session&class=$class\" target=\"_blank\">Preview Class $class Cup Standings Spreadsheet</a></div>";
      echo "<p>Use the text box below to create and manage the heading that will display on the <a href=\"cupspreadsheet.php?session=$session&class=$class\" target=\"_blank\">Class $class Cup Standings Spreadsheet</a> on the NSAA website.</p>";
      echo "<textarea id=\"headertext\"  name=\"headertext\" style=\"width:100%;height:200px;\">$header</textarea>";
      echo "<p><input type='submit' name='saveheader' value='Save Header' class='fancybutton'></p>";

      echo "</form></div>";
      echo $end_html;
      exit();
   } //END IF EDIT
   else /****** GENERATE A FILE FOR A CLASS ******/
   {
      $html="<table style=\"width:100%;\"><tr align=center><td><div style=\"max-width:1200px;\">";
      $html.=GetCupHeader($class,TRUE)."</div><div style=\"margin-bottom:10px;clear:both;\"></div>";	//TRUE indicates this is the Spreadsheet version

      //We will show list ORDERED BY allpoints by DEFAULT, links to SORT BY Boys, Girls
		//COLUMN HEADERS
      $colheader="<tr align='center'><td><b>School</b></td><td><b>Reg Total Pts</b></td>";
	 //GET ACTIVITIES
	 $sql2="SELECT * FROM cupactivities ORDER BY orderby";
	 $result2=mysql_query($sql2);
	 $abbrevs=array(); $i=0;
	 while($row2=mysql_fetch_array($result2))
	 {
	    $colheader.="<td><b>$row2[abbreviation]</b></td>";
	    $cupacts[$i]=$row2[activity]; $abbrevs[$i]=$row2[abbreviation]; $i++;
	 }
      $colheader1=$colheader."<td><a class=\"small\" onClick=\"document.getElementById('byGirls').style.display='block'; document.getElementById('byBoys').style.display='none'; document.getElementById('byAll').style.display='none';\" href=\"#\">GIRLS CUP Pts</a></td>
	<td><a class=\"small\" onClick=\"document.getElementById('byGirls').style.display='none'; document.getElementById('byBoys').style.display='block'; document.getElementById('byAll').style.display='none';\" href=\"#\">BOYS CUP Pts</a></td>
	<th bgcolor='yellow'>All-School CUP Pts</th></tr>";
      $colheader2=$colheader."<th bgcolor='yellow'>GIRLS CUP Pts</th>
        <td><a class=\"small\" onClick=\"document.getElementById('byGirls').style.display='none'; document.getElementById('byBoys').style.display='block'; document.getElementById('byAll').style.display='none';\" href=\"#\">BOYS CUP Pts</a></td>
        <td><a class=\"small\" onClick=\"document.getElementById('byGirls').style.display='none'; document.getElementById('byBoys').style.display='none'; document.getElementById('byAll').style.display='block';\" href=\"#\">All-School CUP Pts</a></td></tr>";
      $colheader3=$colheader."<td><a class=\"small\" onClick=\"document.getElementById('byGirls').style.display='block'; document.getElementById('byBoys').style.display='none'; document.getElementById('byAll').style.display='none';\" href=\"#\">GIRLS CUP Pts</a></td>
        <th bgcolor='yellow'>BOYS CUP Pts</th>
        <td><a class=\"small\" onClick=\"document.getElementById('byGirls').style.display='none'; document.getElementById('byBoys').style.display='none'; document.getElementById('byAll').style.display='block';\" href=\"#\">All-School CUP Pts</a></td></tr>";
		//SORTED BY TOTAL (ALL) POINTS:
      $sql="SELECT t1.*,t2.school FROM cupschools AS t1, headers AS t2 WHERE t1.schoolid=t2.id AND t1.cupclass='$class' AND t1.allpoints>0 ORDER BY t1.allpoints DESC,t2.school ASC";
      $result=mysql_query($sql);
      $html1="<table id=\"byAll\" cellspacing=0 cellpadding=3 frame='all' rules='all' style=\"border:#808080 1px solid;\">".$colheader1;
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         $html1.="<tr align='center'";
         if($ix%2==0) $html1.=" bgcolor='#f0f0f0'";
	 $html1.="><td align='left'";
	 if($row['gender']=="girls") $html1.=" bgcolor='#ff99cc'";
	 else if($row['gender']=="boys") $html1.=" bgcolor='#00ccff'";
	 $html1.=">$row[school]</td>";
		//REGISTRATION
	 $sql2="SELECT SUM(points) FROM cuppoints WHERE class='reg' AND schoolid='$row[schoolid]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $html1.="<td>".$row2[0]."</td>";
		//POINTS FOR EACH ACTIVITY:
	 for($i=0;$i<count($cupacts);$i++)
	 {
	    $sql2="SELECT points,ignorepts FROM cuppoints WHERE activity='$cupacts[$i]' AND class!='reg' AND schoolid='$row[schoolid]'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    if($row2[ignorepts]=='x') $html1.="<td><strike>$row2[0]</strike></td>";
	    else $html1.="<td>".$row2[0]."</td>";
	 }
	 $html1.="<td>$row[girlspoints]</td><td>$row[boyspoints]</td><td>$row[allpoints]</td></tr>";
	 $ix++;
      }
      $html1.="</table>";
                //SORTED BY GIRLS POINTS:
      $sql="SELECT t1.*,t2.school FROM cupschools AS t1, headers AS t2 WHERE t1.schoolid=t2.id AND t1.cupclass='$class' AND t1.allpoints>0 ORDER BY t1.girlspoints DESC,t2.school ASC";
      $result=mysql_query($sql);
      $html1.="<table id=\"byGirls\" cellspacing=0 cellpadding=3 frame='all' rules='all' style=\"display:none;border:#808080 1px solid;\">".$colheader2;
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         $html1.="<tr align='center'";
         if($ix%2==0) $html1.=" bgcolor='#f0f0f0'";
         $html1.="><td align='left'";
         if($row['gender']=="girls") $html1.=" bgcolor='#ff99cc'";
         else if($row['gender']=="boys") $html1.=" bgcolor='#00ccff'";
         $html1.=">$row[school]</td>";
                //REGISTRATION
         $sql2="SELECT SUM(points) FROM cuppoints WHERE class='reg' AND schoolid='$row[schoolid]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $html1.="<td>".$row2[0]."</td>";
                //POINTS FOR EACH ACTIVITY:
         for($i=0;$i<count($cupacts);$i++)
         {
            $sql2="SELECT points FROM cuppoints WHERE activity='$cupacts[$i]' AND class!='reg' AND schoolid='$row[schoolid]'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $html1.="<td>".$row2[0]."</td>";
         }
         $html1.="<td>$row[girlspoints]</td><td>$row[boyspoints]</td><td>$row[allpoints]</td></tr>";
         $ix++;
      }
      $html1.="</table>";
                //SORTED BY BOYS POINTS:
      $sql="SELECT t1.*,t2.school FROM cupschools AS t1, headers AS t2 WHERE t1.schoolid=t2.id AND t1.cupclass='$class' AND t1.allpoints>0 ORDER BY t1.boyspoints DESC,t2.school ASC";
      $result=mysql_query($sql);
      $html1.="<table id=\"byBoys\" cellspacing=0 cellpadding=3 frame='all' rules='all' style=\"display:none;border:#808080 1px solid;\">".$colheader3;
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         $html1.="<tr align='center'";
         if($ix%2==0) $html1.=" bgcolor='#f0f0f0'";
         $html1.="><td align='left'";
         if($row['gender']=="girls") $html1.=" bgcolor='#ff99cc'";
         else if($row['gender']=="boys") $html1.=" bgcolor='#00ccff'";
         $html1.=">$row[school]</td>";
                //REGISTRATION
         $sql2="SELECT SUM(points) FROM cuppoints WHERE class='reg' AND schoolid='$row[schoolid]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $html1.="<td>".$row2[0]."</td>";
                //POINTS FOR EACH ACTIVITY:
         for($i=0;$i<count($cupacts);$i++)
         {
            $sql2="SELECT points FROM cuppoints WHERE activity='$cupacts[$i]' AND class!='reg' AND schoolid='$row[schoolid]'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $html1.="<td>".$row2[0]."</td>";
         }
         $html1.="<td>$row[girlspoints]</td><td>$row[boyspoints]</td><td>$row[allpoints]</td></tr>";
         $ix++;
      }
      $html1.="</table>";
   
      $html.=$html1.$end_html;

      $partialhtml=$html;
      $html=$init_html.$html;

      if(!$publishhtml && !$unpublish)
      {
        echo "<form method=\"post\" action=\"cupspreadsheet.php\">
		<input type=hidden name=\"session\" value=\"$session\">
		<input type=hidden name=\"class\" value=\"$class\">";
	echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";

	echo "<div class=\"alert\" style=\"width:800px;text-align:center;\"><p>To publish the following page to the public website, please click the \"Publish\" button.&nbsp;&nbsp;&nbsp;";
	echo "<input type=submit class=\"fancybutton\" name=\"publishhtml\" value=\"Publish\"></p>";
	echo "<p>To RESET the public version of this page to say \"Information not available,\" click \"Unpublish.\"&nbsp;&nbsp;&nbsp;<input type=submit class=\"fancybutton\" name=\"unpublish\" value=\"Unpublish\"></p></div></form>";

        echo $partialhtml;
      }
      else	//WRITE THE FILE 
      {
         if($unpublish) $html=$init_html."<table width='100%'><tr align=center><td><br><h3>Information not available.</h3>".$end_html;
	 $filename="Class".$class."CupSpreadsheet.html";
  	 if(!$open=fopen(citgf_fopen("../cup/$filename"),"w")) { echo "<div class='error'>ERROR: Could not open $filename</div>"; exit(); }
         if(!fwrite($open,$html)) { echo "<div class='error'>ERROR: Could not write to $filename</div>"; exit(); }
         fclose($open); 
 citgf_makepublic("../cup/$filename");

         echo $init_html."<table style=\"width:100%;\"><tr align=center><td>";
	 echo "<br><br><p style=\"text-align:center;\">The spreadsheet has been published for public viewing. Click the link below to view it. Copy the link below to link to it from the NSAA website.<br><br><br><a href=\"/cup/$filename\">https://secure.nsaahome.org/cup/$filename</a></p>";

	 echo $end_html;
      }
      exit();
   }
} //END IF $class
?>
