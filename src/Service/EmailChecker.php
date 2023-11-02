<?php

namespace App\Service;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmailChecker
{
    /**
     * @param HttpClient $client
     */
    public function __construct(
        private readonly HttpClient $client
    )
    {
    }


    /**
     * Check if email is valid with DNS and MX record
     * and complies with RFC 5322
     * @param $email
     * @return bool|string
     */
    public function isValidEmail($email): bool|string
    {
        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new DNSCheckValidation(),
            new RFCValidation(),
        ]);

        if ($validator->isValid($email, $multipleValidations)) {
            return true;
        } else {
            return $validator->getError()->description();
        }
    }

    /**
     * Checks the user's email address with HIBP API to see
     * if it has been pwned
     * @param $email
     * @param $hibpApi
     * @return array
     */
    public function isEmailPwned($email,$hibpApi): array
    {
        $url = "https://haveibeenpwned.com/api/v3/breachedaccount/$email?truncateResponse";
        $headers = [
            "hibp-api-key: $hibpApi",
            'Content-Type: application/json; charset=UTF-8',
            'User-Agent: data-breach-checker/1.0.0'
        ];

        $curlResponse = $this->client->getUrl($url,$headers);

        if ($curlResponse['statusCode'] === 200) {
            $curlResponse['message'] = json_decode($curlResponse['message']);
            $curlResponse['info'] = "Votre adresse email $email est apparu dans " . count($curlResponse['message']) . " 
            faille(s) de sécurité dont voici les détails :";
        } elseif ($curlResponse['statusCode'] === 429) {
            $info = json_decode($curlResponse['message']);
            $curlResponse['second'] = filter_var($info->message,FILTER_SANITIZE_NUMBER_INT);
        }

        return $curlResponse;
    }
}