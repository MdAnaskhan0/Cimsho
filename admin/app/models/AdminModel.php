<?php
require_once __DIR__ . '/../../core/Model.php';

class AdminModel extends Model {

    public function findByUsername(string $username): ?array {
        return $this->fetchOne(
            'SELECT * FROM admins WHERE username = ? AND is_active = 1 LIMIT 1',
            [$username]
        );
    }

    public function findById(int $id): ?array {
        return $this->fetchOne(
            'SELECT * FROM admins WHERE id = ? LIMIT 1',
            [$id]
        );
    }

    public function updateLastLogin(int $id): void {
        $this->execute(
            'UPDATE admins SET last_login = NOW() WHERE id = ?',
            [$id]
        );
    }

    public function updatePassword(int $id, string $newHash): bool {
        return $this->execute(
            'UPDATE admins SET password_hash = ? WHERE id = ?',
            [$newHash, $id]
        );
    }

    public function updateAvatar(int $id, string $avatar): bool {
        return $this->execute(
            'UPDATE admins SET avatar = ? WHERE id = ?',
            [$avatar, $id]
        );
    }
}
