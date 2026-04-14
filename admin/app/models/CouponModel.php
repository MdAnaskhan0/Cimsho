<?php
require_once __DIR__ . '/../../core/Model.php';

class CouponModel extends Model
{

    protected string $table = 'coupons';
    protected string $primaryKey = 'id';

    /**
     * Get all coupons with pagination
     */
    public function getAllCoupons(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT *, 
                CASE 
                    WHEN expires_at < CURDATE() THEN 'expired'
                    WHEN used_count >= max_uses THEN 'exhausted'
                    WHEN is_active = 0 THEN 'inactive'
                    ELSE 'active'
                END as status
                FROM {$this->table} 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total count of coupons
     */
    public function getTotalCount(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get coupon by ID
     */
    public function getCouponById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get coupon by code
     */
    public function getCouponByCode(string $code): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => $code]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create a new coupon
     */
    public function createCoupon(array $data): int|false
    {
        $sql = "INSERT INTO {$this->table} (code, discount_pct, min_order, max_uses, expires_at, is_active) 
                VALUES (:code, :discount_pct, :min_order, :max_uses, :expires_at, :is_active)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':code' => strtoupper($data['code']),
            ':discount_pct' => $data['discount_pct'],
            ':min_order' => $data['min_order'] ?? 0,
            ':max_uses' => $data['max_uses'] ?? 100,
            ':expires_at' => !empty($data['expires_at']) ? $data['expires_at'] : null,
            ':is_active' => $data['is_active'] ?? 1
        ]);

        return $this->db->lastInsertId() ?: false;
    }

    /**
     * Update coupon
     */
    public function updateCoupon(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET 
                code = :code,
                discount_pct = :discount_pct,
                min_order = :min_order,
                max_uses = :max_uses,
                expires_at = :expires_at,
                is_active = :is_active
                WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':code' => strtoupper($data['code']),
            ':discount_pct' => $data['discount_pct'],
            ':min_order' => $data['min_order'] ?? 0,
            ':max_uses' => $data['max_uses'] ?? 100,
            ':expires_at' => !empty($data['expires_at']) ? $data['expires_at'] : null,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }

    /**
     * Delete coupon
     */
    public function deleteCoupon(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Toggle coupon status
     */
    public function toggleStatus(int $id): bool
    {
        $coupon = $this->getCouponById($id);
        if (!$coupon) return false;

        $newStatus = $coupon['is_active'] == 1 ? 0 : 1;
        $sql = "UPDATE {$this->table} SET is_active = :is_active WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':is_active' => $newStatus, ':id' => $id]);
    }

    /**
     * Check if coupon code exists
     */
    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE code = :code";
        $params = [':code' => strtoupper($code)];

        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Generate unique coupon code
     */
    public function generateCode(string $prefix = ''): string
    {
        $code = $prefix . strtoupper(substr(uniqid(), -6));
        while ($this->codeExists($code)) {
            $code = $prefix . strtoupper(substr(uniqid(), -6));
        }
        return $code;
    }

    /**
     * Validate coupon for usage
     */
    public function validateCoupon(string $code, float $orderTotal): array
    {
        $coupon = $this->getCouponByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Coupon not found'];
        }

        if ($coupon['is_active'] != 1) {
            return ['valid' => false, 'message' => 'Coupon is inactive'];
        }

        if ($coupon['expires_at'] && $coupon['expires_at'] < date('Y-m-d')) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        if ($coupon['used_count'] >= $coupon['max_uses']) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        if ($orderTotal < $coupon['min_order']) {
            return ['valid' => false, 'message' => "Minimum order amount of ৳" . number_format($coupon['min_order'], 2) . " required"];
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => ($orderTotal * $coupon['discount_pct']) / 100
        ];
    }

    /**
     * Increment coupon usage count
     */
    public function incrementUsage(int $id): bool
    {
        $sql = "UPDATE {$this->table} SET used_count = used_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get coupon statistics
     */
    public function getStatistics(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_coupons,
                    SUM(CASE WHEN is_active = 1 AND (expires_at IS NULL OR expires_at >= CURDATE()) THEN 1 ELSE 0 END) as active_coupons,
                    SUM(used_count) as total_uses,
                    AVG(discount_pct) as avg_discount
                FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
