<?php

namespace App\Users\Factory;

use App\Entity\Talk;
use App\Entity\User1;
use App\Users\Enum\TalkEnumStatuses;
use DateTimeImmutable;

class TalkFactory
{
    public function create(User1 $user): Talk
    {
        $talk = new Talk();
        $talk->setUser($user);
        $talk->setStatus(TalkEnumStatuses::CREATED);
        $talk->setCreatedAt(new \DateTimeImmutable());
        $talk->setUpdatedAt(new \DateTimeImmutable());
        $talk->setAtWork(0);
       
       return $talk;
    }
}
