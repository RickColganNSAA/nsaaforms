<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);


 echo $init_html;
 echo $header;
//$session=$_GET[session];

//echo $end_html;
?>
<br><br><br>
<h3>Thank you for submitting Believers & Achievers Nomination Form.</h3><br>
<a href="believers_form.php?session=<?php echo $session;?>&school=<?php echo $school;?>" >Submit another Form</a><br><br>
<a href="welcome.php?session=<?php echo $session; ?>" >Home</a>


<script>
	(function (global) { 

    if(typeof (global) === "undefined") {
        throw new Error("window is undefined");
    }

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

        // making sure we have the fruit available for juice (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };

    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {            
        noBackPlease();

        // disables backspace on page except on input fields and textarea..
        document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };          
    }

})(window);
</script>