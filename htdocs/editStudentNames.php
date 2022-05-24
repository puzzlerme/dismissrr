<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php 
    $studentList = $_GET['list'];
    echo file_put_contents('studentNames.json', $studentList);
    header("Location: http://www.dismissrr.rf.gd");
?>

</body>
</html>