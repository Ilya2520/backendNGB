<?php

namespace App\Users\Auth;

use App\Users\Interface\AuthUserInterface;
use App\Users\Interface\UserFetcherInterface;
use Symfony\Component\Security\Core\Security;
use Webmozart\Assert\Assert;

class UserFetcher implements UserFetcherInterface
{
    public function __construct(private readonly Security $security)
    {
    }
    
    public function getAuthUser(): AuthUserInterface
    {
        /** @var AuthUserInterface $user */
        $user = $this->security->getUser();
        
        Assert::notNull($user);
        Assert::isInstanceOf($user, AuthUserInterface::class);
        
        return $user;
    }
}