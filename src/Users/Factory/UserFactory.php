<?php

namespace App\Users\Factory;

use App\Entity\User1;
use App\Users\Interface\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }
    
    public function create(string $email, string $password): User1
    {
        $user = new User1();
        $user->setEmail($email);
        $user->setPassword($password, $this->passwordHasher);
        $user->setUsername($email);
        $user->setUsername($email);
        $user->setActive(1);
        $user->setRoles(['ROLE_USER']);
        
        return $user;
    }
}
