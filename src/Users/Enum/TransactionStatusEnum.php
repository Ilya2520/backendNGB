<?php

namespace App\Users\Enum;

enum TransactionStatusEnum
{
    const MODERATE = 'moderation';
    const ACCEPT = 'accept';
    const CANCEL = 'cancel';
    
    const STATUSES = [self::MODERATE, self::ACCEPT, self::CANCEL];
}
