<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (ctype_alnum($_POST['password']) && ctype_alnum($_POST['name'])) {
        $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
        if (file_get_contents('adminPassword.json') == hash('sha256', $_POST['password'] . $salt)) {
            $names = file_get_contents('studentNames.json');
            $names = json_decode($names, true);
            array_push($names, $_POST['name']);
            $names = json_encode($names);
            echo file_put_contents('studentNames.json', $names);
        }
    }
?>

</body>
</html>
