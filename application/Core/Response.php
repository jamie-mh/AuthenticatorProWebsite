<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Core;

use Exception;


interface Response
{
    public const STATUS_OK = 200;
    public const STATUS_CREATED = 201;
    public const STATUS_BAD_REQUEST = 400;
    public const STATUS_UNAUTHORISED = 401;
    public const STATUS_FORBIDDEN = 403;
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_METHOD_NOT_ALLOWED = 405;
    public const STATUS_CONFLICT = 409;
    public const STATUS_SERVER_ERROR = 500;

    public const STATUS_MESSAGES = [
        self::STATUS_BAD_REQUEST => "Bad Request",
        self::STATUS_NOT_FOUND => "Not Found",
        self::STATUS_METHOD_NOT_ALLOWED => "Method Not Allowed",
        self::STATUS_SERVER_ERROR => "Internal Server Error",
        self::STATUS_FORBIDDEN => "Forbidden"
    ];

    public function render(): void;

    public function getStatusCode(): int;

    public static function notFound(): Response;

    public static function methodNotAllowed(): Response;

    public static function serverError(Exception $exception): Response;
}
