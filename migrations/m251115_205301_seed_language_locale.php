<?php

use yii\db\Migration;

class m251115_205301_seed_language_locale extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%language_locale}}', [
            'language_code',
            'region_code',
            'locale_code',
            'display_name_en',
            'is_default',
            'status',
        ], [
            // Inglés (US)
            [
                'en',
                'US',
                'en-US',
                'English (United States)',
                0,
                'active',
            ],
            // Español (España) - por defecto
            [
                'es',
                'ES',
                'es-ES',
                'Spanish (Spain)',
                1,
                'active',
            ],
            // Catalán (España)
            [
                'ca',
                'ES',
                'ca-ES',
                'Catalan (Spain)',
                0,
                'active',
            ],
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%language_locale}}', ['locale_code' => 'en-US']);
        $this->delete('{{%language_locale}}', ['locale_code' => 'es-ES']);
        $this->delete('{{%language_locale}}', ['locale_code' => 'ca-ES']);
    }
}