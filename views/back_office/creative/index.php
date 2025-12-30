<?php

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $idDataTable */
/** @var array $dataTableConfig */
/** @var array $rows */

use app\assets\DataTablesAsset;
use app\widgets\Icon;
use nullref\datatable\DataTable;
use yii\bootstrap5\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = $title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Back Office')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Creatives')];
$this->params['breadcrumbs'][] = $this->title;

DataTablesAsset::register($this);

$this->registerJsVar('idDataTable', $idDataTable);
$this->registerJsVar('deleteConfirmJs', Json::htmlEncode(Yii::t('app', 'Are you sure you want to delete this item?')));

?>

<div class="card bg-transparent">
    <div class="card-content shadow-lg">
        <div class="level mb-4">
            <div class="level-left">
                <h1 class="title is-4">
                    <?= Yii::t('app', '{title} list', ['title' => $this->title]) ?>
                </h1>
            </div>
            <div class="level-right">
                <?= Html::a(
                    Icon::widget([
                        'icon' => 'bi-globe',
                        'size' => Icon::SIZE_24,
                        'options' => ['class' => 'flex-shrink-0 me-2'],
                    ]) .
                    Html::tag('span', Yii::t('app', 'New Creative'), ['class' => 'align-middle']),
                    ['back-office/creative-create'],
                    [
                        'class' => 'btn btn-outline-primary d-inline-flex align-items-center rounded-pill',
                        'encode' => false,
                    ]
                ) ?>
            </div>
        </div>

        <?= DataTable::widget($dataTableConfig) ?>
    </div>
</div>