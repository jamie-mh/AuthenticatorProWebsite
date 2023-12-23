<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Core\Validation\Rules;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Respect\Validation\Rules\AbstractRule;

class Captcha extends AbstractRule
{
    private readonly Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function validate($input): bool
    {
        if ($input === null || trim($input) === "") {
            return false;
        }

        $res = $this->client->request("POST", "https://www.google.com/recaptcha/api/siteverify", [
            "form_params" => [
                "secret" => RECAPTCHA_SECRET,
                "response" => $input,
                "remoteip" => $_SERVER["REMOTE_ADDR"]
            ]
        ]);

        $json = (string) $res->getBody();
        return json_decode($json, false, 512, JSON_THROW_ON_ERROR)->success;
    }
}
