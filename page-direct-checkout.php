<?php
/**
 * Template Name: Direct Checkout
 * Payment checkout page after booking
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get booking ID from URL. Direct checkout supports courses/equipment without admin request first.
$booking_id = isset($_GET['booking_id']) ? absint($_GET['booking_id']) : 0;
$booking_code = isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '';
$direct_type = isset($_GET['type']) ? sanitize_key(wp_unslash($_GET['type'])) : '';
$direct_item = isset($_GET['item']) ? sanitize_text_field(wp_unslash($_GET['item'])) : '';
$direct_item_id = isset($_GET['item_id']) ? absint($_GET['item_id']) : 0;
$direct_price = isset($_GET['price']) ? (float) sanitize_text_field(wp_unslash($_GET['price'])) : 0;
$is_direct_checkout = (!$booking_id && $direct_item && in_array($direct_type, ['course', 'equipment'], true));
$direct_out_of_stock = false;

if ($is_direct_checkout && $direct_type === 'equipment' && $direct_item_id && function_exists('wdc_equipment_stock_available')) {
    $direct_out_of_stock = !wdc_equipment_stock_available($direct_item_id);
}

if (!$booking_id && !$is_direct_checkout) {
    wp_redirect(contenly_localized_url('/tour-packages/'));
    exit;
}

if ($is_direct_checkout && !is_user_logged_in()) {
    wp_redirect(add_query_arg('redirect_to', rawurlencode($_SERVER['REQUEST_URI'] ?? '/checkout/'), contenly_localized_url('/member-login/')));
    exit;
}

$booking = null;
$tour = null;
$total = $direct_price;
$status = 'pending_payment';
$user_id = get_current_user_id();
$user = $user_id ? get_userdata($user_id) : null;
$checkout_item_label = $direct_item;
$checkout_kind_label = $direct_type === 'equipment' ? contenly_tr('Equipment', 'Equipment') : contenly_tr('Course', 'Course');

if ($booking_id) {
    $booking = get_post($booking_id);
    if (!$booking || $booking->post_type !== 'tour_booking') {
        wp_redirect(contenly_localized_url('/tour-packages/'));
        exit;
    }

    $tour_id = get_post_meta($booking_id, '_tour_id', true);
    $tour = get_post($tour_id);
    $total = get_post_meta($booking_id, '_total_amount', true);
    $status = get_post_meta($booking_id, '_booking_status', true);
    $user_id = get_post_meta($booking_id, '_user_id', true);
    $user = get_userdata($user_id);
    $checkout_item_label = $tour ? $tour->post_title : contenly_tr('Tidak tersedia', 'N/A');
    $checkout_kind_label = contenly_tr('Tour', 'Tour');
}

// Only allow booking owner to access
// Temporarily disabled for testing
/*
if (get_current_user_id() !== $user_id) {
    wp_redirect('/my-bookings/');
    exit;
}
*/

get_header();
?>

