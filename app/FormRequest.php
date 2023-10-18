<?php

namespace App;

class FormRequest
{
    public $fullName;
    public $email;
    public $phone;
    public $recaptchaToken;
    public $points;

    public function __construct()
    {
        $this->fullName = $_POST['full_name'] ?? '';
        $this->email = $_POST['email'] ?? '';
        $this->phone = $_POST['phone'] ?? '';
        $this->points = (int)($_POST['points'] ?? 0);
        $this->recaptchaToken = $_POST['recaptcha'] ?? '';
    }

    public function toArray($withRecaptcha = true): array {
        $data = [
            'fullName' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
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
            'Телефон' => $this->phone,
            'Количетсво баллов' => $this->points
        ];
    }
}