<?php

class OrderModel extends Model
{
    protected $table = 'orders';

    public function create($data)
    {
        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), -6)) . '-' . date('dmY');
        $result = $this->db->execute(
            "INSERT INTO orders (order_number, user_id, address_id, total_amount, payment_method, shipping_charge, notes, guest_name, guest_email, guest_phone, guest_address, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
            [
                $orderNumber,
                $data['user_id'],
                $data['address_id'],
                $data['total_amount'],
                $data['payment_method'],
                $data['shipping_charge'],
                $data['notes'],
                $data['guest_name'],
                $data['guest_email'],
                $data['guest_phone'],
                $data['guest_address']
            ],
            'siiddssssss'
        );
        $orderId = $this->db->lastId();
        return ['order_id' => $orderId, 'order_number' => $orderNumber];
    }

    public function createOrder($data)
    {
        $orderNumber = 'ORD-' . strtoupper(uniqid());
        $conn = $this->db->getConnection();

        $uid = (int)$data['user_id'];
        $aid = $data['address_id'] ? (int)$data['address_id'] : null;
        $total = (float)$data['total_amount'];
        $ship = (float)$data['shipping_charge'];
        $pm = $conn->real_escape_string($data['payment_method']);
        $notes = $conn->real_escape_string($data['notes'] ?? '');
        $gn = $conn->real_escape_string($data['guest_name'] ?? '');
        $ge = $conn->real_escape_string($data['guest_email'] ?? '');
        $gp = $conn->real_escape_string($data['guest_phone'] ?? '');
        $ga = $conn->real_escape_string($data['guest_address'] ?? '');

        $aidSQL = $aid ? (int)$aid : 'NULL';

        $sql = "INSERT INTO orders (order_number, user_id, address_id, total_amount, payment_method, shipping_charge, notes, guest_name, guest_email, guest_phone, guest_address, order_status) 
                VALUES ('$orderNumber', $uid, $aidSQL, $total, '$pm', $ship, '$notes', '$gn', '$ge', '$gp', '$ga', 'pending')";

        $conn->query($sql);
        $orderId = $conn->insert_id;
        return ['order_id' => $orderId, 'order_number' => $orderNumber];
    }

    public function insertOrder($data)
    {
        $orderNumber = 'ORD' . date('ymd') . strtoupper(substr(uniqid('', true), -4));
        $conn = $this->db->getConnection();

        $uid = (int)($data['user_id'] ?? 0);
        $aid = $data['address_id'] ? (int)$data['address_id'] : null;
        $total = (float)$data['total_amount'];
        $ship = (float)$data['shipping_charge'];
        $pm = $conn->real_escape_string($data['payment_method']);
        $notes = $conn->real_escape_string($data['notes'] ?? '');
        $gn = $conn->real_escape_string($data['guest_name'] ?? '');
        $ge = $conn->real_escape_string($data['guest_email'] ?? '');
        $gp = $conn->real_escape_string($data['guest_phone'] ?? '');
        $ga = $conn->real_escape_string($data['guest_address'] ?? '');

        $aidSQL = $aid ? (int)$aid : 'NULL';

        $sql = "INSERT INTO orders (order_number, user_id, address_id, total_amount, payment_method, shipping_charge, notes, guest_name, guest_email, guest_phone, guest_address, order_status) 
                VALUES ('$orderNumber', $uid, $aidSQL, $total, '$pm', $ship, '$notes', '$gn', '$ge', '$gp', '$ga', 'pending')";

        $conn->query($sql);
        $orderId = $conn->insert_id;
        return ['order_id' => $orderId, 'order_number' => $orderNumber];
    }

    public function addItem($orderId, $orderNumber, $item)
    {
        $conn = $this->db->getConnection();
        $pid = (int)$item['product_id'];
        $qty = (int)$item['qty'];
        $price = (float)$item['unit_price'];
        $size = $conn->real_escape_string($item['size'] ?? '');
        $color = $conn->real_escape_string($item['color'] ?? '');
        $on = $conn->real_escape_string($orderNumber);

        $sql = "INSERT INTO order_items (order_id, order_number, product_id, size, color, qty, unit_price) 
                VALUES ($orderId, '$on', $pid, '$size', '$color', $qty, $price)";
        $conn->query($sql);
    }

    public function addStatusLog($orderId, $orderNumber, $status, $note = '')
    {
        $conn = $this->db->getConnection();
        $on = $conn->real_escape_string($orderNumber);
        $st = $conn->real_escape_string($status);
        $no = $conn->real_escape_string($note);

        $sql = "INSERT INTO order_status_log (order_id, order_number, status, note) 
                VALUES ($orderId, '$on', '$st', '$no')";
        $conn->query($sql);
    }

    public function addPayment($orderId, $orderNumber, $amount, $method, $status = 'pending')
    {
        $conn = $this->db->getConnection();
        $on = $conn->real_escape_string($orderNumber);
        $m = $conn->real_escape_string($method);
        $s = $conn->real_escape_string($status);

        $sql = "INSERT INTO payments (order_id, order_number, amount, payment_method, payment_status) 
                VALUES ($orderId, '$on', $amount, '$m', '$s')";
        $conn->query($sql);
    }

    public function getUserOrders($userId)
    {
        return $this->db->fetchAll("SELECT * FROM orders WHERE user_id = ? ORDER BY placed_at DESC", [$userId], 'i');
    }

    public function getByNumber($orderNumber)
    {
        return $this->db->fetchOne("SELECT * FROM orders WHERE order_number = ?", [$orderNumber], 's');
    }

    public function getItems($orderId)
    {
        return $this->db->fetchAll(
            "SELECT oi.*, p.product_name, pi.image_filename 
             FROM order_items oi 
             JOIN products p ON p.product_id = oi.product_id 
             LEFT JOIN product_images pi ON pi.product_id = oi.product_id AND pi.is_primary = 1 
             WHERE oi.order_id = ?",
            [$orderId],
            'i'
        );
    }

    public function getStatusLog($orderId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM order_status_log WHERE order_id = ? ORDER BY created_at ASC",
            [$orderId],
            'i'
        );
    }

    public function getAllOrders($limit = 50, $offset = 0, $status = '', $search = '')
    {
        $where = '1=1';
        $params = [];
        $types = '';

        if ($status) {
            $where .= ' AND o.order_status = ?';
            $params[] = $status;
            $types .= 's';
        }
        if ($search) {
            $where .= ' AND (o.order_number LIKE ? OR o.guest_name LIKE ? OR o.guest_phone LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'sss';
        }
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        return $this->db->fetchAll(
            "SELECT o.*, u.name as user_name 
             FROM orders o 
             LEFT JOIN users u ON u.user_id = o.user_id 
             WHERE $where 
             ORDER BY o.placed_at DESC 
             LIMIT ? OFFSET ?",
            $params,
            $types
        );
    }

    public function countOrders($status = '')
    {
        if ($status) {
            return $this->db->fetchOne("SELECT COUNT(*) as cnt FROM orders WHERE order_status=?", [$status], 's')['cnt'];
        }
        return $this->db->fetchOne("SELECT COUNT(*) as cnt FROM orders")['cnt'];
    }

    public function updateStatus($orderId, $status)
    {
        $this->db->execute("UPDATE orders SET order_status=? WHERE order_id=?", [$status, $orderId], 'si');
    }

    public function getTotalRevenue()
    {
        $result = $this->db->fetchOne("SELECT SUM(total_amount) as total FROM orders WHERE order_status NOT IN ('cancelled', 'refunded')");
        return $result['total'] ?? 0;
    }

    public function getRecentOrders($limit = 5)
    {
        return $this->db->fetchAll(
            "SELECT o.*, u.name as user_name 
             FROM orders o 
             LEFT JOIN users u ON u.user_id = o.user_id 
             ORDER BY o.placed_at DESC 
             LIMIT ?",
            [$limit],
            'i'
        );
    }
}


