<?php

use Faker\Factory;
use yii\db\Migration;
use yii\db\Query;

class m251220_193049_seed_creative_table extends Migration
{
    const TABLE_CREATIVE = '{{%creative}}';
    const TABLE_ASSET_FILE = '{{%asset_file}}';

    // Listado de tablas dependientes (excluyendo asset_file que lo tratamos aparte)
    const RELATIONS = [
        'brand_id' => '{{%brand}}',
        'agency_id' => '{{%agency}}',
        'device_id' => '{{%device}}',
        'country_id' => '{{%country}}',
        'format_id' => '{{%format}}',
        'sales_type_id' => '{{%sales_type}}',
        'product_id' => '{{%product}}',
        'language_id' => '{{%language_locale}}',
        'user_id' => '{{%user}}',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $faker = Factory::create('es_ES');

        // ============================================================
        // Generar Assets (Archivos)
        // ============================================================
        echo "    > Generando 150 registros en asset_file...\n";

        $assetRows = [];
        for ($i = 0; $i < 150; $i++) {
            $assetRows[] = [
                'hash_sha256' => hash('sha256', uniqid() . $i), // Hash único simulado
                'storage_path' => 'uploads/assets/5d/63/5d63bf084ac01d52f3d5278d29f990d6a0caee294cc4cd4057a9ffa6b885721a.png',
                'mime' => 'image/png',
                'width' => 1138,
                'height' => 641,
                'duration_sec' => $faker->numberBetween(0, 15),
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Insertamos los assets
        $this->batchInsert(self::TABLE_ASSET_FILE, [
            'hash_sha256', 'storage_path', 'mime', 'width', 'height', 'duration_sec', 'created_at'
        ], $assetRows);

        // ============================================================
        // Obtener IDs válidos
        // ============================================================
        echo "    > Obteniendo IDs de tablas relacionadas...\n";

        $ids = [];

        // IDs de Assets recién creados
        $ids['asset_file_id'] = (new Query())
            ->select('id')
            ->from(self::TABLE_ASSET_FILE)
            ->column();

        if (empty($ids['asset_file_id'])) {
            echo "    Error: No se han creado los assets. Abortando.\n";
            return false;
        }

        // IDs del resto de relaciones
        foreach (self::RELATIONS as $key => $tableName) {
            $existingIds = (new Query())
                ->select('id')
                ->from($tableName)
                ->column();

            // Si alguna tabla está vacía, fallará la inserción. Ponemos un aviso.
            if (empty($existingIds)) {
                echo "    Advertencia: La tabla $tableName está vacía. Se pueden producir errores.\n";
            }

            $ids[$key] = $existingIds;
        }

        // ============================================================
        // Generar Creatividades
        // ============================================================
        echo "    > Generando 150 creatividades usando los IDs obtenidos...\n";

        $creativeRows = [];
        for ($i = 0; $i < 150; $i++) {
            // Seleccionamos un Asset ID válido aleatorio
            $randomAssetId = $faker->randomElement($ids['asset_file_id']);

            $creativeRows[] = [
                'hash' => $faker->unique()->regexify('[A-Za-z0-9]{16}'), // Genera char(16) seguro
                'asset_file_id' => $randomAssetId,
                'url_thumbnail' => 'https://placehold.co/1280x720?text=Creative+' . ($i + 1),
                'title' => $faker->catchPhrase(),
                'brand_id' => $faker->randomElement($ids['brand_id']),
                'agency_id' => $faker->randomElement($ids['agency_id']),
                'device_id' => $faker->randomElement($ids['device_id']),
                'country_id' => $faker->randomElement($ids['country_id']),
                'format_id' => $faker->randomElement($ids['format_id']),
                'sales_type_id' => $faker->randomElement($ids['sales_type_id']),
                'product_id' => $faker->randomElement($ids['product_id']),
                'language_id' => $faker->randomElement($ids['language_id']),
                'click_url' => $faker->url(),
                'workflow_status' => $faker->randomElement(['draft', 'reviewed', 'approved']),
                'status' => $faker->randomElement(['active', 'archived', 'pending']),
                'user_id' => $faker->randomElement($ids['user_id']),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // 4. Insertar Creatividades
        $this->batchInsert(self::TABLE_CREATIVE, [
            'hash', 'asset_file_id', 'url_thumbnail', 'title',
            'brand_id', 'agency_id', 'device_id', 'country_id',
            'format_id', 'sales_type_id', 'product_id', 'language_id',
            'click_url', 'workflow_status', 'status', 'user_id',
            'created_at', 'updated_at'
        ], $creativeRows);

        echo "    > ¡Hecho! Se han insertado assets y creatividades correctamente.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "    > Borrando creatividades...\n";
        $this->delete(self::TABLE_CREATIVE);

        echo "    > Borrando assets vinculados...\n";
        // Importante: Borrar los assets después de las creatividades por la Foreign Key
        $this->delete(self::TABLE_ASSET_FILE);

        // Opcional: reiniciar contadores
        try {
            $this->execute("ALTER TABLE " . self::TABLE_CREATIVE . " AUTO_INCREMENT = 1");
            $this->execute("ALTER TABLE " . self::TABLE_ASSET_FILE . " AUTO_INCREMENT = 1");
        } catch (\Exception $e) {
            // Ignorar si no soporta reinicio de auto_increment
        }
    }
}