<?php

namespace App\Users\Controller;

use App\Users\Interface\UserFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/users/me', methods: ['GET'])]
class GetMeAction
{
    public function __construct(private readonly UserFetcherInterface $fetcher)
    {
    }
    
    public function __invoke()
    {
        $user = $this->fetcher->getAuthUser();
        
        return new JsonResponse([
                'id' => $user->getUserId(),
                'email' => $user->getEmail(),
                'role' => in_array('ROLE_ADMIN',$user->getRoles()) ? "ROLE_ADMIN" : null
            ]
        );
    }
}