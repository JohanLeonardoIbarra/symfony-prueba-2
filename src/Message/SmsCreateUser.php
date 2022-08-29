<?php

namespace App\Message;

class SmsCreateUser
{
    private $content;
    private array $userData;

    public function __construct(string $content, array $userData)
    {
        $this->content = $content;
        $this->userData = $userData;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return $this->userData;
    }
}