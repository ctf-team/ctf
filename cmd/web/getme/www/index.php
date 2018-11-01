<?php
define ("FLAG_1", getenv("getme_flag"));
?>
<html>
<body>
<?php if(!isset($_GET['auth'])) { ?>
    <h2>Get me! (if you're authorized)</h2>
    <button href="?auth=false">Submit</button>
<?php } else {
    if ($_GET['auth'] == "false") { ?>
        <h2>Hey, you're not authorized!</h2>
    <?php } else if ($_GET['auth'] == "true") { ?>
        <h2><?php echo FLAG_1; ?></h2>
    <?php }
} ?>
</body>
</html>