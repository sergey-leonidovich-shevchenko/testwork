<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-07
 * Time: 23:26
 */

include_once __DIR__ . '/autoload.php';

/**
 * @var Email $email
 * @var Socket $socket
 */
$email = new Email();
$socket = new Socket(); // It would be possible to use a ready-made library - ddtraceweb/smtp-validator-email

$stmt = $email->getEmail();
if (!$stmt->rowCount()) {
    exit('Email not found!' . PHP_EOL);
}

while ($emailData = $stmt->fetch(PDO::FETCH_OBJ)) {
    if (!$email->checkValidEmail($emailData->email)) {
        echo '[500]', ' - Email: ', $emailData->email, ' is not valid!', PHP_EOL;
        continue;
    }

    $isEmailValid = $socket->connect($emailData->email);
    echo $isEmailValid ? '[200]' : '[404]', ' - Email: ', $emailData->email, PHP_EOL;
}

unset($email, $socket);
exit('DONE' . PHP_EOL . PHP_EOL);



