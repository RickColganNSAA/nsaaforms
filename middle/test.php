<?php
/************************************************************
import_students.php: 
Gives intsructions on how to set up the
file to be imported and provides file field w/ browsing
capabilities.
Copied from ../import_students.php 12/28/09
Author: Ann Gaffigan
************************************************************/

require '../functions.php';
require '../variables.php';


echo $init_html;
echo GetHeader($session);
?>
<form enctype="multipart/form-data" method="post" action="import_student_file.php">
<input type=hidden name="session" value="<?php echo $session; ?>" />
<input type=hidden name="MAX_FILE_SIZE" value="1000000" />
<input type="file" name="import_file">
<input type=submit name="submit" value="Import">
</form> 
<?php echo $end_html; ?>
