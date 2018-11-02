<?php
if(!file_exists(getcwd()."/flag.txt")) {
    file_put_contents(getcwd()."/flag.txt", getenv("pager_flag"));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Manager</title>
</head>
<body>
<h3>Here's something neat that I built to view files on a machine!</h3>
<iframe src="file.php?path=/"></iframe>
</body>
</html>