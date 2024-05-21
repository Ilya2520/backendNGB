<?php

namespace App\Users\Factory;

use App\Entity\BankAccount;
use App\Entity\BankAccountType;
use App\Entity\User1;
use App\Users\Enum\BankAccountStatusEnum;

class BankAccountFactory
{
    
    public function create(User1 $user, BankAccountType $bankAccountType, ?array $info): BankAccount
    {
        $bankAccount = new BankAccount();
        
        $bankAccount->setUser($user);
        $bankAccount->setAmount(0);
        $bankAccount->setType($bankAccountType);
        $bankAccount->setStatus(BankAccountStatusEnum::MODERATE);
        empty($info) ?: $bankAccount->setInformation($info);
        
        return $bankAccount;
    }
}
