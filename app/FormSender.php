<?php

namespace App;

class FormSender
{
    private $request;
    private $url;
    private $token;

    public function __construct(FormRequest $request, ConfigReader $configReader)
    {
        $this->request = $request;
        $this->token = $configReader->get(ConfigReader::TOKEN_KEY);
        $this->url = $configReader->get(ConfigReader::SERVER_ULR_KEY);
    }

    private function prepareCurlOptions(): array
    {
        $payload = $this->request->toJson();

        return [
            CURLOPT_URL => $this->url,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                "X-Appercode-Session-Token: $this->token",
                "Content-Type: application/json",
                'Content-Length: ' . strlen($payload)
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

    private function isRequestSuccessful(array $requestInfo): bool {
        if (isset($requestInfo['http_code'])) {
            return $requestInfo['http_code'] >= 200 and $requestInfo['http_code'] <= 204;
        }

        return false;
    }

    public function send() {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, $this->prepareCurlOptions());

            $response = curl_exec($curl);

            $requestInfo = curl_getinfo($curl);

            curl_close($curl);

            if ($this->isRequestSuccessful($requestInfo)) {
                return json_decode($response, true);;
            }

            return false;
        }
        catch (Exception $e) {
            return false;
        }
    }
}