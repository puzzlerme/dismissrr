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
            echo file_put_contents('studentNames.json', "[]");
            echo file_put_contents('deletedStudentNames.json', "[]");
        }
    }
?>

</body>
</html>
