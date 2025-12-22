<?php

use Faker\Factory;
use yii\db\Migration;

class m251220_193049_seed_adshowcase_creative_table extends Migration
{
    const TABLE_CREATIVE = 'ADSHOWCASE_creative';

    // Listado de tablas dependientes para obtener IDs válidos
    const RELATIONS = [
        'brand_id'      => 'ADSHOWCASE_brand',
        'agency_id'     => 'ADSHOWCASE_agency',
        'device_id'     => 'ADSHOWCASE_device',
        'country_id'    => 'ADSHOWCASE_country',
        'format_id'     => 'ADSHOWCASE_format',
        'sales_type_id' => 'ADSHOWCASE_sales_type',
        'product_id'    => 'ADSHOWCASE_product',
        'language_id'   => 'ADSHOWCASE_language_locale',
        'user_id'       => 'ADSHOWCASE_user',
        'asset_file_id' => 'ADSHOWCASE_asset_file',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $faker = Factory::create('es_ES'); // Datos en español
        $rows = [];
        $ids = [];

        // 1. Obtener IDs válidos de las tablas relacionadas
        echo "    > Obteniendo IDs de tablas relacionadas...\n";

        foreach (self::RELATIONS as $key => $tableName) {
            // Obtenemos todos los IDs existentes
            $existingIds = (new \yii\db\Query())
                ->select('id')
                ->from($tableName)
                ->column();

            $ids[$key] = $existingIds;
        }

        // 2. Generar 150 registros
        echo "    > Generando 150 creatividades falsas...\n";

        for ($i = 0; $i < 150; $i++) {
            $rows[] = [
                'hash'          => $faker->unique()->bothify('****************'), // Genera char(16) aleatorio
                'asset_file_id' => $faker->randomElement($ids['asset_file_id']),
                'url_thumbnail' => 'https://placehold.co/1280x720?text=Creative+' . ($i + 1), // Placeholder imagen
                'title'         => $faker->catchPhrase(),
                'brand_id'      => $faker->randomElement($ids['brand_id']),
                'agency_id'     => $faker->randomElement($ids['agency_id']),
                'device_id'     => $faker->randomElement($ids['device_id']),
                'country_id'    => $faker->randomElement($ids['country_id']),
                'format_id'     => $faker->randomElement($ids['format_id']),
                'sales_type_id' => $faker->randomElement($ids['sales_type_id']),
                'product_id'    => $faker->randomElement($ids['product_id']),
                'language_id'   => $faker->randomElement($ids['language_id']),
                'click_url'     => $faker->url(),
                'workflow_status' => $faker->randomElement(['draft', 'reviewed', 'approved']),
                'status'        => $faker->randomElement(['active', 'archived', 'pending']),
                'user_id'       => $faker->randomElement($ids['user_id']),
                'created_at'    => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
        }

        // 3. Insertar en lotes (Batch Insert) para mejor rendimiento
        $this->batchInsert(self::TABLE_CREATIVE, [
            'hash', 'asset_file_id', 'url_thumbnail', 'title',
            'brand_id', 'agency_id', 'device_id', 'country_id',
            'format_id', 'sales_type_id', 'product_id', 'language_id',
            'click_url', 'workflow_status', 'status', 'user_id',
            'created_at', 'updated_at'
        ], $rows);

        echo "    > ¡Hecho! 150 creatividades insertadas.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Borramos todos los datos de prueba
        $this->delete(self::TABLE_CREATIVE);

        // Opcional: reiniciar el AUTO_INCREMENT
        $this->execute("ALTER TABLE " . self::TABLE_CREATIVE . " AUTO_INCREMENT = 1");
    }
}
