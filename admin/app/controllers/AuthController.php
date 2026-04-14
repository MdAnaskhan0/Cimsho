<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/AdminModel.php';

class AuthController extends Controller {

    private AdminModel $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    // GET /login
    public function login(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        $csrf    = $this->csrfToken();
        $timeout = isset($_GET['timeout']);
        $this->view('auth/login', compact('csrf', 'timeout'));
    }

    // POST /login
    public function authenticate(): void {
        $this->verifyCsrf();

        $username = $this->sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Please enter username and password.');
            $this->redirect('login');
        }

        $admin = $this->adminModel->findByUsername($username);

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            // Slight delay to mitigate brute-force
            sleep(1);
            $this->setFlash('error', 'Invalid username or password.');
            $this->redirect('login');
        }

        // Regenerate session on login
        session_regenerate_id(true);

        $_SESSION['admin_id']       = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_name']     = $admin['full_name'];
        $_SESSION['admin_avatar']   = $admin['avatar'];
        $_SESSION['last_activity']  = time();

        $this->adminModel->updateLastLogin($admin['id']);

        $this->redirect('dashboard');
    }

    // POST /change-password
    public function changePassword(): void {
        $this->requireAuth();
        $this->verifyCsrf();

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (strlen($new) < 8) {
            $this->json(['success' => false, 'message' => 'Password must be at least 8 characters.']);
        }

        if ($new !== $confirm) {
            $this->json(['success' => false, 'message' => 'Passwords do not match.']);
        }

        $admin = $this->adminModel->findById((int)$_SESSION['admin_id']);
        if (!$admin || !password_verify($current, $admin['password_hash'])) {
            $this->json(['success' => false, 'message' => 'Current password is incorrect.']);
        }

        $newHash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->adminModel->updatePassword((int)$_SESSION['admin_id'], $newHash);

        $this->json(['success' => true, 'message' => 'Password changed successfully.']);
    }

    // GET /logout
    public function logout(): void {
        session_destroy();
        $this->redirect('login');
    }

    // -------------------------------------------------------
    private function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}
