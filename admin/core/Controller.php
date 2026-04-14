<?php
abstract class Controller
{

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }
        require $viewFile;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . APP_URL . '/' . ltrim($url, '/'));
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }

    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
        // Session timeout check
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            session_destroy();
            $this->redirect('login?timeout=1');
        }
        $_SESSION['last_activity'] = time();
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            die('Invalid CSRF token.');
        }
    }

    protected function sanitize($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = $this->sanitize($value);
            }
            return $input;
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
