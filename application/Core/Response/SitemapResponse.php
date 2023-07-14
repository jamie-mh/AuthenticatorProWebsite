<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Core\Response;

use AuthPro\Core\Response;
use Exception;

class SitemapResponse implements Response
{
    private array $items;

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function render(): void
    {
        header("Content-Type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->items as $item) {
            echo "<url>";
            echo "<loc>" . PROTOCOL . "://" . DOMAIN_NAME . $item->uri . "</loc>";
            echo "<changefreq>$item->changeFrequency</changefreq>";
            echo "<priority>$item->priority</priority>";
            echo "</url>";
        }

        echo "</urlset>";
    }

    public static function notFound(): Response
    {
        return PageResponse::notFound();
    }

    public static function methodNotAllowed(): Response
    {
        return PageResponse::methodNotAllowed();
    }

    public static function serverError(Exception $exception): Response
    {
        return PageResponse::serverError($exception);
    }

    public function getStatusCode(): int
    {
        return Response::STATUS_OK;
    }
}
