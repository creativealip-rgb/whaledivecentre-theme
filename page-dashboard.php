<?php
/**
 * Template Name: Dashboard
 */

require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
// Keep dashboard counters in sync with sidebar/live data (avoid stale user_meta snapshots)
$booking_count = 0;
$points = isset($dynamic_points) ? (int) $dynamic_points : 0;

// Get booking stats
$bookings = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'meta_query' => [
        [
            'key' => '_user_id',
            'value' => $user_id,
            'compare' => '=',
        ],
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);
$booking_count = count($bookings);
$pending_count = 0;
$confirmed_count = 0;
foreach ($bookings as $booking) {
    $status = get_post_meta($booking->ID, '_booking_status', true);
    if (in_array($status, ['pending_payment', 'payment_uploaded'])) $pending_count++;
    if (in_array($status, ['confirmed', 'paid'])) $confirmed_count++;
}

$current_tier = $current_tier ?? 'free';
$total_spend = isset($total_spend) ? (int) $total_spend : 0;
?>

<!-- Page Header -->
<div style="margin-bottom: 32px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo esc_html(contenly_tr('Dashboard', 'Dashboard')); ?></h1>
    <p style="font-size: 15px; color: #64748b;"><?php echo esc_html(contenly_tr('Kelola booking, pantau perjalanan, dan temukan petualangan baru.', 'Manage your bookings, track your travels, and explore new adventures.')); ?></p>
</div>

<!-- Stats Grid - 2 Columns -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 32px;">
    <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 20px; border-radius: 12px; text-align: center;">
        <div style="font-size: 28px; font-weight: 700; color: #d97706; margin-bottom: 4px;"><?php echo $pending_count; ?></div>
        <div style="font-size: 12px; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo esc_html(contenly_tr('Pending', 'Pending')); ?></div>
    </div>
    <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); padding: 20px; border-radius: 12px; text-align: center;">
        <div style="font-size: 28px; font-weight: 700; color: #059669; margin-bottom: 4px;"><?php echo $confirmed_count; ?></div>
        <div style="font-size: 12px; color: #065f46; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo esc_html(contenly_tr('Terkonfirmasi', 'Confirmed')); ?></div>
    </div>
    <div style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); padding: 20px; border-radius: 12px; text-align: center;">
        <div style="font-size: 28px; font-weight: 700; color: #7c3aed; margin-bottom: 4px;"><?php echo $points; ?></div>
        <div style="font-size: 12px; color: #5b21b6; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo esc_html(contenly_tr('Poin Reward', 'Reward Points')); ?></div>
    </div>
    <div style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); padding: 20px; border-radius: 12px; text-align: center;">
        <div style="font-size: 28px; font-weight: 700; color: #539294; margin-bottom: 4px;"><?php echo $booking_count; ?></div>
        <div style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo esc_html(contenly_tr('Total Booking', 'Total Bookings')); ?></div>
    </div>
</div>

<!-- Progress to Next Tier -->
<?php if ($current_tier !== 'platinum') : 
    $next_tier = $current_tier === 'silver' ? 'gold' : 'platinum';
    $target_spend = $current_tier === 'silver' ? 5000000 : 15000000;
    $previous_spend = $current_tier === 'silver' ? 0 : 5000000;
    $current_window_spend = max(0, $total_spend - $previous_spend);
    $needed_window_spend = max(1, $target_spend - $previous_spend);
    $progress = min(100, ($current_window_spend / $needed_window_spend) * 100);
    $remaining_spend = max(0, $target_spend - $total_spend);
?>
<div style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); padding: 24px; border-radius: 16px; margin-bottom: 32px;">
    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
        <?php echo esc_html(sprintf(contenly_tr('🎯 Progress ke %s', '🎯 Progress to %s'), ucfirst($next_tier))); ?>
    </h2>
    <div style="background: #e2e8f0; border-radius: 9999px; height: 12px; overflow: hidden; margin-bottom: 12px;">
        <div style="width: <?php echo $progress; ?>%; background: linear-gradient(90deg, #539294, #539294); height: 100%; border-radius: 9999px;"></div>
    </div>
    <div style="display: flex; justify-content: space-between; font-size: 14px; color: #64748b;">
        <span><?php echo esc_html(sprintf(contenly_tr('Spend Rp %1$s / Rp %2$s', 'Spend Rp %1$s / Rp %2$s'), number_format($total_spend, 0, ',', '.'), number_format($target_spend, 0, ',', '.'))); ?></span>
        <span><?php echo esc_html(sprintf(contenly_tr('Butuh Rp %s lagi', 'Need Rp %s more'), number_format($remaining_spend, 0, ',', '.'))); ?></span>
    </div>
</div>
<?php endif; ?>

