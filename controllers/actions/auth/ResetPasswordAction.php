<?php

declare(strict_types=1);

namespace app\controllers\actions\auth;

use app\models\forms\auth\ResetPasswordForm;
use app\services\auth\AuthService;
use Yii;
use yii\bootstrap5\ActiveForm;

final class ResetPasswordAction extends BaseLoginAction
{
    public ?string $layout = 'login-layout';
    public ?string $view = '@app/views/auth/' . ResetPasswordForm::FORM_NAME;

    public function run(string $token)
    {
        $service = new AuthService();

        try {
            // Validamos el token
            $user = $service->findByPasswordResetToken($token);

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->controller->redirect(['auth/login']);
        }

        $model = new ResetPasswordForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service->resetUserPassword($user, $model->password);

            Yii::$app->session->setFlash('success', Yii::t('app', 'New password saved. Welcome!'));

            return $this->controller->redirect(['auth/login']);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'title' => Yii::t('app', 'Reset Password'),
        ]);
    }
}