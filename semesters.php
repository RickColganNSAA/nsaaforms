<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Alliance' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Arlington' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Arnold' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Bellevue East' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Bellevue West' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Blair' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Bloomfield' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Brady' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Burwell' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Centennial' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Centura' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Chase County' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Clearwater' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='College View Academy' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Columbus' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Concordia' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Creek Valley' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Cross County' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Douglas County West' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Elm Creek' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Elmwood-Murdock' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Fairbury' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Giltner' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Grand Island' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Grand Island Central Catholic' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Gretna' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Hartington' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Hastings' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Holdrege' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Hyannis' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Kearney' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lawrence-Nelson' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lincoln East' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lincoln High' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lincoln Lutheran' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lincoln North Star' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lincoln Northeast' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Lincoln Southeast' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Loup County' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='McCook' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Meridian' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Millard North' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Minden' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Mitchell' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Mount Michael Benedictine' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Norfolk' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='North Loup-Scotia' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='North Platte' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Ogallala' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Omaha Benson' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Omaha Bryan' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Omaha Marian' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Omaha Skutt Catholic' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Omaha South' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Pawnee City' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Ralston' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Randolph' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Rising City' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Schuyler' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Southeast NE Consolidated' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Southern' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Southern Valley' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Stuart' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Syracuse' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Tekamah-Herman' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Wahoo' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Walthill' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Waverly' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Winnebago' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Wood River' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }
$sql="SELECT school,semesters,count(semesters) FROM eligibility WHERE school='Yutan' GROUP BY semesters"; $result=mysql_query($sql); while($row=mysql_fetch_array($result)) { echo "$row[0], $row[1], $row[2]<br>"; }


?>
