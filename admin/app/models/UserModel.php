<?php
require_once __DIR__ . '/../../core/Model.php';

class UserModel extends Model
{

    protected string $table = 'users';
    protected string $primaryKey = 'user_id';

    /**
     * Get all users (customers only, not admins unless specified)
     */
    public function getAllUsers(string $role = 'user', int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM user_addresses WHERE user_id = u.user_id) as address_count,
                (SELECT COUNT(*) FROM orders WHERE user_id = u.user_id) as order_count
                FROM {$this->table} u
                WHERE u.role = :role
                ORDER BY u.created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total count of users
     */
    public function getTotalCount(string $role = 'user'): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':role' => $role]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get user by ID with full details
     */
    public function getUserById(int $id): ?array
    {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM orders WHERE user_id = u.user_id) as total_orders,
                (SELECT SUM(total_amount) FROM orders WHERE user_id = u.user_id AND order_status = 'delivered') as total_spent
                FROM {$this->table} u
                WHERE u.{$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user['addresses'] = $this->getUserAddresses($id);
        }

        return $user ?: null;
    }

    /**
     * Get user addresses
     */
    public function getUserAddresses(int $userId): array
    {
        $sql = "SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Update user status (active/inactive)
     */
    public function updateStatus(int $userId, int $isActive): bool
    {
        $sql = "UPDATE {$this->table} SET is_active = :is_active WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':is_active' => $isActive, ':id' => $userId]);
    }

    /**
     * Update user role
     */
    public function updateRole(int $userId, string $role): bool
    {
        $sql = "UPDATE {$this->table} SET role = :role WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':role' => $role, ':id' => $userId]);
    }

    /**
     * Update user profile
     */
    public function updateUser(int $userId, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET 
                name = :name,
                phone = :phone,
                avatar = :avatar
                WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $userId,
            ':name' => $data['name'],
            ':phone' => $data['phone'] ?? null,
            ':avatar' => $data['avatar'] ?? null
        ]);
    }

    /**
     * Get user statistics
     */
    public function getStatistics(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_users,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_registered,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as this_month_registered
                FROM {$this->table}
                WHERE role = 'user'";
        $stmt = $this->db->query($sql);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get recent registrations count (last 7 days)
        $sql = "SELECT COUNT(*) as recent FROM {$this->table} 
                WHERE role = 'user' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $this->db->query($sql);
        $recent = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['recent_registrations'] = $recent['recent'] ?? 0;

        return $stats;
    }

    /**
     * Search users
     */
    public function searchUsers(string $keyword, string $role = 'user', int $limit = 20): array
    {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM user_addresses WHERE user_id = u.user_id) as address_count,
                (SELECT COUNT(*) FROM orders WHERE user_id = u.user_id) as order_count
                FROM {$this->table} u
                WHERE u.role = :role 
                AND (u.name LIKE :keyword OR u.email LIKE :keyword OR u.phone LIKE :keyword)
                ORDER BY u.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $keyword = "%{$keyword}%";
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
