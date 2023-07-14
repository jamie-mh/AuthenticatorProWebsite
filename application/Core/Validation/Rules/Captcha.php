<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Core\Validation\Rules;

use JsonException;
use Respect\Validation\Rules\AbstractRule;

class Captcha extends AbstractRule
{
    /**
     * @throws JsonException
     */
    public function validate($input): bool
    {
        if ($input === null || trim($input) === "") {
            return false;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt(
            $curl,
            CURLOPT_POSTFIELDS,
            http_build_query([
                "secret" => RECAPTCHA_SECRET,
                "response" => $input,
                "remoteip" => $_SERVER["REMOTE_ADDR"]
            ])
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, false, 512, JSON_THROW_ON_ERROR)->success;
    }
}
