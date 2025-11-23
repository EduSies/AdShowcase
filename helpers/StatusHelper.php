<?php

namespace app\helpers;

class StatusHelper
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_BANNED = 'banned';

    public const STATUS_PENDING = 'pending';

    public static function statusesFilters(): array
    {
        $statuses = [
            self::STATUS_ACTIVE => ucfirst(self::STATUS_ACTIVE),
            self::STATUS_PENDING => ucfirst(self::STATUS_PENDING),
            self::STATUS_INACTIVE => ucfirst(self::STATUS_INACTIVE),
            self::STATUS_BANNED => ucfirst(self::STATUS_BANNED),
            self::STATUS_ARCHIVED => ucfirst(self::STATUS_ARCHIVED),
        ];

        ksort($statuses);

        return $statuses;
    }
}