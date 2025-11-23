<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Seeds default devices into {{%device}}.
 *
 * Table DDL (recap):
 *  id INT AI PK
 *  name VARCHAR(100) UNIQUE NOT NULL
 *  status ENUM('active','archived','pending') DEFAULT 'active' NOT NULL
 */
class m251123_075448_seed_devices_fake extends Migration
{
    /** @var string[] */
    private array $names = [
        'Desktop',
        'Mobile',
        'Tablet',
        'CTV', // Connected TV
    ];

    public function safeUp(): bool
    {
        foreach ($this->names as $name) {
            $this->upsert('{{%device}}', [
                'name'   => $name,
                'status' => 'active',
            ], [
                'status' => new Expression("VALUES(status)"),
            ]);
        }
        return true;
    }

    public function safeDown(): bool
    {
        return true;
    }
}