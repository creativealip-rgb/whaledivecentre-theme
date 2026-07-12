<?php
/**
 * Template Name: My Gear
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$notice = '';
$notice_type = 'success';
$gear_requests = get_user_meta($user_id, '_wdc_gear_requests', true);
$gear_requests = is_array($gear_requests) ? $gear_requests : [];
$gear_orders = get_user_meta($user_id, '_wdc_gear_orders', true);
$gear_orders = is_array($gear_orders) ? $gear_orders : [];
$prefill_item = sanitize_text_field(wp_unslash($_GET['item'] ?? ''));
$prefill_item_id = absint($_GET['item_id'] ?? 0);

if ((($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') && isset($_POST['wdc_gear_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_gear_nonce'])), 'wdc_gear_request')) {
    $selected_gear = sanitize_text_field(wp_unslash($_POST['selected_gear'] ?? ''));
    $request_type = sanitize_text_field(wp_unslash($_POST['request_type'] ?? ''));
    $size_notes = sanitize_text_field(wp_unslash($_POST['size_notes'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if ($selected_gear) {
        array_unshift($gear_requests, [
            'gear' => $selected_gear,
            'request_type' => $request_type ?: 'Buy advice',
            'size_notes' => $size_notes,
            'message' => $message,
            'status' => 'Requested',
            'created_at' => current_time('mysql'),
        ]);
        update_user_meta($user_id, '_wdc_gear_requests', array_slice($gear_requests, 0, 10));
        if (function_exists('wdc_notify_request')) {
            wdc_notify_request('gear', $user_id, $gear_requests[0]);
        }
        $notice = contenly_tr('Permintaan peralatan tersimpan. Kru akan membantu konfirmasi fitting dan langkah selanjutnya.', 'Gear request saved. The crew can help confirm fit and next steps.');
    } else {
        $notice = contenly_tr('Pilih peralatan terlebih dahulu.', 'Please choose gear first.');
        $notice_type = 'error';
    }
}

$gear = [];
if (post_type_exists('wm_equipment')) {
    $gear_posts = get_posts(['post_type' => 'wm_equipment', 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC', 'meta_query' => ['relation' => 'OR', ['key' => '_wdc_catalog_visible', 'compare' => 'NOT EXISTS'], ['key' => '_wdc_catalog_visible', 'value' => '0', 'compare' => '!=']]]);
    foreach ($gear_posts as $gear_post) {
        $price = (float) get_post_meta($gear_post->ID, '_wm_price', true);
        $stock = get_post_meta($gear_post->ID, '_wm_stock', true);
        $stock_label = $stock === '' || !is_numeric($stock) ? contenly_tr('Tersedia', 'In stock') : ((int) $stock > 0 ? (int) $stock . ' ' . contenly_tr('tersisa', 'left') : contenly_tr('Stok habis', 'Out of stock'));
        $gear[] = [
            'id' => $gear_post->ID,
            'title' => $gear_post->post_title,
            'price' => $price > 0 ? 'Rp ' . number_format($price, 0, ',', '.') : contenly_tr('Tanya kru', 'Ask crew'),
            'stock' => $stock_label,
            'stock_raw' => $stock,
            'checkout' => $price > 0 && ($stock === '' || (int) $stock > 0),
            'href' => get_permalink($gear_post),
        ];
    }
}
if (!$gear) {
    $gear = [
        ['id' => 0, 'title' => 'Masks', 'price' => contenly_tr('Mulai Rp 1.250.000', 'From Rp 1.250.000'), 'checkout' => true, 'href' => '/equipment/masks/'],
        ['id' => 0, 'title' => 'Fins', 'price' => contenly_tr('Mulai Rp 950.000', 'From Rp 950.000'), 'checkout' => true, 'href' => '/equipment/fins/'],
        ['id' => 0, 'title' => 'BCD', 'price' => contenly_tr('Mulai Rp 5.500.000', 'From Rp 5.500.000'), 'checkout' => true, 'href' => '/equipment/bcd/'],
        ['id' => 0, 'title' => 'Regulators', 'price' => contenly_tr('Mulai Rp 4.850.000', 'From Rp 4.850.000'), 'checkout' => true, 'href' => '/equipment/regulators/'],
        ['id' => 0, 'title' => 'Wetsuits', 'price' => contenly_tr('Butuh saran fitting', 'Fit advice required'), 'checkout' => false, 'href' => '/equipment/wetsuits/'],
        ['id' => 0, 'title' => 'Dive Computers', 'price' => contenly_tr('Tanya kru', 'Ask crew'), 'checkout' => false, 'href' => '/equipment/dive-computers/'],
    ];
}
// Member gear CTAs
$equipment_url = function_exists('contenly_localized_url') ? contenly_localized_url('/equipment/') : home_url('/equipment/');
$wa_phone_raw = '';
if (function_exists('wdc_site_get')) {
    $wa_phone_raw = (string) wdc_site_get('phone_tel', '');
    if ($wa_phone_raw === '') {
        $wa_phone_raw = (string) wdc_site_get('phone', '0821-2666-611');
    }
} else {
    $wa_phone_raw = '0821-2666-611';
}
$wa_digits = preg_replace('/\D+/', '', $wa_phone_raw);
if ($wa_digits !== '' && $wa_digits[0] === '0') {
    $wa_digits = '62' . substr($wa_digits, 1);
}
if (strlen($wa_digits) < 10) {
    $wa_digits = '628212666611';
}
$user = wp_get_current_user();
$wa_name = trim((string) ($user->display_name ?: $user->user_login));
$wa_text = contenly_tr(
    'Halo crew Whale Dive Centre, saya ' . $wa_name . ' (member). Mau tanya soal peralatan / fitting.',
    'Hi Whale Dive Centre crew, I am ' . $wa_name . ' (member). I want to ask about gear / fit help.'
);
$wa_url = 'https://wa.me/' . $wa_digits . '?text=' . rawurlencode($wa_text);
?>
<div class="wdc-page-head">
    <h1><?php echo contenly_tr('Peralatan Saya', 'My Gear'); ?></h1>
    <p class="wdc-page-sub"><?php echo contenly_tr('Lihat katalog peralatan, atau tanya crew langsung untuk fitting dan ketersediaan.', 'Browse the gear catalog, or ask the crew directly about fit and availability.'); ?></p>
</div>

<?php if ($notice) : ?>
<div style="margin-bottom:18px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<style>
.wdc-gear-cta-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-bottom:22px}
.wdc-gear-cta{
  display:flex;flex-direction:column;gap:8px;
  background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:18px;
  box-shadow:0 8px 24px rgba(15,23,42,.04);text-decoration:none;color:inherit;
  transition:border-color .15s,box-shadow .15s,transform .15s
}
.wdc-gear-cta:hover{border-color:rgba(0,74,152,.22);box-shadow:0 12px 28px rgba(0,74,152,.08);transform:translateY(-1px)}
.wdc-gear-cta-kicker{font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#004A98}
.wdc-gear-cta h2{margin:0;font-size:17px;font-weight:800;color:#0f172a;line-height:1.25}
.wdc-gear-cta p{margin:0;font-size:13px;color:#64748b;line-height:1.5}
.wdc-gear-cta-btn{
  margin-top:8px;display:inline-flex;align-items:center;justify-content:center;
  width:fit-content;min-height:38px;padding:0 14px;border-radius:999px;
  background:#004A98;color:#fff;font-size:13px;font-weight:800
}
.wdc-gear-cta.is-wa .wdc-gear-cta-btn{background:#16a34a}
.wdc-gear-cta.is-wa .wdc-gear-cta-kicker{color:#166534}
@media(max-width:760px){.wdc-gear-cta-grid{grid-template-columns:1fr}}
</style>

<div class="wdc-gear-cta-grid">
    <a class="wdc-gear-cta" href="<?php echo esc_url($equipment_url); ?>">
        <div class="wdc-gear-cta-kicker"><?php echo contenly_tr('Katalog', 'Catalog'); ?></div>
        <h2><?php echo contenly_tr('Lihat Peralatan', 'Browse Equipment'); ?></h2>
        <p><?php echo contenly_tr('Buka halaman equipment untuk cek masker, fin, BCD, regulator, dan lainnya.', 'Open the equipment page to browse masks, fins, BCDs, regulators, and more.'); ?></p>
        <span class="wdc-gear-cta-btn"><?php echo contenly_tr('Ke Halaman Equipment', 'Go to Equipment'); ?> →</span>
    </a>
    <a class="wdc-gear-cta is-wa" href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer">
        <div class="wdc-gear-cta-kicker"><?php echo contenly_tr('Crew', 'Crew'); ?></div>
        <h2><?php echo contenly_tr('Tanya Langsung ke Crew', 'Ask Crew Directly'); ?></h2>
        <p><?php echo contenly_tr('Chat WhatsApp crew untuk fitting, stok, dan saran setup peralatan.', 'WhatsApp the crew for fit help, stock checks, and gear setup advice.'); ?></p>
        <span class="wdc-gear-cta-btn"><?php echo contenly_tr('Chat WhatsApp', 'Chat on WhatsApp'); ?> →</span>
    </a>
</div>

<aside id="wdc-gear-request" style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:18px;margin-bottom:22px;box-shadow:0 8px 24px rgba(15,23,42,.04);">
    <h2 class="wdc-section-title" style="margin:0 0 6px;"><?php echo contenly_tr('Request Fitting / Ketersediaan', 'Fit / Availability Request'); ?></h2>
    <p style="margin:0 0 14px;font-size:13px;color:#64748b;line-height:1.5;"><?php echo contenly_tr('Simpan request di akun biar crew bisa follow-up. Butuh jawaban cepat? Chat WhatsApp di atas.', 'Save a request to your account so crew can follow up. Need a fast reply? Use WhatsApp above.'); ?></p>
    <form method="post" class="wdc-gear-request-form" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
        <?php wp_nonce_field('wdc_gear_request', 'wdc_gear_nonce'); ?>
        <label style="display:grid;gap:5px;font-size:12px;font-weight:700;color:#475569;"><?php echo contenly_tr('Peralatan', 'Gear'); ?>
            <select name="selected_gear" required style="border:1px solid #dbe4ea;border-radius:10px;padding:9px 10px;min-height:38px;background:#fff;">
                <option value=""><?php echo contenly_tr('Pilih peralatan', 'Choose gear'); ?></option>
                <?php foreach ($gear as $item) :
                    $selected = ($prefill_item_id && (int)($item['id'] ?? 0) === $prefill_item_id) || ($prefill_item && strcasecmp($prefill_item, $item['title']) === 0);
                ?>
                <option value="<?php echo esc_attr($item['title']); ?>" <?php selected($selected); ?>><?php echo esc_html($item['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label style="display:grid;gap:5px;font-size:12px;font-weight:700;color:#475569;"><?php echo contenly_tr('Jenis permintaan', 'Request type'); ?>
            <select name="request_type" style="border:1px solid #dbe4ea;border-radius:10px;padding:9px 10px;min-height:38px;background:#fff;">
                <option value="Buy advice" selected><?php echo contenly_tr('Saran pembelian', 'Buy advice'); ?></option>
                <option value="Fit check"><?php echo contenly_tr('Cek fitting', 'Fit check'); ?></option>
                <option value="Availability check"><?php echo contenly_tr('Cek ketersediaan', 'Availability check'); ?></option>
                <option value="Setup recommendation"><?php echo contenly_tr('Rekomendasi setup', 'Setup recommendation'); ?></option>
            </select>
        </label>
        <label style="display:grid;gap:5px;font-size:12px;font-weight:700;color:#475569;grid-column:1 / -1;"><?php echo contenly_tr('Catatan ukuran / fitting', 'Size / fit notes'); ?>
            <input name="size_notes" placeholder="<?php echo contenly_tr('Tinggi badan, berat badan, ukuran sepatu, masalah fitting masker...', 'Height, weight, shoe size, mask fit issue...'); ?>" style="border:1px solid #dbe4ea;border-radius:10px;padding:9px 10px;min-height:38px;background:#fff;">
        </label>
        <label style="display:grid;gap:5px;font-size:12px;font-weight:700;color:#475569;grid-column:1 / -1;"><?php echo contenly_tr('Catatan', 'Notes'); ?>
            <textarea name="message" rows="3" placeholder="<?php echo contenly_tr('Anggaran, frekuensi menyelam, rencana kursus...', 'Budget, diving frequency, course plan...'); ?>" style="border:1px solid #dbe4ea;border-radius:10px;padding:9px 10px;resize:vertical;background:#fff;"></textarea>
        </label>
        <div style="grid-column:1 / -1;">
            <button type="submit" style="border:0;border-radius:999px;background:#004A98;color:#fff;padding:10px 16px;font-weight:800;font-size:13px;cursor:pointer;"><?php echo contenly_tr('Kirim Request', 'Submit Request'); ?></button>
        </div>
    </form>
</aside>
<style>
@media(max-width:700px){.wdc-gear-request-form{grid-template-columns:1fr!important}}
</style>

<?php if (!empty($gear_orders)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;">
    <h2 class="wdc-section-title" style="margin:0 0 12px;"><?php echo contenly_tr('Pesanan Peralatan', 'Gear Orders'); ?></h2>
    <div style="display:grid;gap:10px;">
        <?php foreach (array_slice($gear_orders, 0, 5) as $order) : $order_link = !empty($order['item_id']) ? get_permalink((int) $order['item_id']) : ''; ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php if ($order_link) : ?><a href="<?php echo esc_url($order_link); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html($order['item'] ?? 'Gear'); ?></a><?php else : ?><?php echo esc_html($order['item'] ?? 'Gear'); ?><?php endif; ?></strong>
                <div style="font-size:13px;color:#64748b;">Order: <?php echo esc_html($order['id'] ?? 'Direct checkout'); ?><?php if (!empty($order['admin_note'])) : ?> · <?php echo esc_html($order['admin_note']); ?><?php endif; ?></div>
            </div>
            <span style="font-size:12px;font-weight:900;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($order['status'] ?? 'Payment Uploaded'); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($gear_requests)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;">
    <h2 class="wdc-section-title" style="margin:0 0 12px;"><?php echo contenly_tr('Request Terbaru', 'Recent Requests'); ?></h2>
    <div style="display:grid;gap:10px;">
        <?php foreach (array_slice($gear_requests, 0, 5) as $request) : ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php echo esc_html($request['gear'] ?? 'Gear'); ?></strong>
                <div style="font-size:13px;color:#64748b;"><?php echo esc_html($request['request_type'] ?? 'Buy advice'); ?> · <?php echo esc_html($request['size_notes'] ?: 'No fit notes'); ?></div>
            </div>
            <span style="font-size:12px;font-weight:900;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($request['status'] ?? 'Requested'); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php if ($prefill_item || $prefill_item_id) : ?>
<script>document.addEventListener('DOMContentLoaded',function(){var el=document.getElementById('wdc-gear-request'); if(el){el.scrollIntoView({behavior:'smooth',block:'start'});}});</script>
<?php endif; ?>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
