<?php
//contact.php: provides e-mail link to nsaa@nsaahome.org
// and link to nsaahome.org/gen.html

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$header=GetHeader($session);
echo $init_html;
echo $header;
?>
<center><font size=2>
<br><br><br>
To send an e-mail to the NSAA, please <a href="mailto:nsaa@nsaahome.org">Click Here</a>.
<br><br>
To access the e-mail addresses of specific NSAA staff members, please <a href="/gen.html" target="new">Click Here</a>.
<br><br><br><br>
<a href="welcome.php?session=<?php echo $session; ?>">Home</a>
</font>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
