<?php
class AjaxController extends Controller
{
    public function getSubcategories()
    {
        header('Content-Type: application/json');
        $categoryId = (int)($_POST['category_id'] ?? 0);

        if (!$categoryId) {
            echo json_encode([]);
            return;
        }

        $subCatModel = new SubCategoryModel();
        $subcategories = $subCatModel->getAllByCategory($categoryId, true);

        echo json_encode($subcategories);
    }

    public function productSizes()
    {
        header('Content-Type: application/json');
        $productId = (int)($_POST['product_id'] ?? 0);

        if (!$productId) {
            echo json_encode([]);
            return;
        }

        $productModel = new ProductModel();
        $sizes = $productModel->getSizes($productId);

        echo json_encode($sizes);
    }

    public function submitReview()
    {
        // Your review submission logic here
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function checkDelivery()
    {
        header('Content-Type: application/json');
        echo json_encode(['available' => true]);
    }
}
