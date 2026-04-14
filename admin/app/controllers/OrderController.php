<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController extends Controller
{

    private OrderModel $model;

    public function __construct()
    {
        $this->model = new OrderModel();
    }

    /**
     * List all orders
     */
    public function index(): void
    {
        $this->requireAuth();

        $status = null;
        $pageTitle = 'All Orders';

        $this->renderOrderList($status, $pageTitle);
    }

    /**
     * List pending orders
     */
    public function pending(): void
    {
        $this->requireAuth();

        $status = 'pending';
        $pageTitle = 'Pending Orders';

        $this->renderOrderList($status, $pageTitle);
    }

    /**
     * List shipped orders
     */
    public function shipped(): void
    {
        $this->requireAuth();

        $status = 'shipped';
        $pageTitle = 'Shipped Orders';

        $this->renderOrderList($status, $pageTitle);
    }

    /**
     * Render order list with filters
     */
    private function renderOrderList(?string $status, string $pageTitle): void
    {
        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Search
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (!empty($search)) {
            $orders = $this->model->searchOrders($search, $status, $limit);
            $total = count($orders);
            $totalPages = 1;
        } else {
            $orders = $this->model->getAllOrders($status, $limit, $offset);
            $total = $this->model->getTotalCount($status);
            $totalPages = ceil($total / $limit);
        }

        $stats = $this->model->getStatistics();
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('orders', 'total', 'page', 'totalPages', 'pageTitle', 'csrf', 'stats', 'search', 'status')
            + ['content_view' => '../orders/index']);
    }

    /**
     * View order details
     */
    public function show(int $id): void
    {
        $this->requireAuth();

        $order = $this->model->getOrderById($id);
        if (!$order) {
            $this->redirect('orders?error=Order not found');
            return;
        }

        $pageTitle = 'Order #' . htmlspecialchars($order['order_number']);
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('order', 'pageTitle', 'csrf')
            + ['content_view' => '../orders/view']);
    }

    /**
     * Update order status (AJAX)
     */
    public function updateStatus(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        header('Content-Type: application/json');

        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $note = $_POST['note'] ?? null;

        $validStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];

        if (!$orderId || !in_array($status, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit;
        }

        $result = $this->model->updateStatus($orderId, $status, $note);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        exit;
    }

    /**
     * Update tracking number (AJAX)
     */
    public function updateTracking(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        header('Content-Type: application/json');

        $orderId = (int)($_POST['order_id'] ?? 0);
        $trackingNumber = $this->sanitize($_POST['tracking_number'] ?? '');

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
            exit;
        }

        $result = $this->model->updateTrackingNumber($orderId, $trackingNumber);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update tracking number']);
        }
        exit;
    }
}