class UserModel extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        return $this->db->fetchOne("SELECT * FROM users WHERE email = ?", [$email], 's');
    }

    public function create($name, $email, $phone, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->db->insert("INSERT INTO users (name,email,phone,password_hash) VALUES (?,?,?,?)", [$name, $email, $phone, $hash], 'ssss');
    }

    public function update($id, $data)
    {
        $this->db->execute("UPDATE users SET name=?,phone=? WHERE user_id=?", [$data['name'], $data['phone'], $id], 'ssi');
    }

    public function updatePassword($id, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->db->execute("UPDATE users SET password_hash=? WHERE user_id=?", [$hash, $id], 'si');
    }

    public function getAddresses($userId)
    {
        return $this->db->fetchAll("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC", [$userId], 'i');
    }

    public function getAddress($id)
    {
        return $this->db->fetchOne("SELECT * FROM user_addresses WHERE id = ?", [$id], 'i');
    }

    public function saveAddress($data)
    {
        if ($data['is_default']) {
            $this->db->execute("UPDATE user_addresses SET is_default=0 WHERE user_id=?", [$data['user_id']], 'i');
        }
        if (isset($data['id']) && $data['id']) {
            $this->db->execute(
                "UPDATE user_addresses SET label=?,full_name=?,phone=?,address_line=?,area=?,city=?,district=?,postal_code=?,is_default=? WHERE id=?",
                [$data['label'], $data['full_name'], $data['phone'], $data['address_line'], $data['area'], $data['city'], $data['district'], $data['postal_code'], $data['is_default'], $data['id']],
                'sssssssiii'
            );
        } else {
            $this->db->insert(
                "INSERT INTO user_addresses (user_id,label,full_name,phone,address_line,area,city,district,postal_code,is_default) VALUES (?,?,?,?,?,?,?,?,?,?)",
                [$data['user_id'], $data['label'], $data['full_name'], $data['phone'], $data['address_line'], $data['area'], $data['city'], $data['district'], $data['postal_code'], $data['is_default']],
                'issssssssi'
            );
        }
    }

    public function getAllUsers($limit = 50, $offset = 0)
    {
        return $this->db->fetchAll("SELECT u.*, COUNT(o.order_id) as order_count FROM users u LEFT JOIN orders o ON o.user_id = u.user_id WHERE u.role='user' GROUP BY u.user_id ORDER BY u.created_at DESC LIMIT ? OFFSET ?", [$limit, $offset], 'ii');
    }

    public function countUsers()
    {
        return $this->db->fetchOne("SELECT COUNT(*) as cnt FROM users WHERE role='user'")['cnt'];
    }

    public function find($id, $pk = 'user_id')
    {
        return $this->db->fetchOne("SELECT * FROM users WHERE user_id = ?", [$id], 'i');
    }
}

