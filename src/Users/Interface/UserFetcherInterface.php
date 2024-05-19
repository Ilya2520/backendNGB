<?php

declare(strict_types=1);

namespace App\Users\Interface;

interface UserFetcherInterface
{
    public function getAuthUser(): AuthUserInterface;
}