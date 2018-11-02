<?php
define("FLAG", getenv("md52_flag"));
define("SALT", "myfuckingsalt");
if ($_GET['str1'] and $_GET['str2']) {
    if ($_GET['str1'] !== $_GET['str2']) {
        if (md5(SALT . $_GET['str1']) == md5(SALT . $_GET["str2"])) {
            exit(FLAG);
        } else {
            exit("Sorry, you're wrong.");
        }
    } else {
        exit("Sorry, the two strings must be unique.");
    }
}
?>
<html>
<body>
<p>People say that MD5 is broken... But they're wrong! All I have to do I use a secret salt. (^:</p>
<p>If you can find two distinct strings that - when prepended with my salt - have the same MD5 hash. I'll give you a flag.</p>
<p>Deal?</p>
<p>Also, here's the <a href="source.txt">source</a>.</p>
<form>
String 1: <input type="text" name="str1"><br>
String 2: <input type="text" name="str2"><br>
<input type="submit" value="Submit">
</form>
</body>
</html>
