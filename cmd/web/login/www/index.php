<?php
define ("FLAG_1", getenv("login_flag"));
if (isset($_GET['user'], $_GET['password'])) {
    if ($_GET['user'] == "admin" && $_GET['password'] == "jKalsFwq") {
        exit(FLAG_1);
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h2>Welcome to the admin portal!</h2>
<p>Currently, the only user that can log in is 'admin'.</p>
<br>
<!-- Shhh, don't tell anyone. The password is jKalsFwq -->
<form method="get">
    Username: <input type="text" name="user"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>