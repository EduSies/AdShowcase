<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\user;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\UserForm;
use app\models\User;
use app\services\back_office\user\BackOfficeUserUpdateService;
use app\services\rbac\RbacRolesService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;

final class UserUpdateAction extends BaseBackOfficeAction
{
    public ?string $can = 'users.manage';
    public ?string $modelClass = UserForm::class;
    public ?string $view = '@app/views/back_office/user/' . UserForm::FORM_NAME;
    public ?array $indexRoute = ['back-office/users'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        $class = $this->modelClass;
        $user = User::findOne(['hash' => $hash]);

        if (!$user) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found.'));
        }

        $model = new $class(['scenario' => UserForm::SCENARIO_UPDATE]);
        $model->setAttributes([
            'id' => $user->id,
            'hash' => $user->hash,
            'email' => $user->email,
            'username' => $user->username,
            'type' => $user->type,
            'name' => $user->name,
            'surname' => $user->surname,
            'status' => $user->status,
            'language_id' => $user->language_id,
            'avatar_url' => $user->avatar_url,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeUserUpdateService();

            if ($service->update($user->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update user.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'roles' => (new RbacRolesService)->getRolesDropDown(),
            'status' => \app\helpers\StatusHelper::statusFilter(),
        ]);
    }
}