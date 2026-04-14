<?php
require_once __DIR__ . '/../../core/Model.php';

class DeliverySettingsModel extends Model
{

    protected string $table = 'delivery_settings';
    protected string $primaryKey = 'id';

    /**
     * Get delivery settings (always id = 1)
     */
    public function getSettings(): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = 1 LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no settings exist, create default
        if (!$result) {
            $this->createDefaultSettings();
            return $this->getSettings();
        }

        return $result;
    }

    /**
     * Create default delivery settings
     */
    private function createDefaultSettings(): void
    {
        $sql = "INSERT INTO {$this->table} (id, inside_dhaka_charge, outside_dhaka_charge, free_delivery_min_amount, express_delivery_charge) 
                VALUES (1, 60.00, 120.00, 2000.00, 150.00)";
        $this->db->exec($sql);
    }

    /**
     * Update delivery settings
     */
    public function updateSettings(array $data): bool
    {
        $sql = "UPDATE {$this->table} SET 
                inside_dhaka_charge = :inside_dhaka_charge,
                outside_dhaka_charge = :outside_dhaka_charge,
                free_delivery_min_amount = :free_delivery_min_amount,
                express_delivery_charge = :express_delivery_charge
                WHERE id = 1";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':inside_dhaka_charge' => $data['inside_dhaka_charge'],
            ':outside_dhaka_charge' => $data['outside_dhaka_charge'],
            ':free_delivery_min_amount' => $data['free_delivery_min_amount'],
            ':express_delivery_charge' => $data['express_delivery_charge']
        ]);
    }

    /**
     * Calculate delivery charge based on location and order amount
     */
    public function calculateDeliveryCharge(string $location, float $orderAmount, string $deliveryType = 'standard'): float
    {
        $settings = $this->getSettings();

        // Check for free delivery
        if ($orderAmount >= $settings['free_delivery_min_amount']) {
            return 0;
        }

        // Express delivery charge
        if ($deliveryType === 'express') {
            return (float)$settings['express_delivery_charge'];
        }

        // Standard delivery based on location
        if (strtolower($location) === 'dhaka' || strtolower($location) === 'inside dhaka') {
            return (float)$settings['inside_dhaka_charge'];
        }

        return (float)$settings['outside_dhaka_charge'];
    }

    /**
     * Get delivery charge breakdown for display
     */
    public function getChargeBreakdown(): array
    {
        $settings = $this->getSettings();

        return [
            'inside_dhaka' => (float)$settings['inside_dhaka_charge'],
            'outside_dhaka' => (float)$settings['outside_dhaka_charge'],
            'express' => (float)$settings['express_delivery_charge'],
            'free_delivery_threshold' => (float)$settings['free_delivery_min_amount']
        ];
    }
}
