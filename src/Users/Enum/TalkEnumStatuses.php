<?php

namespace App\Users\Enum;

enum TalkEnumStatuses
{
    const CREATED = 'created';
    const SOLVED = 'solved';
    const INWORK = 'in_work';
    const DECLINED = 'decline';
    
    const STATUSES = [self::CREATED, self::SOLVED, self::INWORK, self::DECLINED];
}
