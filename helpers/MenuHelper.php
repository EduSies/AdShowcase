<?php

declare(strict_types=1);

namespace app\helpers;

use Yii;

class MenuHelper
{
    /**
     * Retorna la configuración completa del menú BackOffice filtrada por permisos RBAC.
     */
    public static function getBackOfficeMenu(): array
    {
        $user = Yii::$app->user;
        $isGuest = $user->isGuest;

        $menu = [
            [
                'section' => 'creatives',
                'label' => Yii::t('app', 'Creatives'),
                'icon' => 'bi-collection-play',
                'permission' => 'backoffice.access',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Creatives'),
                        'icon' => 'bi-collection-play',
                        'url' => ['back-office/creatives'],
                        'visible' => true,
                        'activePattern' => '@^back-office/creatives@',
                        'description' => 'Browse and manage creatives',
                        'permission' => 'backoffice.access',
                    ],
                ],
            ],
            [
                'section' => 'taxonomies',
                'label' => Yii::t('app', 'Taxonomies'),
                'icon' => 'bi-diagram-3',
                'permission' => 'backoffice.access',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Brands'),
                        'icon' => 'bi-badge-tm',
                        'url' => ['back-office/brands'],
                        'visible' => true,
                        'activePattern' => '@^back-office/brands@',
                        'description' => 'Manage advertiser brands',
                        'permission' => 'taxonomies.manage',
                    ],
                    [
                        'label' => Yii::t('app', 'Agencies'),
                        'icon' => 'bi-building',
                        'url' => ['back-office/agencies'],
                        'visible' => true,
                        'activePattern' => '@^back-office/agencies@',
                        'description' => 'Manage agencies and clients',
                        'permission' => 'taxonomies.manage',
                    ],
                    [
                        'label' => Yii::t('app', 'Devices'),
                        'icon' => 'bi-phone',
                        'url' => ['back-office/devices'],
                        'visible' => true,
                        'activePattern' => '@^back-office/devices@',
                        'description' => 'Configure device types',
                        'permission' => 'taxonomies.manage',
                    ],
                    [
                        'label' => Yii::t('app', 'Formats'),
                        'icon' => 'bi-aspect-ratio',
                        'url' => ['back-office/formats'],
                        'visible' => true,
                        'activePattern' => '@^back-office/formats@',
                        'description' => 'Define ad formats and variants',
                        'permission' => 'taxonomies.manage',
                    ],
                    [
                        'label' => Yii::t('app', 'Countries'),
                        'icon' => 'bi-globe',
                        'url' => ['back-office/countries'],
                        'visible' => true,
                        'activePattern' => '@^back-office/countries@',
                        'description' => 'Country catalog and targeting',
                        'permission' => 'taxonomies.manage',
                    ],
                    [
                        'label' => Yii::t('app', 'Products'),
                        'icon' => 'bi-bag',
                        'url' => ['back-office/products'],
                        'visible' => true,
                        'activePattern' => '@^back-office/products@',
                        'description' => 'Manage products and lines',
                        'permission' => 'taxonomies.manage',
                    ],
                    [
                        'label' => Yii::t('app', 'Sales Types'),
                        'icon' => 'bi-cash-stack',
                        'url' => ['back-office/sales-types'],
                        'visible' => true,
                        'activePattern' => '@^back-office/sales-types@',
                        'description' => 'Define sales models and pricing',
                        'permission' => 'taxonomies.manage',
                    ],
                ],
            ],
            [
                'section' => 'users',
                'label' => Yii::t('app', 'Users'),
                'icon' => 'bi-people',
                'permission' => 'backoffice.access',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Users'),
                        'icon' => 'bi-person-badge',
                        'url' => ['back-office/users'],
                        'visible' => true,
                        'activePattern' => '@^back-office/users@',
                        'description' => 'Manage users and roles',
                        'permission' => 'users.manage',
                    ],
                ],
            ],
            [
                'section' => 'shared_links',
                'label' => Yii::t('app', 'Shared Links'),
                'icon' => 'bi-share',
                'permission' => 'backoffice.access',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Shared Links'),
                        'icon' => 'bi-link-45deg',
                        'url' => ['back-office/shared-link'],
                        'visible' => true,
                        'activePattern' => '@^back-office/shared-link@',
                        'description' => 'View and manage shared links',
                        'permission' => 'share.manage',
                    ],
                ],
            ],
            [
                'section' => 'audit_settings',
                'label' => Yii::t('app', 'Audit & settings'),
                'icon' => 'bi-gear',
                'permission' => 'backoffice.access',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Audit log'),
                        'icon' => 'bi-journal-text',
                        'url' => ['back-office/audit-log'],
                        'visible' => false,
                        'activePattern' => '@^back-office/audit-log@',
                        'description' => 'Review system audit logs',
                        'permission' => 'audit.view',
                    ],
                    [
                        'label' => Yii::t('app', 'Settings'),
                        'icon' => 'bi-sliders',
                        'url' => ['back-office/settings'],
                        'visible' => false,
                        'activePattern' => '@^back-office/settings@',
                        'description' => 'Configure platform settings',
                        'permission' => 'users.manage',
                    ],
                ],
            ],
        ];

        // Lógica de filtrado (Misma lógica que tenías en el controlador)
        foreach ($menu as $sectionIndex => $section) {
            $sectionPermission = $section['permission'] ?? null;

            $sectionAllowed = true;
            if ($sectionPermission !== null) {
                $sectionAllowed = !$isGuest && $user->can($sectionPermission);
            }

            $sectionVisible = $sectionAllowed;
            if (array_key_exists('visible', $section)) {
                $sectionVisible = $sectionVisible && (bool)$section['visible'];
            }

            $items = $section['items'] ?? [];
            foreach ($items as $itemIndex => $item) {
                $itemVisible = $sectionVisible;

                if (array_key_exists('visible', $item)) {
                    $itemVisible = $itemVisible && (bool)$item['visible'];
                }

                $itemPermission = $item['permission'] ?? null;
                if ($itemPermission !== null) {
                    $itemVisible = $itemVisible && !$isGuest && $user->can($itemPermission);
                }

                $items[$itemIndex]['visible'] = $itemVisible;
            }

            $menu[$sectionIndex]['visible'] = $sectionVisible;
            $menu[$sectionIndex]['items']   = $items;
        }

        return $menu;
    }
}