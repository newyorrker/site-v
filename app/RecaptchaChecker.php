<?php

namespace App;

class RecaptchaChecker
{
    private $token;
    private $secret;

    public function __construct(string $token, string $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function check(): bool
    {
        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify';

            $params = [
                'secret' => $this->secret,
                'response' => $this->token,
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            $responseData = json_decode($response, true);

            return $responseData['success'] == 1 && $responseData['score'] >= 0.5 && $responseData['action'] == 'submit_demo';
        }
        catch (Exception $e) {
            return false;
        }
    }

}