<html>
<body>
<?php
$page = isset($_GET['path']) ? $_GET['path'] : "/";
exec("ls -al $page", $output);
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