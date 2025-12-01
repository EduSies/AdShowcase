<?php

use yii\db\Expression;
use yii\db\Migration;

class m251123_084929_seed_sales_type_fake extends Migration
{
    public function safeUp()
    {
        $rows = [
            ['name' => 'Open', 'status' => 'active'],
            ['name' => 'Private Deal', 'status' => 'active'],
            ['name' => 'Direct Campaign', 'status' => 'active'],
            ['name' => 'Partner Deal', 'status' => 'active'],
            ['name' => 'Autopromo', 'status' => 'active'],
            ['name' => 'Mock-up', 'status' => 'active'],
        ];

        foreach ($rows as $row) {
            $this->db->createCommand()->upsert('{{%sales_type}}', [
                'hash' => \Yii::$app->security->generateRandomString(16),
                'name' => $row['name'],
                'status' => $row['status'],
            ], [
                'name' => new Expression('VALUES(name)'),
                'status' => new Expression('VALUES(status)'),
            ])->execute();
        }
    }

    public function safeDown()
    {
        return true;
    }
}