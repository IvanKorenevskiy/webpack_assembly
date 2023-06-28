<?php
// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

sleep(3);


$body = '';

$customTitle = 'Узнать стоимость';
foreach ($_POST as $key => $value) {
    $keyTranslated = $key;
    if (empty($value)) {
        continue;
    }

    if ($key === 'name') {
        $keyTranslated = 'Имя';
    }
    $body .= "
        <tr style='background-color: #f8f8f8'>
            <td style='padding: 10px; border: 1px solid #e9e9e9'><b>$keyTranslated</b></td>
            <td style='padding: 10px; border: 1px solid #e9e9e9'><b>$value</b></td>
        </tr>";
}

function send_mail($mailBody, $recipientsArr) {

    // Формирование самого письма
    $title = 'Кислородные концентраторы';

    // Настройки PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth   = true;
        // $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

        // Настройки вашей почты
        $mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
        $mail->Username   = ''; // Логин на почте
        $mail->Password   = ''; // Пароль на почте
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('', ''); // Адрес самой почты и имя отправителя


        // Получатель письма
        foreach ($recipientsArr as $recipient) {
            $mail->addAddress($recipient);
        }
    // Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $mailBody;



    // Проверяем отравленность сообщения
    if ($mail->send()) {$result = "success";}
    else {$result = "error";}

    echo json_encode($mailBody);

    } catch (Exception $e) {
        $result = "error";
        $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
    }

}


// отправка менеджерам и др. сотрудникам
$recipientsArrManagers = ['', ''];

send_mail($body, $recipientsArrManagers);
