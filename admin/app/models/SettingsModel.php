<?php
require_once __DIR__ . '/../../core/Model.php';

class SettingsModel extends Model
{

    /**
     * Get all settings from a specific table
     */
    public function getAllSettings(string $table): array
    {
        $sql = "SELECT * FROM {$table} ORDER BY setting_key ASC";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Get a single setting value
     */
    public function getSetting(string $table, string $key): ?string
    {
        $sql = "SELECT setting_value FROM {$table} WHERE setting_key = :key LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['setting_value'] : null;
    }

    /**
     * Update or create a setting
     */
    public function setSetting(string $table, string $key, ?string $value): bool
    {
        // Check if setting exists
        $sql = "SELECT id FROM {$table} WHERE setting_key = :key LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':key' => $key]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // Update existing
            $sql = "UPDATE {$table} SET setting_value = :value WHERE setting_key = :key";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':value' => $value, ':key' => $key]);
        } else {
            // Insert new
            $sql = "INSERT INTO {$table} (setting_key, setting_value) VALUES (:key, :value)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':key' => $key, ':value' => $value]);
        }
    }

    /**
     * Batch update settings
     */
    public function updateSettings(string $table, array $settings): bool
    {
        $success = true;
        foreach ($settings as $key => $value) {
            if (!$this->setSetting($table, $key, $value)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Delete a setting
     */
    public function deleteSetting(string $table, string $key): bool
    {
        $sql = "DELETE FROM {$table} WHERE setting_key = :key";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':key' => $key]);
    }
    
    // ============ Shop Settings Specific Methods ============

    /**
     * Get all shop settings
     */
    public function getShopSettings(): array
    {
        return $this->getAllSettings('shop_settings');
    }

    /**
     * Update shop settings
     */
    public function updateShopSettings(array $settings): bool
    {
        return $this->updateSettings('shop_settings', $settings);
    }

    /**
     * Get shop setting value
     */
    public function getShopSetting(string $key): ?string
    {
        return $this->getSetting('shop_settings', $key);
    }
    
    // ============ Site Settings Specific Methods ============

    /**
     * Get all site settings
     */
    public function getSiteSettings(): array
    {
        return $this->getAllSettings('site_settings');
    }

    /**
     * Update site settings
     */
    public function updateSiteSettings(array $settings): bool
    {
        return $this->updateSettings('site_settings', $settings);
    }

    /**
     * Get site setting value
     */
    public function getSiteSetting(string $key): ?string
    {
        return $this->getSetting('site_settings', $key);
    }
    
    // ============ Initialize Default Settings ============

    /**
     * Initialize default shop settings
     */
    public function initShopSettings(): void
    {
        $defaults = [
            'shop_name' => 'My Online Shop',
            'shop_email' => 'info@myshop.com',
            'shop_phone' => '+880123456789',
            'shop_address' => 'Dhaka, Bangladesh',
            'currency' => 'BDT',
            'currency_symbol' => '৳',
            'tax_percentage' => '0',
            'minimum_order_amount' => '0',
            'max_quantity_per_order' => '100',
            'allow_backorder' => '0',
            'enable_reviews' => '1',
            'auto_approve_reviews' => '0',
            'items_per_page' => '20',
            'enable_wishlist' => '1',
            'enable_compare' => '1',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'meta_title' => 'Online Shop - Best Products',
            'meta_description' => 'Shop the best products at affordable prices',
            'meta_keywords' => 'shop, online, products, store',
            'footer_text' => '© 2024 My Online Shop. All rights reserved.',
            'order_prefix' => 'ORD',
            'invoice_prefix' => 'INV',
            'low_stock_threshold' => '10'
        ];

        foreach ($defaults as $key => $value) {
            if ($this->getShopSetting($key) === null) {
                $this->setSetting('shop_settings', $key, $value);
            }
        }
    }

    /**
     * Initialize default site settings
     */
    public function initSiteSettings(): void
    {
        $defaults = [
            'site_name' => 'My E-commerce Site',
            'site_tagline' => 'Best Online Shopping Experience',
            'site_email' => 'admin@mysite.com',
            'site_phone' => '+880123456789',
            'site_address' => 'Dhaka, Bangladesh',
            'site_logo' => '',
            'site_favicon' => '',
            'timezone' => 'Asia/Dhaka',
            'date_format' => 'd M, Y',
            'time_format' => 'h:i A',
            'maintenance_mode' => '0',
            'maintenance_message' => 'Site is under maintenance. Please check back later.',
            'contact_email' => 'contact@mysite.com',
            'contact_phone' => '+880123456789',
            'contact_address' => 'Dhaka, Bangladesh',
            'google_analytics_id' => '',
            'facebook_pixel_id' => '',
            'header_scripts' => '',
            'footer_scripts' => '',
            'cookie_consent_enabled' => '1',
            'cookie_consent_message' => 'We use cookies to enhance your experience.',
            'privacy_policy_url' => '',
            'terms_url' => '',
            'about_us' => 'Welcome to our online store. We provide the best quality products.',
            'shipping_policy' => 'We deliver nationwide within 3-5 business days.',
            'return_policy' => '30 days return policy for unused items.',
            'payment_methods' => 'bkash, nagad, credit_card',
            'bkash_number' => '',
            'nagad_number' => '',
            'rocket_number' => '',
            'bank_account_name' => '',
            'bank_account_number' => '',
            'bank_name' => ''
        ];

        foreach ($defaults as $key => $value) {
            if ($this->getSiteSetting($key) === null) {
                $this->setSetting('site_settings', $key, $value);
            }
        }
    }
}
