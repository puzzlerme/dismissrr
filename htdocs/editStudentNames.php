<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (file_get_contents('adminPassword.json') == hash('sha256', htmlspecialchars($_GET['password'], ENT_QUOTES))) {
        echo file_put_contents('studentNames.json', htmlspecialchars($_GET['list'], ENT_QUOTES));
    }
    //header("Location: http://www.dismissrr.rf.gd");
?>

</body>
</html>
