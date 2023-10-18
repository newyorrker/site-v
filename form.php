<?php

require_once (__DIR__ . "/vendor/autoload.php");

use App\ConfigReader;
use App\FormRequest;
use App\FormSender;
use App\RecaptchaChecker;
use App\Validator;
use App\ValidatorsTypes;
use App\EmailSender;


try {
    header('Content-Type: application/json');

    $request = new FormRequest();
    $validator = new Validator();

    $validationResult = $validator->validateRequest($request, [
        'fullName' => [
            ValidatorsTypes::REQUIRED => 'Заполните ФИО'
        ],
        'email' => [
            ValidatorsTypes::REQUIRED => 'Заполните E-mail',
            ValidatorsTypes::EMAIL => 'Введите валидный E-mail'
        ],
        'phone' => [
            ValidatorsTypes::REQUIRED => 'Заполните телефон'
        ],
        'recaptchaToken' => [
            ValidatorsTypes::REQUIRED => 'Невалидный токен'
        ]
    ]);

    if (!$validationResult['result']) {
        echo json_encode($validationResult);
        die();
    }

    $configReader = new ConfigReader();

    $recaptchaChecker = new RecaptchaChecker($request->recaptchaToken, $configReader->get(ConfigReader::RECAPTCHA_KEY));

    if (!$recaptchaChecker->check()) {
        echo json_encode(['result' => false, 'errors' => ['msg' => 'Что-то пошло не так, попробуйте еще раз через некоторое время.']]);
        die();
    }

    $sender = new FormSender($request, $configReader);

    $response = $sender->send();

    if (!$response) {
        echo json_encode(['result' => false, 'errors' => ['msg' => 'Не удалось выполнить запрос к серверу']]);
        die();
    }


    $emailSender = new EmailSender($configReader, $request->forEmail());
    $emailSender->send();

    echo json_encode([]);
}
catch (Exception $e) {
    echo json_encode(['result' => false, 'errors' => ['msg' => 'Не удалось выполнить запрос к серверу']]);
    die();
}



