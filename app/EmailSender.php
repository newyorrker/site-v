<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    private $recipients;
    private $host;
    private $email;
    private $userName;
    private $password;
    private $port;
    private $html;

    public function __construct(ConfigReader $configReader, $data)
    {
        $recipientsValue = $configReader->get(ConfigReader::EMAIL_RECIPIENTS_KEY, []);

        $this->recipients = is_array($recipientsValue) ? $recipientsValue : [$recipientsValue];

        $emailConfig = $configReader->get(ConfigReader::EMAIL_KEY, []);
        $this->host = $emailConfig['host'] ?? '';
        $this->email = $emailConfig['email'] ?? '';
        $this->userName = $emailConfig['username'] ?? '';
        $this->password = $emailConfig['password'] ?? '';
        $this->port = $emailConfig['port'] ?? 465;

        $this->html = $this->prepareHtml($data);
    }

    public function send() {
        foreach ($this->recipients as $recipient) {
            $this->sendToOne($recipient);
        }
    }

    private function prepareHtml($requestData): string {
        $result = '<p>Новая заявка на демо:</p>';
        $result .= json_encode($requestData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $result;
    }

    private function sendToOne($recipientMail) {
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();                   // Отправка через SMTP
        $mailer->Host			= $this->host;  // Адрес SMTP сервера
        $mailer->SMTPAuth		= true;          // Enable SMTP authentication
        $mailer->Username		= $this->userName;       // ваше имя пользователя (без домена и @)
        $mailer->Password		= $this->password;    // ваш пароль
        $mailer->SMTPSecure	= 'ssl';         // шифрование ssl
        $mailer->Port			= $this->port;               // порт подключения
        $mailer->CharSet		= 'utf-8';


        $mailer->setFrom($this->email, "Digital Sparta");    // от кого
        $mailer->addAddress($recipientMail, $recipientMail); // кому

        $mailer->Subject = "Новая заявка на демо";
        $mailer->msgHTML($this->html);

        if($mailer->send()){
            return true;
        } else {
            return false;
        }
    }
}
