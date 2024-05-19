<?php

namespace App\Users\Interface;

use App\Entity\User;

interface UserPasswordHasherInterface
{
    public function hash(User $user, string $password): string;
}