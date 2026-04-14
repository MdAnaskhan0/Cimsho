<?php
require_once __DIR__.'/../../core/Controller.php';
require_once __DIR__.'/../models/UserModel.php';

class AuthController extends Controller {
    private UserModel $user;
    public function __construct(){ $this->user = new UserModel(); }

    public function loginPage(): void {
        if($this->isLoggedIn()) $this->redirect('account');
        $csrf = $this->csrfToken();
        $this->view('layouts/main', ['title'=>'Sign In','content_view'=>'auth/login','csrf'=>$csrf]);
    }

    public function login(): void {
        $this->verifyCsrf();
        $email = trim($_POST['email']??'');
        $pass  = $_POST['password']??'';
        $user  = $this->user->findByEmail($email);
        if(!$user || !password_verify($pass, $user['password_hash'])){
            sleep(1);
            $this->flash('error','Invalid email or password.');
            $this->redirect('account/login');
        }
        session_regenerate_id(true);
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['last_activity'] = time();
        $redir = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);
        if($redir){ header('Location: '.$redir); exit; }
        $this->redirect('account');
    }

    public function signupPage(): void {
        if($this->isLoggedIn()) $this->redirect('account');
        $csrf = $this->csrfToken();
        $this->view('layouts/main', ['title'=>'Create Account','content_view'=>'auth/signup','csrf'=>$csrf]);
    }

    public function signup(): void {
        $this->verifyCsrf();
        $name  = $this->clean($_POST['name']??'');
        $email = trim($_POST['email']??'');
        $phone = $this->clean($_POST['phone']??'');
        $pass  = $_POST['password']??'';
        $conf  = $_POST['confirm_password']??'';

        if(strlen($name)<2||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<8||$pass!==$conf){
            $this->flash('error','Please fill all fields correctly. Password must be 8+ chars and match.');
            $this->redirect('account/signup');
        }
        if($this->user->emailExists($email)){
            $this->flash('error','This email is already registered. Please login.');
            $this->redirect('account/login');
        }
        $uid = $this->user->create($name,$email,$phone,password_hash($pass,PASSWORD_BCRYPT,['cost'=>12]));
        session_regenerate_id(true);
        $_SESSION['user_id']    = $uid;
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['last_activity'] = time();
        $this->flash('success','Welcome to Cimsho, '.$name.'! Your account is ready.');
        $this->redirect('account');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('');
    }

    public function changePasswordPage(): void {
        $this->requireLogin();
        $csrf = $this->csrfToken();
        $this->view('layouts/main',['title'=>'Change Password','content_view'=>'account/change_password','csrf'=>$csrf]);
    }

    public function changePassword(): void {
        $this->requireLogin(); $this->verifyCsrf();
        $current = $_POST['current_password']??'';
        $new     = $_POST['new_password']??'';
        $conf    = $_POST['confirm_password']??'';
        $user    = $this->user->findById((int)$_SESSION['user_id']);
        if(!$user||!password_verify($current,$user['password_hash'])){
            $this->flash('error','Current password is incorrect.');
            $this->redirect('account/change-password');
        }
        if(strlen($new)<8||$new!==$conf){
            $this->flash('error','New password must be 8+ characters and match confirmation.');
            $this->redirect('account/change-password');
        }
        $this->user->updatePassword((int)$_SESSION['user_id'],password_hash($new,PASSWORD_BCRYPT,['cost'=>12]));
        $this->flash('success','Password changed successfully.');
        $this->redirect('account');
    }
}
