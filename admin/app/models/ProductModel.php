<?php
require_once __DIR__ . '/../../core/Model.php';

class ProductModel extends Model
{

    protected string $table = 'products';
    protected string $primaryKey = 'product_id';

    /**
     * Get all products with category and sub-category names
     */
    // public function getAllProducts(int $limit = 20, int $offset = 0): array
    // {
    //     $sql = "SELECT p.*, 
    //             c.name as category_name, 
    //             sc.name as sub_category_name,
    //             (SELECT COUNT(*) FROM product_sizes WHERE product_id = p.product_id) as size_count,
    //             (SELECT image_filename FROM product_images WHERE product_id = p.product_id AND is_primary = 1 LIMIT 1) as primary_image
    //             FROM {$this->table} p
    //             LEFT JOIN categories c ON p.category_id = c.id
    //             LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
    //             ORDER BY p.created_at DESC 
    //             LIMIT :limit OFFSET :offset";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }


    /**
     * Get all products with category and sub-category names and price range
     */
    public function getAllProducts(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT p.*, 
            c.name as category_name, 
            sc.name as sub_category_name,
            (SELECT COUNT(*) FROM product_sizes WHERE product_id = p.product_id) as size_count,
            (SELECT image_filename FROM product_images WHERE product_id = p.product_id AND is_primary = 1 LIMIT 1) as primary_image,
            (SELECT MIN(regular_price) FROM product_sizes WHERE product_id = p.product_id) as min_regular_price,
            (SELECT MAX(regular_price) FROM product_sizes WHERE product_id = p.product_id) as max_regular_price,
            (SELECT MIN(sale_price) FROM product_sizes WHERE product_id = p.product_id AND sale_price IS NOT NULL) as min_sale_price,
            (SELECT MAX(sale_price) FROM product_sizes WHERE product_id = p.product_id AND sale_price IS NOT NULL) as max_sale_price
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
            ORDER BY p.created_at DESC 
            LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate effective price range (using sale price if available)
        foreach ($products as &$product) {
            $minPrice = null;
            $maxPrice = null;

            // Check if we have sale prices
            if ($product['min_sale_price'] !== null && $product['min_sale_price'] > 0) {
                $minPrice = (float)$product['min_sale_price'];
                $maxPrice = (float)$product['max_sale_price'];
            } else {
                $minPrice = (float)$product['min_regular_price'];
                $maxPrice = (float)$product['max_regular_price'];
            }

            $product['min_price'] = $minPrice;
            $product['max_price'] = $maxPrice;
        }

        return $products;
    }

