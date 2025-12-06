<?php

namespace app\widgets;

use app\assets\FlagAsset;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Widget personalizado para mostrar banderas (Flag Icon CSS).
 *
 * NOTA: Este widget ha sido creado manualmente para replicar la funcionalidad
 * de la extensión 'powerkernel/yii2-flag-icon-css' pero eliminando su dependencia
 * estricta con 'yii\bootstrap' (Bootstrap 3).
 *
 * Como este proyecto utiliza Bootstrap 5 ('yii\bootstrap5'), la extensión original
 * causaba errores al intentar cargar clases inexistentes. Este widget utiliza
 * helpers estándar de Yii2 ('yii\helpers\Html') para asegurar la compatibilidad.
 */
class Flag extends Widget
{
    public $tag = 'span';
    public $country; // Código del país (es, us, gb...)
    public $squared = false; // Opción cuadrada
    public $options = []; // Clases extra, estilos, etc.

    public function run()
    {
        if (empty($this->country)) {
            return '';
        }

        // 1. Registramos el CSS de las banderas (usa la librería que ya tienes instalada)
        FlagAsset::register($this->view);

        // 2. Construimos las clases CSS necesarias
        $class = 'flag-icon flag-icon-' . $this->country;
        Html::addCssClass($this->options, $class);

        if ($this->squared) {
            Html::addCssClass($this->options, 'flag-icon-squared');
        }

        // 3. Renderizamos la etiqueta
        return Html::tag($this->tag, '', $this->options);
    }
}