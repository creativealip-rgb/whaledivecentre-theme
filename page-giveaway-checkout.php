<?php
/**
 * Template Name: Giveaway Checkout
 * Payment page for giveaway shipping cost
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_user_logged_in()) {
    wp_redirect(add_query_arg('redirect_to', rawurlencode($_SERVER['REQUEST_URI'] ?? '/dashboard/'), contenly_localized_url('/login/')));
    exit;
}

$user_id = get_current_user_id();
$order_id = sanitize_text_field($_GET['order'] ?? '');

// Get giveaway order from user meta
$giveaway_order = get_user_meta($user_id, '_wdc_giveaway_order', true);

if (!$giveaway_order || !is_array($giveaway_order)) {
    wp_redirect(contenly_localized_url('/dashboard/'));
    exit;
}

// Verify order ID matches
if ($order_id && $giveaway_order['order_id'] !== $order_id) {
    wp_redirect(contenly_localized_url('/dashboard/'));
    exit;
}

// Already paid?
if (($giveaway_order['status'] ?? '') === 'paid') {
    wp_redirect(add_query_arg('giveaway', 'paid', contenly_localized_url('/dashboard/')));
    exit;
}

// Get item details
$all_items = wdc_get_giveaway_items();
$selected_items = [];
foreach ($all_items as $item) {
    if (in_array($item['id'], $giveaway_order['items'] ?? [], true)) {
        $selected_items[] = $item;
    }
}

$total_weight = array_sum(array_column($selected_items, 'weight'));
$shipping_cost = intval($giveaway_order['shipping_cost'] ?? 0);
$courier = esc_html(strtoupper($giveaway_order['courier'] ?? ''));
$service = esc_html($giveaway_order['service'] ?? '');
$dest = esc_html($giveaway_order['destination'] ?? '');
$address = esc_html($giveaway_order['address'] ?? '');
$phone = esc_html($giveaway_order['phone'] ?? '');
$name = esc_html($giveaway_order['recipient_name'] ?? '');

get_header();
?>

<main class="site-main" style="min-height:80vh;padding:60px 0;background:#f8fafc;">
    <div class="site-container" style="max-width:600px;margin:0 auto;padding:0 20px;">

        <!-- Success banner -->
        <div style="background:linear-gradient(135deg,#fef9c3,#fef08a);border:2px solid #facc15;border-radius:16px;padding:24px;margin-bottom:24px;text-align:center;">
            <div style="font-size:48px;margin-bottom:8px;">🎁</div>
            <h1 style="font-size:24px;font-weight:900;color:#0f172a;margin:0 0 6px;"><?php echo contenly_tr('Giveaway Diklaim!', 'Giveaway Claimed!'); ?></h1>
            <p style="color:#713f12;font-size:14px;margin:0;"><?php echo contenly_tr('Barangnya gratis! Tinggal bayar ongkir aja ya.', 'Items are free! Just pay for shipping.'); ?></p>
        </div>

        <!-- Order Summary Card -->
        <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,.08);margin-bottom:24px;">

            <h2 style="font-size:18px;font-weight:800;color:#0f172a;margin:0 0 20px;"><?php echo contenly_tr('Ringkasan Pesanan', 'Order Summary'); ?></h2>

            <!-- Items -->
            <div style="margin-bottom:20px;">
                <div style="font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:10px;"><?php echo contenly_tr('Item Giveaway', 'Giveaway Items'); ?></div>
                <?php foreach ($selected_items as $item) : ?>
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f0fdf4;border-radius:12px;margin-bottom:8px;">
                    <span style="font-size:28px;">
                        <?php
                        $icons = ['sticker-pack' => '🏷️', 'lanyard' => '🪢', 'keychain' => '🔑'];
                        echo $icons[$item['id']] ?? '🎁';
                        ?>
                    </span>
                    <div style="flex:1;">
                        <strong style="color:#0f172a;font-size:15px;"><?php echo esc_html($item['name']); ?></strong>
                        <div style="font-size:12px;color:#64748b;"><?php echo esc_html($item['desc']); ?> · <?php echo $item['weight']; ?>g</div>
                    </div>
                    <span style="background:#dcfce7;color:#166534;font-weight:900;font-size:12px;padding:4px 10px;border-radius:999px;"><?php echo contenly_tr('GRATIS', 'FREE'); ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Shipping Details -->
            <div style="border-top:1px solid #e5e7eb;padding-top:16px;margin-bottom:16px;">
                <div style="font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:10px;"><?php echo contenly_tr('Detail Pengiriman', 'Shipping Details'); ?></div>
                <div style="background:#f8fafc;border-radius:12px;padding:16px;font-size:14px;color:#374151;line-height:1.8;">
                    <strong><?php echo $name; ?></strong><br>
                    <?php echo $phone; ?><br>
                    <?php echo $address; ?><br>
                    <?php echo $dest; ?>
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div style="border-top:1px solid #e5e7eb;padding-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="color:#64748b;font-size:14px;"><?php echo contenly_tr('Item Giveaway', 'Giveaway Items'); ?></span>
                    <span style="font-weight:700;color:#10b981;"><?php echo count($selected_items); ?> item</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="color:#64748b;font-size:14px;"><?php echo contenly_tr('Berat Total', 'Total Weight'); ?></span>
                    <span style="font-weight:600;color:#374151;"><?php echo $total_weight; ?>g</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="color:#64748b;font-size:14px;"><?php echo contenly_tr('Kurir', 'Courier'); ?></span>
                    <span style="font-weight:600;color:#374151;text-transform:uppercase;"><?php echo $courier; ?> <?php echo $service; ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:2px solid #e5e7eb;">
                    <span style="font-size:16px;font-weight:800;color:#0f172a;"><?php echo contenly_tr('Total Ongkir', 'Total Shipping'); ?></span>
                    <span style="font-size:22px;font-weight:950;color:#059669;">Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,.08);margin-bottom:24px;">
            <h2 style="font-size:18px;font-weight:800;color:#0f172a;margin:0 0 16px;"><?php echo contenly_tr('Instruksi Pembayaran', 'Payment Instructions'); ?></h2>

            <div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:16px;border-radius:8px;margin-bottom:20px;">
                <p style="color:#92400e;margin:0;font-size:14px;line-height:1.7;">
                    <strong><?php echo contenly_tr('Transfer Bank:', 'Bank Transfer:'); ?></strong><br>
                    <?php echo contenly_tr('Bank', 'Bank'); ?>: BCA<br>
                    <?php echo contenly_tr('Rekening', 'Account'); ?>: 1234567890<br>
                    <?php echo contenly_tr('Nama Rekening', 'Account Name'); ?>: Whale Dive Centre<br>
                    <br>
                    <strong><?php echo contenly_tr('Jumlah:', 'Amount:'); ?> Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></strong><br>
                    <br>
                    <?php echo contenly_tr('Transfer sesuai jumlah di atas, lalu upload bukti transfer.', 'Transfer the exact amount above, then upload your proof.'); ?>
                </p>
            </div>

            <!-- Upload Form -->
            <form id="wdc-giveaway-payment-form" style="display:grid;gap:16px;">
                <input type="hidden" name="order_id" value="<?php echo esc_attr($giveaway_order['order_id']); ?>">

                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:700;color:#0f172a;font-size:14px;">
                        <?php echo contenly_tr('Upload Bukti Transfer *', 'Upload Transfer Proof *'); ?>
                    </label>
                    <input type="file" name="payment_proof" accept="image/*" required
                           style="width:100%;padding:12px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;">
                    <p style="font-size:12px;color:#64748b;margin-top:6px;"><?php echo contenly_tr('JPG, PNG. Maks 5MB.', 'JPG, PNG. Max 5MB.'); ?></p>
                </div>

                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:700;color:#0f172a;font-size:14px;">
                        <?php echo contenly_tr('Catatan (Opsional)', 'Notes (Optional)'); ?>
                    </label>
                    <textarea name="notes" rows="2"
                              style="width:100%;padding:12px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;resize:vertical;"
                              placeholder="<?php echo esc_attr(contenly_tr('Jam transfer, dll...', 'Transfer time, etc...')); ?>"></textarea>
                </div>

                <button type="submit" id="wdc-gw-upload-btn"
                        style="width:100%;padding:16px;background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:none;border-radius:12px;font-weight:800;font-size:16px;cursor:pointer;">
                    <?php echo contenly_tr('📤 Upload Bukti Transfer', '📤 Upload Transfer Proof'); ?>
                </button>
            </form>

            <!-- Success message (hidden) -->
            <div id="wdc-gw-payment-success" style="display:none;text-align:center;padding:32px;">
                <div style="font-size:64px;margin-bottom:16px;">✅</div>
                <h2 style="font-size:24px;font-weight:800;color:#0f172a;margin:0 0 8px;"><?php echo contenly_tr('Bukti Transfer Diterima!', 'Transfer Proof Received!'); ?></h2>
                <p style="color:#64748b;margin:0 0 24px;"><?php echo contenly_tr('Crew akan verifikasi dalam 24 jam. Giveaway kamu akan dikirim setelah verifikasi.', 'Crew will verify within 24 hours. Your giveaway will be shipped after verification.'); ?></p>
                <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>"
                   style="display:inline-block;padding:14px 32px;background:#059669;color:#fff;text-decoration:none;border-radius:12px;font-weight:700;">
                    <?php echo contenly_tr('← Kembali ke Dashboard', '← Back to Dashboard'); ?>
                </a>
            </div>
        </div>

        <!-- Back link -->
        <div style="text-align:center;margin-top:24px;">
            <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>" style="color:#64748b;text-decoration:none;font-size:14px;">
                ← <?php echo contenly_tr('Kembali ke Dashboard', 'Back to Dashboard'); ?>
            </a>
        </div>

    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('#wdc-giveaway-payment-form').on('submit', function(e) {
        e.preventDefault();

        var form = this;
        var btn = $('#wdc-gw-upload-btn');
        var originalText = btn.html();

        btn.prop('disabled', true).html('⏳ <?php echo contenly_tr('Mengupload...', 'Uploading...'); ?>');

        var formData = new FormData(form);
        formData.append('action', 'wdc_upload_giveaway_payment');
        formData.append('nonce', wdcGiveawayAjax.nonce);

        $.ajax({
            url: wdcGiveawayAjax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res && res.success) {
                    $('#wdc-giveaway-payment-form').hide();
                    $('#wdc-gw-payment-success').show();
                } else {
                    alert((res && res.data && res.data.message) ? res.data.message : '<?php echo contenly_tr('Upload gagal.', 'Upload failed.'); ?>');
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert('<?php echo contenly_tr('Upload gagal. Coba lagi.', 'Upload failed. Try again.'); ?>');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

<?php get_footer(); ?>
