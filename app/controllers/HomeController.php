<?php
require_once BASE_PATH . '/app/controllers/BaseClientController.php';

class HomeController extends BaseClientController {
    public function index() {
        $products = new ProductModel();
        $categories = new CategoryModel();
        $data = [
            'featured' => $products->getFeatured(8),
            'latest' => $products->getLatest(8),
            'categories' => $categories->getWithSubcategories(),
            'pageTitle' => 'Home'
        ];
        $this->clientView('home', $data);
    }
}
