<?php
// Copyright (C) 2023 jmh
// SPDX-License-Identifier: GPL-3.0-only

namespace AuthPro\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Redis;
use RedisException;

readonly class GitHubService
{
    private const WIKI_FILE_URL = "https://raw.githubusercontent.com/wiki/jamie-mh/AuthenticatorPro";
    private const MARKDOWN_FILE_URL = "https://raw.githubusercontent.com/jamie-mh/AuthenticatorPro/master";
    private const RELEASE_INFO_URL = "https://api.github.com/repos/jamie-mh/AuthenticatorPro/releases/latest";

    private Client $client;
    private Redis $redis;

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->redis = new Redis();
        $this->redis->connect(REDIS_HOST, REDIS_PORT);
    }

    /**
     * @throws RedisException
     * @throws GuzzleException
     */
    public function getWikiPage(string $fileName): string
    {
        $url = self::WIKI_FILE_URL . "/$fileName";
        return $this->getMarkdownFile($url);
    }

    /**
     * @throws RedisException
     * @throws GuzzleException
     */
    public function getMarkdownPage(string $filePath): string
    {
        $url = self::MARKDOWN_FILE_URL . "/$filePath";
        $content = $this->getMarkdownFile($url);
        return preg_replace("/^# (.*)$/m", "", $content);
    }

    /**
     * @throws RedisException
     * @throws GuzzleException
     */
    private function getMarkdownFile(string $url)
    {
        $key = "github:markdown:$url";
        $markdown = $this->redis->get($key);

        if ($markdown === false) {
            $res = $this->client->get($url);
            $markdown = (string) $res->getBody();
            $this->redis->set($key, $markdown);
            $this->redis->expire($key, 86400);
        }

        return $markdown;
    }

    /**
     * @throws RedisException
     * @throws JsonException
     */
    public function getLatestReleaseTag(): string
    {
        $key = "github:latest_release_tag";
        $tag = $this->redis->get($key);

        if ($tag === false) {
            $res = $this->client->get(self::RELEASE_INFO_URL);
            $releaseInfo = json_decode((string) $res->getBody(), false, 512, JSON_THROW_ON_ERROR);
            $tag = $releaseInfo->tag_name;

            $this->redis->set($key, $tag);
            $this->redis->expire($key, 86400);
        }

        return $tag;
    }
}
