<?php
require_once BASE_PATH . '/app/controllers/BaseClientController.php';

class AccountController extends BaseClientController {
    public function index() {
        $this->requireLogin();
        $this->redirect('/account/orders');
    }

    public function orders() {
        $this->requireLogin();
        $orderModel = new OrderModel();
        $orders = $orderModel->getUserOrders($_SESSION['user_id']);
        $this->clientView('account/orders', ['orders' => $orders, 'pageTitle' => 'My Orders']);
    }

    public function orderDetail($orderNumber) {
        $this->requireLogin();
        $orderModel = new OrderModel();
        $order = $orderModel->getByNumber($orderNumber);
        if (!$order || ($order['user_id'] != $_SESSION['user_id'])) $this->redirect('/account/orders');
        $items = $orderModel->getItems($order['order_id']);
        $log = $orderModel->getStatusLog($order['order_id']);
        $this->clientView('account/order-detail', ['order' => $order, 'items' => $items, 'log' => $log, 'pageTitle' => 'Order #' . $orderNumber]);
    }

    public function profile() {
        $this->requireLogin();
        $userModel = new UserModel();
        $user = $userModel->find($_SESSION['user_id'], 'user_id');
        $this->clientView('account/profile', ['user' => $user, 'pageTitle' => 'My Profile']);
    }

    public function updateProfile() {
        $this->requireLogin();
        $userModel = new UserModel();
        $data = ['name' => $this->post('name'), 'phone' => $this->post('phone')];
        $userModel->update($_SESSION['user_id'], $data);
        $_SESSION['user_name'] = $data['name'];
        $password = $_POST['password'] ?? '';
        if ($password) $userModel->updatePassword($_SESSION['user_id'], $password);
        $this->redirect('/account/profile?saved=1');
    }

    public function addresses() {
        $this->requireLogin();
        $userModel = new UserModel();
        $addresses = $userModel->getAddresses($_SESSION['user_id']);
        $this->clientView('account/addresses', ['addresses' => $addresses, 'pageTitle' => 'My Addresses']);
    }

    public function saveAddress() {
        $this->requireLogin();
        $userModel = new UserModel();
        $data = [
            'id' => $this->post('address_id'),
            'user_id' => $_SESSION['user_id'],
            'label' => $this->post('label') ?: 'home',
            'full_name' => $this->post('full_name'),
            'phone' => $this->post('phone'),
            'address_line' => $this->post('address_line'),
            'area' => $this->post('area'),
            'city' => $this->post('city') ?: 'Dhaka',
            'district' => $this->post('district'),
            'postal_code' => $this->post('postal_code'),
            'is_default' => $_POST['is_default'] ?? 0,
        ];
        $userModel->saveAddress($data);
        $this->redirect('/account/addresses?saved=1');
    }
}

class WishlistController extends Controller {
    public function toggle() {
        $productId = (int)($_POST['product_id'] ?? 0);
        if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];
        $key = array_search($productId, $_SESSION['wishlist']);
        if ($key !== false) {
            unset($_SESSION['wishlist'][$key]);
            $this->json(['added' => false]);
        } else {
            $_SESSION['wishlist'][] = $productId;
            $this->json(['added' => true]);
        }
    }
}

class AjaxController extends Controller {
    public function getSubcategories() {
        $catId = (int)($_POST['category_id'] ?? 0);
        $catModel = new CategoryModel();
        $subs = $catModel->getSubcategories($catId);
        $this->json($subs);
    }

    public function productSizes() {
        $productId = (int)($_POST['product_id'] ?? 0);
        $productModel = new ProductModel();
        $sizes = $productModel->getSizes($productId);
        $this->json($sizes);
    }

    public function submitReview() {
        if (!isset($_SESSION['user_id'])) { $this->json(['success' => false, 'message' => 'Login required']); return; }
        $productId = (int)($_POST['product_id'] ?? 0);
        $rating = min(5, max(1, (int)($_POST['rating'] ?? 5)));
        $review = htmlspecialchars(strip_tags($_POST['review'] ?? ''));
        $db = Database::getInstance()->getConnection();
        $uid = (int)$_SESSION['user_id'];
        $db->query("INSERT INTO product_reviews (product_id,user_id,rating,review) VALUES ($productId,$uid,$rating,'" . $db->real_escape_string($review) . "') ON DUPLICATE KEY UPDATE rating=$rating, review='" . $db->real_escape_string($review) . "'");
        $this->json(['success' => true]);
    }

    public function checkDelivery() {
        $district = strtolower(trim($_POST['district'] ?? ''));
        $settings = (new DeliverySettingsModel())->get();
        $dhakaDistricts = ['dhaka', 'narayanganj', 'gazipur', 'manikganj'];
        $charge = in_array($district, $dhakaDistricts) ? $settings['inside_dhaka_charge'] : $settings['outside_dhaka_charge'];
        $this->json(['charge' => $charge, 'inside' => in_array($district, $dhakaDistricts)]);
    }
}
