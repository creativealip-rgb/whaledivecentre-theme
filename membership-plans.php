<?php
/**
 * Membership Plans Configuration
 */

if (!defined('ABSPATH')) {
    exit;
}

class TMP_Membership_Plans {
    
    public static function get_plans() {
        return [
            'silver' => [
                'id' => 'silver',
                'name' => 'Silver',
                'price' => 0,
                'price_display' => 'Rp 0',
                'icon' => '🥈',
                'color' => '#94a3b8',
                'discount' => 0,
                'benefits' => [
                    '✓ Browse all tours',
                    '✓ Standard booking',
                    '✓ Update via email',
                    '✓ Add unlimited destinations',
                ],
            ],
            'gold' => [
                'id' => 'gold',
                'name' => 'Gold',
                'price' => 0,
                'price_display' => 'Rp 0',
                'billing' => '/month',
                'icon' => '🥇',
                'color' => '#fbbf24',
                'discount' => 5,
                'benefits' => [
                    '✓ 5% discount on all tours',
                    '✓ Review email lebih cepat',
                    '✓ Free cancellation (24h)',
                    '✓ Price alerts',
                    '✓ No booking fees',
                ],
                'requirements' => ['spending' => 5000000],
            ],
            'platinum' => [
                'id' => 'platinum',
                'name' => 'Platinum',
                'price' => 0,
                'price_display' => 'Rp 0',
                'billing' => '/month',
                'icon' => '💎',
                'color' => '#355F72',
                'discount' => 10,
                'benefits' => [
                    '✓ 10% discount on all tours',
                    '✓ Priority handling admin',
                    '✓ Free cancellation (48h)',
                    '✓ Room upgrades',
                    '✓ Exclusive deals',
                    '✓ Travel insurance',
                    '✓ Birthday rewards',
                ],
                'requirements' => ['spending' => 15000000],
            ],
        ];
    }
    
    public static function get_discount($tier) {
        $plans = self::get_plans();
        return $plans[$tier]['discount'] ?? 0;
    }
    
    public static function get_next_tier($current) {
        $order = ['silver' => 0, 'gold' => 1, 'platinum' => 2];
        $level = $order[$current] ?? 0;
        if ($level >= 2) return null;
        return array_search($level + 1, $order);
    }
}
