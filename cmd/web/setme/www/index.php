<?php
define("FLAG", getenv("setme_flag"));
if($_COOKIE['auth'] == "true") {
    exit(FLAG);
} else {
    setcookie("auth", "false", time() + 3600);
}
?>
You are not authorized to view this webpage.
