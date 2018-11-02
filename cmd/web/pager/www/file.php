<?php
$page = isset($_GET['path']) ? $_GET['path'] : "/";
exec("ls -al $page", $out);
?>
<pre><?php echo $out; ?></pre>