<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Seeds {{%country}} with a canonical subset of countries (idempotent).
 * Table DDL (for reference):
 *  - iso CHAR(2) PK
 *  - iso3 CHAR(3)
 *  - name VARCHAR
 *  - continent_code CHAR(2)
 *  - currency_code CHAR(3)
 *  - status ENUM('active','archived','pending') DEFAULT 'active'
 *  - url_slug VARCHAR
 *  - created_at DATETIME DEFAULT CURRENT_TIMESTAMP
 *  - updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 */
class m251123_080814_seed_countries_fake extends Migration
{
    /** @var array[] [iso, iso3, name, continent_code, currency_code, status] */
    private array $rows = [
        ['ES','ESP','Spain','EU','EUR','active'],
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

        ['US','USA','United States','NA','USD','active'],
        ['CA','CAN','Canada','NA','CAD','active'],
        ['MX','MEX','Mexico','NA','MXN','active'],

        ['BR','BRA','Brazil','SA','BRL','active'],
        ['AR','ARG','Argentina','SA','ARS','active'],
        ['CL','CHL','Chile','SA','CLP','active'],

        ['JP','JPN','Japan','AS','JPY','active'],
        ['CN','CHN','China','AS','CNY','active'],
        ['IN','IND','India','AS','INR','active'],
        ['AE','ARE','United Arab Emirates','AS','AED','active'],

        ['AU','AUS','Australia','OC','AUD','active'],
        ['NZ','NZL','New Zealand','OC','NZD','active'],

        ['ZA','ZAF','South Africa','AF','ZAR','active'],
        ['EG','EGY','Egypt','AF','EGP','active'],
        ['TR','TUR','Türkiye','AS','TRY','active'],
    ];

    public function safeUp()
    {
        foreach ($this->rows as $r) {
            [$iso,$iso3,$name,$continent,$currency,$status] = $r;

            $this->db->createCommand()->upsert('{{%country}}', [
                'iso'            => $iso,
                'iso3'           => $iso3,
                'name'           => $name,
                'continent_code' => $continent,
                'currency_code'  => $currency,
                'status'         => $status,
                'url_slug'       => $this->slugify($name),
            ], [
                // On duplicate key, refresh descriptive fields + slug
                'iso3'           => $iso3,
                'name'           => $name,
                'continent_code' => $continent,
                'currency_code'  => $currency,
                'status'         => $status,
                'url_slug'       => $this->slugify($name),
                'updated_at'     => new Expression('CURRENT_TIMESTAMP'),
            ])->execute();
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