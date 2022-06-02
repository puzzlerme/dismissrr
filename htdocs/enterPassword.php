<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php 
    $password = $_GET['password'];
    $password = hash('sha256', $password);
    $equal = file_get_contents('password.json') == $password;
    echo $equal;
?>

</body>
</html>