<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php 
    if (ctype_alnum($_POST['password'])) {
        $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
        if (file_get_contents('adminPassword.json') == hash('sha256', $_POST['password'] . $salt)) {
            echo "3";
        } else if (file_get_contents('moderatorPassword.json') == hash('sha256', $_POST['password'] . $salt)) {
            echo "2";
        } else if (file_get_contents('password.json') == hash('sha256', $_POST['password'] . $salt)) {
            echo "1";
        }
    }
?>

</body>
</html>
