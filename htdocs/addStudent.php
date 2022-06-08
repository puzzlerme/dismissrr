<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
    if (file_get_contents('adminPassword.json') == hash('sha256', htmlspecialchars($_POST['password'], ENT_QUOTES) . $salt)) {
        $names = file_get_contents('studentNames.json');
        $names = json_decode($names, true);
        array_push($names, htmlspecialchars($_POST['name'], ENT_QUOTES));
        $names = json_encode($names);
        echo file_put_contents('studentNames.json', $names);
    }
    //header("Location: http://www.dismissrr.rf.gd");
?>

</body>
</html>
