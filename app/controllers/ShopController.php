<?php
require_once BASE_PATH . '/app/controllers/BaseClientController.php';

class ShopController extends BaseClientController
{
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new ProductModel();
    }

    // public function index()
    // {
    //     $page = max(1, (int)($this->get('page') ?: 1));
    //     $limit = 12;
    //     $offset = ($page - 1) * $limit;
    //     $search = $this->get('q');
    //     $catId = $this->get('cat') ? (int)$this->get('cat') : null;
    //     $subCatId = $this->get('sub') ? (int)$this->get('sub') : null;

    //     if ($search) {
    //         $products = $this->productModel->search($search, $limit, $offset);
    //         $total = count($this->productModel->search($search, 1000, 0));
    //     } elseif ($catId) {
    //         $products = $this->productModel->getByCategory($catId, $subCatId, $limit, $offset);
    //         $total = count($this->productModel->getByCategory($catId, $subCatId, 1000, 0));
    //     } else {
    //         $products = $this->productModel->getLatest($limit);
    //         $total = $this->productModel->countAll();
    //     }

    //     $this->clientView('shop', [
    //         'products' => $products,
    //         'total' => $total,
    //         'page' => $page,
    //         'limit' => $limit,
    //         'search' => $search,
    //         'catId' => $catId,
    //         'pageTitle' => 'Shop'
    //     ]);
    // }

    public function index()
    {
        $page = max(1, (int)($this->get('page') ?: 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $search = $this->get('q');
        $catId = $this->get('cat') ? (int)$this->get('cat') : null;
        $subCatId = $this->get('sub') ? (int)$this->get('sub') : null;

        // Get categories for sidebar
        $catModel = new CategoryModel();
        $categories = $catModel->getWithSubcategories();

        if ($search) {
            $products = $this->productModel->search($search, $limit, $offset);
            $total = count($this->productModel->search($search, 1000, 0));
        } elseif ($catId) {
            $products = $this->productModel->getByCategory($catId, $subCatId, $limit, $offset);
            $total = count($this->productModel->getByCategory($catId, $subCatId, 1000, 0));
        } else {
            $products = $this->productModel->getLatest($limit);
            $total = $this->productModel->countAll();
        }

        $this->clientView('shop', [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'catId' => $catId,
            'categories' => $categories,  // Add this for sidebar
            'pageTitle' => 'Shop'
        ]);
    }

    public function product($id)
    {
        $product = $this->productModel->getDetail($id);
        if (!$product) {
            http_response_code(404);
            return;
        }
        $images = $this->productModel->getImages($id);
        $sizes = $this->productModel->getSizes($id);
        $colors = $this->productModel->getColors($id);
        $reviews = $this->productModel->getReviews($id);
        $rating = $this->productModel->getAvgRating($id);
        $related = $this->productModel->getRelated($product['category_id'], $id, 4);

        $this->clientView('product', [
            'product' => $product,
            'images' => $images,
            'sizes' => $sizes,
            'colors' => $colors,
            'reviews' => $reviews,
            'rating' => $rating,
            'related' => $related,
            'pageTitle' => $product['product_name']
        ]);
    }

    public function category($slug)
    {
        $catModel = new CategoryModel();
        $category = $catModel->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            require BASE_PATH . '/app/views/404.php';
            return;
        }

        // Get pagination parameters
        $page = max(1, (int)($this->get('page') ?: 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $subCatId = $this->get('sub') ? (int)$this->get('sub') : null;
        $sort = $this->get('sort') ?? 'newest';

        // Get products with pagination
        $products = $this->productModel->getByCategory($category['id'], $subCatId, $limit, $offset);
        $total = count($this->productModel->getByCategory($category['id'], $subCatId, 1000, 0));

        // Get all categories for sidebar (with subcategories)
        $allCategories = $catModel->getWithSubcategories();

        $this->clientView('shop', [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'category' => $category,
            'categories' => $allCategories,  // For sidebar
            'catId' => $category['id'],       // Current category ID
            'search' => '',                    // Empty search string
            'pageTitle' => $category['name']
        ]);
    }

    public function subcategory($categorySlug, $subCategorySlug)
    {
        $subCatModel = new SubCategoryModel();
        $subcategory = $subCatModel->findBySlug($subCategorySlug);

        if (!$subcategory || !$subcategory['is_active']) {
            http_response_code(404);
            require BASE_PATH . '/app/views/404.php';
            return;
        }

        // Verify subcategory belongs to category
        $catModel = new CategoryModel();
        $category = $catModel->findBySlug($categorySlug);

        if (!$category || $subcategory['category_id'] != $category['id']) {
            http_response_code(404);
            require BASE_PATH . '/app/views/404.php';
            return;
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $sort = $_GET['sort'] ?? 'newest';
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $productModel = new ProductModel();
        $products = $productModel->getBySubcategory($subcategory['id'], $limit, $offset, $sort);
        $total = $productModel->countBySubcategory($subcategory['id']);

        // Get all categories for sidebar
        $allCategories = $catModel->getWithSubcategories();

        $this->clientView('shop', [  // Changed from $this->view to $this->clientView
            'pageTitle' => htmlspecialchars($subcategory['name']) . ' - ' . htmlspecialchars($category['name']),
            'category' => $category,
            'subcategory' => $subcategory,
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'sort' => $sort,
            'currentSlug' => $subCategorySlug,
            'isSubcategory' => true,
            'categories' => $allCategories,  // Add this
            'catId' => $category['id'],       // Add this
            'search' => ''                    // Add this
        ]);
    }

    public function search()
    {
        $this->index();
    }
}
