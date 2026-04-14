<?php
require_once __DIR__ . '/../../core/Model.php';

class SubCategoryModel extends Model
{

    protected string $table = 'sub_categories';

    /**
     * Get all sub-categories with category names
     */
    public function getAllSubCategories(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT sc.*, c.name as category_name 
                FROM {$this->table} sc
                LEFT JOIN categories c ON sc.category_id = c.id
                ORDER BY sc.sort_order ASC, sc.created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total count of sub-categories
     */
    public function getTotalCount(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get sub-category by ID with category info
     */
    public function getSubCategoryById(int $id): ?array
    {
        $sql = "SELECT sc.*, c.name as category_name 
                FROM {$this->table} sc
                LEFT JOIN categories c ON sc.category_id = c.id
                WHERE sc.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get sub-categories by category ID
     */
    public function getSubCategoriesByCategory(int $categoryId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE category_id = :category_id AND is_active = 1 
                ORDER BY sort_order ASC, name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get sub-category by slug
     */
    public function getSubCategoryBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create a new sub-category
     */
    public function createSubCategory(array $data): int|false
    {
        $sql = "INSERT INTO {$this->table} (category_id, name, slug, description, is_active, sort_order) 
                VALUES (:category_id, :name, :slug, :description, :is_active, :sort_order)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
            ':sort_order' => $data['sort_order'] ?? 0
        ]);

        return $this->db->lastInsertId() ?: false;
    }

    /**
     * Update sub-category
     */
    public function updateSubCategory(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET category_id = :category_id,
                    name = :name, 
                    slug = :slug, 
                    description = :description, 
                    is_active = :is_active, 
                    sort_order = :sort_order 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
            ':sort_order' => $data['sort_order'] ?? 0
        ]);
    }

    /**
     * Delete sub-category
     */
    public function deleteSubCategory(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Check if slug exists
     */
    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = [':slug' => $slug];

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
     * Generate unique slug from name
     */
    public function generateSlug(string $name, int $categoryId = null): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = trim($slug, '-');

        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Toggle sub-category status
     */
    public function toggleStatus(int $id): bool
    {
        $subCategory = $this->getSubCategoryById($id);
        if (!$subCategory) return false;

        $newStatus = $subCategory['is_active'] == 1 ? 0 : 1;
        $sql = "UPDATE {$this->table} SET is_active = :is_active WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':is_active' => $newStatus, ':id' => $id]);
    }

    /**
     * Count sub-categories by category
     */
    public function countByCategory(int $categoryId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE category_id = :category_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $categoryId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
}
