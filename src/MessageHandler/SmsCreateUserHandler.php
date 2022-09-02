<?php

namespace App\MessageHandler;

use App\Document\User;
use App\Message\SmsCreateUser;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SmsCreateUserHandler
{
    public function __invoke(SmsCreateUser $message, DocumentManager $documentManager)
    {
        echo $message->getContent();
        $user = $message->getUser();
        $documentManager->persist($user);
        $documentManager->flush();
    }
}