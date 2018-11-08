<html>
<body>
<?php
$page = (isset($_GET['path']) && !empty($_GET['path'])) ? $_GET['path'] : "/";
if (strstr($page, ";") || strstr($page, "&")) {
    exit("please, don't do that.");
}
exec("ls -al ".realpath($page), $output);
?>
<pre>
<?php
foreach($output as $out) {
    echo $out."<br>";
}
?>
</pre>
</body>
</html>