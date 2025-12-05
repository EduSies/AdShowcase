<?php

namespace app\controllers;

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
        return [
            // ===== Dashboard =====
            'index' => [
                'class' => \app\controllers\actions\site\DashboardIndexAction::class,
                'sections' => $this->getSectionsMenu(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    /*    public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
            ];
        }*/

/*    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(\Yii::$app->request->post()) && $model->contact(\Yii::$app->params['adminEmail'])) {
            \Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }*/
}