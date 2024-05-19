<?php

namespace App\Users\Serializer;

use App\Entity\Message;
use App\Entity\Talk;
use Doctrine\Common\Collections\Collection;

class MessageSerializer
{
    public function forOutput(Message $message): array
    {
        $userSerializer = new UserSerializer();
        $to = $message->getToUser()?->getUserId();
        return [
            'id' => $message->getId(),
            'from' => $message->getFromUser()->getUserId(),
            'fromS' => $userSerializer->forMessages($message->getFromUser()),
            'toS' => $userSerializer->forMessages($message->getToUser()),
            'to' => $to,
            'text' => $message->getText(),
            'sendAt' => $message->getSendAt(),
        ];
    }
    
    public function fromArray(Collection|array $messages): array
    {
        $result = [];
        foreach ($messages as $message) {
            $result[] = $this->forOutput($message);
        }
        
        return $result;
    }
}
