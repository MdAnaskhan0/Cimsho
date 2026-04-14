<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController extends Controller
{

    private DashboardModel $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index(): void
    {
        $this->requireAuth();

        $stats = [
            'total_orders'   => $this->model->getTotalOrders(),
            'total_revenue'  => $this->model->getTotalRevenue(),
            'total_users'    => $this->model->getTotalUsers(),
            'total_products' => $this->model->getTotalProducts(),
            'pending_orders' => $this->model->getPendingOrdersCount(),
        ];

        $ordersByStatus  = $this->model->getOrdersByStatus();
        $recentOrders    = $this->model->getRecentOrders(8);
        $lowStock        = $this->model->getLowStockProducts();
        $monthlyRevenue  = $this->model->getMonthlyRevenue();
        $csrf            = $this->csrfToken();
        $pageTitle       = 'Dashboard';

        $this->view('layouts/main', compact(
            'stats',
            'ordersByStatus',
            'recentOrders',
            'lowStock',
            'monthlyRevenue',
            'csrf',
            'pageTitle'
        ) + ['content_view' => '../dashboard/index']);
    }
}
