<?php

namespace app\controllers\actions\shared_link;

use app\helpers\CreativeHelper;
use app\services\back_office\shared_link\BackOfficeSharedLinkService;
use app\widgets\Icon;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class OpenLinkAction extends BaseSharedLinkAction
{
    public ?string $layout = 'main';

    public function run($token)
    {
        $this->controller->view->params['navbar'] = [
            'search' => false,
            'favorites' => false,
            'backoffice' => false,
            'language' => true, // Permitir cambio de idioma
            'user' => false,
        ];

        try {
            $service = new BackOfficeSharedLinkService();

            $sharedLinkAr = $service->getAndValidateLink($token);
            $creativeAr = $sharedLinkAr->creative;

            $this->grantAnonymousAccess($creativeAr->hash);
            $service->registerAccess($sharedLinkAr);

            $creative = $creativeAr->toArray();

            $creative = $creativeAr->toArray();
            $creative['format'] = $creativeAr->format ? $creativeAr->format->toArray() : null;
            $creative['agency'] = $creativeAr->agency ? $creativeAr->agency->toArray() : null;
            $creative['country'] = $creativeAr->country ? $creativeAr->country->toArray() : null;
            $creative['device'] = $creativeAr->device ? $creativeAr->device->toArray() : null;

            // Preparar datos visuales (Iconos, Clases, URLs)
            $creative = CreativeHelper::prepareCreativeDisplayData($creative, []);

            $creative['canFavorite'] = false;
            $creative['canShare'] = false;

            // Configurar Navbar (Icono Central del Dispositivo)
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

            // Definir URL del Iframe y Clase CSS
            $iframeSrc = Url::to(['preview/mockup', 'hash' => $creative['hash']], true);
            $iframeClass = $creative['viewDeviceClass'];

            // Renderizar la vista "preview/index"
            return $this->controller->render('@app/views/preview/index', [
                'creative' => $creative,
                'ajaxUrlCreateList' => null,
                'ajaxUrlToggleItem' => null,
                'ajaxUrlGetDropdown' => null,
                'iframeClass' => $iframeClass,
                'iframeSrc' => $iframeSrc
            ]);

        } catch (NotFoundHttpException $e) {
            $this->controller->layout = 'login-layout';
            return $this->controller->render('@adshowcase.layouts/error', [
                'showText' => false,
                'showButtons' => false,
                // Cambiamos el título a "Enlace no encontrado" o similar
                'name' => Yii::t('app', 'Link Not Found'),
                'message' => $e->getMessage(),
            ]);
        } catch (ForbiddenHttpException $e) {
            $this->controller->layout = 'login-layout';
            return $this->controller->render('@adshowcase.layouts/error', [
                'showText' => false,
                'showButtons' => false,
                'name' => Yii::t('app', 'Link Expired'),
                'message' => $e->getMessage(),
            ]);
        }
    }
}