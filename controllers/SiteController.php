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
                return Yii::$app->response->redirect(['catalog/index']);
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

    /**
     * Previsualiza el diseño del correo en el navegador.
     * Accede vía: /site/email-preview
     */
    public function actionEmailPreview()
    {
        $dummyContent = '
            <div style="text-align: center;">
                <p style="font-family: Helvetica, Arial, sans-serif; color: #1f2937; font-weight: bold; margin-bottom: 5px;">
                    Hey! AdShowcase has shared a preview with you
                </p>
                
                <h2 style="font-family: Helvetica, Arial, sans-serif; color: #FF6600; margin: 0 0 10px 0; font-size: 24px;">
                    Summer Campaign 2026
                </h2>
                
                <p style="font-family: Helvetica, Arial, sans-serif; color: #9ca3af; font-size: 14px; margin-bottom: 30px;">
                    Gaming Skin / Video Overlay
                </p>

                <a href="#" style="background-color: #2563eb; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
                    CLICK HERE TO SEE THE PREVIEW
                </a>
            </div>
        ';

        return $this->renderPartial('@app/mail/layouts/html', [
            'content' => $dummyContent,
        ]);
    }
}