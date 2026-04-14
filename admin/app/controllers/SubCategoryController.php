<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/SubCategoryModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class SubCategoryController extends Controller
{

    private SubCategoryModel $model;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->model = new SubCategoryModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * List all sub-categories
     */
    public function index(): void
    {
        $this->requireAuth();

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $subCategories = $this->model->getAllSubCategories($limit, $offset);
        $total = $this->model->getTotalCount();
        $totalPages = ceil($total / $limit);

        $pageTitle = 'Sub Categories';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact(
            'subCategories',
            'total',
            'page',
            'totalPages',
            'pageTitle',
            'csrf'
        ) + ['content_view' => '../sub_categories/index']);
    }

    /**
     * Show create sub-category form
     */
    public function create(): void
    {
        $this->requireAuth();

        $categories = $this->categoryModel->getAllCategories(1000, 0);
        $pageTitle = 'Add New Sub Category';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('categories', 'pageTitle', 'csrf') + ['content_view' => '../sub_categories/create']);
    }

    /**
     * Store new sub-category
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = $this->sanitize($_POST['name'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        // Validation
        $errors = [];
        if ($categoryId <= 0) {
            $errors[] = 'Please select a category';
        }
        if (empty($name)) {
            $errors[] = 'Sub-category name is required';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('sub-categories/create');
            return;
        }

        // Generate slug
        $slug = $this->model->generateSlug($name, $categoryId);

        // Create sub-category
        $data = [
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $is_active,
            'sort_order' => $sort_order
        ];

        $result = $this->model->createSubCategory($data);

        if ($result) {
            $_SESSION['success_message'] = 'Sub-category created successfully!';
            $this->redirect('sub-categories');
        } else {
            $_SESSION['error_message'] = 'Failed to create sub-category. Please try again.';
            $this->redirect('sub-categories/create');
        }
    }

    /**
     * Show edit sub-category form
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $subCategory = $this->model->getSubCategoryById($id);
        if (!$subCategory) {
            $this->redirect('sub-categories?error=Sub-category not found');
            return;
        }

        $categories = $this->categoryModel->getAllCategories(1000, 0);
        $pageTitle = 'Edit Sub Category';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('subCategory', 'categories', 'pageTitle', 'csrf') + ['content_view' => '../sub_categories/edit']);
    }

    /**
     * Update sub-category
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $subCategory = $this->model->getSubCategoryById($id);
        if (!$subCategory) {
            $this->redirect('sub-categories?error=Sub-category not found');
            return;
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = $this->sanitize($_POST['name'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        // Validation
        $errors = [];
        if ($categoryId <= 0) {
            $errors[] = 'Please select a category';
        }
        if (empty($name)) {
            $errors[] = 'Sub-category name is required';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('sub-categories/edit/' . $id);
            return;
        }

        // Generate slug if name changed
        $slug = $subCategory['slug'];
        if ($name !== $subCategory['name']) {
            $slug = $this->model->generateSlug($name, $categoryId);
        }

        // Update sub-category
        $data = [
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $is_active,
            'sort_order' => $sort_order
        ];

        $result = $this->model->updateSubCategory($id, $data);

        if ($result) {
            $_SESSION['success_message'] = 'Sub-category updated successfully!';
            $this->redirect('sub-categories');
        } else {
            $_SESSION['error_message'] = 'Failed to update sub-category. Please try again.';
            $this->redirect('sub-categories/edit/' . $id);
        }
    }

    /**
     * Delete sub-category
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $subCategory = $this->model->getSubCategoryById($id);
        if (!$subCategory) {
            $_SESSION['error_message'] = 'Sub-category not found';
            $this->redirect('sub-categories');
            return;
        }

        $result = $this->model->deleteSubCategory($id);

        if ($result) {
            $_SESSION['success_message'] = 'Sub-category deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete sub-category. Please try again.';
        }

        $this->redirect('sub-categories');
    }

    /**
     * Toggle sub-category status (AJAX)
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

    /**
     * Get sub-categories by category (AJAX)
     */
    public function getByCategory(int $categoryId): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $subCategories = $this->model->getSubCategoriesByCategory($categoryId);
        echo json_encode(['success' => true, 'data' => $subCategories]);
        exit;
    }
}
