<?php

/* @var $filterSearch yii\web\View */

use app\widgets\Icon;
use yii\bootstrap5\Html;

$this->registerJsVar('idfilterSearch', $idFilterSearch = 'filter-search');

?>

<div id="adshowcase-header-search" class="adshowcase-header-search d-lg-none d-flex align-items-center mx-3 mx-lg-0">
    <?= Icon::widget([
        'icon' => 'bi-search',
        'size' => Icon::SIZE_24,
        'options' => ['class' => 'mx-1 flex-shrink-0'],
    ]) ?>
    <?= Html::textInput($idFilterSearch, $filterSearch, [
        "id" => $idFilterSearch,
        "class" => "form-control",
        "enterkeyhint" => "search",
        "autocomplete" => "off",
        "placeholder" => Yii::t('app', 'Search')."...",
    ]) ?>
    <?= Html::tag('div',
        Html::tag('span', Yii::t('app', 'Cancel'), ['class' => 'align-middle']) .
        Icon::widget([
            'icon' => 'bi-x',
            'size' => Icon::SIZE_24,
            'options' => ['class' => 'flex-shrink-0 ms-2'],
        ]),
        [
            'id' => 'btnSearchCancel',
            'class' => 'd-flex align-items-center cursor-pointer',
            'role' => 'button',
        ]
    ) ?>
</div>