<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
?>
<script language="javascript">
window.close();
</script>
<?php
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
echo "<table width=100%><tr align=center><td>";

if($preview)	//temporarily save table & show preview of table
{
   //save table as "pending"
   $entries="";
   for($i=0;$i<$rows;$i++)
   {
      for($j=0;$j<$cols;$j++)
      {
	 $curentry=addslashes($entry[$i][$j]);
	 $entries.=$curentry."<entry>";
      }
   }
   $entries=substr($entries,0,strlen($entries)-7);
   $boldrows=""; $boldcols="";
   for($i=0;$i<$rows;$i++)
   {
      if($boldrow[$i]=='x') $boldrows.="x";
      $boldrows.="/";
   }
   for($i=0;$i<$cols;$i++)
   {
      if($boldcol[$i]=='x') $boldcols.="x";
      $boldcols.="/";
   }
   $boldrows=substr($boldrows,0,strlen($boldrows)-1);
   $boldcols=substr($boldcols,0,strlen($boldcols)-1);
   $title2=addslashes($title);
   $sql="SELECT id FROM proposaltables WHERE proposalid='$session' AND pending='x'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0 && !$tableid)	//INSERT
   {
      $sql2="INSERT INTO proposaltables (proposalid,rows,cols,boldrows,boldcols,gridlines,title,entries,pending) VALUES ('$session','$rows','$cols','$boldrows','$boldcols','$gridlines','$title2','$entries','x')";
   }
   else if(!$tableid)				//UPDATE
   {
      $sql2="UPDATE proposaltables SET rows='$rows',cols='$cols',boldrows='$boldrows',boldcols='$boldcols',gridlines='$gridlines',title='$title2',entries='$entries' WHERE proposalid='$session' AND pending='x'";
   }
   else
   {
      $sql2="UPDATE proposaltables SET rows='$rows',cols='$cols',boldrows='$boldrows',boldcols='$boldcols',gridlines='$gridlines',title='$title2',entries='$entries',proposalid='$session',pending='x' WHERE id='$tableid'";
   }
   //echo $sql2;
   $result2=mysql_query($sql2);

   echo "<table><caption><b>Preview of Your Table:<hr></b></caption>";
   echo "<tr align=center><td>";
   //their table:
   echo "<table";
   if($gridlines=='x') echo " border=1 bordercolor=#000000";
   echo " cellspacing=1 cellpadding=2>";
   if($title!="")
   {
      echo "<caption class=small><b>$title</b>";
      if($gridlines!='x') echo "<hr>";
      echo "</caption>";
   }
   for($i=0;$i<$rows;$i++)
   {
      echo "<tr align=left>";
      for($j=0;$j<$cols;$j++)
      {
	 echo "<td>";
	 if($boldrow[$i]=='x' || $boldcol[$j]=='x') echo "<b>";
	 echo $entry[$i][$j];
	 if($boldrow[$i]=='x' || $boldcol[$j]=='x') echo "<b>";
	 echo "</td>";
      }
      echo "</tr>";
   }
   echo "</table>";
   echo "</td></tr>";
   echo "<tr align=center><td>";
   echo "<form method=post action=\"inserttable.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=field value=\"$field\">";
   echo "<input type=hidden name=tableid value=$tableid>";
   echo "<input type=submit name=edit value=\"Edit Table\">&nbsp;&nbsp;";
   echo "<input type=submit name=insert value=\"Insert Table\">";
   echo "</form></td></tr>";
   echo $end_html;
   exit();
}
else if($edit || ($tableid && !$insert))
{
   //get this table's info from database
   if($tableid)
      $sql="SELECT * FROM proposaltables WHERE id='$tableid'";
   else
      $sql="SELECT * FROM proposaltables WHERE proposalid='$session' AND pending='x'";
   $result=mysql_query($sql);
   //echo $sql;
   $row=mysql_fetch_array($result);
   $rows=$row[rows]; $cols=$row[cols]; $gridlines=$row[gridlines];
   $boldrows=$row[boldrows]; $boldcols=$row[boldcols];
   $title=$row[title];
   $temp=split("<entry>",$row[entries]);
   $currow=0;
   for($i=0;$i<count($temp);$i++)
   {
      if($i%$cols==0)
	 $curcol=0;
      $entry[$currow][$curcol]=$temp[$i];
      $curcol++;
      if(($i+1)%$cols==0)
	 $currow++;
   }
}
else if($insert)
{
   //get id for this row in proposaltables table
   $sql="SELECT id FROM proposaltables WHERE proposalid='$session' AND pending='x'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $tableid=$row[0];
   //set as NON-pending in database
   $sql="UPDATE proposaltables SET pending='' WHERE proposalid='$session' AND pending='x'";
   //echo $sql;
   $result=mysql_query($sql);

if($field=="current")
{
?>
<script language="javascript">
window.opener.document.forms.proposalform.current.value += "<?php echo "[Table #$tableid]"; ?>";
</script>
<?php
}
else if($field=="changed")
{
?>
<script language="javascript">
window.opener.document.forms.proposalform.changed.value += "<?php echo "[Table #$tableid]"; ?>";
</script>
<?php
}
else if($field=="costanal")
{
?>
<script language="javascript">
window.opener.document.forms.proposalform.costanal.value += "<?php echo "[Table #$tableid]"; ?>";
</script>
<?php
}
else if($field=="rationale")
{
?>
<script language="javascript">
window.opener.document.forms.proposalform.rationale.value += "<?php echo "[Table #$tableid]"; ?>";
</script>
<?php
}
else if($field=="pros")
{
?>
<script language="javascript">
window.opener.document.getElementById('pros').value += "<?php echo "[Table #$tableid]".GetTable($tableid); ?>";
</script>
<?php
}
else if($field=="cons")
{
?>
<script language="javascript">
window.opener.document.forms.proposalform.cons.value += "<?php echo "[Table #$tableid]"; ?>";
</script>
<?php
}
?>
<script language="javascript">
//window.close();
</script>
<?php
   exit();
}

echo "<form method=post action=\"inserttable.php\" name=tableform>";
echo "<input type=hidden name=session value=\"$session\">";
//field is which text box we're inserting a table into on proposal form
echo "<input type=hidden name=field value=\"$field\">";
//tableid is sent if EDITING an existing table already saved in the proposal
echo "<input type=hidden name=tableid value=$tableid>";
echo "<table width=95%>";
echo "<caption><b>Insert a Table:</b><hr></caption>";
echo "<tr align=left><td><b>Indicate the dimensions of your table:</b></td></tr>";
echo "<tr align=left><td><b>Rows:&nbsp;<input type=text name=rows class=tiny size=4 value=\"$rows\">&nbsp;&nbsp;";
echo "Columns:&nbsp;<input type=text name=cols class=tiny size=4 value=\"$cols\"></b>&nbsp;&nbsp;";
echo "<input type=submit name=go value=\"Go\"></td></tr>";

if($rows && $cols)
{
   echo "<tr align=left><td><b>Enter a title for your table (Optional):&nbsp;</b>";
   echo "<input type=text class=tiny name=title value=\"$title\" size=30></td></tr>";
   echo "<tr align=left><td><b>Enter your table data:<br></b><i>If you would like to make the font bold for any of the rows, check the \"Bold\" box at the beginning of the row.  Likewise, to bold a column, check the \"Bold\" box at the top of that column.</i></td></tr>";
   echo "<tr align=center><td><table>";
   echo "<tr align=center><th>&nbsp;</th>";
   $boldrow=split("/",$boldrows);
   $boldcol=split("/",$boldcols);
   for($j=0;$j<$cols;$j++)
   {
      echo "<th align=center class=small><input type=checkbox name=\"boldcol[$j]\" value='x'";
      if($boldcol[$j]=='x') echo " checked";
      echo ">Bold</th>";
   }
   echo "</tr>";
   for($i=0;$i<$rows;$i++)
   {
      echo "<tr align=left><th align=right class=small><input type=checkbox name=\"boldrow[$i]\" value='x'";
      if($boldrow[$i]=='x') echo " checked";
      echo ">Bold</th>";
      for($j=0;$j<$cols;$j++)
      {
         echo "<td><input type=text size=10 class=tiny name=\"entry[$i][$j]\" value=\"".$entry[$i][$j]."\"></td>";
      }
      echo "</tr>";
   }
   echo "</table></td></tr>";
   echo "<tr align=left><td><input type=checkbox name=gridlines value='x'";
   if($gridlines=='x') echo " checked";
   echo ">&nbsp;Show gridlines separating the rows and columns</td></tr>";
   echo "<tr align=center><td><input type=submit name=preview value=\"Preview Table\"></td></tr>";
}

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
