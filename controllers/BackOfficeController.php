<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * BackOfficeController
 *
 * Controlador único del BackOffice con acciones delegadas (Action Classes).
 * Cada URL del backOffice apunta a una Action concreta del espacio:
 *   app\controllers\actions\back_office\*
 *
 * Ejemplos de rutas:
 *   - /back_office/index            -> Listado de usuarios
 *   - /back_office/brands           -> Listado de marcas
 *   - /back_office/brands-datatable -> DataTable AJAX de marcas
 *   - /back_office/brand-create     -> Alta de marca
 *   - /back_office/brand-update     -> Edición de marca
 *   - /back_office/brand-delete     -> Borrado de marca
 *   (equivalentes para agencies, devices, formats, sales-types, products...)
 *
 * Requisitos de acceso:
 *   - backoffice.access para entrar al área
 *   - permisos específicos para CRUD (p.ej. catalog.manage / taxonomies.manage, users.manage, creative.manage, etc.)
 */
class BackOfficeController extends BaseWebController
{
    /**
     * Aseguramos que solo roles con "backoffice.access" puedan entrar aquí.
     * (Además del AccessControl global de BaseWebController que exige sesión).
     */
    public function behaviors(): array
    {
        $parent = parent::behaviors();

        $parent['accessBackOffice'] = [
            'class' => AccessControl::class,
            'denyCallback' => function () {
                if (Yii::$app->user->isGuest) {
                    return Yii::$app->response->redirect(['auth/login']);
                }
                throw new ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
            },
            'rules' => [
                ['allow' => true, 'roles' => ['@'], 'matchCallback' => function () {
                    return Yii::$app->user->can('backoffice.access');
                }],
            ],
        ];

        return $parent;
    }

    /**
     * Mapa de acciones del BackOffice.
     * Crea las clases que se indican en app\controllers\actions\back_office\*
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            // ===== Dashboard =====
            'index' => ['class' => \app\controllers\actions\back_office\user\UserIndexAction::class],

            // ===== Taxonomías / Catálogos (brands, agencies, devices, formats, sales-types, products, countries) =====
            'brands' => ['class' => \app\controllers\actions\back_office\brand\BrandIndexAction::class],
            'brand-create' => ['class' => \app\controllers\actions\back_office\brand\BrandCreateAction::class],
            'brand-update' => ['class' => \app\controllers\actions\back_office\brand\BrandUpdateAction::class],
            'brand-delete' => ['class' => \app\controllers\actions\back_office\brand\BrandDeleteAction::class],

            'agencies' => ['class' => \app\controllers\actions\back_office\agency\AgencyIndexAction::class],
            'agency-create' => ['class' => \app\controllers\actions\back_office\agency\AgencyCreateAction::class],
            'agency-update' => ['class' => \app\controllers\actions\back_office\agency\AgencyUpdateAction::class],
            'agency-delete' => ['class' => \app\controllers\actions\back_office\agency\AgencyDeleteAction::class],

            'devices' => ['class' => \app\controllers\actions\back_office\device\DeviceIndexAction::class],
            'device-create' => ['class' => \app\controllers\actions\back_office\device\DeviceCreateAction::class],
            'device-update' => ['class' => \app\controllers\actions\back_office\device\DeviceUpdateAction::class],
            'device-delete' => ['class' => \app\controllers\actions\back_office\device\DeviceDeleteAction::class],

            'formats' => ['class' => \app\controllers\actions\back_office\format\FormatIndexAction::class],
            'format-create' => ['class' => \app\controllers\actions\back_office\format\FormatCreateAction::class],
            'format-update' => ['class' => \app\controllers\actions\back_office\format\FormatUpdateAction::class],
            'format-delete' => ['class' => \app\controllers\actions\back_office\format\FormatDeleteAction::class],

            'sales-types' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeIndexAction::class],
            'sales-type-create' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeCreateAction::class],
            'sales-type-update' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeUpdateAction::class],
            'sales-type-delete' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeDeleteAction::class],

            'products' => ['class' => \app\controllers\actions\back_office\product\ProductIndexAction::class],
            'product-create' => ['class' => \app\controllers\actions\back_office\product\ProductCreateAction::class],
            'product-update' => ['class' => \app\controllers\actions\back_office\product\ProductUpdateAction::class],
            'product-delete' => ['class' => \app\controllers\actions\back_office\product\ProductDeleteAction::class],

            'countries' => ['class' => \app\controllers\actions\back_office\country\CountryIndexAction::class],
            'country-create' => ['class' => \app\controllers\actions\back_office\country\CountryCreateAction::class],
            'country-update' => ['class' => \app\controllers\actions\back_office\country\CountryUpdateAction::class],
            'country-delete' => ['class' => \app\controllers\actions\back_office\country\CountryDeleteAction::class],
            // ===== Taxonomías / Catálogos (brands, agencies, devices, formats, sales-types, products, countries) =====

            'creatives' => ['class' => \app\controllers\actions\back_office\creative\CreativeIndexAction::class],
            'creative-create' => ['class' => \app\controllers\actions\back_office\creative\CreativeCreateAction::class],
            'creative-update' => ['class' => \app\controllers\actions\back_office\creative\CreativeUpdateAction::class],
            'creative-delete' => ['class' => \app\controllers\actions\back_office\creative\CreativeDeleteAction::class],

            'users' => ['class' => \app\controllers\actions\back_office\user\UserIndexAction::class],
            'user-create' => ['class' => \app\controllers\actions\back_office\user\UserCreateAction::class],
            'user-update' => ['class' => \app\controllers\actions\back_office\user\UserUpdateAction::class],
            'user-delete' => ['class' => \app\controllers\actions\back_office\user\UserDeleteAction::class],
            'user-assign-role' => ['class' => \app\controllers\actions\back_office\user\UserAssignRoleAction::class],
            'user-resend-verification' => ['class' => \app\controllers\actions\back_office\user\ResendVerificationAction::class],

            'shared-link' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkIndexAction::class],
            'shared-link-update' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkUpdateAction::class],
            'shared-link-delete' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkDeleteAction::class],
            'shared-link-revoke' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkRevokeAction::class],
        ]);
    }
}