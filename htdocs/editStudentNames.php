<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
    if (file_get_contents('adminPassword.json') == hash('sha256', htmlspecialchars($_POST['password'], ENT_QUOTES) . $salt)) {
        echo file_put_contents('studentNames.json', htmlspecialchars($_POST['list'], ENT_QUOTES));
    }
    //header("Location: http://www.dismissrr.rf.gd");
?>

</body>
</html>
