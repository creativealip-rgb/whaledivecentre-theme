<?php
/**
 * Member Dashboard - Overview Stats
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get all members
$all_members = get_users(['orderby' => 'registered', 'order' => 'DESC']);
$total_members = count($all_members);

// Get tier distribution
$tier_counts = ['silver' => 0, 'gold' => 0, 'platinum' => 0];
foreach ($all_members as $member) {
    $tier = contenly_get_tier_from_spending(contenly_get_user_total_spending($member->ID));
    if (isset($tier_counts[$tier])) {
        $tier_counts[$tier]++;
    }
}

// Get recent upgrades
$recent_upgrades = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => 5,
    'post_status' => 'any',
    'meta_query' => [
        [
            'key' => '_booking_status',
            'value' => ['paid','confirmed','completed'],
            'compare' => 'IN'
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Calculate total revenue from bookings
$total_revenue = 0;
$all_bookings = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'meta_query' => [
        [
            'key' => '_booking_status',
            'value' => contenly_paid_like_statuses(),
            'compare' => 'IN'
        ]
    ]
]);
foreach ($all_bookings as $booking) {
    $total_revenue += contenly_booking_total_amount($booking->ID);
}

// Get top spenders
$member_spending = [];
foreach ($all_members as $member) {
    $bookings = get_posts([
        'post_type' => 'tour_booking',
        'posts_per_page' => -1,
        'post_status' => 'any',
        'meta_query' => [
            [
                'key' => '_user_id',
                'value' => $member->ID,
                'compare' => '='
            ],
            [
                'key' => '_booking_status',
                'value' => contenly_paid_like_statuses(),
                'compare' => 'IN'
            ]
        ]
    ]);
    
    $spending = 0;
    foreach ($bookings as $booking) {
        $spending += contenly_booking_total_amount($booking->ID);
    }
    
    if ($spending > 0) {
        $member_spending[$member->ID] = [
            'name' => $member->display_name,
            'email' => $member->user_email,
            'tier' => contenly_get_tier_from_spending($spending),
            'spending' => $spending
        ];
    }
}

// Sort by spending (numeric DESC)
uasort($member_spending, function($a, $b) {
    return ($b['spending'] ?? 0) <=> ($a['spending'] ?? 0);
});
$top_spenders = array_slice($member_spending, 0, 5, true);
?>

<div class="wrap">
    <h1 style="font-size: 2em; font-weight: 600; margin-bottom: 20px;">📊 Member Dashboard</h1>
    
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <!-- Total Members -->
        <div style="background: linear-gradient(135deg, #539294, #539294); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $total_members; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Registered users</div>
        </div>
        
        <!-- Platinum Members -->
        <div style="background: linear-gradient(135deg, #fbbf24, #f59e0b); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">💎 Platinum Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $tier_counts['platinum']; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Highest tier</div>
        </div>
        
        <!-- Gold Members -->
        <div style="background: linear-gradient(135deg, #94a3b8, #64748b); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">🥇 Gold Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $tier_counts['gold']; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Mid tier</div>
        </div>
        
        <!-- Total Revenue -->
        <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">💰 Total Revenue</div>
            <div style="font-size: 28px; font-weight: 700;">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">From all bookings</div>
        </div>
    </div>
    
    <!-- Two Column Layout -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Tier Distribution -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #0f172a;">📊 Tier Distribution</h2>
            
            <?php
            $tiers = [
                'silver' => ['name' => 'Silver', 'color' => '#94a3b8', 'icon' => '🥈'],
                'gold' => ['name' => 'Gold', 'color' => '#fbbf24', 'icon' => '🥇'],
                'platinum' => ['name' => 'Platinum', 'color' => '#355F72', 'icon' => '💎']
            ];
            
            foreach ($tiers as $key => $tier):
                $percentage = $total_members > 0 ? ($tier_counts[$key] / $total_members * 100) : 0;
            ?>
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-weight: 500; color: #475569;">
                        <?php echo $tier['icon']; ?> <?php echo $tier['name']; ?>
                    </span>
                    <span style="color: #64748b;"><?php echo $tier_counts[$key]; ?> (<?php echo round($percentage, 1); ?>%)</span>
                </div>
                <div style="background: #f1f5f9; border-radius: 9999px; height: 8px; overflow: hidden;">
                    <div style="width: <?php echo $percentage; ?>%; background: <?php echo $tier['color']; ?>; height: 100%; border-radius: 9999px;"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Top Spenders -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #0f172a;">🏆 Top 5 Spenders</h2>
            
            <?php if (empty($top_spenders)): ?>
                <p style="color: #64748b; text-align: center; padding: 20px;">No spending data yet</p>
            <?php else: ?>
                <?php 
                $rank = 1;
                foreach ($top_spenders as $id => $data): 
                ?>
                <div style="display: flex; align-items: center; padding: 12px 0; border-bottom: <?php echo $rank < 5 ? '1px solid #f1f5f9' : 'none'; ?>">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: <?php echo $rank === 1 ? '#fbbf24' : ($rank === 2 ? '#94a3b8' : '#b45309'); ?>; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 12px;">
                        <?php echo $rank; ?>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html($data['name']); ?></div>
                        <div style="font-size: 12px; color: #64748b;"><?php echo esc_html($data['email']); ?> • <?php echo ucfirst($data['tier']); ?></div>
                    </div>
                    <div style="font-weight: 700; color: #059669;">
                        Rp <?php echo number_format($data['spending'], 0, ',', '.'); ?>
                    </div>
                </div>
                <?php 
                $rank++;
                endforeach; 
                ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 20px;">
        <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #0f172a;">📋 Recent Bookings</h2>
        
        <?php if (empty($recent_upgrades)): ?>
            <p style="color: #64748b; text-align: center; padding: 20px;">No recent bookings</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="text-align: left; padding: 12px; color: #64748b; font-weight: 600;">Booking ID</th>
                        <th style="text-align: left; padding: 12px; color: #64748b; font-weight: 600;">Customer</th>
                        <th style="text-align: left; padding: 12px; color: #64748b; font-weight: 600;">Tour</th>
                        <th style="text-align: left; padding: 12px; color: #64748b; font-weight: 600;">Amount</th>
                        <th style="text-align: left; padding: 12px; color: #64748b; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_upgrades as $booking): 
                        $user_id = get_post_meta($booking->ID, '_user_id', true);
                        $user = get_userdata($user_id);
                        $tour_id = get_post_meta($booking->ID, '_tour_id', true);
                        $tour = get_post($tour_id);
                        $total = get_post_meta($booking->ID, '_total_amount', true);
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px; color: #0f172a;">#<?php echo $booking->ID; ?></td>
                        <td style="padding: 12px; color: #0f172a;">
                            <?php echo $user ? $user->display_name : 'N/A'; ?>
                        </td>
                        <td style="padding: 12px; color: #0f172a;">
                            <?php echo $tour ? $tour->post_title : 'N/A'; ?>
                        </td>
                        <td style="padding: 12px; color: #059669; font-weight: 600;">
                            Rp <?php echo number_format($total, 0, ',', '.'); ?>
                        </td>
                        <td style="padding: 12px; color: #64748b;">
                            <?php echo date_i18n('M d, Y', strtotime($booking->post_date)); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
.wrap { margin-top: 20px; }
</style>
