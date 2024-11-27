<?php

namespace App\Helpers;

class DataHelper
{
    // Define constants for status and type
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    const PURCHASE = 'purchase';
    const REFUND = 'refund';
    const DEDUCTION = 'deduction';

    // Optionally, you can also define methods for dynamic data.
    public static function getStatuses()
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
        ];
    }

    public static function getTypes()
    {
        return [
            self::PURCHASE,
            self::REFUND,
            self::DEDUCTION,
        ];
    }
}
