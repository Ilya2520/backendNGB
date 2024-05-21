<?php

namespace App\Users\Serializer;

use App\Entity\User1;

class UserSerializer
{
    public function __construct()
    {
    }
    
    public function forUser(User1 $user): array
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
    
    
    public function forAdmin(User1 $user): array
    {
        return [
        
        ];
    }
    
    public function forMessages(?User1 $user): ?string
    {
        if (is_null($user)){
            return null;
        }
        
        return $user->getEmail();
    }
}