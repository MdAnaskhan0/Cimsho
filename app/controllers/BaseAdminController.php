<?php
require_once BASE_PATH . '/core/Controller.php';

class BaseAdminController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['admin_id']) && !str_contains($_SERVER['REQUEST_URI'], '/admin/login')) {
            $this->redirect('/admin/login');
            exit;
        }
    }

    protected function adminView($view, $data = []) {
        $data['admin'] = $_SESSION['admin'] ?? [];
        $this->view('admin/' . $view, $data);
    }
}
