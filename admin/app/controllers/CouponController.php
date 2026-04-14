<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/CouponModel.php';

class CouponController extends Controller
{

    private CouponModel $model;

    public function __construct()
    {
        $this->model = new CouponModel();
    }

    /**
     * List all coupons
     */
    public function index(): void
    {
        $this->requireAuth();

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $coupons = $this->model->getAllCoupons($limit, $offset);
        $total = $this->model->getTotalCount();
        $totalPages = ceil($total / $limit);
        $stats = $this->model->getStatistics();

        $pageTitle = 'Coupons';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('coupons', 'total', 'page', 'totalPages', 'pageTitle', 'csrf', 'stats')
            + ['content_view' => '../coupons/index']);
    }

    /**
     * Show create coupon form
     */
    public function create(): void
    {
        $this->requireAuth();

        $pageTitle = 'Add New Coupon';
        $csrf = $this->csrfToken();
        $generatedCode = $this->model->generateCode('COUPON_');

        $this->view('layouts/main', compact('pageTitle', 'csrf', 'generatedCode')
            + ['content_view' => '../coupons/create']);
    }

    /**
     * Store new coupon
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $code = strtoupper($this->sanitize($_POST['code'] ?? ''));
        $discount_pct = (float)($_POST['discount_pct'] ?? 0);
        $min_order = (float)($_POST['min_order'] ?? 0);
        $max_uses = (int)($_POST['max_uses'] ?? 100);
        $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validation
        $errors = [];
        if (empty($code)) {
            $errors[] = 'Coupon code is required';
        }
        if ($discount_pct <= 0 || $discount_pct > 100) {
            $errors[] = 'Discount percentage must be between 1 and 100';
        }
        if ($min_order < 0) {
            $errors[] = 'Minimum order amount cannot be negative';
        }
        if ($max_uses <= 0) {
            $errors[] = 'Maximum uses must be at least 1';
        }
        if ($this->model->codeExists($code)) {
            $errors[] = 'Coupon code already exists';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('coupons/create');
            return;
        }

        // Create coupon
        $data = [
            'code' => $code,
            'discount_pct' => $discount_pct,
            'min_order' => $min_order,
            'max_uses' => $max_uses,
            'expires_at' => $expires_at,
            'is_active' => $is_active
        ];

        $result = $this->model->createCoupon($data);

        if ($result) {
            $_SESSION['success_message'] = 'Coupon created successfully!';
            $this->redirect('coupons');
        } else {
            $_SESSION['error_message'] = 'Failed to create coupon. Please try again.';
            $this->redirect('coupons/create');
        }
    }

    /**
     * Show edit coupon form
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $coupon = $this->model->getCouponById($id);
        if (!$coupon) {
            $this->redirect('coupons?error=Coupon not found');
            return;
        }

        $pageTitle = 'Edit Coupon';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('coupon', 'pageTitle', 'csrf')
            + ['content_view' => '../coupons/edit']);
    }

    /**
     * Update coupon
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $coupon = $this->model->getCouponById($id);
        if (!$coupon) {
            $this->redirect('coupons?error=Coupon not found');
            return;
        }

        $code = strtoupper($this->sanitize($_POST['code'] ?? ''));
        $discount_pct = (float)($_POST['discount_pct'] ?? 0);
        $min_order = (float)($_POST['min_order'] ?? 0);
        $max_uses = (int)($_POST['max_uses'] ?? 100);
        $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validation
        $errors = [];
        if (empty($code)) {
            $errors[] = 'Coupon code is required';
        }
        if ($discount_pct <= 0 || $discount_pct > 100) {
            $errors[] = 'Discount percentage must be between 1 and 100';
        }
        if ($min_order < 0) {
            $errors[] = 'Minimum order amount cannot be negative';
        }
        if ($max_uses <= 0) {
            $errors[] = 'Maximum uses must be at least 1';
        }
        if ($this->model->codeExists($code, $id)) {
            $errors[] = 'Coupon code already exists';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('coupons/edit/' . $id);
            return;
        }

        // Update coupon
        $data = [
            'code' => $code,
            'discount_pct' => $discount_pct,
            'min_order' => $min_order,
            'max_uses' => $max_uses,
            'expires_at' => $expires_at,
            'is_active' => $is_active
        ];

        $result = $this->model->updateCoupon($id, $data);

        if ($result) {
            $_SESSION['success_message'] = 'Coupon updated successfully!';
            $this->redirect('coupons');
        } else {
            $_SESSION['error_message'] = 'Failed to update coupon. Please try again.';
            $this->redirect('coupons/edit/' . $id);
        }
    }

    /**
     * Delete coupon
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $coupon = $this->model->getCouponById($id);
        if (!$coupon) {
            $_SESSION['error_message'] = 'Coupon not found';
            $this->redirect('coupons');
            return;
        }

        $result = $this->model->deleteCoupon($id);

        if ($result) {
            $_SESSION['success_message'] = 'Coupon deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete coupon. Please try again.';
        }

        $this->redirect('coupons');
    }

    /**
     * Toggle coupon status (AJAX)
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
     * Generate random coupon code (AJAX)
     */
    public function generateCode(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $prefix = isset($_GET['prefix']) ? $this->sanitize($_GET['prefix']) : 'COUPON_';
        $code = $this->model->generateCode($prefix);

        echo json_encode(['success' => true, 'code' => $code]);
        exit;
    }
}
