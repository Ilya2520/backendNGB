<?php

namespace App\Users\Interface;

use App\Entity\User1;

interface UserPasswordHasherInterface
{
    public function hash(User1 $user, string $password): string;
}