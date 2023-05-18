<?php

namespace AuthPro\Entity;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class FeedbackForm
{
    public string $email;
    public string $subject;
    public string $message;
    public string $captcha;

    private function __construct()
    {
    }

    public static function load(): FeedbackForm
    {
        $form = new FeedbackForm();
        $form->email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
        $form->subject = htmlspecialchars(filter_input(INPUT_POST, "subject", FILTER_UNSAFE_RAW));
        $form->message = htmlspecialchars(filter_input(INPUT_POST, "message", FILTER_UNSAFE_RAW));
        $form->captcha = filter_input(INPUT_POST, "g-recaptcha-response", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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