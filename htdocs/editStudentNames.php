<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (file_get_contents('adminPassword.json') == hash('sha256', $_GET['password'])) {
        echo file_put_contents('studentNames.json', $_GET['list']);
    }
    //header("Location: http://www.dismissrr.rf.gd");
?>

</body>
</html>
