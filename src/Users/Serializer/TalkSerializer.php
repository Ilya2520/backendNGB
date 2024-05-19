<?php

namespace App\Users\Serializer;

use App\Entity\Talk;
use Doctrine\Common\Collections\Collection;

class TalkSerializer
{
    public function forOutput(Talk $talk): array
    {
        $user = $talk->getUser();
        if ($user instanceof \Doctrine\Persistence\Proxy) {
            $user->__load();
        }
        
        $takedBy = $talk->getTakedBy();
        if ($takedBy instanceof \Doctrine\Persistence\Proxy) {
            $takedBy->__load();
        }
        
        $messages = new MessageSerializer();
        
        return [
            'id' => $talk->getId(),
            'user_email' => $user ? $user->getEmail() : null,
            'updated_at' => $talk->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'closed_at' => $talk->getClosedAt()?->format('Y-m-d H:i:s'),
            'created_at' => $talk->getCreatedAt()?->format('Y-m-d H:i:s'),
            'status' => $talk->getStatus(),
            'messages' => $messages->fromArray($talk->getMessages()),
            'at_work' => $talk->isAtWork(),
            'is_solved' => $talk->isSolved(),
            'taked_by_email' => $takedBy ? $takedBy->getEmail() : null,
        ];
    }
    
    public function fromArray(array $talks): array
    {
        $result = [];
        foreach ($talks as $talk) {
            $result[] = $this->forOutput($talk);
        }
        
        return $result;
    }
}
