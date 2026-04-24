<?php
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/app/models/ProductModel.php';

class HomeController extends Controller
{

    private ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index(): void
    {
        $featured   = $this->productModel->getFeatured(8);
        $categories = $this->productModel->getCategories();

        $this->view('home.index', [
            'title'      => 'Home',
            'featured'   => $featured,
            'categories' => $categories,
        ]);
    }
}
