<?php
require_once __DIR__.'/../../core/Model.php';
class ProductModel extends Model {

    public function getFeatured(int $limit=8): array {
        return $this->fetchAll(
            'SELECT p.*, pi.image_filename,
             MIN(COALESCE(ps.sale_price, ps.regular_price)) as min_price,
             MIN(ps.regular_price) as regular_price, MIN(ps.sale_price) as sale_price
             FROM products p
             LEFT JOIN product_images pi ON pi.product_id=p.product_id AND pi.is_primary=1
             LEFT JOIN product_sizes ps ON ps.product_id=p.product_id
             WHERE p.is_active=1 AND p.is_featured=1
             GROUP BY p.product_id ORDER BY p.created_at DESC LIMIT ?', [$limit]);
    }

    public function getAll(array $filters=[], int $limit=12, int $offset=0): array {
        $where = ['p.is_active=1'];
        $params = [];
        if(!empty($filters['category_id'])){
            $where[] = 'p.category_id=?'; $params[] = $filters['category_id'];
        }
        if(!empty($filters['sub_category_id'])){
            $where[] = 'p.sub_category_id=?'; $params[] = $filters['sub_category_id'];
        }
        if(!empty($filters['search'])){
            $where[] = '(p.product_name LIKE ? OR p.product_description LIKE ?)';
            $params[] = '%'.$filters['search'].'%';
            $params[] = '%'.$filters['search'].'%';
        }
        $sql = 'SELECT p.*, pi.image_filename,
                MIN(COALESCE(ps.sale_price,ps.regular_price)) as min_price,
                MIN(ps.regular_price) as regular_price, MIN(ps.sale_price) as sale_price
                FROM products p
                LEFT JOIN product_images pi ON pi.product_id=p.product_id AND pi.is_primary=1
                LEFT JOIN product_sizes ps ON ps.product_id=p.product_id
                WHERE '.implode(' AND ',$where).'
                GROUP BY p.product_id ORDER BY p.created_at DESC LIMIT ? OFFSET ?';
        $params[] = $limit; $params[] = $offset;
        return $this->fetchAll($sql, $params);
    }

    public function countAll(array $filters=[]): int {
        $where=['p.is_active=1']; $params=[];
        if(!empty($filters['category_id'])){ $where[]='p.category_id=?'; $params[]=$filters['category_id']; }
        if(!empty($filters['sub_category_id'])){ $where[]='p.sub_category_id=?'; $params[]=$filters['sub_category_id']; }
        if(!empty($filters['search'])){ $where[]='(p.product_name LIKE ? OR p.product_description LIKE ?)'; $params[]='%'.$filters['search'].'%'; $params[]='%'.$filters['search'].'%'; }
        $r = $this->fetchOne('SELECT COUNT(DISTINCT p.product_id) as c FROM products p WHERE '.implode(' AND ',$where),$params);
        return (int)($r['c']??0);
    }

    public function getBySlugOrId(string $id): ?array {
        return $this->fetchOne(
            'SELECT p.*, c.name as category_name, sc.name as sub_category_name
             FROM products p
             LEFT JOIN categories c ON c.id=p.category_id
             LEFT JOIN sub_categories sc ON sc.id=p.sub_category_id
             WHERE p.product_id=? AND p.is_active=1', [(int)$id]);
    }

    public function getImages(int $pid): array {
        return $this->fetchAll('SELECT * FROM product_images WHERE product_id=? ORDER BY sort_order ASC', [$pid]);
    }

    public function getSizes(int $pid): array {
        return $this->fetchAll('SELECT * FROM product_sizes WHERE product_id=? ORDER BY sort_order ASC', [$pid]);
    }

    public function getColors(int $pid): array {
        return $this->fetchAll('SELECT * FROM product_colors WHERE product_id=? ORDER BY sort_order ASC', [$pid]);
    }

    public function getReviews(int $pid): array {
        return $this->fetchAll(
            'SELECT r.*, u.name as user_name FROM product_reviews r
             LEFT JOIN users u ON u.user_id=r.user_id
             WHERE r.product_id=? ORDER BY r.created_at DESC', [$pid]);
    }

    public function getAvgRating(int $pid): float {
        $r = $this->fetchOne('SELECT AVG(rating) as avg FROM product_reviews WHERE product_id=?', [$pid]);
        return round((float)($r['avg']??0), 1);
    }

    public function addReview(int $pid, int $uid, int $rating, string $review): bool {
        return $this->execute(
            'INSERT INTO product_reviews (product_id,user_id,rating,review) VALUES (?,?,?,?)',
            [$pid,$uid,$rating,$review]);
    }

    public function getRelated(int $pid, int $catId, int $limit=4): array {
        return $this->fetchAll(
            'SELECT p.*, pi.image_filename,
             MIN(COALESCE(ps.sale_price,ps.regular_price)) as min_price
             FROM products p
             LEFT JOIN product_images pi ON pi.product_id=p.product_id AND pi.is_primary=1
             LEFT JOIN product_sizes ps ON ps.product_id=p.product_id
             WHERE p.is_active=1 AND p.category_id=? AND p.product_id!=?
             GROUP BY p.product_id LIMIT ?', [$catId,$pid,$limit]);
    }
}
