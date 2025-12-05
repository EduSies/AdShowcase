<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class BaseWebController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->response->redirect(['auth/login']);
                    }
                    throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'You do not have access permissions.'));
                },
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actions()
    {
        $this->layout = '@adshowcase/views/layouts/main';

        return array_merge(parent::actions(), [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
                'view' => '@adshowcase/views/layouts/error',
                //'layout' => '@adshowcase/views/layouts/layout-error'
            ],
        ]);
    }

    /**
     * Off-canvas menu configuration shared by all web controllers.
     * Controllers can override $offCanvasMenu if they need a custom menu.
     *
     * @var array<string,mixed>
     */
    protected array $offCanvasMenu = [
        [
            'section' => 'creatives',
            'label' => 'Creatives',
            'icon' => 'bi-collection-play',
            // Cualquier usuario con acceso al backoffice ve la sección Creatives
            'permission' => 'backoffice.access',
            'items' => [
                [
                    'label' => 'Creatives',
                    'icon' => 'bi-collection-play',
                    'url' => ['back-office/creatives'],
                    'visible' => true,
                    'activePattern' => '@^back-office/creatives@',
                    'description' => 'Browse and manage creatives',
                    // Listado/gestión de creatives en backoffice:
                    // lo restringimos también a perfiles internos
                    'permission' => 'backoffice.access',
                ],
            ],
        ],
        [
            'section' => 'taxonomies',
            'label' => 'Taxonomies',
            'icon' => 'bi-diagram-3',
            // Solo perfiles con acceso al backoffice
            'permission' => 'backoffice.access',
            'items' => [
                [
                    'label' => 'Brands',
                    'icon' => 'bi-badge-tm',
                    'url' => ['back-office/brands'],
                    'visible' => true,
                    'activePattern' => '@^back-office/brands@',
                    'description' => 'Manage advertiser brands',
                    // Gestionar catálogos -> taxonomies.manage (solo admin)
                    'permission' => 'taxonomies.manage',
                ],
                [
                    'label' => 'Agencies',
                    'icon' => 'bi-building',
                    'url' => ['back-office/agencies'],
                    'visible' => true,
                    'activePattern' => '@^back-office/agencies@',
                    'description' => 'Manage agencies and clients',
                    'permission' => 'taxonomies.manage',
                ],
                [
                    'label' => 'Devices',
                    'icon' => 'bi-phone',
                    'url' => ['back-office/devices'],
                    'visible' => true,
                    'activePattern' => '@^back-office/devices@',
                    'description' => 'Configure device types',
                    'permission' => 'taxonomies.manage',
                ],
                [
                    'label' => 'Formats',
                    'icon' => 'bi-aspect-ratio',
                    'url' => ['back-office/formats'],
                    'visible' => true,
                    'activePattern' => '@^back-office/formats@',
                    'description' => 'Define ad formats and variants',
                    'permission' => 'taxonomies.manage',
                ],
                [
                    'label' => 'Countries',
                    'icon' => 'bi-globe',
                    'url' => ['back-office/countries'],
                    'visible' => true,
                    'activePattern' => '@^back-office/countries@',
                    'description' => 'Country catalog and targeting',
                    'permission' => 'taxonomies.manage',
                ],
                [
                    'label' => 'Products',
                    'icon' => 'bi-bag',
                    'url' => ['back-office/products'],
                    'visible' => true,
                    'activePattern' => '@^back-office/products@',
                    'description' => 'Manage products and lines',
                    'permission' => 'taxonomies.manage',
                ],
                [
                    'label' => 'Sales Types',
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
            'label' => 'Users',
            'icon' => 'bi-people',
            'permission' => 'backoffice.access',
            'items' => [
                [
                    'label' => 'Users',
                    'icon' => 'bi-person-badge',
                    'url' => ['back-office/users'],
                    'visible' => true,
                    'activePattern' => '@^back-office/users@',
                    'description' => 'Manage users and roles',
                    // Gestión de usuarios -> users.manage (solo admin)
                    'permission' => 'users.manage',
                ],
            ],
        ],
        [
            'section' => 'shared_links',
            'label' => 'Shared links',
            'icon' => 'bi-share',
            'permission' => 'backoffice.access',
            'items' => [
                [
                    'label' => 'Shared links',
                    'icon' => 'bi-link-45deg',
                    'url' => ['back-office/shared-link'],
                    'visible' => true,
                    'activePattern' => '@^back-office/shared-link@',
                    'description' => 'View and manage shared links',
                    // Crear/gestionar enlaces compartidos -> share.manage
                    'permission' => 'share.manage',
                ],
            ],
        ],
        [
            'section' => 'audit_settings',
            'label' => 'Audit & settings',
            'icon' => 'bi-gear',
            'permission' => 'backoffice.access',
            'items' => [
                [
                    'label' => 'Audit log',
                    'icon' => 'bi-journal-text',
                    'url' => ['back-office/audit-log'],
                    'visible' => true,
                    'activePattern' => '@^back-office/audit-log@',
                    'description' => 'Review system audit logs',
                    // Ver auditoría -> audit.view (solo admin)
                    'permission' => 'audit.view',
                ],
                [
                    'label' => 'Settings',
                    'icon' => 'bi-sliders',
                    'url' => ['back-office/settings'],
                    'visible' => true,
                    'activePattern' => '@^back-office/settings@',
                    'description' => 'Configure platform settings',
                    // Asumimos que solo admin (proxy: users.manage)
                    'permission' => 'users.manage',
                ],
            ],
        ],
    ];

    /**
     * Returns sections menu with visibility computed from RBAC permissions.
     *
     * Rules:
     * - If a section has 'permission', the user must have it for its items to be considered visible.
     * - If an item has 'permission', the user must have it for that item to be visible.
     * - Existing 'visible' flags are respected and combined (AND) with permission checks.
     *
     * The view/off-canvas will later filter out items where 'visible' is false and
     * hide sections whose visible items list is empty.
     */
    public function getSectionsMenu(): array
    {
        $user    = Yii::$app->user;
        $isGuest = $user->isGuest;

        // Work on a copy so the base configuration remains unchanged.
        $menu = $this->offCanvasMenu;

        foreach ($menu as $sectionIndex => $section) {
            $sectionPermission = $section['permission'] ?? null;

            // Base visibility for the section according to its own permission (if any).
            $sectionAllowed = true;
            if ($sectionPermission !== null) {
                $sectionAllowed = !$isGuest && $user->can($sectionPermission);
            }

            // We don't currently use section-level 'visible' in the views,
            // but we set it for consistency.
            $sectionVisible = $sectionAllowed;
            if (array_key_exists('visible', $section)) {
                $sectionVisible = $sectionVisible && (bool)$section['visible'];
            }

            $items = $section['items'] ?? [];
            foreach ($items as $itemIndex => $item) {
                // Start from section visibility
                $itemVisible = $sectionVisible;

                // Respect explicit 'visible' on the item if present
                if (array_key_exists('visible', $item)) {
                    $itemVisible = $itemVisible && (bool)$item['visible'];
                }

                // Apply item-level permission if defined
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