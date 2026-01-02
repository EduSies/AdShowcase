<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\shared_link;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\forms\back_office\SharedLinkForm;
use app\models\SharedLink;
use app\services\back_office\shared_link\BackOfficeSharedLinkUpdateService;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

final class SharedLinkUpdateAction extends BaseBackOfficeAction
{
    public ?string $can  = 'share.manage';
    public ?string $modelClass = SharedLinkForm::class;
    public ?string $view = '@app/views/back_office/shared_link/' . SharedLinkForm::FORM_NAME;
    public ?array $indexRoute = ['/back-office/shared-link'];
    public string $idParam = 'hash';

    public function run()
    {
        $this->ensureCan($this->can);

        // Obtener el Hash de la URL
        $hash = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($hash === '') {
            throw new NotFoundHttpException(Yii::t('app', 'Missing hash.'));
        }

        // Buscar el SharedLink por Hash
        $sharedLink = SharedLink::findOne(['hash' => $hash]);

        if (!$sharedLink) {
            throw new NotFoundHttpException(Yii::t('app', 'Shared Link not found.'));
        }

        // Cargar el Formulario con los datos actuales
        $class = $this->modelClass;
        // Asumimos que creas un escenario UPDATE en el SharedLinkForm para estas reglas
        $model = new $class(['scenario' => SharedLinkForm::SCENARIO_UPDATE]);

        $model->setAttributes([
            'id' => $sharedLink->id,
            'hash' => $sharedLink->hash,
            'max_uses' => $sharedLink->max_uses,
            'expires_at' => $sharedLink->expires_at,
            'note' => $sharedLink->note,
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->controller->asJson(ActiveForm::validate($model));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $service = new BackOfficeSharedLinkUpdateService();

            if ($service->update($sharedLink->hash, $model)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Shared Link updated successfully.'));
                return $this->controller->redirect($this->indexRoute);
            }

            $firstError = current($model->getFirstErrors()) ?: \Yii::t('app', 'Unable to update shared link.');
            \Yii::$app->session->setFlash('error', $firstError);
        }

        $sharedUrl = Url::to(['shared-link/open', 'token' => $sharedLink->token], true);
        $privateUrl = Url::to(['preview/index', 'hash' => $sharedLink->creative->hash], true);
        $isRevoked = $sharedLink->revoked_at !== null;
        $accessLogs = $sharedLink->logs;
        $creativeTitle = $sharedLink->creative ? $sharedLink->creative->title : Yii::t('app', 'Unknown Creative');
        $usedCount = $sharedLink->used_count;
        $sharedLinkHash = $sharedLink->hash;
        $revokedAt = $sharedLink->revoked_at;

        return $this->controller->render($this->view, [
            'indexRoute' => $this->indexRoute,
            'model' => $model,
            'sharedUrl' => $sharedUrl,
            'privateUrl' => $privateUrl,
            'isRevoked' => $isRevoked,
            'accessLogs' => $accessLogs,
            'creativeTitle' => $creativeTitle,
            'usedCount' => $usedCount,
            'sharedLinkHash' => $sharedLinkHash,
            'revokedAt' => $revokedAt,
        ]);
    }
}