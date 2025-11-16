<?php

/**
 * Seed de **roles y permisos** (RBAC) para AdShowcase.
 *
 * Roles base:
 * - admin : todo el control del sistema **y acceso al backoffice**.
 * - editor : crea/edita/elimina creatives, comparte, ve todo, **acceso al backoffice**.
 * - sales : ve creatives, crea listas/favoritos, comparte, **acceso al backoffice**.
 * - viewer : sólo lectura del catálogo (usuarios autenticados).
 * - guest : visitante no autenticado (rol por defecto en config).
 *
 * Permisos propuestos:
 * - creative.view
 * - creative.manage
 * - share.manage
 * - favorite.manage
 * - taxonomies.manage  (brands/formats/countries/devices/sales_types/products)
 * - users.manage
 * - audit.view
 * - backoffice.access
 *
 * Herencia (árbol):
 *   admin
 *    ├─ backoffice.access
 *    ├─ users.manage
 *    ├─ taxonomies.manage
 *    ├─ audit.view
 *    └─ (todas las perm. de editor)
 *   editor
 *    ├─ backoffice.access
 *    ├─ creative.view
 *    ├─ creative.manage
 *    └─ share.manage
 *   sales
 *    ├─ backoffice.access
 *    ├─ creative.view
 *    └─ favorite.manage
 *   viewer
 *    └─ creative.view
 *   guest
 *    └─ (sin permisos explícitos; verás públicos si implementas lógica pública)
**/

use yii\db\Migration;

class m251115_200055_seed_rbac extends Migration
{
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        // 1) Crear permisos (si no existen)
        $pViewCreative = $auth->getPermission('creative.view') ?: $auth->createPermission('creative.view');
        $pManageCreative = $auth->getPermission('creative.manage') ?: $auth->createPermission('creative.manage');
        $pShareManage = $auth->getPermission('share.manage') ?: $auth->createPermission('share.manage');
        $pFavManage = $auth->getPermission('favorite.manage') ?: $auth->createPermission('favorite.manage');
        $pTaxManage = $auth->getPermission('taxonomies.manage') ?: $auth->createPermission('taxonomies.manage');
        $pUsersManage = $auth->getPermission('users.manage') ?: $auth->createPermission('users.manage');
        $pAuditView = $auth->getPermission('audit.view') ?: $auth->createPermission('audit.view');
        $pBackoffice = $auth->getPermission('backoffice.access') ?: $auth->createPermission('backoffice.access');

        // Descripciones
        $pViewCreative->description = 'Ver creatives y sus metadatos';
        $pManageCreative->description = 'Crear/editar/eliminar creatives';
        $pShareManage->description = 'Crear/gestionar enlaces compartidos';
        $pFavManage->description = 'Gestionar favoritos y listas';
        $pTaxManage->description = 'Gestionar catálogos (brands, formats, etc.)';
        $pUsersManage->description = 'Gestionar usuarios y roles';
        $pAuditView->description = 'Ver registros de auditoría';
        $pBackoffice->description = 'Acceder al backoffice';

        foreach ([$pViewCreative, $pManageCreative, $pShareManage, $pFavManage, $pTaxManage, $pUsersManage, $pAuditView, $pBackoffice] as $perm) {
            // Sólo añade si no existía
            if ($auth->getPermission($perm->name) === null) {
                $auth->add($perm);
            }
        }

        // 2) Crear roles (si no existen)
        $rAdmin = $auth->getRole('admin') ?: $auth->createRole('admin');
        $rEditor = $auth->getRole('editor') ?: $auth->createRole('editor');
        $rSales = $auth->getRole('sales') ?: $auth->createRole('sales');
        $rViewer = $auth->getRole('viewer') ?: $auth->createRole('viewer');
        $rGuest = $auth->getRole('guest') ?: $auth->createRole('guest');

        foreach ([$rAdmin, $rEditor, $rSales, $rViewer, $rGuest] as $role) {
            if ($auth->getRole($role->name) === null) {
                $auth->add($role);
            }
        }

        // 3) Asignar permisos a roles (componer árbol)
        // viewer: sólo ver
        $this->grantIfMissing($auth, $rViewer, $pViewCreative);

        // sales: ver + gestionar favoritos
        $this->grantIfMissing($auth, $rSales, $pViewCreative);
        $this->grantIfMissing($auth, $rSales, $pFavManage);
        // acceso al backoffice para perfiles internos
        $this->grantIfMissing($auth, $rSales, $pBackoffice);

        // editor: ver + gestionar creatives + compartir
        $this->grantIfMissing($auth, $rEditor, $pViewCreative);
        $this->grantIfMissing($auth, $rEditor, $pManageCreative);
        $this->grantIfMissing($auth, $rEditor, $pShareManage);
        $this->grantIfMissing($auth, $rEditor, $pBackoffice);

        // admin: todo lo de editor + gestión de usuarios, taxonomías y ver auditoría
        $this->grantIfMissing($auth, $rAdmin, $pViewCreative);
        $this->grantIfMissing($auth, $rAdmin, $pManageCreative);
        $this->grantIfMissing($auth, $rAdmin, $pShareManage);
        $this->grantIfMissing($auth, $rAdmin, $pFavManage);
        $this->grantIfMissing($auth, $rAdmin, $pTaxManage);
        $this->grantIfMissing($auth, $rAdmin, $pUsersManage);
        $this->grantIfMissing($auth, $rAdmin, $pAuditView);
        $this->grantIfMissing($auth, $rAdmin, $pBackoffice);
    }

    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        // Eliminar rol->permiso (revokeChildren borra enlaces del rol)
        foreach (['admin','editor','sales','viewer','guest'] as $roleName) {
            $role = $auth->getRole($roleName);
            if ($role) {
                $auth->removeChildren($role);
            }
        }

        // Eliminar permisos
        foreach (['creative.view','creative.manage','share.manage','favorite.manage','taxonomies.manage','users.manage','audit.view','backoffice.access'] as $permName) {
            $perm = $auth->getPermission($permName);
            if ($perm) {
                $auth->remove($perm);
            }
        }

        // Eliminar roles
        foreach (['admin','editor','sales','viewer','guest'] as $roleName) {
            $role = $auth->getRole($roleName);
            if ($role) {
                $auth->remove($role);
            }
        }
    }

    /**
     * Helper para añadir $child a $parent si no estaba enlazado.
     */
    private function grantIfMissing($auth, $parentRoleOrPerm, $childRoleOrPerm): void
    {
        // Evitar relaciones duplicadas
        $children = $auth->getChildren($parentRoleOrPerm->name);
        if (!isset($children[$childRoleOrPerm->name])) {
            $auth->addChild($parentRoleOrPerm, $childRoleOrPerm);
        }
    }
}