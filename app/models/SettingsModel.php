<?php
require_once __DIR__.'/../../core/Model.php';
class SettingsModel extends Model {
    private array $shopCache = [];
    private array $deliveryCache = [];

    public function getShop(): array {
        if(empty($this->shopCache)){
            $rows = $this->fetchAll('SELECT setting_key,setting_value FROM shop_settings');
            foreach($rows as $r) $this->shopCache[$r['setting_key']] = $r['setting_value'];
        }
        return $this->shopCache;
    }

    public function getDelivery(): array {
        if(empty($this->deliveryCache)){
            $r = $this->fetchOne('SELECT * FROM delivery_settings WHERE id=1');
            $this->deliveryCache = $r ?? [];
        }
        return $this->deliveryCache;
    }

    public function getCategories(): array {
        return $this->fetchAll(
            'SELECT c.*, GROUP_CONCAT(sc.id,\':\',sc.name,\':\',sc.slug ORDER BY sc.sort_order SEPARATOR \'|\') as subs
             FROM categories c
             LEFT JOIN sub_categories sc ON sc.category_id=c.id AND sc.is_active=1
             WHERE c.is_active=1 GROUP BY c.id ORDER BY c.sort_order ASC');
    }
}
