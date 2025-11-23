<?php

use yii\db\Migration;
use yii\helpers\Inflector;

class m251122_082844_seed_brands_fake extends Migration
{
    /** Cuántas marcas generar. */
    private int $count = 1000;

    public function safeUp(): void
    {
        $table = '{{%brand}}';

        // Faker si está disponible; si no, fallback
        $faker = class_exists(\Faker\Factory::class) ? \Faker\Factory::create() : null;

        $now = time();
        $usedSlugs = [];

        for ($i = 0; $i < $this->count; $i++) {
            $name = $faker ? mb_substr($faker->unique()->company(), 0, 255) : $this->fallbackCompany($i);

            // slug único (url_name)
            $slugBase = Inflector::slug($name);
            $slug = $slugBase;

            // hash aleatorio de 16 (solo alfanumérico por estética)
            $hash = \Yii::$app->security->generateRandomString(16);

            // status con más probabilidad de 'active'
            $status = $this->weightedStatus();

            // fechas coherentes (created <= updated)
            $createdTs = $now - random_int(0, 3600 * 24 * 365);
            $updatedTs = $createdTs + random_int(0, 3600 * 24 * 60);
            $createdAt = date('Y-m-d H:i:s', $createdTs);
            $updatedAt = date('Y-m-d H:i:s', $updatedTs);

            $row = [
                'hash'       => $hash,
                'name'       => $name,
                'url_name'   => $slug,
                'status'     => $status,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            $this->upsert($table, $row, [
                'hash'       => $row['hash'],
                'name'       => $row['name'],
                'status'     => $row['status'],
                'updated_at' => $row['updated_at'],
            ]);
        }
    }

    public function safeDown(): bool
    {
        return false;
    }

    // ================= Helpers =================

    /** Status con pesos: active (~75%), pending (~15%), archived (~10%). */
    private function weightedStatus(): string
    {
        $r = random_int(1, 100);
        if ($r <= 75) return 'active';
        if ($r <= 90) return 'pending';
        return 'archived';
    }

    /** Fallback simple de nombres si no hay Faker. */
    private function fallbackCompany(int $i): string
    {
        static $pool = [
            'Coca-Cola', 'Fanta', 'Sprite', 'Aquabona', 'Barbie', 'Hot Wheels', 'Max Steel',
            'Monster High', 'American Girl', 'Apptivity', 'Nike', 'Adidas', 'Pepsi',
            'Apple', 'Samsung', 'Amazon', 'Netflix', 'Ikea', 'Sony', 'LEGO', 'Zara', 'H&M',
            'Starbucks', 'Tesla', 'Nvidia', 'Google', 'Meta', 'Microsoft', 'Disney', 'Uber',
        ];
        return $pool[$i % count($pool)];
    }
}
