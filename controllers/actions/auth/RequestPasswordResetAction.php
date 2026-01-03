<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use app\models\forms\auth\RequestPasswordResetForm;
use app\services\auth\AuthService;
use Yii;
use yii\bootstrap5\ActiveForm;

final class RequestPasswordResetAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';
    public ?string $view = '@app/views/auth/' . RequestPasswordResetForm::FORM_NAME;

    public function run()
    {
        $model = new RequestPasswordResetForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new AuthService();

            if ($service->requestPasswordReset(trim($model->email))) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                return $this->controller->redirect(['auth/login']);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.'));
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'title' => Yii::t('app', 'Request Password Reset'),
        ]);
    }
}