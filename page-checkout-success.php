<?php
/**
 * Template Name: Checkout Success
 */

get_header();

$order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';
$tier = get_user_meta(get_current_user_id(), '_tmp_last_order_tier', true) ?: 'silver';
$plans = TMP_Membership_Plans::get_plans();
$plan = $plans[$tier];
?>

<div style="padding: 140px 20px 80px; background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);">
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        
        <!-- Success Icon -->
        <div style="font-size: 80px; margin-bottom: 24px;">✅</div>
        
        <!-- Title -->
        <h1 style="font-size: 36px; font-weight: 800; color: #10b981; margin-bottom: 16px;">
            <?php echo esc_html(contenly_tr('Pembayaran Berhasil!', 'Payment Successful!')); ?>
        </h1>
        
        <p style="font-size: 18px; color: #475569; margin-bottom: 32px;">
            <?php echo wp_kses_post(sprintf(contenly_tr('Selamat datang di membership <strong>%s</strong>!', 'Welcome to the <strong>%s</strong> membership!'), esc_html($plan['name']))); ?>
        </p>
        
        <!-- Order Details -->
        <div style="background: white; border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 32px; text-align: left;">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 20px;"><?php echo esc_html(contenly_tr('Detail Pesanan', 'Order Details')); ?></h2>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Order ID', 'Order ID')); ?></span>
                <span style="color: #0f172a; font-weight: 600;"><?php echo esc_html($order_id); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Tier Membership', 'Membership Tier')); ?></span>
                <span style="color: #0f172a; font-weight: 600;"><?php echo esc_html($plan['icon'] . ' ' . $plan['name']); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Jumlah Dibayar', 'Amount Paid')); ?></span>
                <span style="color: #10b981; font-weight: 700;"><?php echo esc_html($plan['price_display']); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748b;"><?php echo esc_html(contenly_tr('Status', 'Status')); ?></span>
                <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 9999px; font-size: 13px; font-weight: 600;"><?php echo esc_html(contenly_tr('Aktif', 'Active')); ?></span>
            </div>
        </div>
        
        <!-- Benefits Preview -->
        <div style="background: #f0f9ff; border-radius: 20px; padding: 32px; margin-bottom: 32px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 16px;"><?php echo esc_html(contenly_tr('Benefit Kamu', 'Your Benefits')); ?></h2>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($plan['benefits'] as $benefit) : ?>
                <li style="padding: 8px 0; color: #475569; display: flex; align-items: center; gap: 8px;">
                    <span style="color: #10b981; font-weight: 700;">✓</span>
                    <?php echo $benefit; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- CTA Buttons -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>" style="display: block; padding: 16px; background: linear-gradient(135deg, #539294, #539294); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px;">
                <?php echo esc_html(contenly_tr('Ke Dashboard', 'Go to Dashboard')); ?>
            </a>
            <a href="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>" style="display: block; padding: 16px; background: white; color: #539294; border: 2px solid #539294; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px;">
                <?php echo esc_html(sprintf(contenly_tr('Lihat Tour (diskon %s%%!)', 'Browse Tours (with %s%% OFF!)'), (int) ($plan['discount'] ?? 0))); ?>
            </a>
        </div>
        
    </div>
</div>

<?php get_footer(); ?>
