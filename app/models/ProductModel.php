<?php
class ProductModel extends Model
{
    protected $table = 'products';

    public function getFeatured($limit = 8)
    {
        return $this->db->fetchAll("
            SELECT p.*, ps.regular_price, ps.sale_price,
                   pi.image_filename, cat.name as category_name
            FROM products p
            LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
            LEFT JOIN categories cat ON cat.id = p.category_id
            WHERE p.is_active = 1 AND p.is_featured = 1
            ORDER BY p.created_at DESC LIMIT ?", [$limit], 'i');
    }

    public function getLatest($limit = 12)
    {
        return $this->db->fetchAll("
            SELECT p.*, ps.regular_price, ps.sale_price,
                   pi.image_filename, cat.name as category_name
            FROM products p
            LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
            LEFT JOIN categories cat ON cat.id = p.category_id
            WHERE p.is_active = 1
            ORDER BY p.created_at DESC LIMIT ?", [$limit], 'i');
    }

    public function getByCategory($categoryId, $subCategoryId = null, $limit = 20, $offset = 0)
    {
        $params = [$categoryId];
        $types = 'i';
        $where = 'p.category_id = ?';
        if ($subCategoryId) {
            $where .= ' AND p.sub_category_id = ?';
            $params[] = $subCategoryId;
            $types .= 'i';
        }
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        return $this->db->fetchAll("
            SELECT p.*, ps.regular_price, ps.sale_price, pi.image_filename
            FROM products p
            LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
            WHERE p.is_active = 1 AND $where
            ORDER BY p.created_at DESC LIMIT ? OFFSET ?", $params, $types);
    }

    // added subcategory product listing method
    public function getBySubcategory($subCategoryId, $limit = 12, $offset = 0, $sort = 'newest')
    {
        $orderBy = match ($sort) {
            'price_asc' => 'p.regular_price ASC',
            'price_desc' => 'p.regular_price DESC',
            'name_asc' => 'p.product_name ASC',
            'name_desc' => 'p.product_name DESC',
            'oldest' => 'p.created_at ASC',
            default => 'p.created_at DESC'
        };

        return $this->db->fetchAll("
        SELECT DISTINCT p.*, 
            (SELECT pi.image_filename FROM product_images pi WHERE pi.product_id = p.product_id AND pi.is_primary = 1 LIMIT 1) as image
        FROM products p
        WHERE p.sub_category_id = ? AND p.is_deleted = 0
        ORDER BY $orderBy
        LIMIT ? OFFSET ?
    ", [$subCategoryId, $limit, $offset], 'iii');
    }

    public function countBySubcategory($subCategoryId)
    {
        $result = $this->db->fetchOne("
        SELECT COUNT(*) as cnt 
        FROM products p 
        WHERE p.sub_category_id = ? AND p.is_deleted = 0
    ", [$subCategoryId], 'i');
        return $result['cnt'] ?? 0;
    }

    public function search($q, $limit = 20, $offset = 0)
    {
        $like = "%$q%";
        return $this->db->fetchAll("
            SELECT p.*, ps.regular_price, ps.sale_price, pi.image_filename
            FROM products p
            LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
            WHERE p.is_active = 1 AND (p.product_name LIKE ? OR p.product_description LIKE ? OR p.brand LIKE ?)
            ORDER BY p.created_at DESC LIMIT ? OFFSET ?", [$like, $like, $like, $limit, $offset], 'sssii');
    }

    public function getDetail($id)
    {
        return $this->db->fetchOne("
            SELECT p.*, cat.name as category_name, sc.name as sub_category_name
            FROM products p
            LEFT JOIN categories cat ON cat.id = p.category_id
            LEFT JOIN sub_categories sc ON sc.id = p.sub_category_id
            WHERE p.product_id = ? AND p.is_active = 1", [$id], 'i');
    }

    public function getImages($productId)
    {
        return $this->db->fetchAll("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC", [$productId], 'i');
    }

    public function getSizes($productId)
    {
        return $this->db->fetchAll("SELECT * FROM product_sizes WHERE product_id = ? ORDER BY sort_order ASC", [$productId], 'i');
    }

    public function getColors($productId)
    {
        return $this->db->fetchAll("SELECT * FROM product_colors WHERE product_id = ? ORDER BY sort_order ASC", [$productId], 'i');
    }

    public function getReviews($productId)
    {
        return $this->db->fetchAll("SELECT pr.*, u.name as user_name FROM product_reviews pr JOIN users u ON u.user_id = pr.user_id WHERE pr.product_id = ? ORDER BY pr.created_at DESC", [$productId], 'i');
    }

    public function getAvgRating($productId)
    {
        $r = $this->db->fetchOne("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM product_reviews WHERE product_id = ?", [$productId], 'i');
        return $r;
    }

    public function getAllWithDetails($limit = 50, $offset = 0, $search = '')
    {
        $where = 'p.is_active = 1';
        $params = [];
        $types = '';
        if ($search) {
            $where .= ' AND (p.product_name LIKE ? OR p.brand LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        return $this->db->fetchAll("
            SELECT p.*, pi.image_filename, cat.name as category_name,
                   MIN(ps.sale_price) as min_sale, MIN(ps.regular_price) as min_regular
            FROM products p
            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
            LEFT JOIN categories cat ON cat.id = p.category_id
            LEFT JOIN product_sizes ps ON ps.product_id = p.product_id
            WHERE $where GROUP BY p.product_id ORDER BY p.created_at DESC LIMIT ? OFFSET ?", $params, $types);
    }

    public function countAll($search = '')
    {
        if ($search) return $this->db->fetchOne("SELECT COUNT(*) as cnt FROM products p WHERE p.is_active=1 AND (p.product_name LIKE ? OR p.brand LIKE ?)", ["%$search%", "%$search%"], 'ss')['cnt'];
        return $this->db->fetchOne("SELECT COUNT(*) as cnt FROM products WHERE is_active=1")['cnt'];
    }


    public function create($data)
    {
        // Handle sub_category_id - send as NULL if empty
        $subCategoryId = !empty($data['sub_category_id']) ? $data['sub_category_id'] : null;

        return $this->db->insert(
            "INSERT INTO products (product_name, sku, material, brand, category_id, sub_category_id, product_stock, product_description, is_featured, is_active) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)",
            [
                $data['product_name'],      // string
                $data['sku'],               // string (can be null)
                $data['material'],          // string (can be null)
                $data['brand'],             // string (can be null)
                (int)$data['category_id'],  // integer
                $subCategoryId,             // integer or null
                (int)$data['product_stock'], // integer
                $data['product_description'], // string
                (int)$data['is_featured']   // integer
            ],
            'ssssiiisi'  // 9 types: s,s,s,s,i,i,i,s,i
        );
    }


    public function update($id, $data)
    {
        $subCategoryId = !empty($data['sub_category_id']) ? $data['sub_category_id'] : null;

        return $this->db->execute(
            "UPDATE products SET product_name=?, sku=?, material=?, brand=?, category_id=?, sub_category_id=?, product_stock=?, product_description=?, is_featured=? WHERE product_id=?",
            [
                $data['product_name'],
                $data['sku'],
                $data['material'],
                $data['brand'],
                (int)$data['category_id'],
                $subCategoryId,
                (int)$data['product_stock'],
                $data['product_description'],
                (int)$data['is_featured'],
                (int)$id
            ],
            'ssssiiisii'  // 10 types: s,s,s,s,i,i,i,s,i,i
        );
    }

    public function softDelete($id)
    {
        return $this->db->execute("UPDATE products SET is_active=0 WHERE product_id=?", [$id], 'i');
    }

    public function addSize($productId, $size)
    {
        return $this->db->insert(
            "INSERT INTO product_sizes (product_id,size_name,height,width,weight,regular_price,sale_price,sort_order) VALUES (?,?,?,?,?,?,?,?)",
            [$productId, $size['size_name'], $size['height'], $size['width'], $size['weight'], $size['regular_price'], $size['sale_price'], $size['sort_order']],
            'isdddddi'
        );
    }

    public function addColor($productId, $color)
    {
        return $this->db->insert(
            "INSERT INTO product_colors (product_id,color_name,color_code) VALUES (?,?,?)",
            [$productId, $color['color_name'], $color['color_code']],
            'iss'
        );
    }

    public function addImage($productId, $filename, $original, $isPrimary = 0, $order = 0)
    {
        return $this->db->insert(
            "INSERT INTO product_images (product_id,image_filename,image_original_name,is_primary,sort_order) VALUES (?,?,?,?,?)",
            [$productId, $filename, $original, $isPrimary, $order],
            'issii'
        );
    }

    public function deleteSizes($productId)
    {
        $this->db->execute("DELETE FROM product_sizes WHERE product_id=?", [$productId], 'i');
    }
    public function deleteColors($productId)
    {
        $this->db->execute("DELETE FROM product_colors WHERE product_id=?", [$productId], 'i');
    }
    public function deleteImages($productId)
    {
        $this->db->execute("DELETE FROM product_images WHERE product_id=?", [$productId], 'i');
    }

    public function toggleFeatured($id, $val)
    {
        $this->db->execute("UPDATE products SET is_featured=? WHERE product_id=?", [$val, $id], 'ii');
    }

    public function getRelated($categoryId, $excludeId, $limit = 4)
    {
        return $this->db->fetchAll("
            SELECT p.*, ps.regular_price, ps.sale_price, pi.image_filename
            FROM products p
            LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
            LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
            WHERE p.is_active = 1 AND p.category_id = ? AND p.product_id != ?
            ORDER BY RAND() LIMIT ?", [$categoryId, $excludeId, $limit], 'iii');
    }
}
