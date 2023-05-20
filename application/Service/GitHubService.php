<?php

namespace AuthPro\Service;

use AuthPro\Service\Exception\GitHubException;
use Redis;
use RedisException;

readonly class GitHubService
{
    private Redis $redis;

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(REDIS_HOST, REDIS_PORT);
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
        return preg_replace("/^# (.*)$/m", "", $content);
    }

    /**
     * @throws RedisException
     * @throws GitHubException
     */
    private function getMarkdownFile(string $url)
    {
        $key = "github:markdown:$url";
        $markdown = $this->redis->get($key);

        if ($markdown === false) {
            $markdown = file_get_contents($url);

            if ($markdown === false) {
                throw new GitHubException("Failed to load markdown file");
            }

            $this->redis->set($key, $markdown);
            $this->redis->expire($key, 86400);
        }

        return $markdown;
    }
}
