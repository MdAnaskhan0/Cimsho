<?php
class UserModel extends Model {

    public function findByEmail(string $email): mixed {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function findById(int $id): mixed {
        return $this->fetchOne('SELECT * FROM users WHERE user_id = ?', [$id]);
    }

    public function create(string $name, string $email, string $phone, string $password): int {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->query(
            'INSERT INTO users (name, email, phone, password_hash) VALUES (?, ?, ?, ?)',
            [$name, $email, $phone, $hash]
        );
        return (int) $this->lastInsertId();
    }

    public function emailExists(string $email): bool {
        $row = $this->fetchOne('SELECT user_id FROM users WHERE email = ?', [$email]);
        return $row !== false;
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function updateLastLogin(int $userId): void {
        $this->query('UPDATE users SET is_active = 1 WHERE user_id = ?', [$userId]);
    }
}
