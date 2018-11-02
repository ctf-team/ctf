<?php
if(isset($_GET['path'])) {
    if (file_exists($_GET['path'])) {
        $content = file_get_contents(realpath($_GET['path']));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Reader</title>
</head>
<body>
<p><b>This is an experimental file reader!</b></p>
<?php if(isset($content)) { ?>
<pre><?php echo $content; ?></pre>
<?php } else { ?>
<p>No file selected!</p>
<?php } ?>
</body>
</html>