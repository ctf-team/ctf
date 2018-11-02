<?php
define("FLAG", getenv("robots_flag"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secret</title>
</head>
<body bgcolor="#00008b" style="color:#FFF;">
<h2>This is my secret page for my secret robot pictures that nobody knows about (^:</h2>
<img src="images/robot2.jpg">
<p><?php echo FLAG; ?></p>
</body>
</html>