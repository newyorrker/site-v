<?php

namespace App;

class Validator
{
    public function validateRequest($request, $rules): array
    {
        $errors = [];
        foreach ($rules as $fieldName => $fieldRules) {
            foreach ($fieldRules as $fieldRuleName => $fieldRuleMessage) {
                $validationResult = false;
                $validatedValue = $request->{$fieldName} ?? null;

                if (!$this->validate($fieldRuleName, $validatedValue)) {
                    if (!isset($errors[$fieldName])) {
                        $errors[$fieldName] = [];
                    }

                    $errors[$fieldName][] = $fieldRuleMessage;
                }
            }
        }

        return [
            'result' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public function validate($ruleName, $value): bool {
        if ($ruleName === ValidatorsTypes::REQUIRED) {
            return $this->validateRequired($value);
        }
        elseif ($ruleName === ValidatorsTypes::EMAIL) {
            return $this->validateEmail($value);
        }

        return true;
    }

    public function validateRequired($value): bool
    {
        return !empty($value);
    }

    public function validateEmail($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}