<?php
$page = isset($_GET['path']) ? $_GET['path'] : "/";
$out = exec("ls -al $page");
?>
<pre><?php echo $out; ?></pre>