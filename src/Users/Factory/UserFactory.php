<?php

namespace App\Users\Factory;

use App\Entity\User;
use App\Users\Interface\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }
    
    public function create(int $id, string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setId($id+1);
        $user->setPassword($password, $this->passwordHasher);
        $user->setUsername($email);
        $user->setUsername($email);
        $user->setActive(1);
        $user->setRoles(['ROLE_USER']);
        
        return $user;
    }
}
