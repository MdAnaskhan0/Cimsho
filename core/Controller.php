<?php
class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewFile = APP_ROOT . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            die('View not found: ' . $view);
        }
        require $viewFile;
    }

    protected function redirect(string $url): void {
        header('Location: ' . APP_URL . $url);
        exit;
    }

    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth(): void {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('/login');
        }
    }

    protected function guest(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }
    }
}
