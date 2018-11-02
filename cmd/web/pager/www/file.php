<html>
<body>
<?php
$page = isset($_GET['path']) ? $_GET['path'] : "/";
exec("ls -al $page", $output);

foreach($output as $out) {
    echo $out."<br>";
}
?>
</body>
</html>