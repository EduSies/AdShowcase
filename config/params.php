<?php

use yii\helpers\ArrayHelper;

return [
    'senderEmail' => ArrayHelper::getValue($_ENV, 'SENDER_EMAIL'),
    'senderName' => ArrayHelper::getValue($_ENV, 'APP_NAME'),
    'isSsl' => strtolower(($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
];