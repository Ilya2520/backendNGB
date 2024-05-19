<?php

namespace App\Users\Interface;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface AuthUserInterface extends UserInterface, PasswordAuthenticatedUserInterface
{
    public function getUserId(): string;
    public function getEmail(): string;
}