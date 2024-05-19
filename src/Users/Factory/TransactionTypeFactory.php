<?php

namespace App\Users\Factory;

use App\Entity\TransactionType;

class TransactionTypeFactory
{
    public function create(string $name, array $info, ?string $imageLink): TransactionType
    {
        $transactionType = new TransactionType();
        
        $transactionType->setName($name);
        $transactionType->setInfo($info);
        $imageLink === null ?: $transactionType->setImageLink($imageLink);
       
        return $transactionType;
    }
}
