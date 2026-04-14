<?php
require_once __DIR__ . '/../../core/Model.php';

class DashboardModel extends Model {

    public function getTotalOrders(): int {
        $r = $this->fetchOne('SELECT COUNT(*) as total FROM orders');
        return (int)($r['total'] ?? 0);
    }

    public function getTotalRevenue(): float {
        $r = $this->fetchOne(
            "SELECT SUM(total_amount) as revenue FROM orders WHERE order_status != 'cancelled'"
        );
        return (float)($r['revenue'] ?? 0);
    }

    public function getTotalUsers(): int {
        $r = $this->fetchOne("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
        return (int)($r['total'] ?? 0);
    }

    public function getTotalProducts(): int {
        $r = $this->fetchOne('SELECT COUNT(*) as total FROM products WHERE is_active = 1');
        return (int)($r['total'] ?? 0);
    }

    public function getOrdersByStatus(): array {
        return $this->fetchAll(
            'SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status'
        );
    }

    public function getRecentOrders(int $limit = 8): array {
        return $this->fetchAll(
            'SELECT o.*, COALESCE(u.name, o.guest_name) as customer_name
             FROM orders o
             LEFT JOIN users u ON u.user_id = o.user_id
             ORDER BY o.placed_at DESC LIMIT ?',
            [$limit]
        );
    }

    public function getLowStockProducts(int $threshold = 10): array {
        return $this->fetchAll(
            'SELECT product_id, product_name, product_stock FROM products
             WHERE product_stock <= ? AND is_active = 1 ORDER BY product_stock ASC LIMIT 5',
            [$threshold]
        );
    }

    public function getMonthlyRevenue(): array {
        return $this->fetchAll(
            "SELECT DATE_FORMAT(placed_at, '%Y-%m') as month,
                    SUM(total_amount) as revenue,
                    COUNT(*) as orders
             FROM orders
             WHERE order_status != 'cancelled'
               AND placed_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY month ORDER BY month ASC"
        );
    }

    public function getPendingOrdersCount(): int {
        $r = $this->fetchOne("SELECT COUNT(*) as c FROM orders WHERE order_status = 'pending'");
        return (int)($r['c'] ?? 0);
    }
}
