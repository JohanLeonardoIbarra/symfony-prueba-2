<?php

namespace App\Message;

class SmsCreateUser
{
    private $content;
    private User $user;

    public function __construct(string $content, User $user)
    {
        $this->content = $content;
        $this->user = $user;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}