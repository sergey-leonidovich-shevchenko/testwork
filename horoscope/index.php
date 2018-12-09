<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-07
 * Time: 23:26
 */

include_once __DIR__ . '/autoload.php';

define('COUNT_USER_IN_TABLE', 20);

if (!array_key_exists('page', $_GET) || (int)$_GET['page'] === 1) {
    $offset = 0;
    $page = 1;
} else {
    $page = (int)$_GET['page'];
    $offset = ($page - 1) * COUNT_USER_IN_TABLE;
}

$user = new User();
$userList = $user->getUserWithHoroscopeCapricorn(COUNT_USER_IN_TABLE, $offset);
$userCount = $user->getCountUserWithHoroscopeCapricorn()['count'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css">
    <title>Document</title>
</head>
<body class="container-fluid text-center">
    <div class="row mx-auto">
        <h2>All records: <?= $userCount; ?></h2>
        <?= UserTableWidget::widget($userList, $userCount, $page, COUNT_USER_IN_TABLE); ?>
    </div>
    <script src="vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
