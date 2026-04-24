<?php
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/app/models/UserModel.php';

class AccountController extends Controller {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index(): void {
        $this->requireAuth();
        $user = $this->userModel->findById($_SESSION['user_id']);
        $this->view('account.index', ['title' => 'My Account', 'user' => $user]);
    }
}
