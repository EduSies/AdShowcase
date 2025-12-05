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
        return [
            // ===== Dashboard =====
            'index' => ['class' => \app\controllers\actions\back_office\user\UserIndexAction::class],

            // ===== Taxonomías / Catálogos (brands, agencies, devices, formats, sales-types, products, countries) =====
            'brands' => ['class' => \app\controllers\actions\back_office\brand\BrandIndexAction::class],
            'brands-datatable' => ['class' => \app\controllers\actions\back_office\brand\BrandDataTableAction::class],
            'brand-create' => ['class' => \app\controllers\actions\back_office\brand\BrandCreateAction::class],
            'brand-update' => ['class' => \app\controllers\actions\back_office\brand\BrandUpdateAction::class],
            'brand-delete' => ['class' => \app\controllers\actions\back_office\brand\BrandDeleteAction::class],

            'agencies' => ['class' => \app\controllers\actions\back_office\agency\AgencyIndexAction::class],
            'agencies-datatable' => ['class' => \app\controllers\actions\back_office\agency\AgencyDatatableAction::class],
            'agency-create' => ['class' => \app\controllers\actions\back_office\agency\AgencyCreateAction::class],
            'agency-update' => ['class' => \app\controllers\actions\back_office\agency\AgencyUpdateAction::class],
            'agency-delete' => ['class' => \app\controllers\actions\back_office\agency\AgencyDeleteAction::class],

            'devices' => ['class' => \app\controllers\actions\back_office\device\DeviceIndexAction::class],
            'devices-datatable' => ['class' => \app\controllers\actions\back_office\device\DeviceDatatableAction::class],
            'device-create' => ['class' => \app\controllers\actions\back_office\device\DeviceCreateAction::class],
            'device-update' => ['class' => \app\controllers\actions\back_office\device\DeviceUpdateAction::class],
            'device-delete' => ['class' => \app\controllers\actions\back_office\device\DeviceDeleteAction::class],

            'formats' => ['class' => \app\controllers\actions\back_office\format\FormatIndexAction::class],
            'formats-datatable' => ['class' => \app\controllers\actions\back_office\format\FormatDatatableAction::class],
            'format-create' => ['class' => \app\controllers\actions\back_office\format\FormatCreateAction::class],
            'format-update' => ['class' => \app\controllers\actions\back_office\format\FormatUpdateAction::class],
            'format-delete' => ['class' => \app\controllers\actions\back_office\format\FormatDeleteAction::class],

            'sales-types' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeIndexAction::class],
            'sales-types-datatable' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeDatatableAction::class],
            'sales-type-create' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeCreateAction::class],
            'sales-type-update' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeUpdateAction::class],
            'sales-type-delete' => ['class' => \app\controllers\actions\back_office\sales_type\SalesTypeDeleteAction::class],

            'products' => ['class' => \app\controllers\actions\back_office\product\ProductIndexAction::class],
            'products-datatable' => ['class' => \app\controllers\actions\back_office\product\ProductDatatableAction::class],
            'product-create' => ['class' => \app\controllers\actions\back_office\product\ProductCreateAction::class],
            'product-update' => ['class' => \app\controllers\actions\back_office\product\ProductUpdateAction::class],
            'product-delete' => ['class' => \app\controllers\actions\back_office\product\ProductDeleteAction::class],

            'countries' => ['class' => \app\controllers\actions\back_office\country\CountryIndexAction::class],
            'countries-datatable' => ['class' => \app\controllers\actions\back_office\country\CountryDatatableAction::class],
            'country-create' => ['class' => \app\controllers\actions\back_office\country\CountryCreateAction::class],
            'country-update' => ['class' => \app\controllers\actions\back_office\country\CountryUpdateAction::class],
            'country-delete' => ['class' => \app\controllers\actions\back_office\country\CountryDeleteAction::class],

            // ===== Creatives =====
            //'creatives' => ['class' => \app\controllers\actions\back_office\creative\CreativeIndexAction::class],
            'creatives-datatable' => ['class' => \app\controllers\actions\back_office\creative\CreativeDatatableAction::class],
            'creative-create' => ['class' => \app\controllers\actions\back_office\creative\CreativeCreateAction::class],
            'creative-update' => ['class' => \app\controllers\actions\back_office\creative\CreativeUpdateAction::class],
            'creative-delete' => ['class' => \app\controllers\actions\back_office\creative\CreativeDeleteAction::class],

            // ===== Usuarios =====
            'users' => ['class' => \app\controllers\actions\back_office\user\UserIndexAction::class],
            'users-datatable' => ['class' => \app\controllers\actions\back_office\user\UserDatatableAction::class],
            'user-create' => ['class' => \app\controllers\actions\back_office\user\UserCreateAction::class],
            'user-update' => ['class' => \app\controllers\actions\back_office\user\UserUpdateAction::class],
            'user-delete' => ['class' => \app\controllers\actions\back_office\user\UserDeleteAction::class],
            'user-assign-role' => ['class' => \app\controllers\actions\back_office\user\UserAssignRoleAction::class],

            // ===== Favoritos =====
            'fav-lists' => ['class' => \app\controllers\actions\back_office\fav_list\FavListIndexAction::class],
            'fav-lists-datatable' => ['class' => \app\controllers\actions\back_office\fav_list\FavListDatatableAction::class],
            'fav-list-create' => ['class' => \app\controllers\actions\back_office\fav_list\FavListCreateAction::class],
            'fav-list-delete' => ['class' => \app\controllers\actions\back_office\fav_list\FavListDeleteAction::class],

            // ===== Enlaces compartidos =====
            'shared-links' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkIndexAction::class],
            'shared-links-datatable' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkDatatableAction::class],
            'shared-link-revoke' => ['class' => \app\controllers\actions\back_office\shared_link\SharedLinkRevokeAction::class],

            // ===== Auditoría =====
            'audit' => ['class' => \app\controllers\actions\back_office\audit\AuditIndexAction::class],
            'audit-datatable' => ['class' => \app\controllers\actions\back_office\audit\AuditDatatableAction::class],
        ];
    }
}