<?php

namespace app\controllers\actions\shared_link;

use app\helpers\SharedLinkHelper;
use app\models\forms\back_office\SharedLinkForm;
use app\services\back_office\shared_link\BackOfficeSharedLinkService;
use Yii;
use yii\web\Response;

class GenerateLinkAction extends BaseSharedLinkAction
{
    public ?string $can = 'share.manage';
    public ?string $layout = 'main';

    public function run()
    {
        $this->ensureCan($this->can);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new SharedLinkForm(['scenario' => SharedLinkForm::SCENARIO_CREATE]);

        if ($form->load(Yii::$app->request->post(), '')) {

            if ($form->validate()) {
                try {
                    $service = new BackOfficeSharedLinkService();

                    // Usamos el helper del form para obtener las horas limpias
                    $ttlHours = SharedLinkHelper::getHoursFromTtl($form->ttl);

                    // Pasamos los datos limpios al servicio
                    $link = $service->generateLink(
                        $form->creative_hash,
                        $ttlHours,
                        $form->max_uses,
                        Yii::$app->user->id
                    );

                    $url = Yii::$app->urlManager->createAbsoluteUrl(['shared-link/open', 'token' => $link->token]);

                    return [
                        'success' => true,
                        'message' => Yii::t('app', 'Shared link created successfully.'),
                        'url' => $url
                    ];
                } catch (\Exception $e) {
                    return ['success' => false, 'message' => $e->getMessage()];
                }
            }
        }

        return ['success' => false, 'message' => current($form->getFirstErrors())];
    }
}