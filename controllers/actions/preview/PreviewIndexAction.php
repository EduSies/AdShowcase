<?php

declare(strict_types=1);

namespace app\controllers\actions\preview;

use app\helpers\CreativeHelper;
use app\services\back_office\creative\BackOfficeCreativeListService;
use app\services\favorite\FavoriteListService;
use app\widgets\Icon;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

final class PreviewIndexAction extends BasePreviewAction
{
    public ?string $layout = 'main';

    public function run($hash)
    {
        // Datos del servicio
        $service = new BackOfficeCreativeListService();
        $creative = $service->getOne($hash);

        if (!$creative) {
            throw new \yii\web\NotFoundHttpException(Yii::t('app', 'Creative not found'));
        }

        $listsFavorites = (new FavoriteListService())->getUserFavorites();
        $creative = CreativeHelper::prepareCreativeDisplayData($creative, $listsFavorites);

        // Configurar Navbar (Items Centrales)
        $this->controller->view->params['navbar_center_items'] = [
            [
                'label' => '<div class="circle-icon circle-50 rounded-pill shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">' .
                    Icon::widget([
                        'icon' => $creative['viewDeviceIcon'],
                        'size' => Icon::SIZE_32,
                        'options' => ['class' => 'flex-shrink-0'],
                    ]) .
                    '</div>',
                'url' => null,
                'encode' => false,
                'linkOptions' => [
                    'class' => 'p-0 text-decoration-none cursor-pointer',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'bottom',
                    'data-bs-custom-class' => 'custom-tooltip',
                    'data-bs-title' => $creative['device']['name'] ?? 'Device',
                ],
            ],
        ];

        // Verificamos permiso de compartir
        $userCanShare = !Yii::$app->user->isGuest && Yii::$app->user->can('share.manage');

        if ($userCanShare) {
            // Preparamos el botón Share
            $shareButton = [
                'label' => Icon::widget(['icon' => 'bi-share-fill', 'size' => Icon::SIZE_24]) .
                    Html::tag('span', Yii::t('app', 'Share')),
                'url' => null,
                'encode' => false,
                'linkOptions' => [
                    'class' => 'btn btn-primary bg-main-2 text-white rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm me-2',
                    'style' => 'height: 40px;',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#shareModal',
                    'data-creative-hash' => $creative['hash'],
                    'data-creative-title' => $creative['title'],
                    'data-creative-format' => $creative['format']['name'] ?? '',
                    'data-creative-agency' => $creative['agency']['name'] ?? '',
                ],
            ];

            // Inyectamos el botón en los params para que el layout lo recoja
            $this->controller->view->params['navbar_right_items'] = [$shareButton];
        }

        // Configurar Navbar (Items Generales - Ocultar resto)
        $this->controller->view->params['navbar'] = [
            'search' => false,
            'favorites' => false,
            'backoffice' => false,
            'language' => true,
            'user' => true,
        ];

        $iframeSrc = Url::to(['preview/mockup', 'hash' => $hash], true);

        $iframeClass = $creative['viewDeviceClass'];

        return $this->controller->render('index', [
            'creative' => $creative,
            'ajaxUrlCreateList' => Url::to(['favorite/create-list']),
            'ajaxUrlToggleItem' => Url::to(['favorite/toggle-item']),
            'ajaxUrlGetDropdown' => Url::to(['favorite/get-dropdown']),
            'iframeClass' => $iframeClass,
            'iframeSrc' => $iframeSrc
        ]);
    }
}