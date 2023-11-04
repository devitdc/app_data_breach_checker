<?php

namespace App\Service;

final class PasswordChecker
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
     * Check if password is valid
     * @param $password
     * @return bool|string
     */
    public function isValidPassword($password): bool|string
    {
        if (preg_match("/^[A-Za-z0-9._%@#&!*=+-]*$/",$password)) {
            return true;
        }else {
            $message = "Sont autorisés les lettres minuscules, majuscules, chiffres ainsi que les caractères . _ % @ # & ! * = + -.";
        }

        return $message;
    }

    /**
     * Check if the password has appeared in a data breach
     * by submitting password's hash to HIBP API
     * @param $password
     * @return bool|array
     */
    function isPasswordSafe($password): bool|array
    {
        $passwordHashed = sha1($password);
        $firstFiveChars = substr($passwordHashed, 0, 5);
        $suffix = strtoupper(substr($passwordHashed, 5));

        $url = 'https://api.pwnedpasswords.com/range/' .$firstFiveChars;
        $headers = [
            'Content-Type: application/json; charset=UTF-8',
            'User-Agent: data-breach-checker/1.0.0'
        ];

        $curlResponse = $this->client->getUrl($url,$headers);

        if ($curlResponse['statusCode'] !== 0) {
            $suffixes = array_map(function ($entry) {
                return substr($entry, 0, strpos($entry, ':'));
            }, preg_split('/\r\n/', $curlResponse['message']));

            if (in_array($suffix, $suffixes)) {
                $hibpHashList = preg_split('/\r\n/', $curlResponse['message']);
                $passwordHashInfos = explode(':', $hibpHashList[array_search($suffix, $suffixes)]);

                return [
                    "statusCode" => 200,
                    "message" => number_format($passwordHashInfos[1], 0, '', ' ')
                ];
            }

            return [
                "statusCode" => 404
            ];
        } else {
            return $curlResponse;
        }
    }
}