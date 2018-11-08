<?php
define("FLAG", getenv("walloffire_flag"));

if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    if($_SERVER['HTTP_X_FORWARDED_FOR'] == "127.0.0.1") {
        exit(FLAG);
    }
}
?>
<p>The IP address you are currently accessing this page from is unauthorized (<?php echo $_SERVER['REMOTE_ADDR']; ?>).</p>