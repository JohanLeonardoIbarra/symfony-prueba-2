<?php

namespace App\MessageHandler;

use App\Document\User;
use App\Message\SmsNotificateOrder;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SmsNotificateOrderHandler
{
    public function __invoke(SmsNotificateOrder $message, DocumentManager $documentManager)
    {
        $user = $documentManager->getRepository(User::class)->find($message->getUserId());
        if (!$user->getFlag()){
            echo $message->getContent();
            $user->setFlag(true);
            $documentManager->persist($user);
            $documentManager->flush();
        }
    }
}