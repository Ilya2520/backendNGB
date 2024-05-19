<?php

namespace App\Users\Service;

use App\Entity\User;
use App\Users\Interface\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;

class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(private readonly  Hasher $passwordHasher)
    {
    }
    
    public function hash(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }
}