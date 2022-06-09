<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (ctype_alnum($_POST['password'])) {
        $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
        if (file_get_contents('adminPassword.json') == hash('sha256', $_POST['password'] . $salt) || file_get_contents('password.json') == hash('sha256', $_POST['password'] . $salt)) {
            echo json_encode([file_get_contents('studentNames.json'), file_get_contents('deletedStudentNames.json')]);
        }
    }
?>

</body>
</html>
