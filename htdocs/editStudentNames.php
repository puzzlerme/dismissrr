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
    if ($equal) {
        $studentList = $_GET['list'];
        echo file_put_contents('studentNames.json', $studentList);
    }
    header("Location: http://www.dismissrr.rf.gd");
?>

</body>
</html>
