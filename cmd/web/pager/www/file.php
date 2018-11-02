<?php
$page = isset($_GET['path']) ? $_GET['path'] : "/";
$out = passthru("ls -al $page");
?>
<pre><?php echo $out; ?></pre>