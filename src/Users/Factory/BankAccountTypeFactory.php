<?php

namespace App\Users\Factory;

use App\Entity\BankAccountType;

class BankAccountTypeFactory
{
    public function create(string $type, float $conditions): BankAccountType
    {
        $transactionType = new BankAccountType();
        $transactionType->setType($type);
        $transactionType->setConditions($conditions);
       
        return $transactionType;
    }
}