class CategoryModel extends Model
{
    protected $table = 'categories';

    public function getActive()
    {
        return $this->db->fetchAll("SELECT * FROM categories WHERE is_active=1 ORDER BY sort_order ASC, name ASC");
    }

    public function getWithSubcategories()
    {
        $cats = $this->getActive();
        foreach ($cats as &$cat) {
            $cat['subcategories'] = $this->db->fetchAll("SELECT * FROM sub_categories WHERE category_id=? AND is_active=1 ORDER BY sort_order ASC", [$cat['id']], 'i');
        }
        return $cats;
    }

    public function getSubcategories($categoryId)
    {
        return $this->db->fetchAll("SELECT * FROM sub_categories WHERE category_id=? AND is_active=1 ORDER BY sort_order ASC", [$categoryId], 'i');
    }

    public function findBySlug($slug)
    {
        return $this->db->fetchOne("SELECT * FROM categories WHERE slug=?", [$slug], 's');
    }

    public function create($data)
    {
        return $this->db->insert(
            "INSERT INTO categories (name,slug,description,sort_order) VALUES (?,?,?,?)",
            [$data['name'], $data['slug'], $data['description'], $data['sort_order']],
            'sssi'
        );
    }

    public function update($id, $data)
    {
        $this->db->execute(
            "UPDATE categories SET name=?,slug=?,description=?,sort_order=? WHERE id=?",
            [$data['name'], $data['slug'], $data['description'], $data['sort_order'], $id],
            'sssii'
        );
    }

    public function delete($id)
    {
        $this->db->execute("DELETE FROM categories WHERE id=?", [$id], 'i');
    }

    public function createSub($data)
    {
        return $this->db->insert(
            "INSERT INTO sub_categories (category_id,name,slug,description,sort_order) VALUES (?,?,?,?,?)",
            [$data['category_id'], $data['name'], $data['slug'], $data['description'], $data['sort_order']],
            'isssi'
        );
    }
}

class CouponModel extends Model
{
    protected $table = 'coupons';

    public function findByCode($code)
    {
        return $this->db->fetchOne("SELECT * FROM coupons WHERE code=? AND is_active=1", [$code], 's');
    }

    public function isValid($coupon, $orderTotal)
    {
        if (!$coupon) return false;
        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) return false;
        if ($coupon['used_count'] >= $coupon['max_uses']) return false;
        if ($orderTotal < $coupon['min_order']) return false;
        return true;
    }

    public function incrementUsage($id)
    {
        $this->db->execute("UPDATE coupons SET used_count=used_count+1 WHERE id=?", [$id], 'i');
    }

    public function create($data)
    {
        return $this->db->insert(
            "INSERT INTO coupons (code,discount_pct,min_order,max_uses,expires_at) VALUES (?,?,?,?,?)",
            [$data['code'], $data['discount_pct'], $data['min_order'], $data['max_uses'], $data['expires_at']],
            'sddis'
        );
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM coupons ORDER BY created_at DESC");
    }

    public function delete($id)
    {
        $this->db->execute("DELETE FROM coupons WHERE id=?", [$id], 'i');
    }
}

