<?php

use app\widgets\Icon;
use yii\helpers\Html;

/**
 * @var string $label La etiqueta traducida.
 * @var string $color El color semántico.
 * @var string $icon El nombre del icono.
 */

// 1. Generamos el HTML del Icono
$iconHtml = Icon::widget([
    'icon' => $icon,
    'size' => Icon::SIZE_16,
    'options' => ['class' => 'me-2 flex-shrink-0'],
]);

// 2. Generamos el HTML del Texto (Escapado por seguridad)
$labelHtml = Html::encode($label);

// 3. Definimos las clases CSS dinámicas
// Usamos "text-{$color}" para que PHP inserte la variable correctamente dentro del string
$cssClass = "badge rounded-pill bg-{$color} bg-opacity-10 text-{$color} border border-{$color} border-opacity-25 px-3 py-2 d-inline-flex align-items-center";

?>

<div class="d-flex justify-content-center">
    <?php
    // 4. Renderizamos todo junto con Html::tag
    echo Html::tag('span', $iconHtml . $labelHtml, [
        'class' => $cssClass
    ]);
    ?>
</div>