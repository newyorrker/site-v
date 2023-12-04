<?php

namespace App;

class FormRequestVconf
{
    public $fullName;
    public $email;
    public $text;
    public $recaptchaToken;
    public $points;

    public function __construct()
    {
        $this->fullName = $_POST['full_name'] ?? '';
        $this->email = $_POST['email'] ?? '';
        $this->text = $_POST['text'] ?? '';
        $this->points = (int)($_POST['points'] ?? 0);
        $this->recaptchaToken = $_POST['recaptcha'] ?? '';
    }

    public function toArray($withRecaptcha = true): array {
        $data = [
            'fullName' => $this->fullName,
            'email' => $this->email,
            'text' => $this->text,
            'points' => $this->points
        ];

        if ($withRecaptcha) {
            $data['recaptcha'] = $this->recaptchaToken;
        }

        return $data;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function forEmail() {
        return [
            'ФИО' => $this->fullName,
            'E-mail' => $this->email,
            'Текст сообщения' => $this->text,
            'Количетсво баллов' => $this->points,
            'Источник' => 'Vconf'
        ];
    }
}