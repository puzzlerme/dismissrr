<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (file_get_contents('adminPassword.json') == hash('sha256', $_GET['password']) or file_get_contents('password.json') == hash('sha256', $_GET['password'])) {
        echo file_get_contents('studentNames.json');
    }
?>

</body>
</html>