<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($submit=="Save")
{
}
echo "<form method=post action=\"colors.php\">";
echo "<table>";
  for ($i=0;$i<6;$i++)
  {
  echo "<tr valign=top>";
    if ($i==0) $v_color_i="00";
    if ($i==1) $v_color_i="33";
    if ($i==2) $v_color_i="66";
    if ($i==3) $v_color_i="99";
    if ($i==4) $v_color_i="CC";
    if ($i==5) $v_color_i="FF";
    for ($j=0;$j<6;$j++)
    {
      if ($j==0) $v_color_j="00";
      if ($j==1) $v_color_j="33";
      if ($j==2) $v_color_j="66";
      if ($j==3) {$v_color_j="99"; echo "<tr valign=top>";}
      if ($j==4) $v_color_j="CC";
      if ($j==5) $v_color_j="FF";
      for ($k=0;$k<6;$k++)
      {
        if ($k==0) $v_color_k="00";
        if ($k==1) $v_color_k="33";
        if ($k==2) $v_color_k="66";
        if ($k==3) $v_color_k="99";
        if ($k==4) $v_color_k="CC";
        if ($k==5) $v_color_k="FF";
	$hexcolor="$v_color_i$v_color_j$v_color_k";
	if(!$color[$hexcolor]) $color[$hexcolor]=$hexcolor;
        echo "<td bgcolor=#$hexcolor width=50 height=100><input type=text name=color[$hexcolor] value=\"$color[$hexcolor]\" size=8></td>";
      }
      if ($j==5) {$v_color_j="99"; echo"<tr valign=top>";}
    }
    echo "</tr>";
  }
  echo "</table><input type=submit name=submit value=\"Save\"></form>";
?>
