<?php

declare(strict_types=1);

namespace Baghayi\Gravatar;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Gravatar
{
    private const PROFILE_URL = 'https://www.gravatar.com/%s.json';
    private const IMAGE_URL   = 'https://www.gravatar.com/avatar/%s.jpg%s';

    private $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function exists(string $email): bool
    {
        try {
            return $this->http->head(sprintf(self::PROFILE_URL, $this->emailHash($email)))->getStatusCode() === 200;
        } catch(RequestException $e) {
            return false;
        }
    }

    public function imageUrl(string $email, int $size = null): string
    {
        $_size = '';
        if (!is_null($size)) {
            $_size = '?size=' . $size;
        }

        return sprintf(self::IMAGE_URL, $this->emailHash($email), $_size);
    }

    private function emailHash(string $email): string
    {
        return md5(strtolower($email));
    }
}
