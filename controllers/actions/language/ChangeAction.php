<?php

namespace app\controllers\actions\language;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ChangeAction extends Action
{
    public string $idParam = 'lang';

    public function run()
    {
        $lang = (string) Yii::$app->request->get($this->idParam, Yii::$app->request->post($this->idParam));
        if ($lang === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Missing language hash.'));
        }

        // Lista blanca de idiomas permitidos
        $allowedLanguages = [
            ArrayHelper::getValue($_ENV, 'LANGUAGE_ES'),
            ArrayHelper::getValue($_ENV, 'LANGUAGE_CA'),
            ArrayHelper::getValue($_ENV, 'LANGUAGE_EN'),
        ];

        // Si el idioma es válido, lo guardamos en sesión
        if (in_array($lang, $allowedLanguages)) {
            Yii::$app->session->set('_lang', $lang);
        }

        // Redirigir a la página anterior (o al home si no se detecta)
        return $this->controller->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}