<main class="site-main" style="min-height: 80vh; padding: 60px 0; background: #f8fafc;">
    <div class="site-container" style="max-width: 600px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Checkout Card -->
        <div style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="font-size: 48px; margin-bottom: 16px;">💳</div>
                <h1 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo esc_html(contenly_tr('Checkout', 'Checkout')); ?></h1>
                <p style="color: #64748b; font-size: 15px;"><?php echo esc_html($is_direct_checkout ? contenly_tr('Langsung checkout tanpa request admin dulu.', 'Checkout directly without an admin request first.') : contenly_tr('Selesaikan pembayaran untuk mengonfirmasi booking kamu', 'Complete your payment to confirm your booking.')); ?></p>
            </div>
            
            <!-- Booking Info -->
            <div style="background: #f0f9ff; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 16px;"><?php echo esc_html(contenly_tr('Detail Booking', 'Booking Details')); ?></h2>
                
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Kode Booking:', 'Booking Code:')); ?></span>
                        <span style="font-weight: 700; color: #0f172a;"><?php echo esc_html($booking_code ?: 'DIRECT-' . strtoupper(substr(md5($checkout_item_label), 0, 6))); ?></span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;"><?php echo esc_html($checkout_kind_label . ':'); ?></span>
                        <span style="font-weight: 600; color: #0f172a;"><?php echo esc_html($checkout_item_label); ?></span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Total Pembayaran:', 'Total Amount:')); ?></span>
                        <span style="font-weight: 700; color: #059669; font-size: 18px;">Rp <?php echo esc_html(number_format((float) $total, 0, ',', '.')); ?></span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Status:', 'Status:')); ?></span>
                        <span style="padding: 4px 12px; background: #fef3c7; color: #d97706; border-radius: 9999px; font-weight: 600; font-size: 13px;">
                            ⏳ <?php echo esc_html(contenly_tr('Menunggu Pembayaran', 'Pending Payment')); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Instructions -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 16px;"><?php echo esc_html(contenly_tr('Instruksi Pembayaran', 'Payment Instructions')); ?></h2>
                
                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: #92400e; margin: 0; font-size: 14px; line-height: 1.6;">
                        <strong><?php echo esc_html(contenly_tr('Transfer Bank:', 'Bank Transfer:')); ?></strong><br>
                        <?php echo esc_html(contenly_tr('Bank', 'Bank')); ?>: BCA<br>
                        <?php echo esc_html(contenly_tr('Rekening', 'Account')); ?>: 1234567890<br>
                        <?php echo esc_html(contenly_tr('Nama Rekening', 'Account Name')); ?>: TravelShip<br>
                        <br>
                        <?php echo esc_html(contenly_tr('Silakan transfer total pembayaran lalu upload bukti pembayaran kamu di bawah ini.', 'Please transfer the full amount, then upload your payment proof below.')); ?>
                    </p>
                </div>
            </div>
            
            <?php if ($direct_out_of_stock) : ?>
            <div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;padding:16px;margin-bottom:18px;font-weight:800;line-height:1.5;">
                This gear is currently out of stock. Please go back to My Gear and request availability help so the crew can confirm the next restock or alternative setup.
            </div>
            <?php endif; ?>

            <!-- Upload Payment Form -->
            <form id="payment-upload-form" style="display: grid; gap: 16px;">
                <input type="hidden" name="booking_id" value="<?php echo esc_attr($booking_id); ?>">
                <?php if ($is_direct_checkout) : ?>
                <input type="hidden" name="direct_type" value="<?php echo esc_attr($direct_type); ?>">
                <input type="hidden" name="direct_item" value="<?php echo esc_attr($direct_item); ?>">
                <input type="hidden" name="direct_item_id" value="<?php echo esc_attr($direct_item_id); ?>">
                <?php endif; ?>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        <?php echo esc_html(contenly_tr('Upload Bukti Pembayaran *', 'Upload Payment Proof *')); ?>
                    </label>
                    <input type="file" name="payment_proof" accept="image/*" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 14px;">
                    <p style="font-size: 12px; color: #64748b; margin-top: 6px;"><?php echo esc_html(contenly_tr('Format yang diterima: JPG, PNG. Maksimal ukuran: 2MB', 'Accepted formats: JPG, PNG. Max size: 2MB')); ?></p>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        <?php echo esc_html(contenly_tr('Catatan (Opsional)', 'Notes (Optional)')); ?>
                    </label>
                    <textarea name="payment_notes" rows="3" 
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 14px; resize: vertical;"
                              placeholder="<?php echo esc_attr(contenly_tr('Tanggal transfer, jam, atau informasi tambahan lainnya...', 'Transfer date, time, or any additional information...')); ?>"></textarea>
                </div>
                
                <button type="submit" id="upload-btn" <?php disabled($direct_out_of_stock); ?>
                        style="width: 100%; padding: 16px; background: linear-gradient(135deg, #539294, #539294); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s;<?php echo $direct_out_of_stock ? 'opacity:.55;cursor:not-allowed;' : ''; ?>">
                    <?php echo esc_html($direct_out_of_stock ? 'Out of Stock' : contenly_tr('Upload Bukti Pembayaran', 'Upload Payment Proof')); ?>
                </button>
            </form>
            
            <!-- Success Message (hidden by default) -->
            <div id="payment-success" style="display: none; text-align: center; padding: 32px;">
                <div style="font-size: 64px; margin-bottom: 16px;">✅</div>
                <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo esc_html(contenly_tr('Bukti Pembayaran Berhasil Diupload!', 'Payment Uploaded!')); ?></h2>
                <p style="color: #64748b; margin-bottom: 24px;"><?php echo esc_html(contenly_tr('Pembayaran kamu akan kami verifikasi dalam 24 jam.', 'We will verify your payment within 24 hours.')); ?></p>
                <a href="<?php echo esc_url(contenly_localized_url($direct_type === 'equipment' ? '/my-gear/' : '/my-courses/')); ?>" style="display: inline-block; padding: 14px 32px; background: #059669; color: white; text-decoration: none; border-radius: 12px; font-weight: 600;">
                    <?php echo esc_html($direct_type === 'equipment' ? contenly_tr('Lihat Gear Saya', 'View My Gear') : contenly_tr('Lihat Kursus Saya', 'View My Courses')); ?>
                </a>
            </div>
            
        </div>
        
        <!-- Back Link -->
        <div style="text-align: center; margin-top: 24px;">
            <a href="<?php echo esc_url(contenly_localized_url($direct_type === 'equipment' ? '/my-gear/' : '/my-courses/')); ?>" style="color: #64748b; text-decoration: none; font-size: 14px;">
                ← <?php echo esc_html($direct_type === 'equipment' ? contenly_tr('Kembali ke Gear Saya', 'Back to My Gear') : contenly_tr('Kembali ke Kursus Saya', 'Back to My Courses')); ?>
            </a>
        </div>
        
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('#payment-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $('#upload-btn');
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html(<?php echo wp_json_encode(contenly_tr('⏳ Mengupload...', '⏳ Uploading...')); ?>);
        
        var formData = new FormData(this);
        if (<?php echo $is_direct_checkout ? 'true' : 'false'; ?>) {
            formData.append('action', 'wdc_save_direct_checkout');
            formData.append('nonce', (window.wdcMemberAjax && wdcMemberAjax.nonce) ? wdcMemberAjax.nonce : '');
            formData.append('direct_price', <?php echo wp_json_encode((string) $direct_price); ?>);
            $.ajax({
                url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response && response.success) {
                        alert(<?php echo wp_json_encode(contenly_tr('Bukti pembayaran diterima. Crew akan verifikasi dan mengaktifkan pesanan kamu.', 'Payment proof received. The crew will verify and activate your order.')); ?>);
                        window.location.href = <?php echo wp_json_encode(contenly_localized_url($direct_type === 'equipment' ? '/my-gear/' : '/my-courses/')); ?>;
                    } else {
                        alert((response && response.data && response.data.message) ? response.data.message : <?php echo wp_json_encode(contenly_tr('Checkout gagal.', 'Checkout failed.')); ?>);
                        $btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    alert(<?php echo wp_json_encode(contenly_tr('Checkout gagal. Coba lagi ya.', 'Checkout failed. Please try again.')); ?>);
                    $btn.prop('disabled', false).html(originalText);
                }
            });
            return;
        }
        formData.append('action', 'tmpb_upload_payment');
        formData.append('nonce', '<?php echo wp_create_nonce('tmpb_booking_nonce'); ?>');
        
        $.ajax({
            url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $btn.prop('disabled', true).html(<?php echo wp_json_encode(contenly_tr('✅ Berhasil Diupload', '✅ Uploaded')); ?>);
                    window.location.href = <?php echo wp_json_encode(contenly_localized_url($direct_type === 'equipment' ? '/my-gear/' : '/my-courses/')); ?>;
                } else {
                    alert((response && response.data && response.data.message) ? response.data.message : <?php echo wp_json_encode(contenly_tr('Upload gagal.', 'Upload failed.')); ?>);
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert(<?php echo wp_json_encode(contenly_tr('Upload gagal. Coba lagi ya.', 'Upload failed. Please try again.')); ?>);
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

<?php get_footer(); ?>
