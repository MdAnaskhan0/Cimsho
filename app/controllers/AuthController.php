<?php
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/app/models/UserModel.php';

class AuthController extends Controller {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // GET /login
    public function loginForm(): void {
        $this->guest();
        $this->view('auth.login', ['title' => 'Sign In']);
    }

    // POST /login
    public function login(): void {
        $this->guest();
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors   = [];

        if (empty($email))    $errors[] = 'Email is required.';
        if (empty($password)) $errors[] = 'Password is required.';

        if (empty($errors)) {
            $user = $this->userModel->findByEmail($email);
            if ($user && $this->userModel->verifyPassword($password, $user->password_hash)) {
                if (!$user->is_active) {
                    $errors[] = 'Your account has been suspended.';
                } else {
                    $_SESSION['user_id']   = $user->user_id;
                    $_SESSION['user_name'] = $user->name;
                    $_SESSION['user_email']= $user->email;
                    $this->userModel->updateLastLogin($user->user_id);
                    $redirect = $_SESSION['redirect_after_login'] ?? '/';
                    unset($_SESSION['redirect_after_login']);
                    $this->redirect($redirect);
                }
            } else {
                $errors[] = 'Invalid email or password.';
            }
        }

        $this->view('auth.login', [
            'title'  => 'Sign In',
            'errors' => $errors,
            'old'    => ['email' => $email],
        ]);
    }

    // GET /register
    public function registerForm(): void {
        $this->guest();
        $this->view('auth.register', ['title' => 'Create Account']);
    }

    // POST /register
    public function register(): void {
        $this->guest();
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $errors   = [];

        if (empty($name))     $errors[] = 'Full name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                              $errors[] = 'A valid email is required.';
        if (strlen($password) < 6)
                              $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm)
                              $errors[] = 'Passwords do not match.';

        if (empty($errors) && $this->userModel->emailExists($email)) {
            $errors[] = 'This email is already registered.';
        }

        if (empty($errors)) {
            $userId = $this->userModel->create($name, $email, $phone, $password);
            $_SESSION['user_id']    = $userId;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['flash_success'] = 'Welcome to Cimsho, ' . htmlspecialchars($name) . '!';
            $this->redirect('/');
        }

        $this->view('auth.register', [
            'title'  => 'Create Account',
            'errors' => $errors,
            'old'    => compact('name', 'email', 'phone'),
        ]);
    }

    // POST /logout
    public function logout(): void {
        session_destroy();
        $this->redirect('/login');
    }
}
