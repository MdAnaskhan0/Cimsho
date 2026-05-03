<?php
require_once BASE_PATH . '/app/controllers/BaseClientController.php';

class CheckoutController extends BaseClientController {
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) $this->redirect('/cart');

        $addresses = [];
        if ($this->isLoggedIn()) {
            $userModel = new UserModel();
            $addresses = $userModel->getAddresses($_SESSION['user_id']);
        }
        $delivery = (new DeliverySettingsModel())->get();
        $this->clientView('checkout', ['pageTitle' => 'Checkout', 'addresses' => $addresses, 'delivery' => $delivery]);
    }

    public function placeOrder() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) $this->redirect('/cart');

        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        $deliverySettings = (new DeliverySettingsModel())->get();

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        $coupon = $_SESSION['coupon'] ?? null;
        $discount = 0;
        if ($coupon) {
            $couponModel = new CouponModel();
            if ($couponModel->isValid($coupon, $subtotal)) {
                $discount = round($subtotal * $coupon['discount_pct'] / 100, 2);
                $couponModel->incrementUsage($coupon['id']);
            }
        }

        $deliveryType = $this->post('delivery_type') ?: 'inside';
        $shipping = $deliveryType === 'outside' ? $deliverySettings['outside_dhaka_charge'] : $deliverySettings['inside_dhaka_charge'];
        if (($subtotal - $discount) >= $deliverySettings['free_delivery_min_amount']) $shipping = 0;

        $total = $subtotal - $discount + $shipping;

        $userId = $_SESSION['user_id'] ?? 0;
        $addressId = $this->post('address_id') ? (int)$this->post('address_id') : null;
        $paymentMethod = $this->post('payment_method') ?: 'cod';

        $orderData = [
            'user_id' => $userId,
            'address_id' => $addressId,
            'total_amount' => $total,
            'payment_method' => $paymentMethod,
            'shipping_charge' => $shipping,
            'notes' => $this->post('notes'),
            'guest_name' => $this->post('guest_name'),
            'guest_email' => $this->post('guest_email'),
            'guest_phone' => $this->post('guest_phone'),
            'guest_address' => $this->post('guest_address'),
        ];

        $order = $orderModel->insertOrder($orderData);
        $orderId = $order['order_id'];
        $orderNumber = $order['order_number'];

        foreach ($cart as $item) {
            $orderModel->addItem($orderId, $orderNumber, $item);
        }
        $orderModel->addStatusLog($orderId, $orderNumber, 'pending', 'Order placed');
        $orderModel->addPayment($orderId, $orderNumber, $total, $paymentMethod, 'pending');

        unset($_SESSION['cart'], $_SESSION['coupon']);
        $this->redirect('/order-success/' . $orderNumber);
    }

    public function success($orderNumber) {
        $orderModel = new OrderModel();
        $order = $orderModel->getByNumber($orderNumber);
        if (!$order) $this->redirect('/');
        $items = $orderModel->getItems($order['order_id']);
        $this->clientView('order-success', ['order' => $order, 'items' => $items, 'pageTitle' => 'Order Placed!']);
    }
}
