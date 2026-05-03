<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function view($path, $data = []) {
        extract($data);
        $viewFile = BASE_PATH . '/app/views/' . $path . '.php';
        if (file_exists($viewFile)) require $viewFile;
        else die("View not found: $path");
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isAdminLoggedIn() {
        return isset($_SESSION['admin_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdminLoggedIn()) {
            $this->redirect('/admin/login');
        }
    }

    protected function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    protected function post($key, $default = '') {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    protected function get($key, $default = '') {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }
}
