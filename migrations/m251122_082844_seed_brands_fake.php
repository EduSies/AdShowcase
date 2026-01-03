<?php

use yii\db\Migration;
use yii\helpers\Inflector;

class m251122_082844_seed_brands_fake extends Migration
{
    /** Cuántas marcas generar en total. */
    private int $count = 500; // Ajusta este número según necesites

    public function safeUp(): void
    {
        $table = '{{%brand}}';
        $faker = \Faker\Factory::create();
        $now = time();

        // Lista de marcas reales
        $realBrands = $this->getRealBrandsList();
        $totalReal = count($realBrands);

        for ($i = 0; $i < $this->count; $i++) {
            // Lógica: Usar nombre real mientras queden, sino usar Faker
            if ($i < $totalReal) {
                $name = $realBrands[$i];
            } else {
                // Si ya gastamos los nombres reales, generamos aleatorios
                $name = $faker->unique()->company();
            }

            // slug único (url_slug)
            $slug = Inflector::slug($name);

            // hash aleatorio de 16
            $hash = \Yii::$app->security->generateRandomString(16);

            // status con pesos
            $status = $this->weightedStatus();

            // fechas coherentes
            $createdTs = $now - random_int(0, 3600 * 24 * 365);
            $updatedTs = $createdTs + random_int(0, 3600 * 24 * 60);
            $createdAt = date('Y-m-d H:i:s', $createdTs);
            $updatedAt = date('Y-m-d H:i:s', $updatedTs);

            $row = [
                'hash' => $hash,
                'name' => $name,
                'url_slug' => $slug,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            // Usamos upsert para evitar errores si ejecutas la seed varias veces
            $this->upsert($table, $row, [
                'name' => $row['name'], // Si existe hash/slug/id, actualizamos nombre (o lo que quieras)
                'status' => $row['status'],
                'updated_at' => $row['updated_at'],
            ]);
        }
    }

    public function safeDown(): bool
    {
        // En desarrollo solemos hacer truncate, pero return false protege datos
        // $this->truncateTable('{{%brand}}');
        return false;
    }

    private function weightedStatus(): string
    {
        $r = random_int(1, 100);
        if ($r <= 75) return 'active';
        if ($r <= 90) return 'pending';
        return 'archived';
    }

    /**
     * Devuelve un array con marcas reales de diversos sectores.
     */
    private function getRealBrandsList(): array
    {
        return [
            // Tech & Electronics
            'Apple', 'Samsung', 'Google', 'Microsoft', 'Sony', 'Dell', 'HP', 'Lenovo', 'LG', 'Panasonic',
            'Intel', 'Nvidia', 'AMD', 'Cisco', 'Oracle', 'IBM', 'Adobe', 'Salesforce', 'SAP', 'Spotify',
            'Netflix', 'Meta', 'TikTok', 'Uber', 'Airbnb', 'Tesla', 'SpaceX', 'Garmin', 'Canon', 'Nikon',
            'GoPro', 'Dyson', 'Bose', 'Sonos', 'Nintendo', 'PlayStation', 'Xbox', 'Razer', 'Logitech',

            // Auto
            'Toyota', 'Volkswagen', 'Ford', 'Honda', 'BMW', 'Mercedes-Benz', 'Audi', 'Porsche', 'Ferrari',
            'Lamborghini', 'Hyundai', 'Kia', 'Nissan', 'Chevrolet', 'Jeep', 'Land Rover', 'Volvo', 'Lexus',
            'Mazda', 'Subaru', 'Yamaha', 'Ducati', 'Harley-Davidson',

            // Fashion & Apparel
            'Nike', 'Adidas', 'Puma', 'Under Armour', 'New Balance', 'Reebok', 'Zara', 'H&M', 'Uniqlo',
            'Levi\'s', 'Gap', 'Calvin Klein', 'Tommy Hilfiger', 'Ralph Lauren', 'Lacoste', 'The North Face',
            'Patagonia', 'Columbia', 'Vans', 'Converse', 'Dr. Martens', 'Timberland',

            // Luxury
            'Louis Vuitton', 'Gucci', 'Chanel', 'Hermès', 'Prada', 'Dior', 'Rolex', 'Cartier', 'Tiffany & Co.',
            'Burberry', 'Versace', 'Armani', 'Balenciaga', 'Saint Laurent', 'Omega', 'Tag Heuer',

            // Food & Beverage
            'Coca-Cola', 'Pepsi', 'Red Bull', 'Nestlé', 'Danone', 'Kellogg\'s', 'General Mills', 'Kraft Heinz',
            'Mars', 'Hershey\'s', 'Ferrero', 'Mondelez', 'Unilever', 'Starbucks', 'McDonald\'s', 'Burger King',
            'KFC', 'Subway', 'Domino\'s', 'Pizza Hut', 'Taco Bell', 'Heineken', 'Budweiser', 'Corona',
            'Jack Daniel\'s', 'Johnnie Walker', 'Nespresso', 'Lipton',

            // Beauty & Personal Care
            'L\'Oréal', 'Estée Lauder', 'Nivea', 'Dove', 'Gillette', 'Colgate', 'Oral-B', 'Pantene',
            'Head & Shoulders', 'Garnier', 'Maybelline', 'MAC Cosmetics', 'Sephora', 'Lush',

            // Retail & E-commerce
            'Amazon', 'Walmart', 'Target', 'Costco', 'IKEA', 'Home Depot', 'Best Buy', 'Alibaba', 'eBay',
            'Etsy', 'Shopify',

            // Finance & Services
            'Visa', 'Mastercard', 'American Express', 'PayPal', 'JP Morgan', 'Goldman Sachs', 'HSBC',
            'Allianz', 'AXA', 'Santander', 'BBVA',

            // Airlines & Travel
            'Delta', 'American Airlines', 'United Airlines', 'Emirates', 'Lufthansa', 'British Airways',
            'Air France', 'Ryanair', 'Booking.com', 'Expedia', 'TripAdvisor', 'Hilton', 'Marriott', 'Hyatt'
        ];
    }
}