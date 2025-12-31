<?php

declare(strict_types=1);

namespace app\helpers;

use Yii;

class StatusHelper
{
    // Constantes para Back Office
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_BANNED = 'banned';
    public const STATUS_ARCHIVED = 'archived';

    // Constantes para Creative Workflow Status
    public const WORKFLOW_DRAFT = 'draft';
    public const WORKFLOW_REVIEWED = 'reviewed';
    public const WORKFLOW_APPROVED = 'approved';

    /**
     * Devuelve la lista de estados traducidos.
     * * @param int|array|null $filter
     * - null: Devuelve todos.
     * - int (ej: 3): Devuelve los primeros 3 elementos.
     * - array (ej: ['active', 'banned']): Devuelve solo esos estados específicos.
     */
    public static function statusFilter($filter = null): array
    {
        // Definimos el orden lógico deseado (no alfabético)
        $statuses = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_PENDING => Yii::t('app', 'Pending'),
            self::STATUS_ARCHIVED => Yii::t('app', 'Archived'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            self::STATUS_BANNED => Yii::t('app', 'Banned'),
        ];

        // CASO 1: Si pasas un número (ej: 3), devuelve los 3 primeros
        if (is_int($filter)) {
            return array_slice($statuses, 0, $filter);
        }

        // CASO 2: Si pasas un array de claves, devuelve solo esos
        if (is_array($filter) && !empty($filter)) {
            // array_flip convierte ['active', 'banned'] en ['active'=>0, 'banned'=>1] para cruzar claves
            return array_intersect_key($statuses, array_flip($filter));
        }

        return $statuses;
    }

    /**
     * Helper para validaciones 'range' o 'in'.
     * Reutiliza la lógica de arriba para no duplicar código.
     */
    public static function getStatusRange($filter = null): array
    {
        return array_keys(self::statusFilter($filter));
    }

    /**
     * Devuelve la lista de estados de Workflow traducidos.
     * * @param int|array|null $filter
     * * - null: Devuelve todos.
     * * - int (ej: 3): Devuelve los primeros 3 elementos.
     * * - array (ej: ['active', 'banned']): Devuelve solo esos estados específicos.
     */
    public static function workflowStatusFilter($filter = null): array
    {
        // Definimos el orden lógico del flujo de trabajo
        $statuses = [
            self::WORKFLOW_DRAFT => Yii::t('app', 'Draft'),
            self::WORKFLOW_REVIEWED => Yii::t('app', 'Reviewed'),
            self::WORKFLOW_APPROVED => Yii::t('app', 'Approved'),
        ];

        // CASO 1: Si pasas un número, devuelve los N primeros pasos del flujo
        if (is_int($filter)) {
            return array_slice($statuses, 0, $filter);
        }

        // CASO 2: Si pasas un array de claves específicas
        if (is_array($filter) && !empty($filter)) {
            return array_intersect_key($statuses, array_flip($filter));
        }

        return $statuses;
    }

    /**
     * Helper para validaciones 'range' o 'in' de Workflow.
     * Devuelve solo las claves (['draft', 'reviewed', 'approved']).
     */
    public static function getWorkflowStatusRange($filter = null): array
    {
        return array_keys(self::workflowStatusFilter($filter));
    }
}