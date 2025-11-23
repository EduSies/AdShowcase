<?php

use yii\db\Migration;

/**
 * Migra datos fake para la tabla {{%agency}} (ADSHOWCASE_agency).
 *
 * - Genera N agencias con Faker.
 * - Respeta únicos: name y hash.
 * - Distribuye status: active (~70%), pending (~20%), archived (~10%).
 * - country_iso: pool de ISO2 comunes (ajusta si necesitas otras).
 *
 */
class m251123_072820_seed_agencies_fake extends Migration
{
    /** Cuántas filas faker quieres generar. */
    private int $rows = 160;

    /** Lista simple de ISO2 (ajústala a tus países válidos). */
    private array $isoPool = ['ES','US','FR','DE','IT','GB','PT','NL','SE','NO','DK','FI','IE'];

    public function safeUp()
    {
        $faker = \Faker\Factory::create(); // en_US por defecto
        $table = '{{%agency}}';

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
                $candidate = Yii::$app->security->generateRandomString(16);
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

            // --- country_iso al azar del pool ---
            $countryIso = $this->isoPool[array_rand($this->isoPool)];

            // --- fechas coherentes (created <= updated) dentro del último año ---
            $createdTs = $now - mt_rand(0, 3600 * 24 * 365);
            $updatedTs = $createdTs + mt_rand(0, 3600 * 24 * 60);
            $createdAt = date('Y-m-d H:i:s', $createdTs);
            $updatedAt = date('Y-m-d H:i:s', $updatedTs);

            $batch[] = [
                'hash' => $hash,
                'name' => $name,
                'status' => $status,
                'country_iso' => $countryIso,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        $chunkSize = 500;
        $columns = ['hash', 'name', 'status', 'country_iso', 'created_at', 'updated_at'];

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
                        " (`hash`,`name`,`status`,`country_iso`,`created_at`,`updated_at`)
                         VALUES (:hash,:name,:status,:iso,:created,:updated)"
                    )->bindValues([
                        ':hash' => $row['hash'],
                        ':name' => $row['name'],
                        ':status' => $row['status'],
                        ':iso' => $row['country_iso'],
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
