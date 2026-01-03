<?php

use yii\db\Migration;
use yii\helpers\Inflector;

class m251123_074208_seed_formats_fake extends Migration
{
    /** Número de formatos a generar. */
    private int $count = 30;

    public function safeUp()
    {
        $rows = $this->buildRows($this->count);
        foreach ($rows as $row) {
            // Upsert para mantener la idempotencia
            $this->upsert('{{%format}}', $row, true);
        }
    }

    public function safeDown()
    {
        return false;
    }

    /**
     * Construye un set de formatos usando estándares reales (IAB)
     * y rellena con Faker si se piden más filas de las que existen en la lista real.
     */
    private function buildRows(int $count): array
    {
        $faker = \Faker\Factory::create('en_US');
        $realFormats = $this->getRealFormatsList();
        $totalReal = count($realFormats);

        $usedSlugs = [];
        $rows = [];
        $now = time();

        for ($i = 0; $i < $count; $i++) {

            // Elegir datos: Real vs Fallback Faker
            if ($i < $totalReal) {
                // Usar configuración real estándar
                $data = $realFormats[$i];
                $name = $data['name'];
                $format = $data['format'];
                $family = $data['family'];
                $experience = $data['experience'];
                $subtype = $data['subtype'];
            } else {
                // Fallback: Generar combinaciones aleatorias si se acaban los reales
                $family = $faker->randomElement(['banner', 'video', 'native', 'audio']);
                $format = $faker->bothify('###x###');
                $experience = $faker->randomElement(['web', 'app', 'dooh']);
                $subtype = $faker->randomElement([null, 'sticky', 'interstitial']);
                $name = ucfirst($family) . ' ' . $format . ' (' . ucfirst($experience) . ')';
            }

            // Slug único
            $slugBase = Inflector::slug($name);
            $slug = $slugBase;
            $suffix = 1;

            while (isset($usedSlugs[$slug])) {
                $slug = $slugBase . '-' . (++$suffix);
            }
            $usedSlugs[$slug] = true;

            // Fechas
            $createdTs = $now - mt_rand(0, 3600 * 24 * 365); // Último año
            $updatedTs = $createdTs + mt_rand(0, 3600 * 24 * 30);

            // Construir fila
            $rows[] = [
                'hash' => \Yii::$app->security->generateRandomString(16),
                'name' => mb_substr($name, 0, 150),
                'format' => (string)$format,
                'family' => (string)$family,
                'experience' => (string)$experience,
                'subtype' => $subtype,
                'status' => $this->weightedStatus(),
                'url_slug' => $slug,
                'created_at' => date('Y-m-d H:i:s', $createdTs),
                'updated_at' => date('Y-m-d H:i:s', $updatedTs),
            ];
        }

        return $rows;
    }

    private function weightedStatus(): string
    {
        $r = random_int(1, 100);
        if ($r <= 80) return 'active';
        if ($r <= 95) return 'pending';
        return 'archived';
    }

    /**
     * Lista de formatos estándar de la industria (IAB Standards).
     */
    private function getRealFormatsList(): array
    {
        return [
            // --- Standard Display (Banners) ---
            [
                'name' => 'Medium Rectangle (MPU)',
                'format' => '300x250',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => null
            ],
            [
                'name' => 'Leaderboard',
                'format' => '728x90',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => null
            ],
            [
                'name' => 'Wide Skyscraper',
                'format' => '160x600',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => null
            ],
            [
                'name' => 'Half Page (Double MPU)',
                'format' => '300x600',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => null
            ],
            [
                'name' => 'Billboard',
                'format' => '970x250',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => null
            ],
            [
                'name' => 'Large Leaderboard',
                'format' => '970x90',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => null
            ],

            // --- Mobile Specific ---
            [
                'name' => 'Smartphone Static Banner',
                'format' => '320x50',
                'family' => 'banner',
                'experience' => 'app',
                'subtype' => 'sticky'
            ],
            [
                'name' => 'Large Mobile Banner',
                'format' => '320x100',
                'family' => 'banner',
                'experience' => 'app',
                'subtype' => null
            ],
            [
                'name' => 'Mobile Interstitial',
                'format' => '320x480',
                'family' => 'banner',
                'experience' => 'app',
                'subtype' => 'interstitial'
            ],

            // --- Video ---
            [
                'name' => 'In-Stream Pre-Roll (16:9)',
                'format' => '1920x1080',
                'family' => 'video',
                'experience' => 'in-stream',
                'subtype' => 'linear'
            ],
            [
                'name' => 'Out-Stream Video',
                'format' => 'Responsive',
                'family' => 'video',
                'experience' => 'out-stream',
                'subtype' => null
            ],
            [
                'name' => 'Vertical Video (Stories)',
                'format' => '1080x1920',
                'family' => 'video',
                'experience' => 'app',
                'subtype' => 'interstitial'
            ],
            [
                'name' => 'Rewarded Video',
                'format' => 'Fullscreen',
                'family' => 'video',
                'experience' => 'app',
                'subtype' => 'rewarded'
            ],

            // --- Native ---
            [
                'name' => 'Native In-Feed',
                'format' => 'Fluid',
                'family' => 'native',
                'experience' => 'web',
                'subtype' => 'feed'
            ],
            [
                'name' => 'Recommendation Widget',
                'format' => 'Grid',
                'family' => 'native',
                'experience' => 'web',
                'subtype' => 'recommendation'
            ],
            [
                'name' => 'Native App Install',
                'format' => 'Fluid',
                'family' => 'native',
                'experience' => 'app',
                'subtype' => 'app-install'
            ],

            // --- Rich Media / Other ---
            [
                'name' => 'Wallpaper / Skin',
                'format' => 'Custom',
                'family' => 'banner',
                'experience' => 'web',
                'subtype' => 'skin'
            ],
            [
                'name' => 'Push Notification',
                'format' => 'Icon+Text',
                'family' => 'native',
                'experience' => 'app',
                'subtype' => 'push'
            ],
        ];
    }
}