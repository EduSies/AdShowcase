<?php

use yii\db\Migration;

class m251130_191126_seed_language_locale extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%language_locale}}', [
            'language_code',
            'region_code',
            'locale_code',
            'display_name_en',
            'display_name_es',
            'display_name_ca',
            'is_default',
            'status',
        ], [
            // Inglés (US)
            [
                'en',
                'US',
                'en-US',
                'English (United States)',
                'Inglés (Estados Unidos)',
                'Anglès (Estats Units)',
                0,
                'active',
            ],
            // Español (España) - por defecto
            [
                'es',
                'ES',
                'es-ES',
                'Spanish (Spain)',
                'Español (España)',
                'Espanyol (Espanya)',
                1,
                'active',
            ],
            // Catalán (España)
            [
                'ca',
                'ES',
                'ca-ES',
                'Catalan (Spain)',
                'Catalán (España)',
                'Català (Espanya)',
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