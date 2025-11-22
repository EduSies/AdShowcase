<?php

return [
    'adminEmail' => 'edusies89@gmail.com',
    'senderEmail' => 'edusies89@gmail.com',
    'senderName' => 'Example.com mailer',
    'isSsl' => strtolower(($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
];