class AdminModel extends Model
{
    protected $table = 'admins';

    public function findByUsername($username)
    {
        return $this->db->fetchOne("SELECT * FROM admins WHERE username=? AND is_active=1", [$username], 's');
    }

    public function create($data)
    {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->insert("INSERT INTO admins (username,password_hash,full_name) VALUES (?,?,?)", [$data['username'], $hash, $data['full_name']], 'sss');
    }
}

class DeliverySettingsModel extends Model
{
    protected $table = 'delivery_settings';

    public function get()
    {
        return $this->db->fetchOne("SELECT * FROM delivery_settings WHERE id=1") ?: [
            'inside_dhaka_charge' => 60,
            'outside_dhaka_charge' => 120,
            'free_delivery_min_amount' => 2000,
            'express_delivery_charge' => 150
        ];
    }
}



// updated SubCategoryModel with more methods and better structure
class SubCategoryModel extends Model
{
    protected $table = 'sub_categories';

    public function getAllByCategory($categoryId, $activeOnly = true)
    {
        $sql = "SELECT * FROM sub_categories WHERE category_id = ?";
        if ($activeOnly) $sql .= " AND is_active = 1";
        $sql .= " ORDER BY sort_order ASC, name ASC";
        return $this->db->fetchAll($sql, [$categoryId], 'i');
    }

    public function getActive()
    {
        return $this->db->fetchAll("SELECT * FROM sub_categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
    }

    public function getWithCategory()
    {
        return $this->db->fetchAll("
            SELECT sc.*, c.name as category_name, c.slug as category_slug 
            FROM sub_categories sc 
            JOIN categories c ON c.id = sc.category_id 
            WHERE sc.is_active = 1 
            ORDER BY c.sort_order, sc.sort_order, sc.name
        ");
    }

    public function findBySlug($slug)
    {
        return $this->db->fetchOne("SELECT * FROM sub_categories WHERE slug = ?", [$slug], 's');
    }

    public function create($data)
    {
        return $this->db->insert(
            "
            INSERT INTO sub_categories (category_id, name, slug, description, sort_order, is_active) 
            VALUES (?, ?, ?, ?, ?, ?)",
            [$data['category_id'], $data['name'], $data['slug'], $data['description'], $data['sort_order'], $data['is_active']],
            'isssii'
        );
    }

    public function update($id, $data)
    {
        $this->db->execute(
            "
            UPDATE sub_categories 
            SET category_id=?, name=?, slug=?, description=?, sort_order=?, is_active=? 
            WHERE id=?",
            [$data['category_id'], $data['name'], $data['slug'], $data['description'], $data['sort_order'], $data['is_active'], $id],
            'isssiii'
        );
    }

    public function delete($id)
    {
        $this->db->execute("DELETE FROM sub_categories WHERE id=?", [$id], 'i');
    }

    public function toggleStatus($id, $isActive)
    {
        $this->db->execute("UPDATE sub_categories SET is_active=? WHERE id=?", [$isActive, $id], 'ii');
    }

    public function getByCategorySlug($categorySlug, $subCategorySlug = null)
    {
        if ($subCategorySlug) {
            return $this->db->fetchOne("
                SELECT sc.*, c.id as category_id, c.name as category_name, c.slug as category_slug 
                FROM sub_categories sc 
                JOIN categories c ON c.id = sc.category_id 
                WHERE c.slug = ? AND sc.slug = ? AND sc.is_active = 1
            ", [$categorySlug, $subCategorySlug], 'ss');
        }
        return $this->db->fetchAll("
            SELECT sc.*, c.id as category_id, c.name as category_name 
            FROM sub_categories sc 
            JOIN categories c ON c.id = sc.category_id 
            WHERE c.slug = ? AND sc.is_active = 1 
            ORDER BY sc.sort_order ASC
        ", [$categorySlug], 's');
    }
}

class SettingsModel extends Model
{
    protected $table = 'site_settings';

    public function get($key)
    {
        $result = $this->db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = ?", [$key], 's');
        return $result ? $result['setting_value'] : null;
    }

    public function set($key, $value)
    {
        $exists = $this->db->fetchOne("SELECT id FROM site_settings WHERE setting_key = ?", [$key], 's');
        if ($exists) {
            $this->db->execute("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?", [$value, $key], 'ss');
        } else {
            $this->db->insert("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)", [$key, $value], 'ss');
        }
    }

    public function getAll()
    {
        $results = $this->db->fetchAll("SELECT setting_key, setting_value FROM site_settings");
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
}
