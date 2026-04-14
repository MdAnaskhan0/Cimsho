<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/DeliverySettingsModel.php';

class DeliverySettingsController extends Controller
{

    private DeliverySettingsModel $model;

    public function __construct()
    {
        $this->model = new DeliverySettingsModel();
    }

    /**
     * Show delivery settings page
     */
    public function index(): void
    {
        $this->requireAuth();

        $settings = $this->model->getSettings();
        $pageTitle = 'Delivery Settings';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('settings', 'pageTitle', 'csrf')
            + ['content_view' => '../settings/delivery']);
    }

    /**
     * Update delivery settings
     */
    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $insideDhakaCharge = (float)($_POST['inside_dhaka_charge'] ?? 0);
        $outsideDhakaCharge = (float)($_POST['outside_dhaka_charge'] ?? 0);
        $freeDeliveryMinAmount = (float)($_POST['free_delivery_min_amount'] ?? 0);
        $expressDeliveryCharge = (float)($_POST['express_delivery_charge'] ?? 0);

        // Validation
        $errors = [];
        if ($insideDhakaCharge < 0) {
            $errors[] = 'Inside Dhaka delivery charge cannot be negative';
        }
        if ($outsideDhakaCharge < 0) {
            $errors[] = 'Outside Dhaka delivery charge cannot be negative';
        }
        if ($freeDeliveryMinAmount < 0) {
            $errors[] = 'Free delivery minimum amount cannot be negative';
        }
        if ($expressDeliveryCharge < 0) {
            $errors[] = 'Express delivery charge cannot be negative';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $this->redirect('settings/delivery');
            return;
        }

        $data = [
            'inside_dhaka_charge' => $insideDhakaCharge,
            'outside_dhaka_charge' => $outsideDhakaCharge,
            'free_delivery_min_amount' => $freeDeliveryMinAmount,
            'express_delivery_charge' => $expressDeliveryCharge
        ];

        $result = $this->model->updateSettings($data);

        if ($result) {
            $_SESSION['success_message'] = 'Delivery settings updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update delivery settings. Please try again.';
        }

        $this->redirect('settings/delivery');
    }

    /**
     * Calculate delivery charge preview (AJAX)
     */
    public function calculatePreview(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json');

        $location = $_POST['location'] ?? 'dhaka';
        $orderAmount = (float)($_POST['order_amount'] ?? 0);
        $deliveryType = $_POST['delivery_type'] ?? 'standard';

        $charge = $this->model->calculateDeliveryCharge($location, $orderAmount, $deliveryType);
        $settings = $this->model->getSettings();

        echo json_encode([
            'success' => true,
            'charge' => $charge,
            'formatted_charge' => '৳ ' . number_format($charge, 2),
            'is_free' => $charge == 0,
            'free_delivery_threshold' => $settings['free_delivery_min_amount'],
            'remaining_for_free' => $orderAmount < $settings['free_delivery_min_amount']
                ? $settings['free_delivery_min_amount'] - $orderAmount
                : 0
        ]);
        exit;
    }
}
