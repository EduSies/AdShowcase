<?php
/**
 * Migra el **núcleo de tablas** de AdShowcase (excluye RBAC porque ya lo migras con @yii/rbac/migrations).
 *
 * Criterios de diseño:
 * - Todas las tablas usan utf8mb4 para soportar emojis y caracteres internacionales.
 * - Claves primarias BIGINT para entidades con crecimiento potencial (creative, logs, etc.).
 * - Índices pensados para filtros habituales (brand/agency/device/format/country/sales_type).
 * - FKs con acciones ON DELETE adecuadas:
 *     * CASCADE cuando la entidad hija no tiene sentido sin su padre (p. ej. fav_list_item).
 *     * SET NULL cuando el vínculo es referencial pero no esencial (p. ej. product en creative).
 *
 * Nota: las tablas de RBAC (auth_*) NO se crean aquí; se aplican con las migraciones oficiales de Yii2.
 */

use yii\db\Migration;

class m251115_195201_create_adshowcase_core extends Migration
{
    /**
     * Opciones de tabla para MySQL/MariaDB: motor InnoDB + colación utf8mb4.
     * Si usas otra BD, Yii ignora estas opciones sin problema.
     */
    private ?string $tableOptions = null;

    public function init()
    {
        parent::init();
        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
        }
    }

    public function safeUp()
    {
        // === Reutilizables de tipo (mantener DRY) ===
        // Ojo: no incluyen DEFAULT; lo añadimos donde toque para preservar el comportamiento actual.
        $statusEnum = "ENUM('active','archived','pending') NOT NULL";
        $workflowStatusEnum = "ENUM('draft','reviewed','approved') NOT NULL";

        /*
         * =========================
         * 1) Tabla de usuarios
         * =========================
         * Nota: el nombre "user" se escapa como {{%user}} para evitar conflictos.
         */
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'hash' => $this->char(16)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'username' => $this->string(255)->notNull()->unique(),
            'type' => $this->string(32)->notNull(), // tipología interna (admin/editor/sales/...)
            'name' => $this->string(255)->notNull(),
            'surname' => $this->string(255)->notNull(),
            'status' => "ENUM('active','archived','banned','inactive','pending') NOT NULL DEFAULT 'active'",
            'language_id' => $this->integer()->null(),
            'default_profile' => $this->string(64)->null(),
            'avatar_url' => $this->string(255)->null(),
            'password_hash' => $this->string()->notNull(), // Hash seguro de la contraseña
            'auth_key' => $this->string(32)->notNull(), // Clave "remember me" (Yii::$app->security->generateRandomString())
            'password_reset_token' => $this->string(255)->null()->unique(), // Token de reseteo
            'verification_token' => $this->string(255)->null()->unique(), // Token de verificación de email
            'email_verified_at' => $this->dateTime()->null(), // Marca de verificación del email
            'failed_login_attempts' => $this->integer()->notNull()->defaultValue(0), // contador de fallos login
            'locked_until' => $this->dateTime()->null(), // bloqueado hasta...
            'last_login_at' => $this->dateTime()->null(), // último login (timestamp)
            'last_login_ip' => $this->string(45)->null(), // último IP (IPv4/IPv6)
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        /*
         * =========================
         * 2) asset_file
         * =========================
         * “Archivo físico” origen de los creativos (vídeo, imagen, etc.).
         * Se separa de creative para poder reutilizar o versionar en el futuro.
         */
        $this->createTable('{{%asset_file}}', [
            'id' => $this->bigPrimaryKey(),
            'hash_sha256' => $this->char(64)->notNull()->unique(),
            'storage_path' => $this->string(500)->notNull(),
            'mime' => $this->string(100)->notNull(),
            'width' => $this->integer()->null(),
            'height' => $this->integer()->null(),
            'duration_sec' => $this->integer()->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        /*
         * =========================
         * 3) Catálogos
         * =========================
         */
        // sales_type (p. ej. programmatic, direct, guaranteed…)
        $this->createTable('{{%sales_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull()->unique(),
            'status' => $statusEnum . " DEFAULT 'active'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        // brand
        $this->createTable('{{%brand}}', [
            'id' => $this->primaryKey(),
            'hash' => $this->char(16)->notNull()->unique(),
            'name' => $this->string(255)->notNull()->unique(),
            'url_name' => $this->string(255)->notNull()->unique(),
            'status' => $statusEnum . " DEFAULT 'active'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        // agency
        $this->createTable('{{%agency}}', [
            'id' => $this->primaryKey(),
            'hash' => $this->char(16)->notNull()->unique(),
            'name' => $this->string(255)->notNull()->unique(),
            'status' => $statusEnum . " DEFAULT 'active'",
            'country_iso' => $this->char(2)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        // device (Desktop/Mobile/Tablet/CTV…)
        $this->createTable('{{%device}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'status' => $statusEnum . " DEFAULT 'active'",
        ], $this->tableOptions);

        // format (familia/experiencia/subtipo para filtros)
        $this->createTable('{{%format}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
            'format' => $this->string(100)->notNull(),
            'family' => $this->string(100)->notNull(),
            'experience' => $this->string(100)->notNull(),
            'subtype' => $this->string(100)->null(),
            'status' => $statusEnum . " DEFAULT 'active'",
            'url_slug' => $this->string(255)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        // country
        $this->createTable('{{%country}}', [
            'iso' => $this->char(2)->notNull(),
            'iso3' => $this->char(3)->null(),
            'name' => $this->string(255)->notNull(),
            'continent_code' => $this->char(2)->null(),
            'currency_code' => $this->char(3)->null(),
            'status' => $statusEnum . " DEFAULT 'active'",
            'url_slug' => $this->string(255)->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);
        $this->addPrimaryKey('pk_country_iso', '{{%country}}', 'iso');

        // product (catálogo libre por marca si aplica)
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $statusEnum . " DEFAULT 'active'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        /*
         * =========================
         * 4) creative
         * =========================
         * Entidad principal de catálogo de creatividades.
         */
        $this->createTable('{{%creative}}', [
            'id' => $this->bigPrimaryKey(),
            'asset_file_id' => $this->bigInteger()->notNull(),
            'url_thumbnail' => $this->string(500)->notNull(),
            'title' => $this->string(255)->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'agency_id' => $this->integer()->notNull(),
            'device_id' => $this->integer()->notNull(),
            'country_iso' => $this->char(2)->notNull(),
            'format_id' => $this->integer()->notNull(),
            'sales_type_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->null(),
            'language' => $this->char(2)->notNull(),
            'click_url' => $this->string(500)->null(),
            'workflow_status' => $workflowStatusEnum . " DEFAULT 'draft'",
            'status' => $statusEnum . " DEFAULT 'active'",
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

        // Índices de filtrado rápido
        $this->createIndex('idx_creative_brand', '{{%creative}}', 'brand_id');
        $this->createIndex('idx_creative_agency', '{{%creative}}', 'agency_id');
        $this->createIndex('idx_creative_device', '{{%creative}}', 'device_id');
        $this->createIndex('idx_creative_format', '{{%creative}}', 'format_id');
        $this->createIndex('idx_creative_country', '{{%creative}}', 'country_iso');
        $this->createIndex('idx_creative_sales_type', '{{%creative}}', 'sales_type_id');
        $this->createIndex('idx_creative_status', '{{%creative}}', 'status');

        // FKs (ver políticas de borrado)
        $this->addForeignKey('fk_creative_asset', '{{%creative}}', 'asset_file_id', '{{%asset_file}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_brand', '{{%creative}}', 'brand_id', '{{%brand}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_agency', '{{%creative}}', 'agency_id', '{{%agency}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_device', '{{%creative}}', 'device_id', '{{%device}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_format', '{{%creative}}', 'format_id', '{{%format}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_country', '{{%creative}}', 'country_iso', '{{%country}}', 'iso', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_sales_type', '{{%creative}}', 'sales_type_id', '{{%sales_type}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_creative_product', '{{%creative}}', 'product_id', '{{%product}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_creative_user', '{{%creative}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        /*
         * =========================
         * 5) Favoritos 1-click
         * =========================
         * PK compuesta (user_id, creative_id) + FK en cascada.
         */
        $this->createTable('{{%favorite}}', [
            'user_id' => $this->integer()->notNull(),
            'creative_id' => $this->bigInteger()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $this->tableOptions);
        $this->addPrimaryKey('pk_favorite', '{{%favorite}}', ['user_id', 'creative_id']);
        $this->addForeignKey('fk_fav_user', '{{%favorite}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_fav_creative', '{{%favorite}}', 'creative_id', '{{%creative}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * =========================
         * 6) Listas de favoritos
         * =========================
         */
        $this->createTable('{{%fav_list}}', [
            'id' => $this->bigPrimaryKey(),
            'hash' => $this->char(16)->notNull()->unique(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);
        $this->addForeignKey('fk_favlist_user', '{{%fav_list}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%fav_list_item}}', [
            'id' => $this->bigPrimaryKey(),
            'hash' => $this->char(16)->notNull()->unique(),
            'list_id' => $this->bigInteger()->notNull(),
            'creative_id' => $this->bigInteger()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $this->tableOptions);
        $this->addForeignKey('fk_favitem_list', '{{%fav_list_item}}', 'list_id', '{{%fav_list}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_favitem_creative', '{{%fav_list_item}}', 'creative_id', '{{%creative}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * =========================
         * 7) Enlaces compartidos
         * =========================
         * token CHAR(43) para compatibilidad con tokens base64url sin padding (~256 bits).
         */
        $this->createTable('{{%shared_link}}', [
            'id' => $this->bigPrimaryKey(),
            'token' => $this->char(43)->notNull()->unique(),
            'creative_id' => $this->bigInteger()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'expires_at' => $this->dateTime()->null(),
            'max_uses' => $this->integer()->null(),
            'used_count' => $this->integer()->notNull()->defaultValue(0),
            'revoked_at' => $this->dateTime()->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'note' => $this->string(500)->null(),
        ], $this->tableOptions);
        $this->addForeignKey('fk_shared_creative', '{{%shared_link}}', 'creative_id', '{{%creative}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_shared_owner', '{{%shared_link}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%shared_link_access_log}}', [
            'id' => $this->bigPrimaryKey(),
            'shared_link_id' => $this->bigInteger()->notNull(),
            'accessed_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(45)->notNull(),
            'user_agent' => $this->string(255)->notNull(),
        ], $this->tableOptions);
        $this->addForeignKey('fk_sharedlog_link', '{{%shared_link_access_log}}', 'shared_link_id', '{{%shared_link}}', 'id', 'CASCADE', 'CASCADE');

        /*
         * =========================
         * 8) Auditoría
         * =========================
         * Guarda acciones de usuario sobre entidades del sistema.
         */
        $this->createTable('{{%audit_log}}', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer()->null(),
            'action' => $this->string(100)->notNull(),
            'entity' => $this->string(100)->notNull(),
            'entity_id' => $this->bigInteger()->notNull(),
            'meta' => $this->json()->notNull(),
            'created_at'=> $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $this->tableOptions);
        $this->createIndex('idx_audit_entity', '{{%audit_log}}', ['entity', 'entity_id']);
        $this->addForeignKey('fk_audit_user', '{{%audit_log}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        // Borrar en el orden inverso para respetar FKs
        $this->dropForeignKey('fk_audit_user', '{{%audit_log}}');
        $this->dropTable('{{%audit_log}}');

        $this->dropForeignKey('fk_sharedlog_link', '{{%shared_link_access_log}}');
        $this->dropTable('{{%shared_link_access_log}}');

        $this->dropForeignKey('fk_shared_owner', '{{%shared_link}}');
        $this->dropForeignKey('fk_shared_creative', '{{%shared_link}}');
        $this->dropTable('{{%shared_link}}');

        $this->dropForeignKey('fk_favitem_creative', '{{%fav_list_item}}');
        $this->dropForeignKey('fk_favitem_list', '{{%fav_list_item}}');
        $this->dropTable('{{%fav_list_item}}');

        $this->dropForeignKey('fk_favlist_user', '{{%fav_list}}');
        $this->dropTable('{{%fav_list}}');

        $this->dropForeignKey('fk_fav_creative', '{{%favorite}}');
        $this->dropForeignKey('fk_fav_user', '{{%favorite}}');
        $this->dropTable('{{%favorite}}');

        $this->dropForeignKey('fk_creative_user', '{{%creative}}');
        $this->dropForeignKey('fk_creative_product', '{{%creative}}');
        $this->dropForeignKey('fk_creative_sales_type', '{{%creative}}');
        $this->dropForeignKey('fk_creative_country', '{{%creative}}');
        $this->dropForeignKey('fk_creative_format', '{{%creative}}');
        $this->dropForeignKey('fk_creative_device', '{{%creative}}');
        $this->dropForeignKey('fk_creative_agency', '{{%creative}}');
        $this->dropForeignKey('fk_creative_brand', '{{%creative}}');
        $this->dropForeignKey('fk_creative_asset', '{{%creative}}');
        $this->dropTable('{{%creative}}');

        $this->dropTable('{{%product}}');
        $this->dropTable('{{%country}}');
        $this->dropTable('{{%format}}');
        $this->dropTable('{{%device}}');
        $this->dropTable('{{%agency}}');
        $this->dropTable('{{%brand}}');
        $this->dropTable('{{%sales_type}}');

        $this->dropTable('{{%asset_file}}');
        $this->dropTable('{{%user}}');
    }
}