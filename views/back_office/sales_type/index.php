<?php

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $idDataTable */
/** @var array $dataTableConfig */
/** @var array $rows */

use app\assets\DataTablesAsset;
use nullref\datatable\DataTable;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = $title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back Office')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Taxonomies')];
$this->params['breadcrumbs'][] = $this->title;

DataTablesAsset::register($this);

$this->registerJsVar('idDataTable', $idDataTable);
$this->registerJsVar('deleteConfirmJs', Json::htmlEncode(Yii::t('app', 'Are you sure you want to delete this item?')));

?>

<div class="card bg-transparent">
    <div class="card-content">
        <div class="level mb-4">
            <div class="level-left"><h1 class="title is-4"><?= $this->title ?></h1></div>
            <div class="level-right">
                <a class="btn btn-outline-primary"
                   href="<?= Url::to(['back-office/sales-type-create']) ?>"><?= Yii::t('app', 'New Sales Type') ?></a>
            </div>
        </div>

        <?= DataTable::widget($dataTableConfig) ?>
    </div>
</div>