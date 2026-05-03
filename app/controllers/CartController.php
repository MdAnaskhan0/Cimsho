<?php
require_once BASE_PATH . '/app/controllers/BaseClientController.php';

class CartController extends BaseClientController {
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $productModel = new ProductModel();
        $items = [];
        foreach ($cart as $key => $item) {
            $product = $productModel->getDetail($item['product_id']);
            if ($product) {
                $images = $productModel->getImages($item['product_id']);
                $product['image_filename'] = $images[0]['image_filename'] ?? null;
                $items[] = array_merge($item, ['product' => $product, 'key' => $key]);
            }
        }
        $this->clientView('cart', ['items' => $items, 'pageTitle' => 'Cart', 'coupon' => $_SESSION['coupon'] ?? null]);
    }

    public function add() {
        $productId = (int)($_POST['product_id'] ?? 0);
        $size = $this->post('size');
        $color = $this->post('color');
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        $price = (float)($_POST['price'] ?? 0);

        $key = $productId . '_' . md5($size . $color);
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$key] = ['product_id' => $productId, 'size' => $size, 'color' => $color, 'qty' => $qty, 'price' => $price];
        }
        $this->json(['success' => true, 'cartCount' => array_sum(array_column($_SESSION['cart'], 'qty'))]);
    }

    public function update() {
        $key = $_POST['key'] ?? '';
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        if (isset($_SESSION['cart'][$key])) $_SESSION['cart'][$key]['qty'] = $qty;
        $this->json(['success' => true]);
    }

    public function remove() {
        $key = $_POST['key'] ?? '';
        unset($_SESSION['cart'][$key]);
        $this->json(['success' => true, 'cartCount' => array_sum(array_column($_SESSION['cart'] ?? [], 'qty'))]);
    }

    public function applyCoupon() {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $couponModel = new CouponModel();
        $coupon = $couponModel->findByCode($code);
        $subtotal = (float)($_POST['subtotal'] ?? 0);
        if ($coupon && $couponModel->isValid($coupon, $subtotal)) {
            $_SESSION['coupon'] = $coupon;
            $discount = round($subtotal * $coupon['discount_pct'] / 100, 2);
            $this->json(['success' => true, 'discount' => $discount, 'pct' => $coupon['discount_pct'], 'message' => "Coupon applied! {$coupon['discount_pct']}% off"]);
        } else {
            unset($_SESSION['coupon']);
            $this->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
        }
    }
}
