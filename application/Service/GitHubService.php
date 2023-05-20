<?php

namespace AuthPro\Service;

use Redis;
use RedisException;
use RuntimeException;

readonly class GitHubService
{
    private Redis $_redis;

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->_redis = new Redis();
        $this->_redis->connect(REDIS_HOST, REDIS_PORT);
    }

    /**
     * @throws RedisException
     */
    public function getWikiPage(string $fileName): string
    {
        $url = "https://raw.githubusercontent.com/wiki/jamie-mh/AuthenticatorPro/$fileName";
        return $this->getMarkdownFile($url);
    }

    /**
     * @throws RedisException
     */
    public function getMarkdownPage(string $filePath): string
    {
        $url = "https://raw.githubusercontent.com/jamie-mh/AuthenticatorPro/master/$filePath";
        $content = $this->getMarkdownFile($url);
        return preg_replace("/^# (.*?)$/m", "", $content);
    }

    /**
     * @throws RedisException
     */
    private function getMarkdownFile(string $url)
    {
        $key = "github:markdown:$url";
        $markdown = $this->_redis->get($key);

        if ($markdown === false) {
            $markdown = file_get_contents($url);

            if ($markdown === false) {
                throw new RuntimeException("Failed to load markdown file");
            }

            $this->_redis->set($key, $markdown);
            $this->_redis->expire($key, 86400);
        }

        return $markdown;
    }
}