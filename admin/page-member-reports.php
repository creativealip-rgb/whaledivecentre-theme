<?php
/**
 * Spending Reports - Member Spending Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

// Date range filter
$first_booking = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => 1,
    'post_status' => 'any',
    'orderby' => 'date',
    'order' => 'ASC',
]);
$default_start = !empty($first_booking) ? date('Y-m-d', strtotime($first_booking[0]->post_date)) : date('Y-m-01');

if (!function_exists('contenly_normalize_admin_date')) {
    function contenly_normalize_admin_date($raw, $fallback) {
        $raw = trim((string)$raw);
        if ($raw === '') return $fallback;
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y'];
        foreach ($formats as $fmt) {
            $dt = DateTime::createFromFormat($fmt, $raw);
            if ($dt instanceof DateTime) return $dt->format('Y-m-d');
        }
        $ts = strtotime($raw);
        return $ts ? date('Y-m-d', $ts) : $fallback;
    }
}

$start_date = contenly_normalize_admin_date($_GET['start_date'] ?? '', $default_start);
$end_date = contenly_normalize_admin_date($_GET['end_date'] ?? '', date('Y-m-d'));

// Get all bookings in date range
$all_bookings = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'meta_query' => [
        [
            'key' => '_booking_status',
            'value' => ['paid', 'confirmed', 'completed'],
            'compare' => 'IN'
        ]
    ],
    'date_query' => [
        [
            'after' => $start_date,
            'before' => $end_date,
            'inclusive' => true
        ]
    ]
]);

// Calculate metrics
$total_revenue = 0;
$total_bookings = count($all_bookings);
$member_spending = [];
$monthly_revenue = [];

foreach ($all_bookings as $booking) {
    $user_id = get_post_meta($booking->ID, '_user_id', true);
    $total = get_post_meta($booking->ID, '_total_amount', true);
    if ($total === '' || $total === null) $total = get_post_meta($booking->ID, '_total_price', true);
    if ($total === '' || $total === null) $total = get_post_meta($booking->ID, '_price', true);
    $booking_date = $booking->post_date;
    
    if ($total !== '' && $total !== null) {
        $total_revenue += (float) $total;
        
        // Per member spending
        if (!isset($member_spending[$user_id])) {
            $member_spending[$user_id] = 0;
        }
        $member_spending[$user_id] += (float) $total;
        
        // Monthly revenue
        $month = date('Y-m', strtotime($booking_date));
        if (!isset($monthly_revenue[$month])) {
            $monthly_revenue[$month] = 0;
        }
        $monthly_revenue[$month] += (float) $total;
    }
}

// Get member details
$member_details = [];
foreach ($member_spending as $user_id => $spending) {
    $user = get_userdata($user_id);
    if ($user) {
        $tier = contenly_get_tier_from_spending($spending);
        $member_details[$user_id] = [
            'name' => $user->display_name,
            'email' => $user->user_email,
            'tier' => $tier,
            'spending' => $spending
        ];
    }
}

// Sort by spending (numeric DESC)
uasort($member_details, function($a, $b) {
    return ($b['spending'] ?? 0) <=> ($a['spending'] ?? 0);
});

// Average spending per member
$avg_spending = count($member_details) > 0 ? $total_revenue / count($member_details) : 0;
?>

<div class="wrap">
    <h1 style="font-size: 2em; font-weight: 600; margin-bottom: 20px;">📈 Spending Reports</h1>
    
    <!-- Date Filter -->
    <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
        <form method="GET" action="" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: end;">
            <input type="hidden" name="page" value="tmpb-member-reports">
            
            <div>
                <label style="display: block; font-size: 13px; color: #64748b; margin-bottom: 6px; font-weight: 600;">Start Date</label>
                <input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>"
                       style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; font-size: 13px; color: #64748b; margin-bottom: 6px; font-weight: 600;">End Date</label>
                <input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>"
                       style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
            </div>
            
            <button type="submit" style="padding: 10px 24px; background: #539294; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; height: 42px;">
                📊 Apply Filter
            </button>
            
            <a href="?page=tmpb-member-reports&start_date=<?php echo esc_attr($default_start); ?>&end_date=<?php echo date('Y-m-d'); ?>"
               style="padding: 10px 24px; background: #f1f5f9; color: #64748b; text-decoration: none; border-radius: 8px; font-weight: 600; height: 42px; display: inline-flex; align-items: center;">
                Reset
            </a>
            <a href="?page=tmpb-member-reports&start_date=<?php echo date('Y-m-01'); ?>&end_date=<?php echo date('Y-m-d'); ?>"
               style="padding: 10px 16px; background: #eef2ff; color: #3730a3; text-decoration: none; border-radius: 8px; font-weight: 600; height: 42px; display: inline-flex; align-items: center;">
                This Month
            </a>
            <a href="?page=tmpb-member-reports&start_date=<?php echo esc_attr($default_start); ?>&end_date=<?php echo date('Y-m-d'); ?>"
               style="padding: 10px 16px; background: #ecfeff; color: #155e75; text-decoration: none; border-radius: 8px; font-weight: 600; height: 42px; display: inline-flex; align-items: center;">
                All Time
            </a>
            
            <a href="?page=tmpb-member-reports&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&export=csv"
               style="padding: 10px 24px; background: #059669; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; height: 42px; display: inline-flex; align-items: center;">
                📥 Export CSV
            </a>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <?php if (empty($all_bookings)) : ?>
    <div style="background:#fff7ed;border:1px solid #fdba74;color:#9a3412;padding:12px 14px;border-radius:10px;margin-bottom:16px;">ℹ️ No paid-like bookings found in selected range.</div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Revenue</div>
            <div style="font-size: 32px; font-weight: 700;">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;"><?php echo date('M d', strtotime($start_date)); ?> - <?php echo date('M d', strtotime($end_date)); ?></div>
        </div>
        
        <div style="background: linear-gradient(135deg, #539294, #539294); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Bookings</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $total_bookings; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Confirmed & Paid</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Active Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo count($member_details); ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Made purchases</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Avg Spending</div>
            <div style="font-size: 28px; font-weight: 700;">Rp <?php echo number_format($avg_spending, 0, ',', '.'); ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Per member</div>
        </div>
    </div>
    
    <!-- Two Column Layout -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Top Spenders -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #0f172a;">🏆 Top 10 Spenders</h2>
            
            <?php 
            $top_spenders = array_slice($member_details, 0, 10, true);
            if (empty($top_spenders)): 
            ?>
                <p style="color: #64748b; text-align: center; padding: 20px;">No spending data in this period</p>
            <?php else: ?>
                <?php 
                $rank = 1;
                foreach ($top_spenders as $id => $data): 
                    $tier_badges = [
                        'silver' => '🥈',
                        'gold' => '🥇',
                        'platinum' => '💎'
                    ];
                ?>
                <div style="display: flex; align-items: center; padding: 12px 0; border-bottom: <?php echo $rank < 10 ? '1px solid #f1f5f9' : 'none'; ?>">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: <?php echo $rank === 1 ? '#fbbf24' : ($rank === 2 ? '#94a3b8' : ($rank === 3 ? '#b45309' : '#e2e8f0')); ?>; color: <?php echo $rank <= 3 ? 'white' : '#64748b'; ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 12px;">
                        <?php echo $rank; ?>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #0f172a;">
                            <?php echo $tier_badges[$data['tier']]; ?> <?php echo esc_html($data['name']); ?>
                        </div>
                        <div style="font-size: 12px; color: #64748b;"><?php echo esc_html($data['email']); ?></div>
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
        
        <!-- Monthly Revenue -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #0f172a;">📊 Monthly Breakdown</h2>
            
            <?php if (empty($monthly_revenue)): ?>
                <p style="color: #64748b; text-align: center; padding: 20px;">No revenue data</p>
            <?php else: ?>
                <?php 
                arsort($monthly_revenue);
                foreach ($monthly_revenue as $month => $revenue): 
                    $percentage = $total_revenue > 0 ? ($revenue / $total_revenue * 100) : 0;
                ?>
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600; color: #475569;"><?php echo date('F Y', strtotime($month . '-01')); ?></span>
                        <span style="color: #64748b;">Rp <?php echo number_format($revenue, 0, ',', '.'); ?> (<?php echo round($percentage, 1); ?>%)</span>
                    </div>
                    <div style="background: #f1f5f9; border-radius: 9999px; height: 8px; overflow: hidden;">
                        <div style="width: <?php echo $percentage; ?>%; background: linear-gradient(90deg, #539294, #539294); height: 100%; border-radius: 9999px;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- All Member Spending Table -->
    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; font-weight: 600; color: #0f172a; margin: 0;">📋 All Member Spending</h2>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Rank</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Member</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Tier</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Total Spending</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">% of Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                foreach ($member_details as $id => $data): 
                    $percentage = $total_revenue > 0 ? ($data['spending'] / $total_revenue * 100) : 0;
                    $tier_badges = [
                        'silver' => ['label' => '🥈 Silver', 'color' => '#94a3b8', 'bg' => '#f1f5f9'],
                        'gold' => ['label' => '🥇 Gold', 'color' => '#fbbf24', 'bg' => '#fef3c7'],
                        'platinum' => ['label' => '💎 Platinum', 'color' => '#355F72', 'bg' => '#EEF5F4']
                    ];
                    $tier_info = $tier_badges[$data['tier']] ?? $tier_badges['silver'];
                ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px; font-weight: 700; color: #64748b;">#<?php echo $rank; ?></td>
                    <td style="padding: 16px;">
                        <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html($data['name']); ?></div>
                        <div style="font-size: 12px; color: #64748b;"><?php echo esc_html($data['email']); ?></div>
                    </td>
                    <td style="padding: 16px;">
                        <span style="padding: 6px 12px; background: <?php echo $tier_info['bg']; ?>; color: <?php echo $tier_info['color']; ?>; border-radius: 9999px; font-weight: 600; font-size: 13px;">
                            <?php echo $tier_info['label']; ?>
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        <span style="font-weight: 700; color: #059669;">
                            Rp <?php echo number_format($data['spending'], 0, ',', '.'); ?>
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="flex: 1; background: #f1f5f9; border-radius: 9999px; height: 6px; overflow: hidden; max-width: 100px;">
                                <div style="width: <?php echo $percentage; ?>%; background: #539294; height: 100%;"></div>
                            </div>
                            <span style="font-size: 13px; color: #64748b;"><?php echo round($percentage, 1); ?>%</span>
                        </div>
                    </td>
                </tr>
                <?php 
                $rank++;
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.wrap { margin-top: 20px; }
</style>
