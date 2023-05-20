<?php

namespace AuthPro\Core\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class CaptchaException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "The captcha is not solved"
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => "The captcha is not solved"
        ],
    ];
}