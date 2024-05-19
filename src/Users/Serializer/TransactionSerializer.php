<?php

namespace App\Users\Serializer;

use App\Entity\Transaction;

class TransactionSerializer
{
    public function forOutput(Transaction $transaction): array
    {
        return [
            'id' => $transaction->getId(),
            'amount' => $transaction->getAmount(),
            'status' => $transaction->getStatus(),
            'fromBankAccount' => $transaction->getFromBankAccount()?->getId(),
            'toBankAccount' => $transaction->getToBankAccount()?->getId(),
            'transactionType' => $transaction->getTransactionType(),
            'message' => $transaction->getMessage(),
        ];
    }
    
    public function fromArray(array $transactions): array
    {
        $result = [];
        foreach ($transactions as $transaction) {
            $result[] = $this->forOutput($transaction);
        }
        return $result;
    }
}
