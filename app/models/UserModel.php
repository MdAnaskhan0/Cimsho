<?php
require_once __DIR__.'/../../core/Model.php';
class UserModel extends Model {

    public function findByEmail(string $email): ?array {
        return $this->fetchOne('SELECT * FROM users WHERE email=? AND is_active=1 LIMIT 1',[$email]);
    }
    public function findById(int $id): ?array {
        return $this->fetchOne('SELECT * FROM users WHERE user_id=? LIMIT 1',[$id]);
    }
    public function create(string $name, string $email, string $phone, string $hash): int {
        $this->execute('INSERT INTO users (name,email,phone,password_hash,role) VALUES (?,?,?,?,\'user\')',
            [$name,$email,$phone,$hash]);
        return (int)$this->lastId();
    }
    public function updateProfile(int $id, string $name, string $phone): bool {
        return $this->execute('UPDATE users SET name=?,phone=? WHERE user_id=?',[$name,$phone,$id]);
    }
    public function updatePassword(int $id, string $hash): bool {
        return $this->execute('UPDATE users SET password_hash=? WHERE user_id=?',[$hash,$id]);
    }
    public function emailExists(string $email): bool {
        $r = $this->fetchOne('SELECT user_id FROM users WHERE email=? LIMIT 1',[$email]);
        return $r !== null;
    }

    // Addresses
    public function getAddresses(int $uid): array {
        return $this->fetchAll('SELECT * FROM user_addresses WHERE user_id=? ORDER BY is_default DESC, id DESC',[$uid]);
    }
    public function getAddress(int $id, int $uid): ?array {
        return $this->fetchOne('SELECT * FROM user_addresses WHERE id=? AND user_id=?',[$id,$uid]);
    }
    public function getDefaultAddress(int $uid): ?array {
        return $this->fetchOne('SELECT * FROM user_addresses WHERE user_id=? AND is_default=1 LIMIT 1',[$uid])
            ?? $this->fetchOne('SELECT * FROM user_addresses WHERE user_id=? ORDER BY id DESC LIMIT 1',[$uid]);
    }
    public function addAddress(int $uid, array $d): int {
        if($d['is_default']) $this->execute('UPDATE user_addresses SET is_default=0 WHERE user_id=?',[$uid]);
        $this->execute(
            'INSERT INTO user_addresses (user_id,label,full_name,phone,address_line,area,city,district,postal_code,is_default)
             VALUES (?,?,?,?,?,?,?,?,?,?)',
            [$uid,$d['label'],$d['full_name'],$d['phone'],$d['address_line'],$d['area'],$d['city'],$d['district'],$d['postal_code'],$d['is_default']?1:0]);
        return (int)$this->lastId();
    }
    public function updateAddress(int $id, int $uid, array $d): bool {
        if($d['is_default']) $this->execute('UPDATE user_addresses SET is_default=0 WHERE user_id=?',[$uid]);
        return $this->execute(
            'UPDATE user_addresses SET label=?,full_name=?,phone=?,address_line=?,area=?,city=?,district=?,postal_code=?,is_default=? WHERE id=? AND user_id=?',
            [$d['label'],$d['full_name'],$d['phone'],$d['address_line'],$d['area'],$d['city'],$d['district'],$d['postal_code'],$d['is_default']?1:0,$id,$uid]);
    }
    public function deleteAddress(int $id, int $uid): bool {
        return $this->execute('DELETE FROM user_addresses WHERE id=? AND user_id=?',[$id,$uid]);
    }
    public function setDefaultAddress(int $id, int $uid): void {
        $this->execute('UPDATE user_addresses SET is_default=0 WHERE user_id=?',[$uid]);
        $this->execute('UPDATE user_addresses SET is_default=1 WHERE id=? AND user_id=?',[$id,$uid]);
    }
}
