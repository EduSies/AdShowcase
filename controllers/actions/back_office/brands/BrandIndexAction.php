<?php

declare(strict_types=1);

namespace app\controllers\actions\back_office\brands;

use app\controllers\actions\back_office\BaseBackOfficeAction;
use app\models\Brand;
use Yii;

final class BrandIndexAction extends BaseBackOfficeAction
{
    public ?string $can = 'taxonomies.manage';
    public ?string $view = '@app/views/back_office/brands/index';

    public function run()
    {
        $this->ensureCan($this->can);
        // Renderiza la vista de listado (DataTable/filters/…)

        $rows = Brand::find()->orderBy(['id' => SORT_DESC])->asArray()->all();

        return $this->controller->render($this->view ?? 'index', [
            'title' => Yii::t('app', 'Brands list'),
            'rows' => $rows,
        ]);
    }
}