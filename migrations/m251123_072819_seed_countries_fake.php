<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Seeds {{%country}} with a canonical subset of countries (idempotent).
 *
 * New schema note: country now uses an integer PK `id` (AUTO_INCREMENT).
 * We insert a deterministic `id` for each row so other seeders can depend on
 * stable IDs (e.g., agencies.country_id). On re-run, we upsert by the table's
 * unique keys and refresh descriptive fields, never changing an existing `id`.
 *
 * Expected columns:
 *  - id INT PK AI
 *  - iso CHAR(2)
 *  - iso3 CHAR(3) NULL
 *  - name VARCHAR
 *  - continent_code CHAR(2) NULL
 *  - currency_code  CHAR(3) NULL
 *  - status ENUM('active','archived','pending') DEFAULT 'active'
 *  - url_slug VARCHAR
 *  - created_at DATETIME DEFAULT CURRENT_TIMESTAMP
 *  - updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 */
class m251123_072819_seed_countries_fake extends Migration
{
    /** @var array[] [iso, iso3, name, continent_code, currency_code, status] */
    private array $rows = [
        // --- EUROPA ---
        ['ES','ESP','Spain','EU','EUR','active'],
        ['AD','AND','Andorra','EU','EUR','active'],
        ['PT','PRT','Portugal','EU','EUR','active'],
        ['FR','FRA','France','EU','EUR','active'],
        ['DE','DEU','Germany','EU','EUR','active'],
        ['IT','ITA','Italy','EU','EUR','active'],
        ['GB','GBR','United Kingdom','EU','GBP','active'],
        ['IE','IRL','Ireland','EU','EUR','active'],
        ['NL','NLD','Netherlands','EU','EUR','active'],
        ['BE','BEL','Belgium','EU','EUR','active'],
        ['SE','SWE','Sweden','EU','SEK','active'],
        ['NO','NOR','Norway','EU','NOK','active'],
        ['DK','DNK','Denmark','EU','DKK','active'],
        ['FI','FIN','Finland','EU','EUR','active'],
        ['PL','POL','Poland','EU','PLN','active'],
        ['CZ','CZE','Czechia','EU','CZK','active'],
        ['AT','AUT','Austria','EU','EUR','active'],
        ['CH','CHE','Switzerland','EU','CHF','active'],
        ['GR','GRC','Greece','EU','EUR','active'],
        ['HU','HUN','Hungary','EU','HUF','active'],
        ['RO','ROU','Romania','EU','RON','active'],
        ['BG','BGR','Bulgaria','EU','BGN','active'],
        ['SK','SVK','Slovakia','EU','EUR','active'],
        ['SI','SVN','Slovenia','EU','EUR','active'],
        ['HR','HRV','Croatia','EU','EUR','active'],
        ['UA','UKR','Ukraine','EU','UAH','active'],

        // --- NORTEAMÉRICA ---
        ['US','USA','United States','NA','USD','active'],
        ['CA','CAN','Canada','NA','CAD','active'],
        ['MX','MEX','Mexico','NA','MXN','active'],

        // --- SUDAMÉRICA ---
        ['BR','BRA','Brazil','SA','BRL','active'],
        ['AR','ARG','Argentina','SA','ARS','active'],
        ['CL','CHL','Chile','SA','CLP','active'],
        ['CO','COL','Colombia','SA','COP','active'],
        ['PE','PER','Peru','SA','PEN','active'],

        // --- ASIA / ORIENTE MEDIO ---
        ['JP','JPN','Japan','AS','JPY','active'],
        ['CN','CHN','China','AS','CNY','active'],
        ['KR','KOR','South Korea','AS','KRW','active'],
        ['IN','IND','India','AS','INR','active'],
        ['SG','SGP','Singapore','AS','SGD','active'],
        ['AE','ARE','United Arab Emirates','AS','AED','active'],
        ['SA','SAU','Saudi Arabia','AS','SAR','active'],
        ['IL','ISR','Israel','AS','ILS','active'],
        ['TR','TUR','Türkiye','AS','TRY','active'],
        ['TH','THA','Thailand','AS','THB','active'],

        // --- OCEANÍA ---
        ['AU','AUS','Australia','OC','AUD','active'],
        ['NZ','NZL','New Zealand','OC','NZD','active'],

        // --- ÁFRICA ---
        ['ZA','ZAF','South Africa','AF','ZAR','active'],
        ['EG','EGY','Egypt','AF','EGP','active'],
        ['MA','MAR','Morocco','AF','MAD','active'],
    ];

    public function safeUp()
    {
        $id = 1;
        foreach ($this->rows as $r) {
            [$iso,$iso3,$name,$continent,$currency,$status] = $r;

            $this->db->createCommand()->upsert('{{%country}}', [
                'id' => $id,
                'hash' => \Yii::$app->security->generateRandomString(16),
                'iso' => $iso,
                'iso3' => $iso3,
                'name' => $name,
                'continent_code' => $continent,
                'currency_code' => $currency,
                'status' => $status,
                'url_slug' => $this->slugify($name),
            ], [
                'iso3' => $iso3,
                'name' => $name,
                'continent_code' => $continent,
                'currency_code' => $currency,
                'status' => $status,
                'url_slug' => $this->slugify($name),
                'updated_at' => new Expression('CURRENT_TIMESTAMP'),
            ])->execute();

            $id++;
        }
    }

    public function safeDown()
    {
        return true;
    }

    /**
     * Very small slugifier (ASCII, lowercase, dashes).
     */
    private function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('~[^\\pL\\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-a-z0-9]+~', '', $text);
        return $text ?: 'n-a';
    }
}