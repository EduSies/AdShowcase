<?php

declare(strict_types=1);

namespace app\services\back_office\user;

use app\models\User;
use yii\db\Expression;

final class BackOfficeUserListService
{
    /** Returns flat array for DataTables. */
    public function findAll(): array
    {
        return User::find()
            ->alias('u')
            ->select([
                'u.*',
                'email_username'  => new Expression("TRIM(CONCAT(u.email, ' (', u.username, ')'))"),
                'full_name'  => new Expression("TRIM(CONCAT(u.name, ' ', u.surname))"),
                'language_name'   => 'l.display_name_en',
                'created_at' => new Expression("DATE_FORMAT(u.created_at, '%Y-%m-%d')"),
                'updated_at' => new Expression("DATE_FORMAT(u.updated_at, '%Y-%m-%d')"),
            ])
            ->joinWith(['language l'], false)
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
    }
}