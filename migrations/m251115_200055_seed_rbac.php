<?php

/**
 * Seed de **roles y permisos** (RBAC).
 *
 * Roles base:
 * - admin : control total + backoffice.
 * - editor : gestión de contenido + backoffice.
 * - sales : ver, compartir, favoritos (SIN backoffice).
 * - viewer : sólo lectura (SIN backoffice).
 */

use yii\db\Migration;

class m251115_200055_seed_rbac extends Migration
{
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        // Crear permisos
        $pViewCreative = $auth->getPermission('creative.view') ?: $auth->createPermission('creative.view');
        $pManageCreative = $auth->getPermission('creative.manage') ?: $auth->createPermission('creative.manage');
        $pShareManage = $auth->getPermission('share.manage') ?: $auth->createPermission('share.manage');
        $pFavManage = $auth->getPermission('favorite.manage') ?: $auth->createPermission('favorite.manage');
        $pTaxManage = $auth->getPermission('taxonomies.manage') ?: $auth->createPermission('taxonomies.manage');
        $pUsersManage = $auth->getPermission('users.manage') ?: $auth->createPermission('users.manage');
        $pAuditView = $auth->getPermission('audit.view') ?: $auth->createPermission('audit.view');
        $pBackoffice = $auth->getPermission('backoffice.access') ?: $auth->createPermission('backoffice.access');

        // Descripciones
        $pViewCreative->description = 'Ver creatives y metadatos';
        $pManageCreative->description = 'Crear/editar/eliminar creatives';
        $pShareManage->description = 'Gestión de enlaces compartidos';
        $pFavManage->description = 'Gestión de favoritos';
        $pTaxManage->description = 'Gestión de taxonomías';
        $pUsersManage->description = 'Gestión de usuarios';
        $pAuditView->description = 'Ver auditoría';
        $pBackoffice->description = 'Entrar al backoffice';

        foreach ([$pViewCreative, $pManageCreative, $pShareManage, $pFavManage, $pTaxManage, $pUsersManage, $pAuditView, $pBackoffice] as $perm) {
            if ($auth->getPermission($perm->name) === null) {
                $auth->add($perm);
            }
        }

        // Crear roles
        $rAdmin = $auth->getRole('admin') ?: $auth->createRole('admin');
        $rEditor = $auth->getRole('editor') ?: $auth->createRole('editor');
        $rSales = $auth->getRole('sales') ?: $auth->createRole('sales');
        $rViewer = $auth->getRole('viewer') ?: $auth->createRole('viewer');

        $rAdmin->description = 'Administrator';
        $rEditor->description = 'Editor';
        $rSales->description = 'Sales';
        $rViewer->description = 'Viewer';

        foreach ([$rAdmin, $rEditor, $rSales, $rViewer] as $role) {
            if ($auth->getRole($role->name) === null) {
                $auth->add($role);
            } else {
                $auth->update($role->name, $role);
            }
        }

        // VIEWER: Solo ver
        $this->grantIfMissing($auth, $rViewer, $pViewCreative);

        // SALES: Ver + Favoritos + Compartir
        $this->grantIfMissing($auth, $rSales, $pViewCreative);
        $this->grantIfMissing($auth, $rSales, $pFavManage);
        $this->grantIfMissing($auth, $rSales, $pShareManage);

        // EDITOR: lo anterior + Gestionar Creatives + Backoffice
        $this->grantIfMissing($auth, $rEditor, $pViewCreative);
        $this->grantIfMissing($auth, $rEditor, $pManageCreative);
        $this->grantIfMissing($auth, $rEditor, $pShareManage);
        $this->grantIfMissing($auth, $rEditor, $pFavManage);
        $this->grantIfMissing($auth, $rEditor, $pBackoffice);

        // ADMIN: Full access
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

        foreach (['admin','editor','sales','viewer'] as $roleName) {
            if ($role = $auth->getRole($roleName)) {
                $auth->removeChildren($role);
                $auth->remove($role);
            }
        }

        // Eliminar permisos
        foreach (['creative.view','creative.manage','share.manage','favorite.manage','taxonomies.manage','users.manage','audit.view','backoffice.access'] as $permName) {
            if ($perm = $auth->getPermission($permName)) {
                $auth->remove($perm);
            }
        }

        // Eliminar roles
        foreach (['admin','editor','sales','viewer','guest'] as $roleName) {
            if ($role = $auth->getRole($roleName)) {
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