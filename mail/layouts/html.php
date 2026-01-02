<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?= Html::encode($this->title) ?></title>
        <style type="text/css">
            /* Estilos globales y reseteo */
            body { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
            img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
            /* Estilos responsivos para móviles */
            @media only screen and (max-width: 600px) {
                .container { width: 100% !important; }
                .content-padding { padding: 20px !important; }
            }
        </style>
    </head>
    <body style="margin: 0; padding: 0; background-color: #dbeafe;">
    <?php $this->beginBody() ?>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: linear-gradient(135deg, #c4d0fb 0%, #fbcfe8 100%); background-color: #dbeafe; min-height: 100vh;">
        <tr>
            <td align="center" valign="top" style="padding: 50px 15px;">

                <table border="0" cellpadding="0" cellspacing="0" width="600" class="container" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">
                    <tr>
                        <td class="content-padding" align="center" style="padding: 50px 40px;">

                            <h1 style="font-family: Helvetica, Arial, sans-serif; color: #FF6600; font-size: 28px; margin: 0 0 30px 0; font-weight: bold;">
                                AdShowcase
                            </h1>

                            <div style="font-family: Helvetica, Arial, sans-serif; color: #333333; font-size: 16px; line-height: 1.6;">
                                <?= $content ?>
                            </div>

                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" style="padding-top: 20px;">
                            <p style="font-family: Helvetica, Arial, sans-serif; color: #52525b; font-size: 12px; margin: 0;">
                                The AdShowcase team
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>