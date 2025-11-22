<?php

declare(strict_types=1);

namespace app\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Icon renders a bootstrap.
 *
 * For example,
 *
 * ```php
 * echo Icon::widget([
 *     'label' => 'Label',
 *     'options' => ['class' => 'rounded-pill bg-primary'],
 * ]);
 * ```
 * @author Eduardo Sierra Escobar <edusies89@gmail.com>
 */
class Icon extends \yii\bootstrap5\Widget
{
    const SIZE_16 = 16;
    const SIZE_24 = 24;
    const SIZE_32 = 32;
    const SIZE_40 = 40;
    const SIZE_56 = 56;

    const SIZE_80 = 80;

    /**
     * @var string the tag to use to render the button
     */
    public $tagName = 'i';
    /**
     * @var string the button label
     */
    public $label = '';
    /**
     * @var bool whether the label should be HTML-encoded.
     */
    public $encodeLabel = true;

    public $icon = '';

    public $size = '';

    public $sizeMap = [
        self::SIZE_16 => self::SIZE_16,
        self::SIZE_24 => self::SIZE_24,
        self::SIZE_32 => self::SIZE_32,
        self::SIZE_40 => self::SIZE_40,
        self::SIZE_56 => self::SIZE_56,
        self::SIZE_80 => self::SIZE_80,
    ];

    public function init(): void
    {
        parent::init();

        $this->clientOptions = [];

        $icon = ['bi'];

        $currentSize = empty($this->size) ?
            $this->sizeMap[self::SIZE_24] :
            ArrayHelper::getValue($this->sizeMap, "$this->size", $this->sizeMap[self::SIZE_24]);

        $icon[] = $this->icon;
        $icon[] = "icon-{$currentSize}px";

        Html::addCssClass($this->options, ['widget' => implode(' ', $icon)]);
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function run(): string
    {
        return Html::tag(
            $this->tagName,
            $this->encodeLabel ? Html::encode($this->label) : $this->label,
            $this->options
        );
    }
}