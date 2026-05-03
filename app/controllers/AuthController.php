<?php
require_once BASE_PATH . '/app/controllers/BaseClientController.php';

class AuthController extends BaseClientController {
    public function loginPage() {
        if ($this->isLoggedIn()) $this->redirect('/account');
        $this->clientView('login', ['pageTitle' => 'Login']);
    }

    public function login() {
        $email = $this->post('email');
        $password = $_POST['password'] ?? '';
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $redirect = $this->get('redirect') ?: '/account';
            $this->redirect($redirect);
        }
        $this->clientView('login', ['error' => 'Invalid email or password.', 'pageTitle' => 'Login']);
    }

    public function registerPage() {
        if ($this->isLoggedIn()) $this->redirect('/account');
        $this->clientView('register', ['pageTitle' => 'Register']);
    }

    public function register() {
        $name = $this->post('name');
        $email = $this->post('email');
        $phone = $this->post('phone');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm) {
            return $this->clientView('register', ['error' => 'Passwords do not match.', 'pageTitle' => 'Register']);
        }
        $userModel = new UserModel();
        if ($userModel->findByEmail($email)) {
            return $this->clientView('register', ['error' => 'Email already registered.', 'pageTitle' => 'Register']);
        }
        $id = $userModel->create($name, $email, $phone, $password);
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $this->redirect('/account');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
