<?php

namespace app\controllers;

use app\models\ContactForm;
use Yii;
use yii\filters\AccessControl;

class SiteController extends BaseWebController
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function behaviors(): array
    {
        $parent = parent::behaviors();

        $parent['accessSite'] = [
            'class' => AccessControl::class,
            'denyCallback' => function () {
                if (Yii::$app->user->isGuest) {
                    return Yii::$app->response->redirect(['auth/login']);
                }

                // Authenticated user without backoffice.access
                // redirect to the public catalog.
                return Yii::$app->response->redirect(['catalog']);
            },
            'rules' => [
                ['allow' => true, 'roles' => ['@'], 'matchCallback' => function () {
                    return Yii::$app->user->can('backoffice.access');
                }],
            ],
        ];

        return $parent;
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            // ===== Dashboard =====
            'dashboard' => [
                'class' => \app\controllers\actions\site\DashboardIndexAction::class,
                'sections' => $this->getSectionsMenu(),
            ],
            'catalog' => [
                'class' => \app\controllers\actions\site\CatalogIndexAction::class,
                'sections' => $this->getSectionsMenu(),
            ],
        ]);
    }
}