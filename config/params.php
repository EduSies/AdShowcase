<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'isSsl' => strtolower(($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
];