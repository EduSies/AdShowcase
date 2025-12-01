<?php

use yii\db\Migration;

class m251123_074208_seed_formats_fake extends Migration
{
    public function safeUp()
    {
        $rows = $this->buildRows(15);
        foreach ($rows as $row) {
            // Upsert to keep idempotency if you run the seeder more than once
            $this->upsert('{{%format}}', $row, true);
        }
    }

    public function safeDown()
    {
        return false;
    }

    /**
     * Build a deterministic set of fake formats using Faker.
     */
    private function buildRows(int $count = 15): array
    {
        $faker = \Faker\Factory::create('en_US');
        // Seed to make safeDown reproducible
        $faker->seed(740208);

        $families = [
            'banner' => [
                'formats' => ['300x250','160x600','728x90','300x600','970x250','320x50','336x280','468x60','250x250','120x600'],
                'experiences' => ['web','app','display'],
                'subtypes' => [null,'sticky','expandable','responsive'],
            ],
            'video' => [
                'formats' => ['VAST','MP4'],
                'experiences' => ['in-stream','out-stream','in-banner'],
                'subtypes' => [null,'skippable','non-skippable','bumper'],
            ],
            'native' => [
                'formats' => ['infeed','article','card','recommendation'],
                'experiences' => ['web','app'],
                'subtypes' => [null,'app-install','content','product'],
            ],
        ];

        $statuses = ['active','active','active','pending','archived']; // bias to active
        $usedSlugs = [];
        $rows = [];

        for ($i = 0; $i < $count; $i++) {
            $family = $faker->randomElement(array_keys($families));
            $conf = $families[$family];

            $format = $faker->randomElement($conf['formats']);
            $experience = $faker->randomElement($conf['experiences']);
            $subtype = $faker->randomElement($conf['subtypes']);

            // Build a readable name, keep <= 150 chars
            $nameParts = [ucfirst($family), $format];
            if ($subtype) {
                $nameParts[] = '(' . $subtype . ')';
            }
            $name = trim(implode(' ', $nameParts));

            $slug = $this->slugify($name);

            // Ensure unique slug within this batch
            $suffix = 1;
            $baseSlug = $slug;
            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug . '-' . (++$suffix);
            }
            $usedSlugs[$slug] = true;

            $createdAt = $faker->dateTimeBetween('-2 years', '-1 week');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            $rows[] = [
                'hash' => \Yii::$app->security->generateRandomString(16),
                'name' => mb_substr($name, 0, 150),
                'format' => (string)$format,
                'family' => (string)$family,
                'experience' => (string)$experience,
                'subtype' => $subtype,
                'status' => $faker->randomElement($statuses),
                'url_slug' => $slug,
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
                'updated_at' => $updatedAt->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }

    /**
     * Basic slugify (ASCII, dashes, lowercase)
     */
    private function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('~[^\\pL\\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-a-z0-9]+~', '', $text);
        return $text ?: 'format-' . uniqid();
    }
}
