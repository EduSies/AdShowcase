<?php

use yii\db\Migration;

/**
 * Migra datos fake para la tabla {{%agency}} (ADSHOWCASE_agency).
 *
 * - Genera N agencias con Faker.
 * - Respeta únicos: name y hash.
 * - Distribuye status: active (~70%), pending (~20%), archived (~10%).
 * - country_id: se asigna a partir de los IDs reales de {{%country}}.
 *
 */
class m251123_072820_seed_agencies_fake extends Migration
{
    /** Cuántas filas faker quieres generar. */
    private int $rows = 160;

    public function safeUp()
    {
        $faker = \Faker\Factory::create(); // en_US por defecto
        $table = '{{%agency}}';

        // Obtenemos todos los IDs de países existentes en la tabla country
        $countryIds = (new \yii\db\Query())
            ->select('id')
            ->from('{{%country}}')
            ->column();

        if (empty($countryIds)) {
            throw new \RuntimeException('No hay países en la tabla {{%country}} para asociar a las agencias fake.');
        }

        // Para asegurar unicidad antes de insertar (evita choques con unique() de Faker).
        $usedNames = [];
        $usedHashes = [];

        // Construimos los registros en memoria (y luego batchInsert en bloques).
        $batch = [];
        $now = time();

        for ($i = 0; $i < $this->rows; $i++) {
            // --- name único ---
            // Intentamos hasta que no choque (por si existe ya en BD).
            $name = null;
            for ($t = 0; $t < 10; $t++) {
                $candidate = $faker->unique()->company;
                if (!isset($usedNames[$candidate])) {
                    $name = $candidate;
                    $usedNames[$candidate] = true;
                    break;
                }
            }
            if ($name === null) {
                // fallback si Faker se queda sin únicos
                $name = 'Agency ' . $faker->unique()->bothify('#### ?????????');
            }

            // --- hash único de 16 chars ---
            $hash = null;
            for ($t = 0; $t < 10; $t++) {
                $candidate = \Yii::$app->security->generateRandomString(16);
                if (!isset($usedHashes[$candidate])) {
                    $hash = $candidate;
                    $usedHashes[$candidate] = true;
                    break;
                }
            }
            if ($hash === null) {
                $hash = \Yii::$app->security->generateRandomString(16);
            }

            // --- status con pesos 70/20/10 ---
            $rnd = mt_rand(1, 100);
            $status = $rnd <= 70 ? 'active' : ($rnd <= 90 ? 'pending' : 'archived');

            // --- country_id al azar de los existentes ---
            $countryId = $countryIds[array_rand($countryIds)];

            // --- fechas coherentes (created <= updated) dentro del último año ---
            $createdTs = $now - mt_rand(0, 3600 * 24 * 365);
            $updatedTs = $createdTs + mt_rand(0, 3600 * 24 * 60);
            $createdAt = date('Y-m-d H:i:s', $createdTs);
            $updatedAt = date('Y-m-d H:i:s', $updatedTs);

            $batch[] = [
                'hash'        => $hash,
                'name'        => $name,
                'status'      => $status,
                'country_id'  => $countryId,
                'created_at'  => $createdAt,
                'updated_at'  => $updatedAt,
            ];
        }

        $chunkSize = 500;
        $columns = ['hash', 'name', 'status', 'country_id', 'created_at', 'updated_at'];

        foreach (array_chunk($batch, $chunkSize) as $chunk) {
            // Como pueden existir nombres/hasheados previos, intentamos batchInsert
            // y si falla por UNIQUE, caemos a inserciones individuales con IGNORE.
            try {
                $this->batchInsert($table, $columns, $chunk);
            } catch (\Throwable $e) {
                // Insert individual “a prueba de únicos”
                foreach ($chunk as $row) {
                    // Con comando SQL con IGNORE evitamos romper la migración por duplicados ya existentes.
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
            }
        }
    }

    public function safeDown()
    {
        return false;
    }
}