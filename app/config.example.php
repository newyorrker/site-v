<?php

return [
  'serverUrl' => 'https://test.appercode.com/v1/meetings/objects/DemoRequests', //url to the needed method
  'token' => '', //session token
  'recaptchaToken' => '', //google secret token,
  'recaptchaTokenVconf' => '', //google secret token for vconf,
  'email' => [
      'host' => 'smtp.yandex.ru',
      'email' => 'no-reply@konferenza.com',
      'username' => 'username',
      'password' => 'ivan',
      'port' => 465
  ],
  'recipients' => [
      'tsyryatsyrenov@yandex.ru'
  ]
];
