<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/SettingsModel.php';

class SettingsController extends Controller
{

    private SettingsModel $model;

    public function __construct()
    {
        $this->model = new SettingsModel();
        // Initialize default settings if not exists
        $this->model->initShopSettings();
        $this->model->initSiteSettings();
    }

    /**
     * Shop Settings Page
     */
    public function shop(): void
    {
        $this->requireAuth();

        $settings = $this->model->getShopSettings();
        $pageTitle = 'Shop Settings';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('settings', 'pageTitle', 'csrf')
            + ['content_view' => '../settings/shop']);
    }

    /**
     * Update Shop Settings
     */
    public function updateShop(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        // Collect all settings from POST
        $settings = [];
        foreach ($_POST as $key => $value) {
            if ($key !== 'csrf_token' && $key !== 'submit') {
                // Handle array values (like checkboxes)
                if (is_array($value)) {
                    // Convert array to comma-separated string
                    $settings[$key] = implode(',', $value);
                } else {
                    $settings[$key] = $this->sanitize($value);
                }
            }
        }

        $result = $this->model->updateShopSettings($settings);

        if ($result) {
            $_SESSION['success_message'] = 'Shop settings updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update shop settings. Please try again.';
        }

        $this->redirect('settings/shop');
    }

    /**
     * Site Settings Page
     */
    public function site(): void
    {
        $this->requireAuth();

        // Parse payment methods back to array for checkboxes
        $settings = $this->model->getSiteSettings();
        if (isset($settings['payment_methods'])) {
            $settings['payment_methods_array'] = explode(',', $settings['payment_methods']);
        }

        $pageTitle = 'Site Settings';
        $csrf = $this->csrfToken();

        $this->view('layouts/main', compact('settings', 'pageTitle', 'csrf')
            + ['content_view' => '../settings/site']);
    }
    

    /**
     * Update Site Settings
     */
    public function updateSite(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        // Collect all settings from POST
        $settings = [];
        foreach ($_POST as $key => $value) {
            if ($key !== 'csrf_token' && $key !== 'submit') {
                // Handle array values (like checkboxes)
                if (is_array($value)) {
                    // Convert array to comma-separated string
                    $settings[$key] = implode(',', $value);
                } else {
                    $settings[$key] = $this->sanitize($value);
                }
            }
        }

        $result = $this->model->updateSiteSettings($settings);

        if ($result) {
            $_SESSION['success_message'] = 'Site settings updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to update site settings. Please try again.';
        }

        $this->redirect('settings/site');
    }

    
}
