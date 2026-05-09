<?php
/**
 * Tier Monitoring - Track member tiers from spending
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get all upgrade payments (stored as custom post type or booking with meta)
// For now, we'll track bookings where user upgraded tier
$all_members = get_users(['orderby' => 'registered', 'order' => 'DESC']);

$tier_members = [];
foreach ($all_members as $member) {
    $total_spending = contenly_get_user_total_spending($member->ID);
    $tier = contenly_get_tier_from_spending($total_spending);
    $bookings = get_posts([
        'post_type' => 'tour_booking',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            ['key' => '_user_id', 'value' => $member->ID, 'compare' => '='],
            ['key' => '_booking_status', 'value' => contenly_paid_like_statuses(), 'compare' => 'IN'],
        ],
    ]);

    $tier_members[] = [
            'user_id' => $member->ID,
            'name' => $member->display_name,
            'email' => $member->user_email,
            'tier' => $tier,
            'total_spending' => $total_spending,
            'joined' => $member->user_registered,
            'booking_count' => count($bookings)
        ];
}

// Sort by total spending DESC
usort($tier_members, function($a, $b) {
    return $b['total_spending'] - $a['total_spending'];
});

$total_member_spending = array_sum(array_column($tier_members, 'total_spending'));
$platinum_count = count(array_filter($tier_members, fn($p) => $p['tier'] === 'platinum'));
$gold_count = count(array_filter($tier_members, fn($p) => $p['tier'] === 'gold'));
$silver_count = count(array_filter($tier_members, fn($p) => $p['tier'] === 'silver'));
?>

<div class="wrap">
    <h1 style="font-size: 2em; font-weight: 600; margin-bottom: 20px;">💳 Tier Monitoring</h1>
    
    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #355F72, #539294); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">💎 Platinum Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $platinum_count; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Spent 15M+</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fbbf24, #f59e0b); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">🥇 Gold Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $gold_count; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Spent 5M+</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #539294, #539294); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">🥈 Silver Members</div>
            <div style="font-size: 36px; font-weight: 700;"><?php echo $silver_count; ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Baseline tier</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 24px; border-radius: 12px; color: white;">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">💰 Total Spending</div>
            <div style="font-size: 24px; font-weight: 700;">Rp <?php echo number_format($total_member_spending, 0, ',', '.'); ?></div>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">Paid / confirmed bookings</div>
        </div>
    </div>
    
    <!-- Tier Table -->
    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 18px; font-weight: 600; color: #0f172a; margin: 0;">Member Tier by Spending</h2>
            
            <div style="display: flex; gap: 12px;">
                <select id="tier-filter" onchange="filterTable()" style="padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    <option value="">All Tiers</option>
                    <option value="silver">🥈 Silver</option>
                    <option value="gold">🥇 Gold</option>
                    <option value="platinum">💎 Platinum</option>
                </select>
            </div>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;" id="upgrade-table">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Member</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Current Tier</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Total Spending</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Bookings</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Joined</th>
                    <th style="text-align: left; padding: 16px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: 600;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tier_members)): ?>
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #64748b;">
                        Belum ada data spending member
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($tier_members as $payment): 
                        $tier_badges = [
                            'silver' => ['label' => '🥈 Silver', 'color' => '#94a3b8', 'bg' => '#f1f5f9'],
                            'gold' => ['label' => '🥇 Gold', 'color' => '#fbbf24', 'bg' => '#fef3c7'],
                            'platinum' => ['label' => '💎 Platinum', 'color' => '#355F72', 'bg' => '#EEF5F4']
                        ];
                        $tier_info = $tier_badges[$payment['tier']] ?? $tier_badges['silver'];
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;" data-tier="<?php echo $payment['tier']; ?>">
                        <td style="padding: 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #539294, #539294); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                                    <?php echo strtoupper(substr($payment['name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html($payment['name']); ?></div>
                                    <div style="font-size: 12px; color: #64748b;"><?php echo esc_html($payment['email']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <span style="padding: 6px 12px; background: <?php echo $tier_info['bg']; ?>; color: <?php echo $tier_info['color']; ?>; border-radius: 9999px; font-weight: 600; font-size: 13px;">
                                <?php echo $tier_info['label']; ?>
                            </span>
                        </td>
                        <td style="padding: 16px;">
                            <span style="font-weight: 700; color: #059669;">
                                Rp <?php echo number_format($payment['total_spending'], 0, ',', '.'); ?>
                            </span>
                        </td>
                        <td style="padding: 16px; color: #475569;">
                            <?php echo $payment['booking_count']; ?> bookings
                        </td>
                        <td style="padding: 16px; color: #64748b;">
                            <?php echo date_i18n('M d, Y', strtotime($payment['joined'])); ?>
                        </td>
                        <td style="padding: 16px;">
                            <span style="padding: 6px 12px; background: #d1fae5; color: #059669; border-radius: 9999px; font-weight: 600; font-size: 13px;">
                                Auto by spending
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterTable() {
    const tier = document.getElementById('tier-filter').value;
    const rows = document.querySelectorAll('#upgrade-table tbody tr');
    
    rows.forEach(row => {
        if (!tier || row.dataset.tier === tier) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<style>
.wrap { margin-top: 20px; }
</style>
