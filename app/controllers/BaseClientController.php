<?php
require_once BASE_PATH . '/core/Controller.php';

class BaseClientController extends Controller {
    protected $categories;
    protected $cartCount;

    public function __construct() {
        parent::__construct();
        $catModel = new CategoryModel();
        $this->categories = $catModel->getWithSubcategories();
        $this->cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'qty'));
    }

    protected function clientView($view, $data = []) {
        $data['categories'] = $this->categories;
        $data['cartCount'] = $this->cartCount;
        $data['session'] = $_SESSION;
        $this->view('client/' . $view, $data);
    }
}
