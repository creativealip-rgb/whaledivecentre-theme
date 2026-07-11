<?php
/**
 * Template Name: Dashboard
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$course_requests = get_user_meta($user_id, '_wdc_course_requests', true);
$gear_requests = get_user_meta($user_id, '_wdc_gear_requests', true);
$course_requests = is_array($course_requests) ? $course_requests : [];
$gear_requests = is_array($gear_requests) ? $gear_requests : [];
$course_orders = get_user_meta($user_id, '_wdc_course_orders', true);
$gear_orders = get_user_meta($user_id, '_wdc_gear_orders', true);
$course_orders = is_array($course_orders) ? $course_orders : [];
$gear_orders = is_array($gear_orders) ? $gear_orders : [];
$active_items = array_filter(array_merge($course_orders, $gear_orders), function($item) {
    return in_array($item['status'] ?? '', ['Verified', 'Active', 'Completed'], true);
});

// Manual orders from admin (wdc_order CPT)
$manual_orders = get_posts([
    'post_type'      => 'wdc_order',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => [['key' => '_wdc_customer_id', 'value' => $user_id]],
]);
$manual_pending = 0;
$manual_total = 0;
$manual_statuses = [];
foreach ($manual_orders as $mo) {
    $s = get_post_meta($mo->ID, '_wdc_order_status', true) ?: 'pending';
    $manual_statuses[] = $s;
    $manual_total++;
    if ($s === 'pending') {
        $manual_pending++;
    }
}
?>
<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:8px;"><?php echo contenly_tr('Dashboard Member', 'Member Dashboard'); ?></h1>
    <p style="font-size:15px;color:#64748b;"><?php echo contenly_tr('Pusat Whale Dive Centre untuk perencanaan kursus, permintaan alat selam, dan dukungan kru.', 'Your Whale Dive Centre hub for course planning, scuba gear requests, and crew support.'); ?></p>
</div>

<div class="wdc-dash-stats" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:28px;">
    <div style="background:linear-gradient(135deg,#eef9fc,#dff4fa);padding:20px;border-radius:16px;border:1px solid #ccecf5;">
        <div style="font-size:12px;color:#0b617c;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Permintaan Kursus', 'Course Requests'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#06384d;"><?php echo count($course_requests); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);padding:20px;border-radius:16px;border:1px solid #fed7aa;">
        <div style="font-size:12px;color:#9a3412;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Permintaan Peralatan', 'Gear Requests'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#7c2d12;"><?php echo count($gear_requests); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#f8fafc,#eef2ff);padding:20px;border-radius:16px;border:1px solid #e2e8f0;">
        <div style="font-size:12px;color:#475569;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Pesanan Langsung', 'Direct Orders'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#0f172a;"><?php echo count($course_orders) + count($gear_orders) + $manual_total; ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#ecfdf5,#dcfce7);padding:20px;border-radius:16px;border:1px solid #bbf7d0;">
        <div style="font-size:12px;color:#166534;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Terverifikasi / Aktif', 'Verified / Active'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#166534;"><?php echo count($active_items); ?></div>
    </div>
</div>

<!-- Giveaway Section (only for users who haven't claimed) — pulls from Informasi post -->
<?php if (is_user_logged_in() && get_option('wdc_giveaway_enabled', '1') && wdc_is_new_user() && !wdc_user_claimed_giveaway()) :
    // Get active giveaway from Informasi
    $giveaway_post = get_posts([
        'post_type' => 'wdc_info',
        'meta_key' => '_wdc_giveaway_active',
        'meta_value' => '1',
        'posts_per_page' => 1,
        'post_status' => 'publish',
    ]);
    $gw_title = $giveaway_post ? $giveaway_post[0]->post_title : contenly_tr('Pilih Giveaway Kamu', 'Pick Your Giveaway');
    $gw_excerpt = $giveaway_post ? $giveaway_post[0]->post_excerpt : contenly_tr('Barangnya gratis — kamu cukup bayar ongkirnya aja!', 'Items are free — just pay for shipping!');
    $gw_link = $giveaway_post ? get_permalink($giveaway_post[0]->ID) : contenly_localized_url('/informasi/');
?>
<section id="wdc-giveaway-section" style="background:linear-gradient(135deg,#fef9c3,#fef08a);border:2px solid #facc15;border-radius:20px;padding:28px;margin-bottom:28px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <span style="font-size:32px;">🎁</span>
        <div>
            <h2 style="font-size:22px;font-weight:900;color:#0f172a;margin:0;"><?php echo esc_html($gw_title); ?></h2>
            <p style="font-size:14px;color:#713f12;margin:4px 0 0;"><?php echo esc_html($gw_excerpt); ?></p>
            <a href="<?php echo esc_url($gw_link); ?>" style="font-size:13px;font-weight:800;color:#0b617c;text-decoration:none;"><?php echo contenly_tr('Baca selengkapnya →', 'Read more →'); ?></a>
        </div>
    </div>

    <div id="wdc-giveaway-items" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:20px;">
        <?php foreach (wdc_get_giveaway_items() as $item) : ?>
        <label class="wdc-giveaway-card" data-item-id="<?php echo esc_attr($item['id']); ?>" style="background:#fff;border:2px solid #e5e7eb;border-radius:16px;padding:20px;cursor:pointer;transition:all .2s;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;">
            <input type="checkbox" name="wdc_giveaway_items[]" value="<?php echo esc_attr($item['id']); ?>" style="position:absolute;top:12px;right:12px;width:20px;height:20px;accent-color:#10b981;">
            <div style="width:80px;height:80px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:36px;">
                <?php
                $icons = ['sticker-pack' => '🏷️', 'lanyard' => '🪢', 'keychain' => '🔑'];
                echo $icons[$item['id']] ?? '🎁';
                ?>
            </div>
            <strong style="font-size:15px;color:#0f172a;margin-bottom:4px;"><?php echo esc_html($item['name']); ?></strong>
            <span style="font-size:13px;color:#64748b;"><?php echo esc_html($item['desc']); ?></span>
            <span style="font-size:11px;color:#10b981;font-weight:800;margin-top:8px;text-transform:uppercase;"><?php echo contenly_tr('GRATIS', 'FREE'); ?></span>
        </label>
        <?php endforeach; ?>
    </div>

    <?php
    $gw_origin = function_exists('wdc_giveaway_origin_label') ? wdc_giveaway_origin_label() : 'Jakarta Selatan (12240)';
    $gw_ongkir_url = function_exists('wdc_giveaway_external_ongkir_url') ? wdc_giveaway_external_ongkir_url() : 'https://cekongkir.com/';
    ?>
    <!-- Shipping address form (hidden until items selected) -->
    <div id="wdc-giveaway-shipping" style="display:none;background:#fff;border-radius:16px;padding:24px;border:1px solid #e5e7eb;">
        <h3 style="font-size:18px;font-weight:800;color:#0f172a;margin:0 0 8px;"><?php echo contenly_tr('Detail Pengiriman', 'Shipping Details'); ?></h3>
        <p style="font-size:13px;color:#64748b;margin:0 0 16px;line-height:1.6;"><?php echo contenly_tr('Barang gratis. Ongkir dihitung di web cek ongkir luar, lalu isi nominal sesuai SS.', 'Items free. Check shipping on external site, then enter amount matching the screenshot.'); ?></p>

        <div style="display:grid;gap:14px;">
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Nama Lengkap', 'Full Name'); ?> *</label>
                <input type="text" id="wdc-gw-name" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="Nama penerima">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('No. HP', 'Phone Number'); ?> *</label>
                <input type="tel" id="wdc-gw-phone" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Kota / Kodepos Tujuan', 'Destination City / Postal Code'); ?> *</label>
                <input type="text" id="wdc-gw-city" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="Contoh: Bandung / 40111" autocomplete="off">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Alamat Lengkap', 'Full Address'); ?> *</label>
                <textarea id="wdc-gw-address" rows="3" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;resize:vertical;" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
            </div>
        </div>

        <div style="margin-top:18px;padding:16px;border-radius:14px;background:#eff6ff;border:1px solid #bfdbfe;">
            <div style="font-size:14px;font-weight:900;color:#1e3a8a;margin-bottom:8px;"><?php echo contenly_tr('Cek Ongkir di Web Lain (sementara)', 'Check Shipping on External Site (temporary)'); ?></div>
            <ol style="margin:0 0 12px 18px;padding:0;color:#1e40af;font-size:13px;line-height:1.7;">
                <li><?php echo contenly_tr('Asal kirim:', 'Ship from:'); ?> <strong><?php echo esc_html($gw_origin); ?></strong></li>
                <li><?php echo contenly_tr('Isi kota tujuan + berat item yang dipilih (gram).', 'Enter destination city + selected items weight (grams).'); ?></li>
                <li><?php echo contenly_tr('Screenshot hasil cek ongkir, lalu isi kurir + nominal di bawah.', 'Screenshot the quote, then fill courier + amount below.'); ?></li>
            </ol>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                <span id="wdc-gw-weight-hint" style="display:inline-flex;align-items:center;padding:8px 12px;border-radius:999px;background:#fff;border:1px solid #93c5fd;color:#1d4ed8;font-size:12px;font-weight:800;">0g</span>
                <a id="wdc-gw-open-ongkir" href="<?php echo esc_url($gw_ongkir_url); ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;padding:10px 14px;border-radius:999px;background:#2563eb;color:#fff;text-decoration:none;font-size:13px;font-weight:900;"><?php echo contenly_tr('Buka Cek Ongkir', 'Open Shipping Checker'); ?> →</a>
            </div>
            <p style="margin:0;font-size:12px;color:#1e40af;"><?php echo contenly_tr('Link default: cekongkir.com — admin bisa ganti di Giveaway Settings.', 'Default link: cekongkir.com — admin can change in Giveaway Settings.'); ?></p>
        </div>

        <div style="display:grid;gap:14px;margin-top:16px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Kurir', 'Courier'); ?> *</label>
                    <input type="text" id="wdc-gw-courier" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="JNE / JNT / SiCepat">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Layanan', 'Service'); ?></label>
                    <input type="text" id="wdc-gw-service" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="REG / EZ / BEST">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Ongkir (Rp) sesuai SS', 'Shipping (Rp) from screenshot'); ?> *</label>
                <input type="text" id="wdc-gw-shipping-cost" inputmode="numeric" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="Contoh: 18000">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Screenshot Cek Ongkir', 'Shipping Quote Screenshot'); ?> *</label>
                <input type="file" id="wdc-gw-quote-ss" accept="image/*" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;background:#fff;">
                <p style="margin:6px 0 0;font-size:12px;color:#64748b;"><?php echo contenly_tr('JPG/PNG, maks 5MB. Harus jelas nominal + kurir.', 'JPG/PNG, max 5MB. Must clearly show amount + courier.'); ?></p>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Catatan Quote (opsional)', 'Quote Notes (optional)'); ?></label>
                <input type="text" id="wdc-gw-quote-notes" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="Contoh: JNE REG 2-3 hari">
            </div>
        </div>

        <button id="wdc-btn-claim-giveaway" style="margin-top:16px;width:100%;padding:16px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;border:none;border-radius:12px;font-size:16px;font-weight:900;cursor:pointer;">
            🎁 <?php echo contenly_tr('Klaim Giveaway & Lanjut Bayar Ongkir', 'Claim Giveaway & Continue Shipping Payment'); ?>
        </button>
    </div>

    <div id="wdc-giveaway-error" style="display:none;background:#fee2e2;color:#991b1b;border-radius:10px;padding:12px;margin-top:12px;font-size:14px;font-weight:600;"></div>
</section>

<style>
.wdc-giveaway-card:hover { border-color:#10b981 !important; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
.wdc-giveaway-card.selected { border-color:#10b981 !important; background:#f0fdf4 !important; box-shadow:0 0 0 3px rgba(16,185,129,.2); }
@media(max-width:640px){
  #wdc-giveaway-shipping div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}
}
</style>

<script>
jQuery(document).ready(function($) {
    var selectedItems = [];
    var itemWeights = {
<?php
$w_js = [];
foreach (wdc_get_giveaway_items() as $item) {
    $w_js[] = "        '" . esc_js($item['id']) . "': " . intval($item['weight']);
}
echo implode(",\n", $w_js) . "\n";
?>
    };

    function updateWeightHint() {
        var total = 0;
        selectedItems.forEach(function(id){ total += (itemWeights[id] || 0); });
        $('#wdc-gw-weight-hint').text(total + 'g · item terpilih: ' + selectedItems.length);
    }

    $('.wdc-giveaway-card input[type="checkbox"]').on('change', function() {
        var card = $(this).closest('.wdc-giveaway-card');
        if (this.checked) card.addClass('selected'); else card.removeClass('selected');
        selectedItems = [];
        $('.wdc-giveaway-card input:checked').each(function(){ selectedItems.push($(this).val()); });
        updateWeightHint();
        if (selectedItems.length > 0) $('#wdc-giveaway-shipping').slideDown(200);
        else $('#wdc-giveaway-shipping').slideUp(200);
    });
    updateWeightHint();

    function onlyDigits(v){ return String(v || '').replace(/\D+/g, ''); }

    $('#wdc-gw-shipping-cost').on('input', function(){
        var d = onlyDigits(this.value);
        this.value = d ? Number(d).toLocaleString('id-ID') : '';
    });

    $('#wdc-btn-claim-giveaway').on('click', function() {
        var btn = $(this);
        var name = $('#wdc-gw-name').val().trim();
        var phone = $('#wdc-gw-phone').val().trim();
        var address = $('#wdc-gw-address').val().trim();
        var city = $('#wdc-gw-city').val().trim();
        var courier = $('#wdc-gw-courier').val().trim();
        var service = $('#wdc-gw-service').val().trim();
        var cost = parseInt(onlyDigits($('#wdc-gw-shipping-cost').val()), 10) || 0;
        var quoteFile = $('#wdc-gw-quote-ss')[0].files[0];
        var notes = $('#wdc-gw-quote-notes').val().trim();

        if (selectedItems.length === 0) {
            showGiveawayError('<?php echo contenly_tr('Pilih minimal 1 item giveaway.', 'Select at least 1 giveaway item.'); ?>');
            return;
        }
        if (!name || !phone || !address || !city) {
            showGiveawayError('<?php echo contenly_tr('Lengkapi nama, HP, kota, dan alamat.', 'Complete name, phone, city, and address.'); ?>');
            return;
        }
        if (!courier || cost < 1000) {
            showGiveawayError('<?php echo contenly_tr('Isi kurir + ongkir sesuai SS (min Rp1.000).', 'Fill courier + shipping from screenshot (min Rp1,000).'); ?>');
            return;
        }
        if (!quoteFile) {
            showGiveawayError('<?php echo contenly_tr('Upload screenshot cek ongkir dulu.', 'Upload shipping quote screenshot first.'); ?>');
            return;
        }

        btn.prop('disabled', true).html('⏳ Memproses...');
        hideGiveawayError();

        var fd = new FormData();
        fd.append('action', 'wdc_submit_giveaway');
        fd.append('nonce', wdcGiveawayAjax.nonce);
        selectedItems.forEach(function(id){ fd.append('item_ids[]', id); });
        fd.append('courier', courier);
        fd.append('service', service || 'manual');
        fd.append('shipping_cost', cost);
        fd.append('destination', city);
        fd.append('dest_area_id', '');
        fd.append('address', address);
        fd.append('phone', phone);
        fd.append('recipient_name', name);
        fd.append('quote_source', '<?php echo esc_js($gw_ongkir_url); ?>');
        fd.append('quote_notes', notes);
        fd.append('shipping_quote_ss', quoteFile);

        $.ajax({
            url: wdcGiveawayAjax.ajaxurl,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res) {
                if (!res || !res.success) {
                    showGiveawayError((res && res.data && res.data.message) ? res.data.message : '<?php echo contenly_tr('Gagal claim giveaway.', 'Failed to claim giveaway.'); ?>');
                    btn.prop('disabled', false).html('🎁 <?php echo contenly_tr('Klaim Giveaway & Lanjut Bayar Ongkir', 'Claim Giveaway & Continue Shipping Payment'); ?>');
                    return;
                }
                window.location.href = res.data.checkout_url;
            },
            error: function() {
                showGiveawayError('<?php echo contenly_tr('Gagal menghubungi server.', 'Failed to reach server.'); ?>');
                btn.prop('disabled', false).html('🎁 <?php echo contenly_tr('Klaim Giveaway & Lanjut Bayar Ongkir', 'Claim Giveaway & Continue Shipping Payment'); ?>');
            }
        });
    });

    function showGiveawayError(msg) { $('#wdc-giveaway-error').text(msg).show(); }
    function hideGiveawayError() { $('#wdc-giveaway-error').hide(); }
});
</script>
<?php endif; ?>

<?php
// Giveaway progress tracker for claimed members
$gw_order = is_user_logged_in() ? get_user_meta(get_current_user_id(), '_wdc_giveaway_order', true) : null;
if (is_array($gw_order) && !empty($gw_order['order_id']) && function_exists('wdc_giveaway_status_meta')) :
    $gw_status = sanitize_key($gw_order['status'] ?? 'awaiting_payment');
    $gw_meta = wdc_giveaway_status_meta($gw_status);
    $gw_steps = wdc_giveaway_progress_steps();
    $gw_step_keys = array_keys($gw_steps);
    $gw_current_step = max(0, array_search($gw_status === 'cancelled' ? 'awaiting_payment' : $gw_status, $gw_step_keys, true));
    if ($gw_status === 'cancelled') { $gw_current_step = -1; }
    $gw_items_all = wdc_get_giveaway_items();
    $gw_item_names = [];
    foreach ($gw_items_all as $it) {
        if (in_array($it['id'], $gw_order['items'] ?? [], true)) {
            $gw_item_names[] = $it['name'];
        }
    }
    $gw_track_no = $gw_order['tracking_number'] ?? '';
    $gw_track_url = !empty($gw_order['tracking_url']) ? $gw_order['tracking_url'] : wdc_giveaway_tracking_url($gw_order['courier'] ?? '', $gw_track_no);
    $gw_checkout = add_query_arg(['type' => 'giveaway', 'order' => $gw_order['order_id']], contenly_localized_url('/giveaway-checkout/'));
?>
<section id="wdc-giveaway-progress" style="background:linear-gradient(135deg,#f0f9ff,#ecfeff);border:2px solid #7dd3fc;border-radius:20px;padding:24px;margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:flex-start;flex-wrap:wrap;margin-bottom:16px;">
        <div>
            <div style="font-size:12px;font-weight:950;text-transform:uppercase;letter-spacing:.08em;color:#0369a1;margin-bottom:4px;"><?php echo contenly_tr('Progres Giveaway', 'Giveaway Progress'); ?></div>
            <h2 style="font-size:22px;font-weight:900;color:#0f172a;margin:0 0 4px;"><?php echo esc_html($gw_order['order_id']); ?></h2>
            <div style="font-size:13px;color:#475569;"><?php echo esc_html(implode(', ', $gw_item_names) ?: 'Giveaway items'); ?> · Ongkir Rp <?php echo number_format(intval($gw_order['shipping_cost'] ?? 0), 0, ',', '.'); ?></div>
        </div>
        <span style="display:inline-flex;align-items:center;padding:8px 14px;border-radius:999px;background:<?php echo esc_attr($gw_meta['bg']); ?>;color:<?php echo esc_attr($gw_meta['color']); ?>;font-size:13px;font-weight:900;"><?php echo esc_html($gw_meta['label']); ?></span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:8px;margin-bottom:18px;">
        <?php foreach ($gw_step_keys as $idx => $key) :
            $done = $gw_current_step >= $idx;
            $active = $gw_current_step === $idx;
            ?>
            <div style="background:<?php echo $done ? '#dcfce7' : '#fff'; ?>;border:1px solid <?php echo $active ? '#0ea5e9' : ($done ? '#86efac' : '#e2e8f0'); ?>;border-radius:12px;padding:10px 8px;text-align:center;">
                <div style="font-size:11px;font-weight:900;color:<?php echo $done ? '#166534' : '#64748b'; ?>;line-height:1.3;"><?php echo esc_html($gw_steps[$key]); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="display:grid;gap:10px;background:#fff;border:1px solid #bae6fd;border-radius:14px;padding:16px;">
        <div style="font-size:14px;color:#334155;line-height:1.7;">
            <div><strong><?php echo contenly_tr('Penerima', 'Recipient'); ?>:</strong> <?php echo esc_html($gw_order['recipient_name'] ?? '-'); ?> · <?php echo esc_html($gw_order['phone'] ?? '-'); ?></div>
            <div><strong><?php echo contenly_tr('Alamat', 'Address'); ?>:</strong> <?php echo esc_html($gw_order['address'] ?? '-'); ?>, <?php echo esc_html($gw_order['destination'] ?? '-'); ?></div>
            <div><strong><?php echo contenly_tr('Kurir', 'Courier'); ?>:</strong> <?php echo esc_html(strtoupper($gw_order['courier'] ?? '-')); ?> <?php echo esc_html($gw_order['service'] ?? ''); ?></div>
            <?php if (!empty($gw_order['admin_note'])) : ?>
            <div><strong><?php echo contenly_tr('Catatan crew', 'Crew note'); ?>:</strong> <?php echo esc_html($gw_order['admin_note']); ?></div>
            <?php endif; ?>
        </div>

        <?php if (in_array($gw_status, ['awaiting_payment'], true)) : ?>
            <a href="<?php echo esc_url($gw_checkout); ?>" style="display:inline-flex;justify-content:center;padding:12px 16px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;text-decoration:none;font-weight:900;">
                <?php echo contenly_tr('Lanjut Bayar Ongkir / Upload Bukti', 'Continue Payment / Upload Proof'); ?>
            </a>
        <?php elseif ($gw_status === 'payment_uploaded') : ?>
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:12px;color:#1e40af;font-size:13px;font-weight:700;">
                <?php echo contenly_tr('Bukti transfer sudah diterima. Menunggu admin verifikasi.', 'Transfer proof received. Waiting for admin verification.'); ?>
            </div>
        <?php elseif ($gw_status === 'verified') : ?>
            <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;padding:12px;color:#065f46;font-size:13px;font-weight:700;">
                <?php echo contenly_tr('Pembayaran diverifikasi. Crew sedang siapkan pengiriman.', 'Payment verified. Crew is preparing shipment.'); ?>
            </div>
        <?php elseif (in_array($gw_status, ['shipped', 'delivered'], true) && $gw_track_no) : ?>
            <div style="display:grid;gap:10px;">
                <div style="background:#f5f3ff;border:1px solid #ddd6fe;border-radius:12px;padding:12px;">
                    <div style="font-size:12px;font-weight:900;color:#5b21b6;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;"><?php echo contenly_tr('Nomor Resi', 'Tracking Number'); ?></div>
                    <div style="font-size:20px;font-weight:950;color:#0f172a;letter-spacing:.04em;"><?php echo esc_html($gw_track_no); ?></div>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;"><?php echo contenly_tr('Pakai resi ini untuk cek posisi paket di situs kurir.', 'Use this tracking number to check package progress on courier site.'); ?></div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <?php if ($gw_track_url) : ?>
                    <a href="<?php echo esc_url($gw_track_url); ?>" target="_blank" rel="noopener" style="display:inline-flex;padding:12px 16px;border-radius:12px;background:#6d28d9;color:#fff;text-decoration:none;font-weight:900;">
                        <?php echo contenly_tr('Cek Tracking Resi →', 'Track Package →'); ?>
                    </a>
                    <?php endif; ?>
                    <button type="button" id="wdc-copy-resi" data-resi="<?php echo esc_attr($gw_track_no); ?>" style="display:inline-flex;padding:12px 16px;border-radius:12px;background:#fff;border:1px solid #c4b5fd;color:#5b21b6;font-weight:900;cursor:pointer;">
                        <?php echo contenly_tr('Salin Resi', 'Copy Tracking No.'); ?>
                    </button>
                </div>
            </div>
            <script>
            jQuery(function($){
                $('#wdc-copy-resi').on('click', function(){
                    var v = $(this).data('resi') || '';
                    if (!v) return;
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(v);
                    } else {
                        var ta = document.createElement('textarea'); ta.value=v; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
                    }
                    $(this).text('<?php echo esc_js(contenly_tr('Tersalin!', 'Copied!')); ?>');
                });
            });
            </script>
        <?php elseif ($gw_status === 'cancelled') : ?>
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:12px;color:#991b1b;font-size:13px;font-weight:700;">
                <?php echo contenly_tr('Claim dibatalkan. Hubungi crew jika ada pertanyaan.', 'Claim cancelled. Contact crew if you have questions.'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<style>
@media(max-width:720px){
  #wdc-giveaway-progress > div[style*="grid-template-columns:repeat(5"]{grid-template-columns:1fr 1fr!important}
}
</style>
<?php endif; ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;margin-bottom:28px;">
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <span style="font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;"><?php echo contenly_tr('Belajar', 'Learn'); ?></span>
        <h2 style="font-size:24px;color:#0f172a;margin:10px 0 10px;letter-spacing:.03em;"><?php echo contenly_tr('Gabung Kursus Menyelam', 'Join a dive course'); ?></h2>
        <p style="color:#64748b;line-height:1.65;margin:0 0 18px;"><?php echo contenly_tr('Mulai Open Water, lanjut ke Advanced, atau bangun keterampilan rescue dan kepemimpinan bersama kru.', 'Start Open Water, continue to Advanced, or build safer rescue and leadership skills with the crew.'); ?></p>
        <a href="/my-courses/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Buka Kursus Saya', 'Open My Courses'); ?></a>
    </article>
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <span style="font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;"><?php echo contenly_tr('Peralatan', 'Gear'); ?></span>
        <h2 style="font-size:24px;color:#0f172a;margin:10px 0 10px;letter-spacing:.03em;"><?php echo contenly_tr('Beli Peralatan Selam', 'Buy scuba equipment'); ?></h2>
        <p style="color:#64748b;line-height:1.65;margin:0 0 18px;"><?php echo contenly_tr('Jelajahi masker, fin, BCD, regulator, baju selam, dan dive computer dengan bantuan fitting sebelum checkout.', 'Browse masks, fins, BCDs, regulators, wetsuits, and dive computers with fit support before checkout.'); ?></p>
        <a href="/my-gear/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Buka Peralatan Saya', 'Open My Gear'); ?></a>
    </article>
</div>


<?php
$recent_activity = array_slice(array_merge($course_orders, $gear_orders, $course_requests, $gear_requests), 0, 5);
$status_steps = ['Payment Uploaded' => 'Proof received', 'Verified' => 'Payment verified', 'Active' => 'Ready / active', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled', 'Requested' => 'Crew review', 'Awaiting Payment' => 'Waiting for payment'];
?>

<!-- Manual Orders Section (from WA / admin input) -->
<?php if (!empty($manual_orders)) :
    $order_statuses = wdc_get_order_statuses();
?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;box-shadow:0 12px 34px rgba(15,23,42,.05);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0;letter-spacing:.03em;"><?php echo contenly_tr('Pesanan Saya', 'My Orders'); ?></h2>
        <span style="font-size:12px;font-weight:800;background:#e8f8fc;color:#0b617c;border-radius:999px;padding:6px 12px;"><?php echo $manual_total; ?> <?php echo contenly_tr('pesanan', 'orders'); ?></span>
    </div>
    <div style="display:grid;gap:10px;">
        <?php foreach ($manual_orders as $mo) :
            $code    = get_post_meta($mo->ID, '_wdc_order_code', true);
            $item    = get_post_meta($mo->ID, '_wdc_item_name', true);
            $total   = get_post_meta($mo->ID, '_wdc_total_price', true);
            $os      = get_post_meta($mo->ID, '_wdc_order_status', true) ?: 'pending';
            $s_info  = $order_statuses[$os] ?? ['label' => $os, 'color' => '#6b7280'];
        ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php echo esc_html($item); ?></strong>
                <div style="font-size:13px;color:#64748b;"><?php echo esc_html($code); ?> · Rp <?php echo number_format($total, 0, ',', '.'); ?></div>
                <div style="font-size:12px;color:#94a3b8;"><?php echo get_the_date('d M Y', $mo); ?></div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:12px;font-weight:900;color:<?php echo $s_info['color']; ?>;background:<?php echo $s_info['color']; ?>22;border-radius:999px;padding:6px 12px;"><?php echo esc_html($s_info['label']); ?></span>
                <a href="<?php echo esc_url(home_url('/invoice/' . $code . '/')); ?>" style="font-size:12px;font-weight:800;color:#0b617c;text-decoration:none;">📄 Invoice</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php if (!empty($recent_activity)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;box-shadow:0 12px 34px rgba(15,23,42,.05);">
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;letter-spacing:.03em;"><?php echo contenly_tr('Aktivitas Terbaru', 'Latest Activity'); ?></h2>
    <div style="display:grid;gap:10px;">
        <?php foreach ($recent_activity as $item) : $status = $item['status'] ?? 'Requested'; ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php echo esc_html($item['item'] ?? $item['course'] ?? $item['gear'] ?? 'Member item'); ?></strong>
                <div style="font-size:13px;color:#64748b;"><?php echo esc_html($status_steps[$status] ?? 'Crew update'); ?> · <?php echo esc_html($item['admin_note'] ?? ($item['id'] ?? 'Crew will update this soon.')); ?></div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:8px;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.04em;">
                    <?php foreach (['Payment Uploaded', 'Verified', 'Active'] as $step) : $done = array_search($status, ['Payment Uploaded', 'Verified', 'Active', 'Completed'], true) !== false && array_search($step, ['Payment Uploaded', 'Verified', 'Active'], true) <= array_search($status, ['Payment Uploaded', 'Verified', 'Active', 'Completed'], true); ?>
                    <span style="padding:5px 8px;border-radius:999px;background:<?php echo $done ? '#dcfce7' : '#f1f5f9'; ?>;color:<?php echo $done ? '#166534' : '#64748b'; ?>;"><?php echo esc_html($step); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <span style="font-size:12px;font-weight:900;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($status); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<div style="background:linear-gradient(135deg,#f8fdff,#eef9fc);border:1px solid #ccecf5;border-radius:20px;padding:22px;">
    <div style="font-size:12px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;margin-bottom:6px;"><?php echo contenly_tr('Langkah Selanjutnya', 'Recommended next step'); ?></div>
    <h2 style="font-size:22px;color:#06384d;margin:0 0 6px;letter-spacing:.03em;"><?php echo contenly_tr('Pilih kursus atau minta saran peralatan.', 'Pick a course or request gear advice.'); ?></h2>
    <p style="color:#64748b;margin:0 0 16px;line-height:1.6;"><?php echo contenly_tr('Area member fokus pada kebutuhan anggota: kursus menyelam dan membeli peralatan selam yang tepat.', 'The member area now focuses on what Whale Dive Centre members need most: joining courses and buying the right dive gear.'); ?></p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;"><a href="/my-courses/" style="display:inline-flex;padding:10px 14px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Lihat Kursus', 'Browse Courses'); ?></a><a href="/my-gear/" style="display:inline-flex;padding:10px 14px;border-radius:999px;background:#fff;color:#06384d;text-decoration:none;font-weight:900;border:1px solid #ccecf5;"><?php echo contenly_tr('Lihat Peralatan', 'Browse Gear'); ?></a></div>
</div>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
