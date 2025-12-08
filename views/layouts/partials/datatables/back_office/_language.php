<?php

use app\widgets\Flag;
use yii\helpers\Html;

/**
 * @var string $flag Código del país para la bandera (ej: 'es').
 * @var string $label Nombre del idioma.
 **/

?>

<div class="d-flex gap-2">
    <?php
        // 1. Renderizamos la bandera
        echo Flag::widget([
            'tag' => 'span',
            'country' => $flag,
        ]);

        // 2. Renderizamos el nombre del idioma
        echo Html::encode(Yii::t('app', $label));
    ?>
</div>