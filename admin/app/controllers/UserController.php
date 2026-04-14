<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController extends Controller
{

    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * List all customers
     */
    public function index(): void
    {
        $this->requireAuth();

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Search
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (!empty($search)) {
            $users = $this->model->searchUsers($search, 'user', $limit);
            $total = count($users);
            $totalPages = 1;
        } else {
            $users = $this->model->getAllUsers('user', $limit, $offset);
            $total = $this->model->getTotalCount('user');
            $totalPages = ceil($total / $limit);
        }

        $stats = $this->model->getStatistics();
        $pageTitle = 'Customers';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('users', 'total', 'page', 'totalPages', 'pageTitle', 'csrf', 'stats', 'search')
            + ['content_view' => '../users/index']);
    }

    /**
     * View customer details
     */
    public function viewUser(int $id): void
    {
        $this->requireAuth();

        $user = $this->model->getUserById($id);
        if (!$user) {
            $this->redirect('users?error=Customer not found');
            return;
        }

        $pageTitle = 'Customer Details - ' . htmlspecialchars($user['name']);
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('user', 'pageTitle', 'csrf')
            + ['content_view' => '../users/view']);
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(int $id): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $user = $this->model->getUserById($id);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }

        $newStatus = $user['is_active'] == 1 ? 0 : 1;
        $result = $this->model->updateStatus($id, $newStatus);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        exit;
    }

    /**
     * Update user role (AJAX)
     */
    public function updateRole(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $userId = (int)($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'user';

        if (!$userId || !in_array($role, ['user', 'admin'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit;
        }

        $result = $this->model->updateRole($userId, $role);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update role']);
        }
        exit;
    }
}
