<?php

declare(strict_types=1);

namespace app\services\rbac;

use Yii;
use yii\db\Query;

final class RbacRolesService
{
    /**
     * Devuelve un array listo para dropDownList con TODOS los roles (type = 1):
     *
     * [
     *   'admin'  => 'Role Admin',
     *   'editor' => 'Role Editor',
     *   ...
     * ]
     */
    public function getRolesDropDown(): array
    {
        $rows = (new Query())
            ->select(['name', 'description'])
            ->from('{{%auth_item}}')
            ->where(['type' => 1]) // 1 = ROLE
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $items = [];

        foreach ($rows as $row) {
            $name = (string) $row['name'];
            $description = $row['description'] ?? null;

            // Usamos la descripción como label principal (p.ej. "Role Admin")
            if (!empty($description)) {
                $label = Yii::t('app', $description);
            } else {
                // Fallback si no hay descripción
                $label = Yii::t('app', ucfirst($name));
            }

            $items[$name] = $label;
        }

        return $items;
    }
}