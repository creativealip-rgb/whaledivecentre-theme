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
<div class="wdc-page-head">
    <h1><?php echo contenly_tr('Dashboard Member', 'Member Dashboard'); ?></h1>
    <p class="wdc-page-sub"><?php echo contenly_tr('Pusat Whale Dive Centre untuk perencanaan kursus, permintaan alat selam, dan dukungan kru.', 'Your Whale Dive Centre hub for course planning, scuba gear requests, and crew support.'); ?></p>
</div>



<!-- Giveaway Section (only for users who haven't claimed) — pulls from Informasi post -->
<?php if (is_user_logged_in() && get_option('wdc_giveaway_enabled', '1') && wdc_is_new_user() && !wdc_user_claimed_giveaway()) :
    $giveaway_post = get_posts([
        'post_type' => 'wdc_info',
        'meta_key' => '_wdc_giveaway_active',
        'meta_value' => '1',
        'posts_per_page' => 1,
        'post_status' => 'publish',
    ]);
    $gw_title = $giveaway_post ? $giveaway_post[0]->post_title : contenly_tr('Pilih Giveaway Kamu', 'Pick Your Giveaway');
    $gw_excerpt = $giveaway_post ? $giveaway_post[0]->post_excerpt : contenly_tr('Barang gratis — bayar ongkir saja.', 'Items free — just pay shipping.');
    $gw_link = $giveaway_post ? get_permalink($giveaway_post[0]->ID) : contenly_localized_url('/informasi/');
    $gw_origin = function_exists('wdc_giveaway_origin_label') ? wdc_giveaway_origin_label() : 'Jakarta Selatan (12240)';
    $gw_ongkir_url = function_exists('wdc_giveaway_external_ongkir_url') ? wdc_giveaway_external_ongkir_url() : 'https://cekongkir.com/';
?>
<section id="wdc-giveaway-section" class="wdc-gw">
    <div class="wdc-gw-head">
        <div>
            <span class="wdc-gw-badge"><?php echo contenly_tr('Member baru', 'New member'); ?></span>
            <h2 class="wdc-gw-title"><?php echo esc_html($gw_title); ?></h2>
            <p class="wdc-gw-sub"><?php echo esc_html($gw_excerpt); ?></p>
            <a class="wdc-gw-more" href="<?php echo esc_url($gw_link); ?>"><?php echo contenly_tr('Detail giveaway', 'Giveaway details'); ?> →</a>
        </div>
        <div class="wdc-gw-head-note"><?php echo contenly_tr('Pilih 1–3 item gratis', 'Pick 1–3 free items'); ?></div>
    </div>

    <div id="wdc-giveaway-items" class="wdc-gw-items">
        <?php foreach (wdc_get_giveaway_items() as $item) :
            $icons = ['sticker-pack' => '🏷️', 'lanyard' => '🪢', 'keychain' => '🔑'];
            $icon = $icons[$item['id']] ?? '🎁';
        ?>
        <label class="wdc-giveaway-card wdc-gw-card" data-item-id="<?php echo esc_attr($item['id']); ?>">
            <input type="checkbox" name="wdc_giveaway_items[]" value="<?php echo esc_attr($item['id']); ?>">
            <span class="wdc-gw-check" aria-hidden="true"></span>
            <span class="wdc-gw-icon"><?php echo $icon; ?></span>
            <span class="wdc-gw-card-body">
                <strong><?php echo esc_html($item['name']); ?></strong>
                <em><?php echo esc_html($item['desc']); ?></em>
                <b><?php echo contenly_tr('GRATIS', 'FREE'); ?> · <?php echo intval($item['weight']); ?>g</b>
            </span>
        </label>
        <?php endforeach; ?>
    </div>

    <div id="wdc-giveaway-shipping" class="wdc-gw-ship" style="display:none;">
        <div class="wdc-gw-step">
            <span>1</span>
            <div>
                <h3><?php echo contenly_tr('Detail Pengiriman', 'Shipping Details'); ?></h3>
                <p><?php echo contenly_tr('Isi data penerima. Barang gratis, kamu cukup bayar ongkir.', 'Fill recipient details. Items free — you only pay shipping.'); ?></p>
            </div>
        </div>

        <div class="wdc-gw-fields">
            <div class="wdc-gw-field">
                <label for="wdc-gw-name"><?php echo contenly_tr('Nama Lengkap', 'Full Name'); ?> *</label>
                <input type="text" id="wdc-gw-name" placeholder="Nama penerima" autocomplete="name">
            </div>
            <div class="wdc-gw-field">
                <label for="wdc-gw-phone"><?php echo contenly_tr('No. HP', 'Phone Number'); ?> *</label>
                <input type="tel" id="wdc-gw-phone" placeholder="08xxxxxxxxxx" autocomplete="tel">
            </div>
            <div class="wdc-gw-field">
                <label for="wdc-gw-city"><?php echo contenly_tr('Kota / Kodepos', 'City / Postal Code'); ?> *</label>
                <input type="text" id="wdc-gw-city" placeholder="Contoh: Bandung / 40111" autocomplete="address-level2">
            </div>
            <div class="wdc-gw-field wdc-gw-field-full">
                <label for="wdc-gw-address"><?php echo contenly_tr('Alamat Lengkap', 'Full Address'); ?> *</label>
                <textarea id="wdc-gw-address" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
            </div>
        </div>

        <div class="wdc-gw-step" style="margin-top:18px;">
            <span>2</span>
            <div>
                <h3><?php echo contenly_tr('Cek Ongkir', 'Check Shipping'); ?></h3>
                <p><?php echo contenly_tr('Sementara cek di web luar, lalu isi nominal sesuai screenshot.', 'For now check on external site, then enter amount from screenshot.'); ?></p>
            </div>
        </div>

        <div class="wdc-gw-ongkir">
            <div class="wdc-gw-ongkir-meta">
                <div><small><?php echo contenly_tr('Asal', 'From'); ?></small><strong><?php echo esc_html($gw_origin); ?></strong></div>
                <div><small><?php echo contenly_tr('Berat', 'Weight'); ?></small><strong id="wdc-gw-weight-hint">0g</strong></div>
            </div>
            <a id="wdc-gw-open-ongkir" class="wdc-gw-btn-secondary" href="<?php echo esc_url($gw_ongkir_url); ?>" target="_blank" rel="noopener">
                <?php echo contenly_tr('Buka Cek Ongkir', 'Open Shipping Checker'); ?> →
            </a>
            <ol class="wdc-gw-help">
                <li><?php echo contenly_tr('Pakai asal + berat di atas.', 'Use origin + weight above.'); ?></li>
                <li><?php echo contenly_tr('Screenshot hasil cek ongkir.', 'Screenshot the shipping quote.'); ?></li>
                <li><?php echo contenly_tr('Isi kurir + nominal di bawah.', 'Fill courier + amount below.'); ?></li>
            </ol>
        </div>

        <div class="wdc-gw-fields" style="margin-top:14px;">
            <div class="wdc-gw-field">
                <label for="wdc-gw-courier"><?php echo contenly_tr('Kurir', 'Courier'); ?> *</label>
                <input type="text" id="wdc-gw-courier" placeholder="JNE / J&T / SiCepat">
            </div>
            <div class="wdc-gw-field">
                <label for="wdc-gw-service"><?php echo contenly_tr('Layanan', 'Service'); ?></label>
                <input type="text" id="wdc-gw-service" placeholder="REG / EZ / BEST">
            </div>
            <div class="wdc-gw-field">
                <label for="wdc-gw-shipping-cost"><?php echo contenly_tr('Ongkir (Rp)', 'Shipping (Rp)'); ?> *</label>
                <input type="text" id="wdc-gw-shipping-cost" inputmode="numeric" placeholder="Contoh: 18.000">
            </div>
            <div class="wdc-gw-field">
                <label for="wdc-gw-quote-notes"><?php echo contenly_tr('Catatan (opsional)', 'Notes (optional)'); ?></label>
                <input type="text" id="wdc-gw-quote-notes" placeholder="JNE REG 2-3 hari">
            </div>
            <div class="wdc-gw-field wdc-gw-field-full">
                <label for="wdc-gw-quote-ss"><?php echo contenly_tr('Screenshot Cek Ongkir', 'Shipping Quote Screenshot'); ?> *</label>
                <div class="wdc-gw-file">
                    <input type="file" id="wdc-gw-quote-ss" accept="image/*">
                    <span id="wdc-gw-file-label"><?php echo contenly_tr('Pilih gambar JPG/PNG (maks 5MB)', 'Choose JPG/PNG image (max 5MB)'); ?></span>
                </div>
            </div>
        </div>

        <button id="wdc-btn-claim-giveaway" class="wdc-gw-btn-primary" type="button">
            <?php echo contenly_tr('Klaim & Lanjut Bayar Ongkir', 'Claim & Continue Shipping Payment'); ?>
        </button>
    </div>

    <div id="wdc-giveaway-error" class="wdc-gw-error" style="display:none;"></div>
</section>

<style>
.wdc-gw{background:#fff;border:1px solid #e6edf2;border-radius:20px;padding:20px;margin-bottom:24px;box-shadow:0 10px 28px rgba(15,23,42,.05)}
.wdc-gw-head{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:16px}
.wdc-gw-badge{display:inline-flex;padding:5px 10px;border-radius:999px;background:#ecfeff;color:#0e7490;font-size:11px;font-weight:900;letter-spacing:.06em;text-transform:uppercase}
.wdc-gw-title{margin:8px 0 6px;font-size:22px;line-height:1.2;color:#0f172a;font-weight:900}
.wdc-gw-sub{margin:0;color:#64748b;font-size:14px;line-height:1.55}
.wdc-gw-more{display:inline-block;margin-top:8px;color:#0b617c;font-size:13px;font-weight:800;text-decoration:none}
.wdc-gw-head-note{flex:0 0 auto;padding:8px 12px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;color:#475569;font-size:12px;font-weight:800;white-space:nowrap}
.wdc-gw-items{display:grid;grid-template-columns:1fr;gap:10px;margin-bottom:4px}
.wdc-gw-card{position:relative;display:grid;grid-template-columns:44px 1fr;gap:12px;align-items:center;padding:14px;border:1.5px solid #e2e8f0;border-radius:16px;background:#fbfdff;cursor:pointer;transition:border-color .15s,background .15s,box-shadow .15s}
.wdc-gw-card input{position:absolute;opacity:0;pointer-events:none}
.wdc-gw-check{position:absolute;top:12px;right:12px;width:20px;height:20px;border-radius:999px;border:1.5px solid #cbd5e1;background:#fff}
.wdc-gw-card.selected{border-color:#0ea5e9;background:#f0f9ff;box-shadow:0 0 0 3px rgba(14,165,233,.12)}
.wdc-gw-card.selected .wdc-gw-check{border-color:#0284c7;background:#0284c7;box-shadow:inset 0 0 0 3px #fff}
.wdc-gw-icon{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;background:#e0f2fe;font-size:22px}
.wdc-gw-card-body{display:grid;gap:2px;min-width:0;padding-right:24px}
.wdc-gw-card-body strong{color:#0f172a;font-size:14px;font-weight:900}
.wdc-gw-card-body em{color:#64748b;font-style:normal;font-size:12px;line-height:1.4}
.wdc-gw-card-body b{color:#0284c7;font-size:11px;font-weight:900;letter-spacing:.04em;text-transform:uppercase}
.wdc-gw-ship{margin-top:16px;padding-top:16px;border-top:1px solid #eef2f7}
.wdc-gw-step{display:flex;gap:12px;align-items:flex-start;margin-bottom:12px}
.wdc-gw-step span{width:28px;height:28px;border-radius:999px;display:grid;place-items:center;background:#0b617c;color:#fff;font-size:13px;font-weight:900;flex:0 0 auto}
.wdc-gw-step h3{margin:0 0 2px;font-size:16px;color:#0f172a}
.wdc-gw-step p{margin:0;font-size:13px;color:#64748b;line-height:1.45}
.wdc-gw-fields{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.wdc-gw-field{display:grid;gap:6px}
.wdc-gw-field-full{grid-column:1 / -1}
.wdc-gw-field label{font-size:12px;font-weight:800;color:#475569}
.wdc-gw-field input,.wdc-gw-field textarea{width:100%;border:1px solid #dbe3ea;border-radius:12px;padding:12px 14px;font-size:14px;background:#fff;color:#0f172a;box-sizing:border-box}
.wdc-gw-field input:focus,.wdc-gw-field textarea:focus{outline:3px solid rgba(14,165,233,.18);border-color:#38bdf8}
.wdc-gw-ongkir{display:grid;gap:12px;padding:14px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0}
.wdc-gw-ongkir-meta{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.wdc-gw-ongkir-meta div{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:10px 12px}
.wdc-gw-ongkir-meta small{display:block;color:#64748b;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px}
.wdc-gw-ongkir-meta strong{color:#0f172a;font-size:13px}
.wdc-gw-help{margin:0;padding-left:18px;color:#64748b;font-size:12px;line-height:1.55}
.wdc-gw-btn-secondary{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 16px;border-radius:999px;background:var(--wdc-btn-primary,#004A98);color:#fff!important;text-decoration:none;font-size:13px;font-weight:900}
.wdc-gw-btn-primary{display:inline-flex;align-items:center;justify-content:center;width:100%;min-height:48px;padding:0 16px;border:0;border-radius:999px;background:var(--wdc-btn-primary,#004A98);color:#fff!important;font-size:14px;font-weight:900;cursor:pointer;box-shadow:var(--wdc-btn-shadow,0 10px 22px rgba(6,56,77,.18))}
.wdc-gw-btn-primary:disabled{opacity:.7;cursor:wait}
.wdc-gw-file{display:flex;align-items:center;gap:10px;padding:12px;border:1.5px dashed #cbd5e1;border-radius:12px;background:#fff}
.wdc-gw-file input{max-width:140px}
.wdc-gw-file span{color:#64748b;font-size:12px;line-height:1.4}
.wdc-gw-error{margin-top:12px;padding:12px 14px;border-radius:12px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:13px;font-weight:700}
@media(min-width:760px){
  .wdc-gw{padding:24px}
  .wdc-gw-items{grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
  .wdc-gw-card{grid-template-columns:1fr;text-align:center;padding:18px 14px 16px;min-height:180px}
  .wdc-gw-icon{margin:0 auto}
  .wdc-gw-card-body{padding-right:0}
}
@media(max-width:640px){
  .wdc-gw-head{flex-direction:column}
  .wdc-gw-head-note{white-space:normal}
  .wdc-gw-fields{grid-template-columns:1fr}
  .wdc-gw-title{font-size:20px}
  .wdc-gw-file{flex-direction:column;align-items:flex-start}
  .wdc-gw-file input{max-width:100%}
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
        $('#wdc-gw-weight-hint').text(total + 'g · ' + selectedItems.length + ' item');
    }

    $('.wdc-giveaway-card input[type="checkbox"]').on('change', function() {
        var card = $(this).closest('.wdc-giveaway-card');
        if (this.checked) card.addClass('selected'); else card.removeClass('selected');
        selectedItems = [];
        $('.wdc-giveaway-card input:checked').each(function(){ selectedItems.push($(this).val()); });
        updateWeightHint();
        if (selectedItems.length > 0) $('#wdc-giveaway-shipping').slideDown(180);
        else $('#wdc-giveaway-shipping').slideUp(180);
    });
    updateWeightHint();

    function onlyDigits(v){ return String(v || '').replace(/\D+/g, ''); }

    $('#wdc-gw-shipping-cost').on('input', function(){
        var d = onlyDigits(this.value);
        this.value = d ? Number(d).toLocaleString('id-ID') : '';
    });

    $('#wdc-gw-quote-ss').on('change', function(){
        var f = this.files && this.files[0] ? this.files[0].name : '<?php echo esc_js(contenly_tr('Pilih gambar JPG/PNG (maks 5MB)', 'Choose JPG/PNG image (max 5MB)')); ?>';
        $('#wdc-gw-file-label').text(f);
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

        btn.prop('disabled', true).text('<?php echo esc_js(contenly_tr('Memproses...', 'Processing...')); ?>');
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
                    btn.prop('disabled', false).text('<?php echo esc_js(contenly_tr('Klaim & Lanjut Bayar Ongkir', 'Claim & Continue Shipping Payment')); ?>');
                    return;
                }
                window.location.href = res.data.checkout_url;
            },
            error: function() {
                showGiveawayError('<?php echo contenly_tr('Gagal menghubungi server.', 'Failed to reach server.'); ?>');
                btn.prop('disabled', false).text('<?php echo esc_js(contenly_tr('Klaim & Lanjut Bayar Ongkir', 'Claim & Continue Shipping Payment')); ?>');
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
<section id="wdc-giveaway-progress" class="wdc-card wdc-gw-progress">
    <div class="wdc-gw-progress-head">
        <div class="wdc-gw-progress-copy">
            <div class="wdc-gw-progress-kicker wdc-section-sub" style="margin:0 0 4px;text-transform:uppercase;letter-spacing:.08em;font-size:11px;font-weight:900;color:#0b617c;"><?php echo contenly_tr('Progres Giveaway', 'Giveaway Progress'); ?></div>
            <h2 class="wdc-gw-progress-title"><?php echo esc_html($gw_order['order_id']); ?></h2>
            <div class="wdc-gw-progress-meta"><?php echo esc_html(implode(', ', $gw_item_names) ?: 'Giveaway items'); ?> · Ongkir Rp <?php echo number_format(intval($gw_order['shipping_cost'] ?? 0), 0, ',', '.'); ?></div>
        </div>
        <span class="wdc-gw-progress-badge" style="background:<?php echo esc_attr($gw_meta['bg']); ?>;color:<?php echo esc_attr($gw_meta['color']); ?>;"><?php echo esc_html($gw_meta['label']); ?></span>
    </div>

    <div class="wdc-gw-progress-steps">
        <?php foreach ($gw_step_keys as $idx => $key) :
            $done = $gw_current_step >= $idx;
            $active = $gw_current_step === $idx;
            $cls = 'wdc-gw-progress-step';
            if ($done) $cls .= ' is-done';
            if ($active) $cls .= ' is-active';
            ?>
            <div class="<?php echo esc_attr($cls); ?>">
                <span class="wdc-gw-progress-step-no"><?php echo intval($idx + 1); ?></span>
                <span class="wdc-gw-progress-step-label"><?php echo esc_html($gw_steps[$key]); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="wdc-gw-progress-body">
        <div class="wdc-gw-progress-details">
            <div><strong><?php echo contenly_tr('Penerima', 'Recipient'); ?>:</strong> <?php echo esc_html($gw_order['recipient_name'] ?? '-'); ?> · <?php echo esc_html($gw_order['phone'] ?? '-'); ?></div>
            <div><strong><?php echo contenly_tr('Alamat', 'Address'); ?>:</strong> <?php echo esc_html($gw_order['address'] ?? '-'); ?>, <?php echo esc_html($gw_order['destination'] ?? '-'); ?></div>
            <div><strong><?php echo contenly_tr('Kurir', 'Courier'); ?>:</strong> <?php echo esc_html(strtoupper($gw_order['courier'] ?? '-')); ?> <?php echo esc_html($gw_order['service'] ?? ''); ?></div>
            <?php if (!empty($gw_order['admin_note'])) : ?>
            <div><strong><?php echo contenly_tr('Catatan crew', 'Crew note'); ?>:</strong> <?php echo esc_html($gw_order['admin_note']); ?></div>
            <?php endif; ?>
        </div>

        <?php if (in_array($gw_status, ['awaiting_payment'], true)) : ?>
            <a class="wdc-gw-progress-cta wdc-gw-progress-cta--warn" href="<?php echo esc_url($gw_checkout); ?>">
                <?php echo contenly_tr('Lanjut Bayar Ongkir / Upload Bukti', 'Continue Payment / Upload Proof'); ?>
            </a>
        <?php elseif ($gw_status === 'payment_uploaded') : ?>
            <div class="wdc-gw-progress-note wdc-gw-progress-note--info">
                <?php echo contenly_tr('Bukti transfer sudah diterima. Menunggu admin verifikasi.', 'Transfer proof received. Waiting for admin verification.'); ?>
            </div>
        <?php elseif ($gw_status === 'verified') : ?>
            <div class="wdc-gw-progress-note wdc-gw-progress-note--ok">
                <?php echo contenly_tr('Pembayaran diverifikasi. Crew sedang siapkan pengiriman.', 'Payment verified. Crew is preparing shipment.'); ?>
            </div>
        <?php elseif (in_array($gw_status, ['shipped', 'delivered'], true) && $gw_track_no) : ?>
            <div class="wdc-gw-progress-track">
                <div class="wdc-gw-progress-resi">
                    <div class="wdc-gw-progress-resi-label"><?php echo contenly_tr('Nomor Resi', 'Tracking Number'); ?></div>
                    <div class="wdc-gw-progress-resi-no"><?php echo esc_html($gw_track_no); ?></div>
                    <div class="wdc-gw-progress-resi-help"><?php echo contenly_tr('Pakai resi ini untuk cek posisi paket di situs kurir.', 'Use this tracking number to check package progress on courier site.'); ?></div>
                </div>
                <div class="wdc-gw-progress-actions">
                    <?php if ($gw_track_url) : ?>
                    <a class="wdc-gw-progress-cta" href="<?php echo esc_url($gw_track_url); ?>" target="_blank" rel="noopener">
                        <?php echo contenly_tr('Cek Tracking Resi →', 'Track Package →'); ?>
                    </a>
                    <?php endif; ?>
                    <button type="button" id="wdc-copy-resi" class="wdc-gw-progress-cta wdc-gw-progress-cta--ghost" data-resi="<?php echo esc_attr($gw_track_no); ?>">
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
            <div class="wdc-gw-progress-note wdc-gw-progress-note--danger">
                <?php echo contenly_tr('Claim dibatalkan. Hubungi crew jika ada pertanyaan.', 'Claim cancelled. Contact crew if you have questions.'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<style>
.wdc-gw-progress{width:100%;max-width:100%;box-sizing:border-box;margin-bottom:24px}
.wdc-gw-progress-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:16px}
.wdc-gw-progress-copy{min-width:0;flex:1 1 auto}
.wdc-gw-progress-kicker{font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#0369a1;margin-bottom:4px}
.wdc-gw-progress-title{font-size:22px;font-weight:900;color:#0f172a;margin:0 0 4px;line-height:1.2;word-break:break-word}
.wdc-gw-progress-meta{font-size:13px;color:#475569;line-height:1.45}
.wdc-gw-progress-badge{display:inline-flex;align-items:center;padding:8px 12px;border-radius:999px;font-size:12px;font-weight:900;white-space:nowrap;flex:0 0 auto}
.wdc-gw-progress-steps{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:8px;margin-bottom:16px}
.wdc-gw-progress-step{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;min-height:74px;padding:10px 8px;border-radius:14px;border:1px solid #e2e8f0;background:#fff;text-align:center;box-sizing:border-box}
.wdc-gw-progress-step.is-done{background:#dcfce7;border-color:#86efac}
.wdc-gw-progress-step.is-active{border-color:#0ea5e9;box-shadow:0 0 0 3px rgba(14,165,233,.12)}
.wdc-gw-progress-step-no{width:22px;height:22px;border-radius:999px;display:grid;place-items:center;background:#e2e8f0;color:#334155;font-size:11px;font-weight:900}
.wdc-gw-progress-step.is-done .wdc-gw-progress-step-no{background:#16a34a;color:#fff}
.wdc-gw-progress-step.is-active .wdc-gw-progress-step-no{background:#0284c7;color:#fff}
.wdc-gw-progress-step-label{font-size:11px;font-weight:800;color:#64748b;line-height:1.3}
.wdc-gw-progress-step.is-done .wdc-gw-progress-step-label{color:#166534}
.wdc-gw-progress-body{display:grid;gap:12px;width:100%;box-sizing:border-box;background:#f8fafc;border:1px solid #e6edf2;border-radius:14px;padding:16px}
.wdc-gw-progress-details{font-size:14px;color:#334155;line-height:1.7}
.wdc-gw-progress-note{border-radius:12px;padding:12px 14px;font-size:13px;font-weight:700;line-height:1.5}
.wdc-gw-progress-note--info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}
.wdc-gw-progress-note--ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
.wdc-gw-progress-note--danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.wdc-gw-progress-track{display:grid;gap:10px}
.wdc-gw-progress-resi{background:#f5f3ff;border:1px solid #ddd6fe;border-radius:12px;padding:12px 14px}
.wdc-gw-progress-resi-label{font-size:12px;font-weight:900;color:#004A98;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px}
.wdc-gw-progress-resi-no{font-size:20px;font-weight:950;color:#0f172a;letter-spacing:.04em;word-break:break-all}
.wdc-gw-progress-resi-help{font-size:12px;color:#64748b;margin-top:4px}
.wdc-gw-progress-actions{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
.wdc-gw-progress-cta{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 14px;border-radius:999px;background:var(--wdc-btn-primary,#004A98);color:#fff!important;text-decoration:none;font-weight:900;border:0;cursor:pointer;width:100%;box-sizing:border-box}
.wdc-gw-progress-cta--warn{background:var(--wdc-btn-primary,#004A98)!important}
.wdc-gw-progress-cta--ghost{background:#fff!important;border:1px solid var(--wdc-btn-secondary-border,#cfe0e8)!important;color:var(--wdc-btn-secondary-text,#004A98)!important;box-shadow:none!important}
@media(max-width:767.98px){
  .wdc-gw-progress-head{flex-direction:column;align-items:stretch}
  .wdc-gw-progress-badge{align-self:flex-start}
  .wdc-gw-progress-steps{grid-template-columns:1fr}
  .wdc-gw-progress-step{min-height:58px;flex-direction:row;justify-content:flex-start;text-align:left;padding:12px}
  .wdc-gw-progress-actions{grid-template-columns:1fr}
}
</style>
<?php endif; ?>

<?php
// Latest Informasi (same feed as /informasi/)
$wdc_dash_infos = get_posts([
    'post_type' => 'wdc_info',
    'posts_per_page' => 5,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
]);
$wdc_info_colors = [
    '1st Giveaway' => ['#ecfdf5', '#166534'],
    'Event' => ['#eff6ff', '#1e40af'],
    'Trip' => ['#fff7ed', '#9a3412'],
    'Update NAUI/WDC/TDI/DAN' => ['#f5f3ff', '#6d28d9'],
];
if ($wdc_dash_infos) :
?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:18px;margin-bottom:24px;box-shadow:0 8px 24px rgba(15,23,42,.04);">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px;flex-wrap:wrap;">
        <h2 class="wdc-section-title" style="margin:0;"><?php echo contenly_tr('Informasi Terbaru', 'Latest Information'); ?></h2>
        <a href="<?php echo esc_url(contenly_localized_url('/informasi/')); ?>" style="font-size:13px;font-weight:800;color:#004A98;text-decoration:none;"><?php echo contenly_tr('Lihat semua', 'View all'); ?> →</a>
    </div>
    <div style="display:grid;gap:0;border:1px solid #eef2f6;border-radius:12px;overflow:hidden;">
        <?php foreach ($wdc_dash_infos as $idx => $info_post) :
            $types = wp_get_post_terms($info_post->ID, 'info_type', ['fields' => 'names']);
            $type_name = !is_wp_error($types) && $types ? $types[0] : 'Info';
            $colors = $wdc_info_colors[$type_name] ?? ['#f8fafc', '#475569'];
            $border = $idx > 0 ? 'border-top:1px solid #eef2f6;' : '';
            $excerpt = $info_post->post_excerpt ?: wp_trim_words(wp_strip_all_tags($info_post->post_content), 14, '…');
            $excerpt = wp_trim_words(wp_strip_all_tags($excerpt), 14, '…');
        ?>
        <a href="<?php echo esc_url(get_permalink($info_post)); ?>" style="display:flex;gap:12px;align-items:flex-start;padding:12px 14px;text-decoration:none;color:inherit;background:#fff;<?php echo $border; ?>">
            <div style="flex:1;min-width:0;">
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:3px;">
                    <span style="display:inline-block;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:<?php echo esc_attr($colors[1]); ?>;background:<?php echo esc_attr($colors[0]); ?>;border-radius:999px;padding:3px 8px;"><?php echo esc_html($type_name); ?></span>
                    <span style="font-size:12px;color:#94a3b8;"><?php echo esc_html(get_the_date('', $info_post)); ?></span>
                </div>
                <div style="font-size:14px;font-weight:800;color:#0f172a;line-height:1.35;"><?php echo esc_html(get_the_title($info_post)); ?></div>
                <?php if ($excerpt) : ?><div style="font-size:12px;color:#64748b;line-height:1.45;margin-top:3px;"><?php echo esc_html($excerpt); ?></div><?php endif; ?>
            </div>
            <span style="flex-shrink:0;font-size:12px;font-weight:800;color:#004A98;">→</span>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php
$recent_activity = array_values(array_filter(array_merge($course_orders, $gear_orders, $course_requests, $gear_requests), function ($item) {
    if (!is_array($item)) return false;
    $type = sanitize_key($item['type'] ?? '');
    $id = (string) ($item['id'] ?? '');
    $label = (string) ($item['item'] ?? $item['course'] ?? $item['gear'] ?? '');
    if ($type === 'giveaway' || stripos($id, 'GW-') === 0 || stripos($label, 'Giveaway') === 0) {
        return false;
    }
    return true;
}));
$recent_activity = array_slice($recent_activity, 0, 5);
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
    <h2 style="font-size:22px;color:#004A98;margin:0 0 6px;letter-spacing:.03em;"><?php echo contenly_tr('Pilih kursus atau minta saran peralatan.', 'Pick a course or request gear advice.'); ?></h2>
    <p style="color:#64748b;margin:0 0 16px;line-height:1.6;"><?php echo contenly_tr('Area member fokus pada kebutuhan anggota: kursus menyelam dan membeli peralatan selam yang tepat.', 'The member area now focuses on what Whale Dive Centre members need most: joining courses and buying the right dive gear.'); ?></p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;"><a href="/my-courses/" style="display:inline-flex;padding:10px 14px;border-radius:999px;background:#004A98;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Lihat Kursus', 'Browse Courses'); ?></a><a href="/my-gear/" style="display:inline-flex;padding:10px 14px;border-radius:999px;background:#fff;color:#004A98;text-decoration:none;font-weight:900;border:1px solid #ccecf5;"><?php echo contenly_tr('Lihat Peralatan', 'Browse Gear'); ?></a></div>
</div>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
