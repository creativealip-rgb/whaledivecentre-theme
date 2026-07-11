<?php
/**
 * Template Name: My Bookings
 */

require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();

// Get all bookings
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
?>

<h1 class="page-title">🎫 My Bookings</h1>
<p class="page-subtitle">View and manage all your tour bookings</p>

<?php if (empty($bookings)) : ?>
    <div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 16px;">
        <div style="font-size: 64px; margin-bottom: 16px;">🗺️</div>
        <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">No bookings yet</h3>
        <p style="color: #64748b; margin-bottom: 24px;">Start exploring our amazing tours!</p>
        <a href="/tour-packages" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #539294, #539294); color: white; text-decoration: none; border-radius: 12px; font-weight: 600;">Browse Tours</a>
    </div>
<?php else : ?>
<div style="display: grid; gap: 16px;">
    <?php foreach ($bookings as $booking) :
        $tour_id = get_post_meta($booking->ID, '_tour_id', true);
        $tour = get_post($tour_id);
        $status = get_post_meta($booking->ID, '_booking_status', true);
        $total = get_post_meta($booking->ID, '_total_amount', true);
        $pax = get_post_meta($booking->ID, '_pax', true);
        $travel_date = get_post_meta($booking->ID, '_travel_date', true);
        
        $status_labels = [
            'pending_payment' => ['label' => '⏳ Pending Payment', 'color' => '#fbbf24'],
            'payment_uploaded' => ['label' => '📤 Payment Uploaded', 'color' => '#539294'],
            'paid' => ['label' => '✅ Paid', 'color' => '#10b981'],
            'confirmed' => ['label' => '✓ Confirmed', 'color' => '#10b981'],
            'cancelled' => ['label' => '❌ Cancelled', 'color' => '#dc2626'],
            'completed' => ['label' => '✔ Completed', 'color' => '#10b981'],
        ];
        $status_info = $status_labels[$status] ?? ['label' => $status, 'color' => '#64748b'];
    ?>
    <div style="border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; background: white;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                    <?php echo $tour ? $tour->post_title : 'Tour Booking'; ?>
                </h3>
                <div style="font-size: 14px; color: #64748b;">Booking #<?php echo $booking->ID; ?></div>
            </div>
            <span style="padding: 6px 16px; background: <?php echo $status_info['color']; ?>20; color: <?php echo $status_info['color']; ?>; border-radius: 9999px; font-size: 13px; font-weight: 600;">
                <?php echo $status_info['label']; ?>
            </span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Travel Date</div>
                <div style="font-weight: 600; color: #0f172a;"><?php echo $travel_date ? date_i18n(get_option('date_format'), strtotime($travel_date)) : 'Not set'; ?></div>
            </div>
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Pax</div>
                <div style="font-weight: 600; color: #0f172a;"><?php echo $pax ?: 1; ?> persons</div>
            </div>
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Total</div>
                <div style="font-weight: 600; color: #0f172a;">Rp <?php echo number_format($total ?: 0, 0, ',', '.'); ?></div>
            </div>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 16px;">
            <a href="<?php echo get_permalink($tour_id); ?>" style="padding: 10px 20px; background: #f0f9ff; color: #539294; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">View Tour</a>
            <?php if (in_array($status, ['pending_payment', 'payment_uploaded'])) : ?>
            <button style="padding: 10px 20px; background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer;">Cancel Booking</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
