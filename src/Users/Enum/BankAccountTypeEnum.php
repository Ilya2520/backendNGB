<?php

namespace App\Users\Enum;

enum BankAccountTypeEnum
{
    public const CREDIT = 'credit';
    public const DEBIT = 'debit';
    public const SAVINGS = 'savings';
    
    const TYPES = [self::CREDIT, self::DEBIT, self::SAVINGS];
}
