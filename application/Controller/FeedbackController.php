<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Controller;

use AuthPro\Core\Controller;
use AuthPro\Core\Response;
use AuthPro\Core\Response\PageResponse;
use AuthPro\Entity\FeedbackForm;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Respect\Validation\Exceptions\ValidationException;

class FeedbackController extends Controller
{
    public function index(): PageResponse
    {
        $res = new PageResponse();
        $res->meta->title = "Feedback";
        $res->meta->description = "Provide feedback for the Authenticator Pro app";
        $res->setView("feedback/index.twig");
        return $res;
    }

    public function submit(): Response
    {
        $res = $this->index();
        $form = FeedbackForm::init(INPUT_POST);
        $res->viewData["form"] = $form;

        try {
            $form->validate();
        } catch (ValidationException $e) {
            $res->viewData["error"] = $e->getMessage();
            return $res;
        }

        try {
            $this->sendMessage($form);
            $res->viewData["success"] = true;
        } catch (Exception) {
            $res->viewData["error"] = "An error occurred";
        }

        return $res;
    }

    /**
     * @throws Exception
     */
    private function sendMessage(FeedbackForm $form): void
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = EMAIL_HOST;
        $mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = EMAIL_PORT;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->isHTML();

        $mail->setFrom(EMAIL_RECIPIENT);
        $mail->addAddress(EMAIL_RECIPIENT);
        $mail->addReplyTo($form->email);

        $mail->Subject = "Authenticator Pro: " . $form->subject;
        $mail->Body = nl2br($form->message);

        $mail->send();
    }
}
