<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/SubCategoryModel.php';
require_once __DIR__ . '/../../helpers/ImageHelper.php';

class ProductController extends Controller
{

    private ProductModel $model;
    private CategoryModel $categoryModel;
    private SubCategoryModel $subCategoryModel;

    public function __construct()
    {
        $this->model = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->subCategoryModel = new SubCategoryModel();
    }


    /**
     * Toggle product status (AJAX)
     */
    public function toggleStatus(int $id): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $product = $this->model->getProductById($id);
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        $newStatus = $product['is_active'] == 1 ? 0 : 1;
        $result = $this->model->updateProduct($id, ['is_active' => $newStatus] + $product);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to toggle status']);
        }
        exit;
    }

    /**
     * List all products
     */
    public function index(): void
    {
        $this->requireAuth();

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $products = $this->model->getAllProducts($limit, $offset);
        $total = $this->model->getTotalCount();
        $totalPages = ceil($total / $limit);

        $pageTitle = 'Products';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('products', 'total', 'page', 'totalPages', 'pageTitle', 'csrf')
            + ['content_view' => '../products/index']);
    }

    /**
     * Show create product form
     */
    public function create(): void
    {
        $this->requireAuth();

        $categories = $this->categoryModel->getAllCategories(1000, 0);
        $pageTitle = 'Add New Product';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('categories', 'pageTitle', 'csrf')
            + ['content_view' => '../products/create']);
    }

    /**
     * Store new product
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        // Basic product data
        $productData = [
            'product_name' => $this->sanitize($_POST['product_name']),
            'sku' => $this->sanitize($_POST['sku'] ?? ''),
            'material' => $this->sanitize($_POST['material'] ?? ''),
            'brand' => $this->sanitize($_POST['brand'] ?? ''),
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'sub_category_id' => !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null,
            'product_stock' => (int)($_POST['product_stock'] ?? 0),
            'product_description' => $this->sanitize($_POST['product_description'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Auto-generate SKU if not provided
        if (empty($productData['sku'])) {
            $productData['sku'] = $this->model->generateSKU($productData['product_name'], $productData['category_id']);
        }

        // Validation
        $errors = [];
        if (empty($productData['product_name'])) {
            $errors[] = 'Product name is required';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('products/create');
            return;
        }

        // Create product
        $productId = $this->model->createProduct($productData);

        if (!$productId) {
            $_SESSION['error_message'] = 'Failed to create product';
            $this->redirect('products/create');
            return;
        }

        // Save sizes
        if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            $this->model->saveProductSizes($productId, $_POST['sizes']);
        }

        // Save colors
        if (isset($_POST['colors']) && is_array($_POST['colors'])) {
            $this->model->saveProductColors($productId, $_POST['colors']);
        }

        // Handle image uploads
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $this->uploadImages($productId, $_FILES['images']);
        }

        $_SESSION['success_message'] = 'Product created successfully!';
        $this->redirect('products');
    }

    /**
     * Show edit product form
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $product = $this->model->getProductById($id);
        if (!$product) {
            $this->redirect('products?error=Product not found');
            return;
        }

        $categories = $this->categoryModel->getAllCategories(1000, 0);
        $subCategories = $product['category_id'] ? $this->subCategoryModel->getSubCategoriesByCategory($product['category_id']) : [];
        $pageTitle = 'Edit Product';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('product', 'categories', 'subCategories', 'pageTitle', 'csrf')
            + ['content_view' => '../products/edit']);
    }

    /**
     * Update product
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $product = $this->model->getProductById($id);
        if (!$product) {
            $this->redirect('products?error=Product not found');
            return;
        }

        // Basic product data
        $productData = [
            'product_name' => $this->sanitize($_POST['product_name']),
            'sku' => $this->sanitize($_POST['sku'] ?? ''),
            'material' => $this->sanitize($_POST['material'] ?? ''),
            'brand' => $this->sanitize($_POST['brand'] ?? ''),
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'sub_category_id' => !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : null,
            'product_stock' => (int)($_POST['product_stock'] ?? 0),
            'product_description' => $this->sanitize($_POST['product_description'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Update product
        $result = $this->model->updateProduct($id, $productData);

        if (!$result) {
            $_SESSION['error_message'] = 'Failed to update product';
            $this->redirect('products/edit/' . $id);
            return;
        }

        // Save sizes
        if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            $this->model->saveProductSizes($id, $_POST['sizes']);
        }

        // Save colors
        if (isset($_POST['colors']) && is_array($_POST['colors'])) {
            $this->model->saveProductColors($id, $_POST['colors']);
        }

        // Handle new image uploads
        if (isset($_FILES['new_images']) && !empty($_FILES['new_images']['name'][0])) {
            $this->uploadImages($id, $_FILES['new_images']);
        }

        $_SESSION['success_message'] = 'Product updated successfully!';
        $this->redirect('products');
    }

    /**
     * Delete product
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $result = $this->model->deleteProduct($id);

        if ($result) {
            $_SESSION['success_message'] = 'Product deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete product';
        }

        $this->redirect('products');
    }

    /**
     * Delete product image (AJAX)
     */
    public function deleteImage(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $imageId = (int)($_POST['image_id'] ?? 0);
        if (!$imageId) {
            echo json_encode(['success' => false, 'message' => 'Invalid image ID']);
            exit;
        }

        $result = $this->model->deleteProductImage($imageId);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete image']);
        }
        exit;
    }

    /**
     * Set primary image (AJAX)
     */
    public function setPrimaryImage(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $imageId = (int)($_POST['image_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);

        if (!$imageId || !$productId) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit;
        }

        $result = $this->model->setPrimaryImage($imageId, $productId);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to set primary image']);
        }
        exit;
    }

    /**
     * Stock management page
     */
    public function stock(): void
    {
        $this->requireAuth();

        $lowStock = $this->model->getLowStockProducts(10);
        $allProducts = $this->model->getAllProducts(1000, 0);

        $pageTitle = 'Stock Management';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('lowStock', 'allProducts', 'pageTitle', 'csrf')
            + ['content_view' => '../products/stock']);
    }

    /**
     * Update stock (AJAX)
     */
    public function updateStock(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $productId = (int)($_POST['product_id'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);

        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
            exit;
        }

        $result = $this->model->updateStock($productId, $stock);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update stock']);
        }
        exit;
    }

    /**
     * Get sub-categories by category (AJAX)
     */
    public function getSubCategories(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $categoryId = (int)($_GET['category_id'] ?? 0);
        if (!$categoryId) {
            echo json_encode(['success' => false, 'data' => []]);
            exit;
        }

        $subCategories = $this->subCategoryModel->getSubCategoriesByCategory($categoryId);
        echo json_encode(['success' => true, 'data' => $subCategories]);
        exit;
    }

    /**
     * Upload and resize images
     */
    private function uploadImages(int $productId, array $files): void
    {
        $uploadDir = __DIR__ . '/../../public/productImages/';

        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $images = [];
        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === 0) {
                $tmpName = $files['tmp_name'][$key];
                $filename = ImageHelper::generateFilename($name, $productId);
                $destination = $uploadDir . $filename;

                // Resize and save image
                if (ImageHelper::resizeAndSave($tmpName, $destination, 600, 600)) {
                    $images[] = [
                        'filename' => $filename,
                        'original_name' => $name,
                        'is_primary' => ($key === 0 && empty($this->model->getProductImages($productId))) // First image becomes primary
                    ];
                }
            }
        }

        if (!empty($images)) {
            $this->model->saveProductImages($productId, $images);
        }
    }
}
