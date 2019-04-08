<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');


//TYPES OF SANCTIONED EVENTS
$eventtypetables=array("interstatesanctions","internationalsanctions","interstatefasanctions");
$eventtypenames=array("Interstate Athletic Events","International Athletic Events","Interstate Fine Arts Events");

//SPORTS SANCTIONS APPLY TO
$sanctionsp=array("Fall Season","Girls Golf","Boys Tennis","Softball","Girls Cross-Country","Boys Cross-Country","Girls AND Boys Cross-Country","Volleyball","Football","Winter Season","Wrestling","Girls Swimming and Diving","Boys Swimming and Diving","Girls AND Boys Swimming and Diving","Girls Basketball","Boys Basketball","Girls AND Boys Basketball","Spring Season","Baseball","Girls Soccer","Boys Soccer","Girls AND Boys Soccer","Girls Tennis","Girls Track and Field","Boys Track and Field","Girls AND Boys Track & Field","Boys Golf");
$sanctionsp2=array("","go_g","te_b","sb","cc_g","cc_b","cc","vb","fb","","wr","sw_g","sw_b","sw","bb_g","bb_b","bb","","ba","so_g","so_b","so","te_g","tr_g","tr_b","tr","go_b");

//ACTIVITIES
$sanctionact=array("Debate","Journalism","Music","Play Production","Speech");
$sanctionact2=array("de","jo","mu","pp","sp");

//LIST OF STATES
$states=array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WV","WA","WI","WY","DC");

?>
