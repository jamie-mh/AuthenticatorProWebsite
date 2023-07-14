<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Entity;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class FeedbackForm
{
    public string $email;
    public string $subject;
    public string $message;
    public string $captcha;

    public static function init(int $input): FeedbackForm
    {
        $form = new self();
        $form->email = filter_input($input, "email", FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
        $form->subject = htmlspecialchars(filter_input($input, "subject", FILTER_UNSAFE_RAW));
        $form->message = htmlspecialchars(filter_input($input, "message", FILTER_UNSAFE_RAW));
        $form->captcha = filter_input($input, "g-recaptcha-response", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $form;
    }

    /**
     * @throws ValidationException
     */
    public function validate(): void
    {
        v::email()->setName("Email")->check($this->email);
        v::stringType()->length(1, 120)->setName("Subject")->check($this->subject);
        v::stringType()->length(1, 2000)->setName("Message")->check($this->message);
        v::captcha()->setName("Captcha")->check($this->captcha);
    }
}
