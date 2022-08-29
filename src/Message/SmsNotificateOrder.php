<?php

namespace App\Message;

class SmsNotificateOrder
{
    private string $content;

    public function __construct(string $content, string $userId)
    {
        $this->content = $content;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    private string $userId;

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}