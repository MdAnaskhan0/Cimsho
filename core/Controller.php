<?php
abstract class Controller {
    protected function view(string $view, array $data=[]): void {
        extract($data);
        $f = __DIR__.'/../app/views/'.$view.'.php';
        if(!file_exists($f)) die("View not found: $view");
        require $f;
    }
    protected function redirect(string $url): void {
        header('Location: '.APP_URL.'/'.ltrim($url,'/'));
        exit;
    }
    protected function redirectBack(): void {
        $ref = $_SERVER['HTTP_REFERER'] ?? APP_URL.'/';
        header('Location: '.$ref); exit;
    }
    protected function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }
    protected function requireLogin(): void {
        if(!$this->isLoggedIn()){
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('account/login');
        }
    }
    protected function json(array $d, int $s=200): void {
        http_response_code($s);
        header('Content-Type: application/json');
        echo json_encode($d); exit;
    }
    protected function csrfToken(): string {
        if(empty($_SESSION['csrf_token']))
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    protected function verifyCsrf(): void {
        if(!hash_equals($_SESSION['csrf_token']??'', $_POST['csrf_token']??''))
            die('CSRF mismatch.');
    }
    protected function clean(string $s): string {
        return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
    }
    protected function flash(string $type, string $msg): void {
        $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg];
    }
}
