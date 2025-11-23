
<?php

use yii\db\Migration;

class m251123_082603_seed_products_fake extends Migration
{
    public function safeUp()
    {
        $rows = [
            ['name' => 'Art & Entertainment', 'url_slug' => 'art-entertainment', 'status' => 'active'],
            ['name' => 'Animals & Pet', 'url_slug' => 'animals-pet', 'status' => 'active'],
            ['name' => 'Apparel / Fashion & Jewelry', 'url_slug' => 'apparel-fashion-jewelry', 'status' => 'active'],
            ['name' => 'Automotive', 'url_slug' => 'automotive', 'status' => 'active'],
            ['name' => 'Beauty & Personal Care', 'url_slug' => 'beauty-personal-care', 'status' => 'active'],
            ['name' => 'Alcoholic Beverages', 'url_slug' => 'alcoholic-beverages', 'status' => 'active'],
            ['name' => 'Education & Employment', 'url_slug' => 'education-employment', 'status' => 'active'],
            ['name' => 'Finance / Insurance & Business', 'url_slug' => 'finance-insurance-business', 'status' => 'active'],
            ['name' => 'Pharma / Health & Fitness', 'url_slug' => 'pharma-health-fitness', 'status' => 'active'],
            ['name' => 'Home & Garden', 'url_slug' => 'home-garden', 'status' => 'active'],
            ['name' => 'Restaurants', 'url_slug' => 'restaurants', 'status' => 'active'],
            ['name' => 'Sports', 'url_slug' => 'sports', 'status' => 'active'],
            ['name' => 'Retail', 'url_slug' => 'retail', 'status' => 'active'],
            ['name' => 'Travel', 'url_slug' => 'travel', 'status' => 'active'],
            ['name' => 'Utilities (Energy / Telco and Water)', 'url_slug' => 'utilities-energy-telco-and-water', 'status' => 'active'],
            ['name' => 'Government / Institutional', 'url_slug' => 'government-institutional', 'status' => 'active'],
            ['name' => 'Kids', 'url_slug' => 'kids', 'status' => 'active'],
            ['name' => 'Gambling', 'url_slug' => 'gambling', 'status' => 'active'],
            ['name' => 'Tech & Electronics', 'url_slug' => 'tech-electronics', 'status' => 'active'],
            ['name' => 'Luxury', 'url_slug' => 'luxury', 'status' => 'active'],
            ['name' => 'Other', 'url_slug' => 'other', 'status' => 'active'],
        ];

        foreach ($rows as $row) {
            $this->upsert('{{%product}}', $row, [
                'name'     => $row['name'],
                'url_slug' => $row['url_slug'],
                'status'   => $row['status'],
            ]);
        }
    }

    public function safeDown()
    {
        return true;
    }
}
