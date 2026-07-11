<?php
/**
 * All Members - List of Registered Members
 */

if (!defined('ABSPATH')) {
    exit;
}

// Pagination
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = 20;

// Get all members with pagination
$args = [
    'orderby' => 'registered',
    'order' => 'DESC',
    'number' => $per_page,
    'paged' => $paged
];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $args['search'] = '*' . sanitize_text_field($_GET['search']) . '*';
}

if (isset($_GET['tier']) && !empty($_GET['tier'])) {
    // Will filter after query
    $tier_filter = sanitize_text_field($_GET['tier']);
}

$all_members = get_users($args);
$total_users = count_users()['total_users'];
$total_pages = ceil($total_users / $per_page);

// Handle tier filtering
if (isset($tier_filter) && !empty($tier_filter)) {
    $all_members = array_filter($all_members, function($user) use ($tier_filter) {
        $tier = contenly_get_tier_from_spending(contenly_get_user_total_spending($user->ID));
        return $tier === $tier_filter;
    });
}
?>

<div class="wrap">
    <h1 style="font-size: 2em; font-weight: 600; margin-bottom: 20px;">👥 All Members</h1>
    
    <!-- Search & Filter -->
    <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
        <form method="GET" action="" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <input type="hidden" name="page" value="tmpb-member-all">
            
            <input type="text" name="search" placeholder="Search by name or email..." 
                   value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>"
                   style="flex: 1; min-width: 200px; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
            
            <select name="tier" style="padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; min-width: 150px;">
                <option value="">All Tiers</option>
                <option value="silver" <?php selected(isset($_GET['tier']) ? $_GET['tier'] : '', 'silver'); ?>>🥈 Silver</option>
                <option value="gold" <?php selected(isset($_GET['tier']) ? $_GET['tier'] : '', 'gold'); ?>>🥇 Gold</option>
                <option value="platinum" <?php selected(isset($_GET['tier']) ? $_GET['tier'] : '', 'platinum'); ?>>💎 Platinum</option>
            </select>
            
            <button type="submit" style="padding: 10px 24px; background: #539294; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                🔍 Search
            </button>
            
            <?php if (isset($_GET['search']) || isset($_GET['tier'])): ?>
            <a href="?page=tmpb-member-all" style="padding: 10px 24px; background: #f1f5f9; color: #64748b; text-decoration: none; border-radius: 8px; font-weight: 600;">
                Clear
            </a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Members Table -->
    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Member</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Email</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Tier</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Joined</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Bookings</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Total Spending</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($all_members)): ?>
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #64748b;">
                        No members found
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($all_members as $member): 
                        $tier = contenly_get_tier_from_spending(contenly_get_user_total_spending($member->ID));
                        $points = get_user_meta($member->ID, '_tmp_reward_points', true) ?: 0;
                        
                        // Get booking count and spending
                        $bookings = get_posts([
                            'post_type' => 'tour_booking',
                            'posts_per_page' => -1,
                            'post_status' => 'any',
                            'meta_query' => [
                                [
                                    'key' => '_user_id',
                                    'value' => $member->ID,
                                    'compare' => '='
                                ]
                            ]
                        ]);
                        $booking_count = count($bookings);
                        
                        $total_spending = 0;
                        foreach ($bookings as $booking) {
                            $status = get_post_meta($booking->ID, '_booking_status', true);
                            if (in_array($status, ['paid', 'confirmed', 'completed'])) {
                                $total = get_post_meta($booking->ID, '_total_amount', true);
                                if ($total === '' || $total === null) $total = get_post_meta($booking->ID, '_total_price', true);
                                if ($total === '' || $total === null) $total = get_post_meta($booking->ID, '_price', true);
                                if ($total !== '' && $total !== null) {
                                    $total_spending += (float) $total;
                                }
                            }
                        }
                        
                        $tier_badges = [
                            'silver' => ['label' => '🥈 Silver', 'color' => '#94a3b8', 'bg' => '#f1f5f9'],
                            'gold' => ['label' => '🥇 Gold', 'color' => '#fbbf24', 'bg' => '#fef3c7'],
                            'platinum' => ['label' => '💎 Platinum', 'color' => '#355F72', 'bg' => '#EEF5F4']
                        ];
                        $tier_info = $tier_badges[$tier] ?? $tier_badges['silver'];
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #539294, #539294); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                                    <?php echo strtoupper(substr($member->display_name, 0, 1)); ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html($member->display_name); ?></div>
                                    <div style="font-size: 12px; color: #64748b;">ID: <?php echo $member->ID; ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px; color: #475569;">
                            <?php echo esc_html($member->user_email); ?>
                        </td>
                        <td style="padding: 16px;">
                            <span style="padding: 6px 12px; background: <?php echo $tier_info['bg']; ?>; color: <?php echo $tier_info['color']; ?>; border-radius: 9999px; font-weight: 600; font-size: 13px;">
                                <?php echo $tier_info['label']; ?>
                            </span>
                        </td>
                        <td style="padding: 16px; color: #64748b;">
                            <?php echo date_i18n('M d, Y', strtotime($member->user_registered)); ?>
                        </td>
                        <td style="padding: 16px;">
                            <span style="font-weight: 600; color: #0f172a;"><?php echo $booking_count; ?></span>
                        </td>
                        <td style="padding: 16px;">
                            <span style="font-weight: 700; color: #059669;">
                                Rp <?php echo number_format($total_spending, 0, ',', '.'); ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="<?php echo admin_url('user-edit.php?user_id=' . $member->ID); ?>" 
                                   style="padding: 6px 12px; background: #EEF5F4; color: #539294; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                    Edit
                                </a>
                                <a href="?page=tmpb-member-all&action=view&user_id=<?php echo $member->ID; ?>" 
                                   style="padding: 6px 12px; background: #f0fdf4; color: #059669; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div style="display: flex; justify-content: center; gap: 8px; margin-top: 20px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=tmpb-member-all&paged=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['tier']) ? '&tier=' . urlencode($_GET['tier']) : ''; ?>"
               style="padding: 8px 16px; background: <?php echo $i === $paged ? '#539294' : 'white'; ?>; color: <?php echo $i === $paged ? 'white' : '#64748b'; ?>; text-decoration: none; border-radius: 6px; font-weight: 600; border: 1px solid #e2e8f0;">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.wrap { margin-top: 20px; }
</style>
