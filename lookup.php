<?php
//3/8/07 (Ajax used)
//lookup.php: allows NSAA users only to look up passcodes, other info for school staff

require 'functions.php';
require_once('variables.php');
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/UserLookup.js"></script>
</head>
<?php
echo GetHeader($session);
$level=GetLevel($session);
if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','lookupform');">
<table width=100%><tr align=center><td>
<?php
echo "<form name=lookupform method=post action=\"lookup.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<a name=\"top\"><table cellspacing=2 cellpadding=2></a>";
echo "<tr align=center valign=top>";
echo "<td colspan=2><a href=\"passcodereport.php?session=$session\">Generate File of Passcodes for All Schools</a></td>";
echo "</tr><tr valign=top align=center><td class=nine>";
echo "<b>School:</b> <i>(start typing school/college name)</i></td><td align=left>";
echo "<input name=\"school\" value=\"$school\" id=\"school\" class=lookup type=text onkeyup=\"javascript:UserLookup.lookup(this.id, this.value, '', 'school');\" /><input type=submit name=lookup id=\"lookup\" value=\"Go\"><div id=\"schoolList\" class=\"list\"></div></td></tr>";
echo "<tr align=center><td colspan=2>";
//echo "<div id=\"schoolResults\" class=\"results\">";
if($school!='')
{
   $school=ereg_replace("`","'",$school);
   $school2=addslashes($school);
   $sql="SELECT * FROM headers WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $fullph=$row[phone];
   $phonebase=substr($fullph,4,8);
   $area=substr($fullph,0,3);
   if(!ereg("Public Schools",$school) && mysql_num_rows($result)>0)//regular  high school
   {
      echo "<table class=nine cellspacing=0 cellpadding=4>";
      echo "<tr align=center><td colspan=7><table cellspacing=0 cellpadding=3>";
      echo "<tr align=left><td width=200 align=center><a target=\"_blank\" href=\"directory.php?session=$session&school_ch=$school\" class=small >Edit $school's Directory</a></td>";
      echo "<td bgcolor=\"#E0E0E0\"><b>School:</b></td><td bgcolor=\"#E0E0E0\">$school</td></tr>";
      echo "<tr align=left valign=top><td align=center width=100 height=100 rowspan=10><img src=\"/images/$row[logo]\" width='150px'></td>";
      echo "<td><b>NSAA District:</b></td><td>$row[nsaadist]</td></tr>";
      echo "<tr valign=top align=left><td bgcolor=\"#E0E0E0\"><b>Address:</b></td><td bgcolor=\"#E0E0E0\">$row[address1]<br>$row[address2]</td></tr>";
      echo "<tr align=left><td><b>City, State Zip:</b></td><td>$row[city_state] $row[zip]</td></tr>";
      echo "<tr align=left><td bgcolor=\"#E0E0E0\"><b>Phone:</b></td><td bgcolor=\"#E0E0E0\">$row[phone]</td></tr>";
      echo "<tr align=left><td><b>Fax:</b></td><td>$row[fax]</td></tr>";
      if(!ereg("http",$row[website])) $url="http://".$row[website];
      else $url=$row[website];
      echo "<tr align=left><td bgcolor=\"#E0E0E0\"><b>Website:</b></td><td bgcolor=\"#E0E0E0\"><a target=\"_blank\" href=\"$url\">$row[website]</a></td></tr>";
      echo "<tr align=left><td><b>Colors:</b></td><td>";
      echo "<table cellspacing=3 border=1 bordercolor=\"#000000\"><tr align=center><td width=25 bgcolor=\"$row[color1]\">&nbsp;</td>";
      echo "<td width=25 bgcolor=\"$row[color2]\">&nbsp;</td>";
      if($row[color3]!='') 
         echo "<td width=25 bgcolor=\"$row[color3]\">&nbsp;</td>";
      echo "</tr></table></td></tr>";
      echo "<tr align=left valign=top><td bgcolor=\"#E0E0E0\"><b>Color Names:</b><br>(as shown in Directory)</td><td bgcolor=\"#E0E0E0\">$row[color_names]</td></tr>";
      echo "<tr align=left><td><b>Mascot:</b></td><td>$row[mascot]</td></tr>";
      echo "<tr align=left><td bgcolor=\"#E0E0E0\"><b>Conference:</b></td><td bgcolor=\"#E0E0E0\">$row[conference]</td></tr>";
      echo "</table></td></tr>"; 
      echo "<tr align=left><td align=center><font style=\"font-size:8pt\"><b>Main School<br>Contact</b></font></td><td><b>Name</b></td><td><b>Title</b></td><td><b>E-mail</b></td><td><b>Phone</b></td><td><b>Passcode</b></td></tr>";
      for($i=0;$i<count($staff);$i++)
      {
         $staff2[$i]=addslashes($staff[$i]);
         $sql="SELECT * FROM logins WHERE school='$school2' AND sport LIKE '$staff2[$i]%'";
         if($staff[$i]=="Athletic Director")
     	    $sql="SELECT * FROM logins WHERE school='$school2' AND level='2'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if($i%2==0) echo "<tr align=left bgcolor=#E0E0E0>";
         else echo "<tr align=left>";
         if($row[maincontact]=='y') echo "<td align=center><b>X</b></td>";
         else echo "<td>&nbsp;</td>";
         
		 	if (  $staff[$i]=="Athletic Director" || $staff[$i]=="Superintendent" || $staff[$i]=="Principal"){ 
				if (  strpos($row[name],',') !== false){ 
				$su = explode(",",$row[name]);
				$row[name]=$su[0];
				}
			}
		 
		 echo "<td>$row[name]</td>";
         $title="";
         if($row[level]=='2')
            $title="Athletic Director";
         else 
            $title=$row[sport];
         if(($row[level]=='2' || $row[level]=='3') && $row[sport]!="AD Secretary" && $row[sport]!="Assistant Athletic Director")
	    $title="<b>".$title."</b>";
         echo "<td>$title</td><td><a href=\"mailto:$row[email]\">$row[email]</a></td>";
         $ph=split("-",$row[phone]);
         if($ph[0]=='') $ph[0]=$area;
         if($ph[1]=='' || $ph[2]=='') $base=$phonebase;
         else $base="$ph[1]-$ph[2]";
         if($row[phone]!='---')
         {
            $phone="($ph[0]) $base";
            if($ph[3]!='') $phone.=" ext. $ph[3]";
         }
         else $phone=$row[phone];
         echo "<td>$phone&nbsp;&nbsp;";
         if($row[hours]!='' && $row[hours]!="--" && ($staff[$i]=="Athletic Director" || $staff[$i]=="Activities Director"))
	 {
            $cellphone=split("-",$row[hours]);
	    echo "<br>Cell: ($cellphone[0]) $cellphone[1]-$cellphone[2]&nbsp;&nbsp;"; 
         }
         echo "</td>";
         if($row[passcode]=='' && $row[sport]=="Activities Director" && $row[name]!='')
            $row[passcode]="(See AD)";
         echo "<td><b>$row[passcode]</b></td>";
         echo "</tr>";
      }
   }
   else	if(ereg("Public Schools",$school))//Large Public Schools
   {
      $sql="SELECT * FROM logins WHERE school='$school2' ORDER BY name";
      $result=mysql_query($sql);
      echo "<table class=nine cellspacing=0 cellpadding=3><tr align=left><td><b>Name</b></td><td><b>Passcode</b></td></tr>";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         if($ix%2==0) echO "<tr align=left bgcolor=#E0E0E0>";
         else echo "<tr align=left>";
	 echo "<td>$row[name]</td><td><b>$row[passcode]</b></td></tr>";
         $ix++;
      }
   }   
   else	//College
   {
      $sql="SELECT * FROM logins WHERE level='4' AND school='$school2' ORDER BY name";
      $result=mysql_query($sql);
      echo "<table class=nine cellspacing=0 cellpadding=3><tr align=left><td><b>Name</b></td><td><b>Address</b></td><td><b>City, State</b></td><td><b>Zip</b></td><td><b>Passcode</b></td></tr>";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
	 if($ix%2==0) echo "<tr align=left bgcolor=#E0E0E0 valign=top>";
    	 else echo "<tr align=left valign=top>";
	 if($row[name]=='') $row[name]="<i>[No Name Specified]</i>";
         if($row[usertitle]!='') $row[name].=" (Music)";
	 echo "<td>$row[name]</td><td>$row[address1]<br>$row[address2]</td><td>$row[city_state]</td><td>$row[zip]</td><td><b>$row[passcode]</b></td></tr>";
	 $ix++;
      }
   }
   echo "</table>";
   echo "<br><a href=\"#top\">Return to Top</a>";
}
//echo "</div><textarea name=\"schoolText\" id=\"schoolText\" rows=10 cols=40></textarea>";
echo "</td></tr></table></form>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
