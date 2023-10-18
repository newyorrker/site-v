<?php

namespace App;

class ConfigReader
{
    const SERVER_ULR_KEY = 'serverUrl';
    const TOKEN_KEY = 'token';
    const RECAPTCHA_KEY = 'recaptchaToken';
    const EMAIL_KEY = 'email';
    const EMAIL_RECIPIENTS_KEY = 'recipients';

    private $config;

    public function __construct()
    {
        if (!file_exists(__DIR__ . '/config.php')) {
            throw new Exception("Config has not been found");
        }

        $this->config = require_once (__DIR__ . '/config.php');
    }

    public function get($key, $defaultValue = null) {
        return $this->config[$key] ?? $defaultValue;
    }
}