<!-- Recent Bookings -->
<h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 24px;"><?php echo esc_html(contenly_tr('Booking Terbaru', 'Recent Bookings')); ?></h2>
<?php if (empty($bookings)) : ?>
    <div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 16px;">
        <div style="font-size: 64px; margin-bottom: 16px;">🗺️</div>
        <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo esc_html(contenly_tr('Belum ada booking', 'No bookings yet')); ?></h3>
        <p style="color: #64748b; margin-bottom: 24px;"><?php echo esc_html(contenly_tr('Mulai jelajahi tour terbaik kami!', 'Start exploring our amazing tours!')); ?></p>
        <a href="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #539294, #539294); color: white; text-decoration: none; border-radius: 12px; font-weight: 600;"><?php echo esc_html(contenly_tr('Jelajahi Tour', 'Browse Tours')); ?></a>
    </div>
<?php else : 
    $recent_bookings = array_slice($bookings, 0, 6);
?>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <?php foreach ($recent_bookings as $booking) :
        $tour_id = get_post_meta($booking->ID, '_tour_id', true);
        $tour = get_post($tour_id);
        $status = get_post_meta($booking->ID, '_booking_status', true);
        $total = get_post_meta($booking->ID, '_total_amount', true);
        $pax = get_post_meta($booking->ID, '_pax', true);
        $travel_date = get_post_meta($booking->ID, '_travel_date', true);
        
        $status_labels = [
            'pending_payment' => ['label' => '⏳ ' . contenly_tr('Pending', 'Pending'), 'color' => '#fbbf24', 'bg' => '#fef3c7'],
            'payment_uploaded' => ['label' => '📤 ' . contenly_tr('Bukti Diupload', 'Uploaded'), 'color' => '#539294', 'bg' => '#DCE9E6'],
            'paid' => ['label' => '✅ ' . contenly_tr('Lunas', 'Paid'), 'color' => '#10b981', 'bg' => '#d1fae5'],
            'confirmed' => ['label' => '✓ ' . contenly_tr('Terkonfirmasi', 'Confirmed'), 'color' => '#10b981', 'bg' => '#d1fae5'],
            'cancelled' => ['label' => '❌ ' . contenly_tr('Dibatalkan', 'Cancelled'), 'color' => '#dc2626', 'bg' => '#fee2e2'],
        ];
        $status_info = $status_labels[$status] ?? ['label' => $status, 'color' => '#64748b', 'bg' => '#f1f5f9'];
    ?>
    <div style="border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; background: white; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
            <div style="flex: 1;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px; line-height: 1.4;">
                    <?php echo esc_html($tour ? $tour->post_title : contenly_tr('Booking Tour', 'Tour Booking')); ?>
                </h3>
                <div style="font-size: 13px; color: #94a3b8;"><?php echo esc_html(contenly_tr('Booking', 'Booking')); ?> #<?php echo esc_html($booking->ID); ?></div>
            </div>
            <span style="padding: 4px 12px; background: <?php echo $status_info['bg']; ?>; color: <?php echo $status_info['color']; ?>; border-radius: 9999px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                <?php echo esc_html($status_info['label']); ?>
            </span>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding-top: 16px; border-top: 1px solid #f1f5f9; margin-top: 16px;">
            <div>
                <div style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;"><?php echo esc_html(contenly_tr('Tanggal Perjalanan', 'Travel Date')); ?></div>
                <div style="font-weight: 600; color: #0f172a; font-size: 14px;"><?php echo esc_html($travel_date ? date_i18n('M d, Y', strtotime($travel_date)) : contenly_tr('Belum diatur', 'Not set')); ?></div>
            </div>
            <div>
                <div style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;">Pax</div>
                <div style="font-weight: 600; color: #0f172a; font-size: 14px;"><?php echo esc_html(($pax ?: 1) . ' ' . contenly_tr('orang', 'persons')); ?></div>
            </div>
        </div>
        
        <div style="display: flex; gap: 8px; margin-top: 16px;">
            <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" style="flex: 1; padding: 10px; background: #f0f9ff; color: #539294; text-align: center; text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 13px;"><?php echo esc_html(contenly_tr('Lihat Tour', 'View Tour')); ?></a>
            <?php if (in_array($status, ['pending_payment', 'payment_uploaded'])) : ?>
            <button style="flex: 1; padding: 10px; background: #fee2e2; color: #dc2626; border: none; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer;"><?php echo esc_html(contenly_tr('Batalkan', 'Cancel')); ?></button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div style="text-align: center;">
    <a href="<?php echo esc_url(contenly_localized_url('/my-travels/')); ?>" style="display: inline-block; padding: 12px 32px; background: white; color: #539294; border: 2px solid #e2e8f0; text-decoration: none; border-radius: 12px; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='#f0f9ff'; this.style.borderColor='#539294'"><?php echo esc_html(contenly_tr('Lihat semua booking →', 'View all bookings →')); ?></a>
</div>
<?php endif; ?>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
