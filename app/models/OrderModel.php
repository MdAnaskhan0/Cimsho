<?php
require_once __DIR__.'/../../core/Model.php';
class OrderModel extends Model {

    public function create(array $d): int {
        $orderNum = 'ORD'.date('Ymd').strtoupper(substr(uniqid(),-5));
        $this->execute(
            'INSERT INTO orders (order_number,user_id,address_id,total_amount,payment_method,shipping_charge,notes,
             guest_name,guest_email,guest_phone,guest_address)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)',
            [$orderNum,$d['user_id'],$d['address_id'],$d['total_amount'],$d['payment_method'],
             $d['shipping_charge'],$d['notes']??null,
             $d['guest_name']??null,$d['guest_email']??null,$d['guest_phone']??null,$d['guest_address']??null]);
        $oid = (int)$this->lastId();
        foreach($d['items'] as $item){
            $this->execute(
                'INSERT INTO order_items (order_id,order_number,product_id,size,color,qty,unit_price) VALUES (?,?,?,?,?,?,?)',
                [$oid,$orderNum,$item['product_id'],$item['size']??null,$item['color']??null,$item['qty'],$item['unit_price']]);
        }
        $this->execute('INSERT INTO order_status_log (order_id,order_number,status,note) VALUES (?,?,\'pending\',?)',
            [$oid,$orderNum,'Order placed successfully.']);
        return $oid;
    }

    public function getByUser(int $uid): array {
        return $this->fetchAll(
            'SELECT o.*, COUNT(oi.id) as item_count FROM orders o
             LEFT JOIN order_items oi ON oi.order_id=o.order_id
             WHERE o.user_id=? GROUP BY o.order_id ORDER BY o.placed_at DESC', [$uid]);
    }

    public function getById(int $id): ?array {
        return $this->fetchOne('SELECT * FROM orders WHERE order_id=?',[$id]);
    }

    public function getByNumber(string $num): ?array {
        return $this->fetchOne('SELECT * FROM orders WHERE order_number=?',[$num]);
    }

    public function getByNumberAndContact(string $num, string $contact): ?array {
        return $this->fetchOne(
            'SELECT * FROM orders WHERE order_number=? AND (guest_phone=? OR guest_email=? OR
             user_id IN (SELECT user_id FROM users WHERE email=? OR phone=?))',
            [$num,$contact,$contact,$contact,$contact]);
    }

    public function getItems(int $oid): array {
        return $this->fetchAll(
            'SELECT oi.*, p.product_name, pi.image_filename FROM order_items oi
             LEFT JOIN products p ON p.product_id=oi.product_id
             LEFT JOIN product_images pi ON pi.product_id=oi.product_id AND pi.is_primary=1
             WHERE oi.order_id=?', [$oid]);
    }

    public function getStatusLog(int $oid): array {
        return $this->fetchAll('SELECT * FROM order_status_log WHERE order_id=? ORDER BY created_at ASC',[$oid]);
    }

    public function applyCoupon(string $code, float $amount): ?array {
        $c = $this->fetchOne(
            'SELECT * FROM coupons WHERE code=? AND is_active=1 AND used_count<max_uses
             AND (expires_at IS NULL OR expires_at>=CURDATE())', [$code]);
        if(!$c) return null;
        if($amount < $c['min_order']) return ['error'=>'Minimum order of '.CURRENCY_SYMBOL.number_format($c['min_order'],2).' required.'];
        return $c;
    }

    public function useCoupon(int $id): void {
        $this->execute('UPDATE coupons SET used_count=used_count+1 WHERE id=?',[$id]);
    }
}
