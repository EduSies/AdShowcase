<?php

declare(strict_types=1);

namespace app\services\back_office\shared_link;

use app\models\SharedLink;
use Yii;

final class BackOfficeSharedLinkListService
{
    /** Returns array for DataTables. */
    public function findAll(): array
    {
        $query = SharedLink::find()
            ->alias('sl')
            ->select([
                'sl.*',
                'creative_name' => 'c.title',
                'creative_hash' => 'c.hash',
                'user_name' => 'u.name',
                'user_surname' => 'u.surname'
            ])
            ->joinWith(['creative c', 'user u'], false);

        if (Yii::$app->user->identity->type !== 'admin') {
            $query->andWhere(['sl.user_id' => Yii::$app->user->id]);
        }

        $rows = $query->orderBy(['sl.id' => SORT_DESC])
            ->asArray()
            ->all();

        return array_map(function ($row) {

            // Concatenar nombre
            $row['full_name'] = trim(($row['user_name'] ?? '') . ' ' . ($row['user_surname'] ?? ''));

            // Lógica de "Usos / Máximo" (Maneja el NULL como 'Unlimited')
            $maxLabel = ($row['max_uses'] === null) ? Yii::t('app', 'Unlimited') : $row['max_uses'];
            $row['previews_used'] = $row['used_count'] . ' / ' . $maxLabel;

            // Formato de Fechas
            $row['created_at'] = Yii::$app->formatter->asDatetime($row['created_at'], 'php:Y-m-d H:i');

            // Expiración (Maneja el NULL como 'Unlimited')
            $row['expires_at'] = $row['expires_at']
                ? Yii::$app->formatter->asDatetime($row['expires_at'], 'php:Y-m-d H:i')
                : Yii::t('app', 'Unlimited');

            $row['revoked_at'] = $row['revoked_at']
                ? Yii::$app->formatter->asDatetime($row['revoked_at'], 'php:Y-m-d H:i')
                : Yii::t('app', 'Unknown');

            return $row;
        }, $rows);
    }
}