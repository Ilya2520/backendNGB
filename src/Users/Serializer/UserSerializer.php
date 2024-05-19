<?php

namespace App\Users\Serializer;

use App\Entity\User;

class UserSerializer
{
    public function __construct()
    {
    }
    
    public function forUser(User $user): array
    {
        return [
            'id' => $user->getUserId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'last_name' => $user->getLastName(),
            'phone' => $user->getPhone(),
            'isActive' => $user->isActive(),
            'roles' => $user->getRoles(),
            'settings' => $user->getSettings(),
        ];
    }
    
    
    public function forAdmin(User $user): array
    {
        return [
        
        ];
    }
    
    public function forMessages(?User $user): ?string
    {
        if (is_null($user)){
            return null;
        }
        
        return $user->getEmail();
    }
}