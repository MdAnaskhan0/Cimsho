<?php
require_once __DIR__ . '/../../core/Model.php';

class OrderModel extends Model
{

    protected string $table = 'orders';
    protected string $primaryKey = 'order_id';

    /**
     * Get all orders with customer info
     */
    public function getAllOrders(string $status = null, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT o.*, 
                CASE 
                    WHEN o.user_id > 0 THEN u.name 
                    ELSE o.guest_name 
                END as customer_name,
                CASE 
                    WHEN o.user_id > 0 THEN u.email 
                    ELSE o.guest_email 
                END as customer_email,
                (SELECT COUNT(*) FROM order_items WHERE order_id = o.order_id) as item_count
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.user_id";

        if ($status) {
            $sql .= " WHERE o.order_status = :status";
        }

        $sql .= " ORDER BY o.placed_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if ($status) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total count of orders
     */
    public function getTotalCount(string $status = null): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if ($status) {
            $sql .= " WHERE order_status = :status";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $this->db->query($sql);
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get order by ID with full details
     */
    public function getOrderById(int $orderId): ?array
    {
        $sql = "SELECT o.*, 
                CASE 
                    WHEN o.user_id > 0 THEN u.name 
                    ELSE o.guest_name 
                END as customer_name,
                CASE 
                    WHEN o.user_id > 0 THEN u.email 
                    ELSE o.guest_email 
                END as customer_email,
                CASE 
                    WHEN o.user_id > 0 THEN u.phone 
                    ELSE o.guest_phone 
                END as customer_phone,
                o.guest_address as customer_address,
                o.notes as order_notes
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.user_id
                WHERE o.{$this->primaryKey} = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
            $order['status_logs'] = $this->getStatusLogs($orderId);
            $order['address'] = $this->getOrderAddress($orderId);
        }

        return $order ?: null;
    }

    /**
     * Get order items
     */
    public function getOrderItems(int $orderId): array
    {
        $sql = "SELECT oi.*, p.product_name 
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = :order_id
                ORDER BY oi.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get order status logs
     */
    public function getStatusLogs(int $orderId): array
    {
        $sql = "SELECT * FROM order_status_log 
                WHERE order_id = :order_id 
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get order address from user_addresses
     */
    public function getOrderAddress(int $orderId): ?array
    {
        $sql = "SELECT o.address_id, ua.* 
                FROM orders o
                LEFT JOIN user_addresses ua ON o.address_id = ua.id
                WHERE o.order_id = :order_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status, ?string $note = null): bool
    {
        // Start transaction
        $this->db->beginTransaction();

        try {
            // Update order status
            $sql = "UPDATE {$this->table} SET order_status = :status WHERE {$this->primaryKey} = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status, ':id' => $orderId]);

            // Get order number
            $order = $this->getOrderById($orderId);

            // Add to status log
            $sql = "INSERT INTO order_status_log (order_id, order_number, status, note) 
                    VALUES (:order_id, :order_number, :status, :note)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':order_id' => $orderId,
                ':order_number' => $order['order_number'],
                ':status' => $status,
                ':note' => $note
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Update tracking number
     */
    public function updateTrackingNumber(int $orderId, string $trackingNumber): bool
    {
        $sql = "UPDATE {$this->table} SET tracking_number = :tracking_number WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':tracking_number' => $trackingNumber, ':id' => $orderId]);
    }

    /**
     * Get order statistics
     */
    public function getStatistics(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN order_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_orders,
                    SUM(CASE WHEN order_status = 'shipped' THEN 1 ELSE 0 END) as shipped_orders,
                    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                    SUM(total_amount) as total_revenue,
                    SUM(CASE WHEN DATE(placed_at) = CURDATE() THEN total_amount ELSE 0 END) as today_revenue
                FROM {$this->table}";

        $stmt = $this->db->query($sql);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get monthly revenue for chart
        $sql = "SELECT 
                    DATE_FORMAT(placed_at, '%Y-%m') as month,
                    SUM(total_amount) as revenue,
                    COUNT(*) as order_count
                FROM {$this->table}
                WHERE placed_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(placed_at, '%Y-%m')
                ORDER BY month ASC";

        $stmt = $this->db->query($sql);
        $stats['monthly_data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    /**
     * Search orders
     */
    public function searchOrders(string $keyword, string $status = null, int $limit = 20): array
    {
        $sql = "SELECT o.*, 
                CASE 
                    WHEN o.user_id > 0 THEN u.name 
                    ELSE o.guest_name 
                END as customer_name
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.user_id
                WHERE (o.order_number LIKE :keyword 
                    OR o.guest_name LIKE :keyword 
                    OR o.guest_email LIKE :keyword
                    OR u.name LIKE :keyword
                    OR u.email LIKE :keyword)";

        if ($status) {
            $sql .= " AND o.order_status = :status";
        }

        $sql .= " ORDER BY o.placed_at DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $keyword = "%{$keyword}%";
        $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        if ($status) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
