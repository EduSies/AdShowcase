<?php

use yii\db\Migration;

/**
 * Crea usuarios “semilla” (uno por rol) y les asigna su rol RBAC.
 *
 * - Usuarios creados (email/username):
 *      * admin  -> admin@adshowcase.com
 *      * editor -> editor@adshowcase.com
 *      * sales  -> sales@adshowcase.com
 *      * viewer -> viewer@adshowcase.com
 *      * guest  -> guest@adshowcase.com
 *
 * - Idempotente: si los usuarios existen, los reutiliza; si el rol ya está asignado, no lo duplica.
 * - Requisitos: tablas RBAC migradas y roles ya creados (admin, editor, sales, viewer, guest).
 *
 * NOTA: Tu tabla `user` ({{%user}}) no incluye password hash; esto solo crea filas para
 * pruebas/UI. Si en el futuro necesitas login real, añade los campos adecuados y semilla credenciales.
 */

class m251115_205302_seed_users_per_role extends Migration
{
    /**
     * Inserta/asegura un usuario por rol y asigna el rol correspondiente.
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        // ---- 1) Definición de “semillas” por rol ----
        // name/surname son puramente demostrativos; status = 'active' para encajar con tu esquema.
        $seeds = [
            'admin' => ['email' => 'admin@adshowcase.com', 'username' => 'admin', 'name' => 'Admin', 'surname' => 'Seed'],
            'editor' => ['email' => 'editor@adshowcase.com', 'username' => 'editor', 'name' => 'Editor', 'surname' => 'Seed'],
            'sales' => ['email' => 'sales@adshowcase.com', 'username' => 'sales', 'name' => 'Sales', 'surname' => 'Seed'],
            'viewer' => ['email' => 'viewer@adshowcase.com', 'username' => 'viewer', 'name' => 'Viewer', 'surname' => 'Seed'],
            'guest' => ['email' => 'guest@adshowcase.com', 'username' => 'guest', 'name' => 'Guest', 'surname' => 'Seed'],
        ];

        foreach ($seeds as $roleName => $u) {
            // ---- 2) Verificar que el rol RBAC exista ----
            $role = $auth->getRole($roleName);
            if ($role === null) {
                // Si prefieres crear silenciosamente el rol:
                // $role = $auth->createRole($roleName); $auth->add($role);
                throw new \yii\base\InvalidConfigException("El rol RBAC '{$roleName}' no existe. Ejecuta la seed de roles antes de esta migración.");
            }

            // ---- 3) Asegurar el usuario (crear si no existe) ----
            $userId = $this->ensureUser($u['email'], $u['username'], $roleName, $u['name'], $u['surname']);

            // ---- 4) Asignar el rol al usuario (si no está asignado) ----
            if ($auth->getAssignment($roleName, (string)$userId) === null) {
                $auth->assign($role, (string)$userId);
            }
        }
    }

    /**
     * Revoca roles y elimina los usuarios “semilla”.
     * Se hace por email para no afectar a otros usuarios reales.
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        $byRole = [
            'admin' => 'admin@adshowcase.com',
            'editor' => 'editor@adshowcase.com',
            'sales' => 'sales@adshowcase.com',
            'viewer' => 'viewer@adshowcase.com',
            'guest' => 'guest@adshowcase.com',
        ];

        foreach ($byRole as $roleName => $email) {
            $id = $this->findUserIdByEmail($email);
            if ($id !== null) {
                // Revocar la asignación del rol (si existe)
                $role = $auth->getRole($roleName);
                if ($role !== null) {
                    $auth->revoke($role, (string)$id);
                }
                // Eliminar usuario semilla
                $this->delete('{{%user}}', ['id' => $id]);
            }
        }
    }

    /**
     * Crea el usuario si no existe (por email/username) y devuelve su ID.
     * Campos usados: ver tu migración de núcleo (hash, email, username, type, name, surname, status, language_id, avatar_url).
     */
    private function ensureUser(string $email, string $username, string $type, string $name, string $surname): int
    {
        // Buscar por email o username (ambos tienen UNIQUE en tu esquema)
        $id = $this->findUserIdByEmailOrUsername($email, $username);
        if ($id !== null) {
            return (int)$id;
        }

        // Generar hash corto de 10 chars como pediste (para compartir/enlaces “bonitos”)
        $hash = \Yii::$app->security->generateRandomString(16);

        // Insertar fila mínima válida. created_at/updated_at usan CURRENT_TIMESTAMP por defecto.
        $this->insert('{{%user}}', [
            'hash' => $hash,
            'email' => $email,
            'username' => $username,
            'type' => $type,
            'name' => $name,
            'surname' => $surname,
            'status' => 'active',
            'language_id' => null,
            'avatar_url' => null,
            'password_hash' => \Yii::$app->security->generatePasswordHash('Adshowcase25'),
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_reset_token' => null,
            'verification_token' => null,
            'email_verified_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => null,
            'last_login_ip' => null,
        ]);

        // Recuperar el ID autoincrement
        return (int)$this->db->getLastInsertID();
    }

    /**
     * Devuelve el ID del usuario por email o username, o null si no existe.
     */
    private function findUserIdByEmailOrUsername(string $email, string $username): ?int
    {
        $row = (new \yii\db\Query())
            ->select('id')
            ->from('{{%user}}')
            ->where(['or', ['email' => $email], ['username' => $username]])
            ->one($this->db);

        return $row ? (int)$row['id'] : null;
    }

    /**
     * Devuelve el ID del usuario por email, o null si no existe.
     */
    private function findUserIdByEmail(string $email): ?int
    {
        $row = (new \yii\db\Query())
            ->select('id')
            ->from('{{%user}}')
            ->where(['email' => $email])
            ->one($this->db);

        return $row ? (int)$row['id'] : null;
    }
}