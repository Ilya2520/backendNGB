<?php

namespace App\Users\Serializer;



use App\Entity\BankAccount;
use App\Entity\BankAccountType;
use App\Users\Enum\BankAccountTypeEnum;
use Doctrine\Common\Collections\Collection;

class BankAccountSerializer
{
    public function forOutput(BankAccount $bankAccount): array
    {
        $conditions = null;
        $type = null;
        if ($bankAccount->getType() !== null) {
            $type = $bankAccount->getType()->getType();
            $type === BankAccountTypeEnum::DEBIT ?? $conditions = $bankAccount->getType()->getConditions();
        }
        return [
            'id' => $bankAccount->getId(),
            'user_email' => $bankAccount->getUser()->getEmail(),
            'status' => $bankAccount->getStatus(),
            'amount' => $bankAccount->getAmount(),
            'conditions' => $conditions,
            'userId' => $bankAccount->getUser()->getUserId(),
            'type' => $type,
        ];
    }
    
    public function fromArray(Collection|array $bankAccounts): array
    {
        $result = [];
        foreach ($bankAccounts as $bankAccount){
            array_push($result, $this->forOutput($bankAccount));
        }
        
        return $result;
    }
}