    /**
     * Get total count
     */
    public function getTotalCount(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get product by ID with all relations
     */
    public function getProductById(int $id): ?array
    {
        $sql = "SELECT p.*, c.name as category_name, sc.name as sub_category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
                WHERE p.{$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product['sizes'] = $this->getProductSizes($id);
            $product['colors'] = $this->getProductColors($id);
            $product['images'] = $this->getProductImages($id);
        }

        return $product ?: null;
    }

    /**
     * Get product sizes
     */
    public function getProductSizes(int $productId): array
    {
        $sql = "SELECT * FROM product_sizes WHERE product_id = :product_id ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get product colors
     */
    public function getProductColors(int $productId): array
    {
        $sql = "SELECT * FROM product_colors WHERE product_id = :product_id ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get product images
     */
    public function getProductImages(int $productId): array
    {
        $sql = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create product
     */
    public function createProduct(array $data): int|false
    {
        $sql = "INSERT INTO {$this->table} (product_name, sku, material, brand, category_id, sub_category_id, 
                product_stock, product_description, is_featured, is_active) 
                VALUES (:product_name, :sku, :material, :brand, :category_id, :sub_category_id, 
                :product_stock, :product_description, :is_featured, :is_active)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':product_name' => $data['product_name'],
            ':sku' => $data['sku'] ?? null,
            ':material' => $data['material'] ?? null,
            ':brand' => $data['brand'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':sub_category_id' => $data['sub_category_id'] ?? null,
            ':product_stock' => $data['product_stock'] ?? 0,
            ':product_description' => $data['product_description'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);

        return $this->db->lastInsertId() ?: false;
    }

    /**
     * Update product
     */
    public function updateProduct(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET 
                product_name = :product_name,
                sku = :sku,
                material = :material,
                brand = :brand,
                category_id = :category_id,
                sub_category_id = :sub_category_id,
                product_stock = :product_stock,
                product_description = :product_description,
                is_featured = :is_featured,
                is_active = :is_active
                WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':product_name' => $data['product_name'],
            ':sku' => $data['sku'] ?? null,
            ':material' => $data['material'] ?? null,
            ':brand' => $data['brand'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':sub_category_id' => $data['sub_category_id'] ?? null,
            ':product_stock' => $data['product_stock'] ?? 0,
            ':product_description' => $data['product_description'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id): bool
    {
        // First delete related data
        $this->deleteProductSizes($id);
        $this->deleteProductColors($id);
        $this->deleteProductImages($id);

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Save product sizes
     */
    public function saveProductSizes(int $productId, array $sizes): void
    {
        $this->deleteProductSizes($productId);

        $sql = "INSERT INTO product_sizes (product_id, size_name, height, width, weight, regular_price, sale_price, sort_order) 
                VALUES (:product_id, :size_name, :height, :width, :weight, :regular_price, :sale_price, :sort_order)";
        $stmt = $this->db->prepare($sql);

        foreach ($sizes as $index => $size) {
            if (!empty($size['size_name']) && !empty($size['regular_price'])) {
                $stmt->execute([
                    ':product_id' => $productId,
                    ':size_name' => $size['size_name'],
                    ':height' => $size['height'] ?? null,
                    ':width' => $size['width'] ?? null,
                    ':weight' => $size['weight'] ?? null,
                    ':regular_price' => $size['regular_price'],
                    ':sale_price' => !empty($size['sale_price']) ? $size['sale_price'] : null,
                    ':sort_order' => $index
                ]);
            }
        }
    }

    /**
     * Save product colors
     */
    public function saveProductColors(int $productId, array $colors): void
    {
        $this->deleteProductColors($productId);

        $sql = "INSERT INTO product_colors (product_id, color_name, color_code, sort_order) 
                VALUES (:product_id, :color_name, :color_code, :sort_order)";
        $stmt = $this->db->prepare($sql);

        foreach ($colors as $index => $color) {
            if (!empty($color['color_name'])) {
                $stmt->execute([
                    ':product_id' => $productId,
                    ':color_name' => $color['color_name'],
                    ':color_code' => $color['color_code'] ?? null,
                    ':sort_order' => $index
                ]);
            }
        }
    }

    /**
     * Save product images
     */
    public function saveProductImages(int $productId, array $images): void
    {
        // Don't delete existing images, just add new ones
        $sql = "INSERT INTO product_images (product_id, image_filename, image_original_name, is_primary, sort_order) 
                VALUES (:product_id, :image_filename, :image_original_name, :is_primary, :sort_order)";
        $stmt = $this->db->prepare($sql);

        foreach ($images as $index => $image) {
            if (!empty($image['filename'])) {
                $stmt->execute([
                    ':product_id' => $productId,
                    ':image_filename' => $image['filename'],
                    ':image_original_name' => $image['original_name'],
                    ':is_primary' => isset($image['is_primary']) ? 1 : 0,
                    ':sort_order' => $index
                ]);
            }
        }
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage(int $imageId, int $productId): bool
    {
        // Reset all images for this product
        $sql = "UPDATE product_images SET is_primary = 0 WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);

        // Set new primary
        $sql = "UPDATE product_images SET is_primary = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $imageId]);
    }

    /**
     * Delete product image
     */
    public function deleteProductImage(int $imageId): bool
    {
        // Get filename first
        $sql = "SELECT image_filename FROM product_images WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $imageId]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            require_once __DIR__ . '/../../helpers/ImageHelper.php';
            ImageHelper::deleteImage($image['image_filename']);
        }

        $sql = "DELETE FROM product_images WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $imageId]);
    }

    private function deleteProductSizes(int $productId): void
    {
        $sql = "DELETE FROM product_sizes WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
    }

    private function deleteProductColors(int $productId): void
    {
        $sql = "DELETE FROM product_colors WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
    }

    private function deleteProductImages(int $productId): void
    {
        // Get all images first
        $images = $this->getProductImages($productId);
        require_once __DIR__ . '/../../helpers/ImageHelper.php';

        foreach ($images as $image) {
            ImageHelper::deleteImage($image['image_filename']);
        }

        $sql = "DELETE FROM product_images WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
    }

    /**
     * Update stock
     */
    public function updateStock(int $productId, int $stock): bool
    {
        $sql = "UPDATE {$this->table} SET product_stock = :stock WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':stock' => $stock, ':id' => $productId]);
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts(int $threshold = 10): array
    {
        $sql = "SELECT p.*, 
                (SELECT image_filename FROM product_images WHERE product_id = p.product_id AND is_primary = 1 LIMIT 1) as primary_image
                FROM {$this->table} p 
                WHERE p.product_stock <= :threshold AND p.is_active = 1 
                ORDER BY p.product_stock ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':threshold' => $threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Generate SKU
     */
    public function generateSKU(string $productName, ?int $categoryId = null): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 3));
        $categoryCode = $categoryId ? str_pad($categoryId, 2, '0', STR_PAD_LEFT) : '00';
        $random = strtoupper(substr(uniqid(), -4));
        return "{$prefix}{$categoryCode}{$random}";
    }
}
