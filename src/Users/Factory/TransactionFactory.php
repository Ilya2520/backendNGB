<?php

namespace App\Users\Factory;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Entity\User;
use App\Users\Enum\TransactionStatusEnum;

class TransactionFactory
{
    public function create(BankAccount $bankAccountFrom, BankAccount $bankAccountTo, float $amount, string $text): Transaction
    {
        $transaction = new Transaction();
        
        $transaction->setFromBankAccount($bankAccountFrom);
        $transaction->setToBankAccount($bankAccountTo);
        $transaction->setAmount($amount);
        $transaction->setStatus(TransactionStatusEnum::ACCEPT);
        $transaction->setMessage($text);
        
        return $transaction;
    }
}
