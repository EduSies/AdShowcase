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
            'index' => ['class' => \app\controllers\actions\back_office\users\UserIndexAction::class],

            // ===== Taxonomías / Catálogos (brands, agencies, devices, formats, sales-types, products, countries) =====
            'brands' => ['class' => \app\controllers\actions\back_office\brands\BrandIndexAction::class],
            'brands-datatable' => ['class' => \app\controllers\actions\back_office\brands\BrandDataTableAction::class],
            'brand-create' => ['class' => \app\controllers\actions\back_office\brands\BrandCreateAction::class],
            'brand-update' => ['class' => \app\controllers\actions\back_office\brands\BrandUpdateAction::class],
            'brand-delete' => ['class' => \app\controllers\actions\back_office\brands\BrandDeleteAction::class],

            'agencies' => ['class' => \app\controllers\actions\back_office\agencies\AgencyIndexAction::class],
            'agencies-datatable' => ['class' => \app\controllers\actions\back_office\agencies\AgencyDatatableAction::class],
            'agency-create' => ['class' => \app\controllers\actions\back_office\agencies\AgencyCreateAction::class],
            'agency-update' => ['class' => \app\controllers\actions\back_office\agencies\AgencyUpdateAction::class],
            'agency-delete' => ['class' => \app\controllers\actions\back_office\agencies\AgencyDeleteAction::class],

            'devices' => ['class' => \app\controllers\actions\back_office\devices\DeviceIndexAction::class],
            'devices-datatable' => ['class' => \app\controllers\actions\back_office\devices\DeviceDatatableAction::class],
            'device-create' => ['class' => \app\controllers\actions\back_office\devices\DeviceCreateAction::class],
            'device-update' => ['class' => \app\controllers\actions\back_office\devices\DeviceUpdateAction::class],
            'device-delete' => ['class' => \app\controllers\actions\back_office\devices\DeviceDeleteAction::class],

            'formats' => ['class' => \app\controllers\actions\back_office\formats\FormatIndexAction::class],
            'formats-datatable' => ['class' => \app\controllers\actions\back_office\formats\FormatDatatableAction::class],
            'format-create' => ['class' => \app\controllers\actions\back_office\formats\FormatCreateAction::class],
            'format-update' => ['class' => \app\controllers\actions\back_office\formats\FormatUpdateAction::class],
            'format-delete' => ['class' => \app\controllers\actions\back_office\formats\FormatDeleteAction::class],

            'sales-types' => ['class' => \app\controllers\actions\back_office\sales_types\SalesTypeIndexAction::class],
            'sales-types-datatable' => ['class' => \app\controllers\actions\back_office\sales_types\SalesTypeDatatableAction::class],
            'sales-type-create' => ['class' => \app\controllers\actions\back_office\sales_types\SalesTypeCreateAction::class],
            'sales-type-update' => ['class' => \app\controllers\actions\back_office\sales_types\SalesTypeUpdateAction::class],
            'sales-type-delete' => ['class' => \app\controllers\actions\back_office\sales_types\SalesTypeDeleteAction::class],

            'products' => ['class' => \app\controllers\actions\back_office\products\ProductIndexAction::class],
            'products-datatable' => ['class' => \app\controllers\actions\back_office\products\ProductDatatableAction::class],
            'product-create' => ['class' => \app\controllers\actions\back_office\products\ProductCreateAction::class],
            'product-update' => ['class' => \app\controllers\actions\back_office\products\ProductUpdateAction::class],
            'product-delete' => ['class' => \app\controllers\actions\back_office\products\ProductDeleteAction::class],

            'countries' => ['class' => \app\controllers\actions\back_office\countries\CountryIndexAction::class],
            'countries-datatable' => ['class' => \app\controllers\actions\back_office\countries\CountryDatatableAction::class],
            'country-create' => ['class' => \app\controllers\actions\back_office\countries\CountryCreateAction::class],
            'country-update' => ['class' => \app\controllers\actions\back_office\countries\CountryUpdateAction::class],
            'country-delete' => ['class' => \app\controllers\actions\back_office\countries\CountryDeleteAction::class],

            // ===== Creatives =====
            'creatives' => ['class' => \app\controllers\actions\back_office\creatives\CreativeIndexAction::class],
            'creatives-datatable' => ['class' => \app\controllers\actions\back_office\creatives\CreativeDatatableAction::class],
            'creative-create' => ['class' => \app\controllers\actions\back_office\creatives\CreativeCreateAction::class],
            'creative-update' => ['class' => \app\controllers\actions\back_office\creatives\CreativeUpdateAction::class],
            'creative-delete' => ['class' => \app\controllers\actions\back_office\creatives\CreativeDeleteAction::class],

            // ===== Usuarios =====
            'users' => ['class' => \app\controllers\actions\back_office\users\UserIndexAction::class],
            'users-datatable' => ['class' => \app\controllers\actions\back_office\users\UserDatatableAction::class],
            'user-create' => ['class' => \app\controllers\actions\back_office\users\UserCreateAction::class],
            'user-update' => ['class' => \app\controllers\actions\back_office\users\UserUpdateAction::class],
            'user-delete' => ['class' => \app\controllers\actions\back_office\users\UserDeleteAction::class],
            'user-assign-role' => ['class' => \app\controllers\actions\back_office\users\UserAssignRoleAction::class],

            // ===== Favoritos =====
            'fav-lists' => ['class' => \app\controllers\actions\back_office\fav_lists\FavListIndexAction::class],
            'fav-lists-datatable' => ['class' => \app\controllers\actions\back_office\fav_lists\FavListDatatableAction::class],
            'fav-list-create' => ['class' => \app\controllers\actions\back_office\fav_lists\FavListCreateAction::class],
            'fav-list-delete' => ['class' => \app\controllers\actions\back_office\fav_lists\FavListDeleteAction::class],

            // ===== Enlaces compartidos =====
            'shared-links' => ['class' => \app\controllers\actions\back_office\shared_links\SharedLinkIndexAction::class],
            'shared-links-datatable' => ['class' => \app\controllers\actions\back_office\shared_links\SharedLinkDatatableAction::class],
            'shared-link-revoke' => ['class' => \app\controllers\actions\back_office\shared_links\SharedLinkRevokeAction::class],

            // ===== Auditoría =====
            'audit' => ['class' => \app\controllers\actions\back_office\audit\AuditIndexAction::class],
            'audit-datatable' => ['class' => \app\controllers\actions\back_office\audit\AuditDatatableAction::class],
        ];
    }
}