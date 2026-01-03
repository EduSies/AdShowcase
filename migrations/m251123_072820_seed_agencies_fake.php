<?php

use yii\db\Migration;

class m251123_072820_seed_agencies_fake extends Migration
{
    /** Cuántas filas generar. Si pones más que la lista real, usará Faker. */
    private int $rows = 100;

    public function safeUp()
    {
        $faker = \Faker\Factory::create();
        $table = '{{%agency}}';

        // Obtener IDs de países
        $countryIds = (new \yii\db\Query())->select('id')->from('{{%country}}')->column();
        if (empty($countryIds)) {
            // Fallback por seguridad si no hay países cargados
            $countryIds = [1];
        }

        $realAgencies = $this->getRealAgenciesList();
        $totalReal = count($realAgencies);

        $batch = [];
        $now = time();

        for ($i = 0; $i < $this->rows; $i++) {

            // Determinar Nombre
            if ($i < $totalReal) {
                $name = $realAgencies[$i];
            } else {
                // Fallback a Faker: "Nombre + Agency/Group/Media"
                $suffix = $faker->randomElement(['Agency', 'Group', 'Media', 'Worldwide', 'Communications', 'Partners']);
                $name = $faker->unique()->lastName . ' ' . $suffix;
            }

            // Hash
            $hash = \Yii::$app->security->generateRandomString(16);

            // Status
            $rnd = mt_rand(1, 100);
            $status = $rnd <= 70 ? 'active' : ($rnd <= 90 ? 'pending' : 'archived');

            // Country
            $countryId = $countryIds[array_rand($countryIds)];

            // Fechas
            $createdTs = $now - mt_rand(0, 3600 * 24 * 365);
            $updatedTs = $createdTs + mt_rand(0, 3600 * 24 * 60);

            $batch[] = [
                'hash' => $hash,
                'name' => $name,
                'status' => $status,
                'country_id' => $countryId,
                'created_at' => date('Y-m-d H:i:s', $createdTs),
                'updated_at' => date('Y-m-d H:i:s', $updatedTs),
            ];
        }

        // Insertar en bloques
        $chunkSize = 500;
        $columns = ['hash', 'name', 'status', 'country_id', 'created_at', 'updated_at'];

        foreach (array_chunk($batch, $chunkSize) as $chunk) {
            // Usamos IGNORE para saltar duplicados si el nombre ya existiera
            try {
                foreach ($chunk as $row) {
                    $this->db->createCommand()->setSql(
                        "INSERT IGNORE INTO " . $this->db->quoteTableName($table) .
                        " (`hash`,`name`,`status`,`country_id`,`created_at`,`updated_at`)
                         VALUES (:hash,:name,:status,:country_id,:created,:updated)"
                    )->bindValues([
                        ':hash' => $row['hash'],
                        ':name' => $row['name'],
                        ':status' => $row['status'],
                        ':country_id' => $row['country_id'],
                        ':created' => $row['created_at'],
                        ':updated' => $row['updated_at'],
                    ])->execute();
                }
            } catch (\Throwable $e) {
                echo "Error insertando lote: " . $e->getMessage() . "\n";
            }
        }
    }

    public function safeDown()
    {
        return false;
    }

    /**
     * Lista de agencias de publicidad, medios y comunicación reales.
     */
    private function getRealAgenciesList(): array
    {
        return [
            // The Big Holdings & Networks
            'Ogilvy', 'McCann Worldgroup', 'DDB Worldwide', 'BBDO', 'TBWA\Worldwide',
            'Leo Burnett', 'Publicis Worldwide', 'Saatchi & Saatchi', 'Grey Group',
            'VML', 'Wunderman Thompson', 'Havas Creative', 'Dentsu International',
            'FCB (Foote, Cone & Belding)', 'MullenLowe Group', 'R/GA', 'AKQA',
            'VaynerMedia', 'Droga5', 'Wieden+Kennedy', '72andSunny', 'Anomaly',
            'Crispin Porter Bogusky', 'Forsman & Bodenfors', 'Mother London',
            'BBH (Bartle Bogle Hegarty)', 'Goodby Silverstein & Partners', 'Deutsch',
            'Jung von Matt', 'Serviceplan Group', 'Hakuhodo', 'Cheil Worldwide',

            // Media Agencies
            'Mindshare', 'OMD', 'Carat', 'MediaCom', 'Wavemaker', 'Starcom',
            'Zenith', 'PHD Media', 'Initiative', 'UM (Universal McCann)',
            'Havas Media', 'iProspect', 'Essence', 'Spark Foundry', 'Assembly',
            'Hearts & Science', 'Horizon Media',

            // Consultancies / Digital
            'Accenture Song', 'Deloitte Digital', 'IBM iX', 'PwC Digital',
            'Globant', 'Media.Monks', 'Huge', 'Critical Mass', 'Mirum',
            'Isobar', 'Digitas', 'Razorfish',

            // PR & Communications
            'Edelman', 'Weber Shandwick', 'FleishmanHillard', 'Ketchum',
            'Burson', 'Hill & Knowlton', 'MSL', 'Golin', 'Ogilvy PR'
        ];
    }
}