<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php 
    $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
    if (file_get_contents('adminPassword.json') == hash('sha256', htmlspecialchars($_POST['password'], ENT_QUOTES) . $salt)) {
        echo "2";
    } else if (file_get_contents('password.json') == hash('sha256', htmlspecialchars($_POST['password'], ENT_QUOTES) . $salt)) {
        echo "1";
    }
?>

</body>
</html>
