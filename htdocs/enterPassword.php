<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php 
    if (file_get_contents('adminPassword.json') == hash('sha256', htmlspecialchars($_GET['password'], ENT_QUOTES))) {
        echo "2";
    } else if (file_get_contents('password.json') == hash('sha256', htmlspecialchars($_GET['password'], ENT_QUOTES))) {
        echo "1";
    }
?>

</body>
</html>
