<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body>

<?php
    if (ctype_alnum($_POST['password']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) === 0 || !filter_var($_POST['id'], FILTER_VALIDATE_INT) === false) {
        $salt = "SENtxqzqMrcWIVhShV01.LkLNSJkggNw";
        if (file_get_contents('adminPassword.json') == hash('sha256', $_POST['password'] . $salt) || file_get_contents('moderatorPassword.json') == hash('sha256', $_POST['password'] . $salt)) {
            $names = file_get_contents('studentNames.json');
            $names = json_decode($names, true);
            $deletedName = $names[$_POST['id']];
            array_splice($names, $_POST['id'], 1);
            $names = json_encode($names);
            echo file_put_contents('studentNames.json', $names);

            if (ctype_alnum(str_replace(' ','',$deletedName))) {
                $deletedNames = file_get_contents('deletedStudentNames.json');
                $deletedNames = json_decode($deletedNames, true);
                array_push($deletedNames, $deletedName);
                $deletedNames = json_encode($deletedNames);
                echo file_put_contents('deletedStudentNames.json', $deletedNames);
            }
        }
    }
?>

</body>
</html>
