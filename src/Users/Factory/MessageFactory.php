<?php

namespace App\Users\Factory;

use App\Entity\Message;
use App\Entity\Talk;
use App\Entity\User1;
use DateTimeImmutable;

class MessageFactory
{
    public function create(Talk $talk, User1 $user, string $text): Message
    {
        $message = new Message();
        
        $message->setTalk($talk);
        $message->setText($text);
        $message->setFromUser($user);
        
        $talk->getTakedBy() === null ?: $message->setToUser($talk->getTakedBy());
        $message->setSendAt(new DateTimeImmutable());
        
        return $message;
    }
}
