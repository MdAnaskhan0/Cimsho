<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController extends Controller
{

    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    /**
     * List all categories
     */
    public function index(): void
    {
        $this->requireAuth();

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $categories = $this->model->getAllCategories($limit, $offset);
        $total = $this->model->getTotalCount();
        $totalPages = ceil($total / $limit);

        $pageTitle = 'Categories';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact(
            'categories',
            'total',
            'page',
            'totalPages',
            'pageTitle',
            'csrf'
        ) + ['content_view' => '../categories/index']);
    }

    /**
     * Show create category form
     */
    public function create(): void
    {
        $this->requireAuth();

        $pageTitle = 'Add New Category';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('pageTitle', 'csrf') + ['content_view' => '../categories/create']);
    }

    /**
     * Store new category
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $name = $this->sanitize($_POST['name'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        // Validation
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Category name is required';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('categories/create');
            return;
        }

        // Generate slug
        $slug = $this->model->generateSlug($name);

        // Create category
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $is_active,
            'sort_order' => $sort_order
        ];

        $result = $this->model->createCategory($data);

        if ($result) {
            $_SESSION['success_message'] = 'Category created successfully!';
            $this->redirect('categories');
        } else {
            $_SESSION['error_message'] = 'Failed to create category. Please try again.';
            $this->redirect('categories/create');
        }
    }

    /**
     * Show edit category form
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $category = $this->model->getCategoryById($id);
        if (!$category) {
            $this->redirect('categories?error=Category not found');
            return;
        }

        $pageTitle = 'Edit Category';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('category', 'pageTitle', 'csrf') + ['content_view' => '../categories/edit']);
    }

    /**
     * Update category
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $category = $this->model->getCategoryById($id);
        if (!$category) {
            $this->redirect('categories?error=Category not found');
            return;
        }

        $name = $this->sanitize($_POST['name'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        // Validation
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Category name is required';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('categories/edit/' . $id);
            return;
        }

        // Generate slug if name changed
        $slug = $category['slug'];
        if ($name !== $category['name']) {
            $slug = $this->model->generateSlug($name);
        }

        // Update category
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $is_active,
            'sort_order' => $sort_order
        ];

        $result = $this->model->updateCategory($id, $data);

        if ($result) {
            $_SESSION['success_message'] = 'Category updated successfully!';
            $this->redirect('categories');
        } else {
            $_SESSION['error_message'] = 'Failed to update category. Please try again.';
            $this->redirect('categories/edit/' . $id);
        }
    }

    /**
     * Delete category
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $category = $this->model->getCategoryById($id);
        if (!$category) {
            $_SESSION['error_message'] = 'Category not found';
            $this->redirect('categories');
            return;
        }

        $result = $this->model->deleteCategory($id);

        if ($result) {
            $_SESSION['success_message'] = 'Category deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete category. Please try again.';
        }

        $this->redirect('categories');
    }

    /**
     * Toggle category status (AJAX)
     */
    public function toggleStatus(int $id): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $result = $this->model->toggleStatus($id);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Failed to toggle status']);
        }
        exit;
    }
}
