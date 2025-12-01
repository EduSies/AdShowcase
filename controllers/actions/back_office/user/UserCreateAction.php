<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\user;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\UserForm;
use app\services\back_office\user\BackOfficeUserCreateService;
use app\services\rbac\RbacRolesService;
use Yii;

final class UserCreateAction extends BaseBackOfficeAction
{
    public ?string $can = 'users.manage';
    public ?string $modelClass = UserForm::class;
    public ?string $view = '@app/views/back_office/user/' . UserForm::FORM_NAME;

    public function run()
    {
        $this->ensureCan($this->can);

        $class = $this->modelClass;
        $model = new $class(['scenario' => UserForm::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeUserCreateService();
            $ok = $service->create($model);

            if ($ok) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Created successfully.'));
                return $this->controller->redirect(['back-office/users']);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to create user.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'roles' => (new RbacRolesService)->getRolesDropDown(),
        ]);
    }
}