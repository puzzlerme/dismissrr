<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (ctype_alnum($_POST['password']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) === 0 || !filter_var($_POST['id'], FILTER_VALIDATE_INT) === false) {
        $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
        if (file_get_contents('adminPassword.json') == hash('sha256', $_POST['password'] . $salt)) {
            $names = file_get_contents('studentNames.json');
            $names = json_decode($names, true);
            array_splice($names, $_POST['id'], 1);
            $names = json_encode($names);
            echo file_put_contents('studentNames.json', $names);
        }
    }
?>

</body>
</html>
