<?php
class ProductModel extends Model
{

    public function getFeatured(int $limit = 8): array
    {
        $products = $this->fetchAll(
            'SELECT p.*, 
                    pi.image_filename, 
                    ps.regular_price, 
                    ps.sale_price,
                    c.name AS category_name
             FROM products p
             LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
             LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.is_active = 1
             GROUP BY p.product_id
             ORDER BY p.is_featured DESC, p.created_at DESC
             LIMIT ?',
            [$limit]
        );

        // Add image URL to each product
        foreach ($products as $product) {
            $product->image_url = $this->getProductImageUrl($product);
        }

        return $products;
    }

    public function getAll(int $limit = 12, int $offset = 0, ?int $categoryId = null): array
    {
        $where = 'WHERE p.is_active = 1';
        $params = [];
        if ($categoryId) {
            $where .= ' AND p.category_id = ?';
            $params[] = $categoryId;
        }
        $params[] = $limit;
        $params[] = $offset;

        $products = $this->fetchAll(
            "SELECT p.*, 
                    pi.image_filename, 
                    ps.regular_price, 
                    ps.sale_price,
                    c.name AS category_name
             FROM products p
             LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
             LEFT JOIN product_sizes ps ON ps.product_id = p.product_id AND ps.sort_order = 0
             LEFT JOIN categories c ON c.id = p.category_id
             $where
             GROUP BY p.product_id
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?",
            $params
        );

        // Add image URL to each product
        foreach ($products as $product) {
            $product->image_url = $this->getProductImageUrl($product);
        }

        return $products;
    }

    public function getById(int $id): mixed
    {
        $product = $this->fetchOne(
            'SELECT p.*, 
                    c.name AS category_name, 
                    sc.name AS sub_category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN sub_categories sc ON sc.id = p.sub_category_id
             WHERE p.product_id = ? AND p.is_active = 1',
            [$id]
        );

        if ($product) {
            $product->image_url = $this->getProductImageUrl($product);
        }

        return $product;
    }

    public function getImages(int $productId): array
    {
        $images = $this->fetchAll(
            'SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order',
            [$productId]
        );

        // Add full URL to each image
        foreach ($images as $image) {
            $image->image_url = $this->getProductImageUrl($image);
        }

        return $images;
    }

    public function getSizes(int $productId): array
    {
        return $this->fetchAll(
            'SELECT * FROM product_sizes WHERE product_id = ? ORDER BY sort_order',
            [$productId]
        );
    }

    public function getColors(int $productId): array
    {
        return $this->fetchAll(
            'SELECT * FROM product_colors WHERE product_id = ? ORDER BY sort_order',
            [$productId]
        );
    }

    public function getCategories(): array
    {
        return $this->fetchAll('SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order');
    }

    // Helper method to get full image URL
    public function getProductImageUrl($item): string
    {
        // Check if it's a product or image object
        $imageFile = '';
        if (isset($item->image_filename)) {
            $imageFile = $item->image_filename;
        } elseif (isset($item->image)) {
            $imageFile = $item->image;
        }

        if (!empty($imageFile)) {
            return PRODUCT_IMAGE_BASE . '/' . ltrim($imageFile, '/');
        }

        return DEFAULT_PRODUCT_IMAGE;
    }
}
