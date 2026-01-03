<?php

use Faker\Factory;
use yii\db\Migration;
use yii\db\Query;
use yii\helpers\FileHelper;

class m251220_193049_seed_creative_table extends Migration
{
    const TABLE_CREATIVE = '{{%creative}}';
    const TABLE_ASSET_FILE = '{{%asset_file}}';

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

    public function safeUp()
    {
        $faker = Factory::create('es_ES');

        $uploadRoot = \Yii::getAlias('@app/web');
        if (!is_dir($uploadRoot)) {
            $uploadRoot = \Yii::getAlias('@app') . '/web';
        }

        echo "    > Descargando imagen base de Picsum para replicar...\n";

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        // Descargamos una imagen JPG genérica
        $dummyImageContent = @file_get_contents('https://picsum.photos/800/600', false, $context);

        echo "    > Generando 100 registros y ARCHIVOS FÍSICOS...\n";

        $assetRows = [];
        // Forzamos jpg para los físicos porque Picsum devuelve jpg (evita mismatch de extensión)
        $physicalExtension = 'jpg';

        for ($i = 0; $i < 100; $i++) {
            $hash = hash('sha256', uniqid() . $i . microtime());

            $folder1 = substr($hash, 0, 2);
            $folder2 = substr($hash, 2, 2);

            // Ruta relativa (DB)
            $relativePath = "/uploads/assets/{$folder1}/{$folder2}/{$hash}.{$physicalExtension}";

            // Ruta absoluta (Disco)
            $absoluteDir = $uploadRoot . "/uploads/assets/{$folder1}/{$folder2}";
            $absolutePath = $uploadRoot . "/" . $relativePath;

            try {
                if (!is_dir($absoluteDir)) {
                    FileHelper::createDirectory($absoluteDir, 0775, true);
                }
                file_put_contents($absolutePath, $dummyImageContent);
            } catch (\Exception $e) {
                echo "      ! Error IO: " . $e->getMessage() . "\n";
            }

            $assetRows[] = [
                'hash_sha256' => $hash,
                'storage_path' => $relativePath,
                'mime' => "image/jpeg",
                'width' => 800,
                'height' => 600,
                'duration_sec' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->batchInsert(self::TABLE_ASSET_FILE, [
            'hash_sha256', 'storage_path', 'mime', 'width', 'height', 'duration_sec', 'created_at'
        ], $assetRows);

        echo "    > Obteniendo IDs...\n";

        $ids = [];
        $ids['asset_file_id'] = (new Query())->select('id')->from(self::TABLE_ASSET_FILE)->column();

        if (empty($ids['asset_file_id'])) {
            echo "No se crearon assets. Abortando.\n";
            return false;
        }

        foreach (self::RELATIONS as $key => $tableName) {
            $ids[$key] = (new Query())->select('id')->from($tableName)->column();
        }

        $realUrls = $this->getRealWebsites();

        echo "    > Generando 150 creatividades...\n";
        $creativeRows = [];

        for ($i = 0; $i < 150; $i++) {
            $randomAssetId = $faker->randomElement($ids['asset_file_id']);
            $imageSeed = uniqid();

            $randomRealUrl = $realUrls[array_rand($realUrls)];

            $creativeRows[] = [
                'hash' => $faker->unique()->regexify('[A-Za-z0-9]{16}'),
                'asset_file_id' => $randomAssetId,
                'url_thumbnail' => "https://picsum.photos/seed/{$imageSeed}/1280/720",
                'title' => $faker->catchPhrase(),
                'brand_id' => $faker->randomElement($ids['brand_id']),
                'agency_id' => $faker->randomElement($ids['agency_id']),
                'device_id' => $faker->randomElement($ids['device_id']),
                'country_id' => $faker->randomElement($ids['country_id']),
                'format_id' => $faker->randomElement($ids['format_id']),
                'sales_type_id' => $faker->randomElement($ids['sales_type_id']),
                'product_id' => $faker->randomElement($ids['product_id']),
                'language_id' => $faker->randomElement($ids['language_id']),
                'click_url' => $randomRealUrl,
                'workflow_status' => $faker->randomElement(['draft', 'reviewed', 'approved']),
                'status' => $faker->randomElement(['active', 'archived', 'pending']),
                'user_id' => $faker->randomElement($ids['user_id']),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->batchInsert(self::TABLE_CREATIVE, [
            'hash', 'asset_file_id', 'url_thumbnail', 'title',
            'brand_id', 'agency_id', 'device_id', 'country_id',
            'format_id', 'sales_type_id', 'product_id', 'language_id',
            'click_url', 'workflow_status', 'status', 'user_id',
            'created_at', 'updated_at'
        ], $creativeRows);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_CREATIVE);
        $this->delete(self::TABLE_ASSET_FILE);
    }

    /**
     * Devuelve un array de URLs reales para simular tráfico.
     */
    private function getRealWebsites(): array
    {
        return [
            'https://www.google.com', 'https://www.facebook.com', 'https://www.amazon.com',
            'https://www.apple.com', 'https://www.netflix.com', 'https://www.youtube.com',
            'https://www.instagram.com', 'https://www.linkedin.com', 'https://www.microsoft.com',
            'https://www.twitter.com', 'https://www.wikipedia.org', 'https://www.nytimes.com',
            'https://www.cnn.com', 'https://www.bbc.com', 'https://www.forbes.com',
            'https://www.techcrunch.com', 'https://www.wired.com', 'https://www.theverge.com',
            'https://www.spotify.com', 'https://www.adobe.com', 'https://www.salesforce.com',
            'https://www.airbnb.com', 'https://www.uber.com', 'https://www.tesla.com',
            'https://www.nike.com', 'https://www.adidas.com', 'https://www.zara.com',
            'https://www.ikea.com', 'https://www.walmart.com', 'https://www.target.com',
            'https://www.bestbuy.com', 'https://www.samsung.com', 'https://www.sony.com',
            'https://www.lg.com', 'https://www.canon.com', 'https://www.nikon.com',
            'https://www.booking.com', 'https://www.expedia.com', 'https://www.tripadvisor.com',
            'https://www.skyscanner.com', 'https://www.reddit.com', 'https://www.pinterest.com',
            'https://www.tiktok.com', 'https://www.twitch.tv', 'https://www.slack.com',
            'https://www.zoom.us', 'https://www.dropbox.com', 'https://www.github.com'
        ];
    }
}