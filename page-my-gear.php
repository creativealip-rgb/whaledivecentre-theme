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
?>
<div class="wdc-page-head">
    <h1><?php echo contenly_tr('Peralatan Saya', 'My Gear'); ?></h1>
    <p class="wdc-page-sub"><?php echo contenly_tr('Beli peralatan standar langsung, atau minta bantuan fitting/ketersediaan saat ukuran dan setup butuh panduan kru.', 'Buy standard gear directly, or request fit/availability help when sizing and setup need crew guidance.'); ?></p>
</div>


<?php if ($notice) : ?>
<div style="margin-bottom:18px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:26px;">
    <div style="background:#eef9fc;border:1px solid #ccecf5;border-radius:16px;padding:18px;">
        <div style="font-size:12px;color:#0b617c;text-transform:uppercase;font-weight:800;letter-spacing:.08em;"><?php echo contenly_tr('Aktivitas Peralatan', 'Gear Activity'); ?></div>
        <div style="font-size:32px;font-weight:900;color:#06384d;margin-top:6px;"><?php echo count($gear_requests); ?></div>
    </div>
    <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:16px;padding:18px;">
        <div style="font-size:12px;color:#9a3412;text-transform:uppercase;font-weight:800;letter-spacing:.08em;"><?php echo contenly_tr('Cek Fitting', 'Fit Check'); ?></div>
        <div style="font-size:20px;font-weight:900;color:#7c2d12;margin-top:8px;"><?php echo contenly_tr('Opsional untuk item sensitif fitting', 'Optional for fit-sensitive items'); ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1.2fr) minmax(300px,.8fr);gap:20px;align-items:start;margin-bottom:28px;">
    <section>
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0 0 16px;letter-spacing:.03em;"><?php echo contenly_tr('Peralatan Unggulan', 'Featured Gear'); ?></h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;">
            <?php foreach ($gear as $item) : ?>
            <article style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:20px;box-shadow:0 12px 30px rgba(15,23,42,.05);">
                <span style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo contenly_tr('Beli / saran fitting', 'Buy / fit advice'); ?></span>
                <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin:16px 0 8px;letter-spacing:.03em;"><?php echo esc_html($item['title']); ?></h3>
                <p style="font-size:15px;color:#06384d;font-weight:900;margin:0 0 6px;"><?php echo esc_html($item['price']); ?></p>
                <p style="font-size:12px;color:<?php echo (!empty($item['stock_raw']) && is_numeric($item['stock_raw']) && (int) $item['stock_raw'] <= 0) ? '#991b1b' : '#64748b'; ?>;font-weight:800;margin:0 0 16px;"><?php echo esc_html($item['stock'] ?? contenly_tr('Ketersediaan atas permintaan', 'Availability on request')); ?></p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <a href="#" onclick="event.preventDefault();document.querySelector('select[name=selected_gear]').value='<?php echo esc_js($item['title']); ?>';document.querySelector('select[name=request_type]').value='Buy advice';document.querySelector('aside form').scrollIntoView({behavior:'smooth'});" style="display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:999px;background:#4cc8ed;color:#06384d;text-decoration:none;font-weight:950;font-size:13px;"><?php echo contenly_tr('Ajukan Beli', 'Request Buy'); ?></a>
                    <a href="<?php echo esc_url($item['href']); ?>" style="display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:999px;background:#f3fbff;color:#06384d;text-decoration:none;font-weight:900;font-size:13px;border:1px solid rgba(6,56,77,.12);"><?php echo contenly_tr('Detail', 'Details'); ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <aside id="wdc-gear-request" style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;letter-spacing:.03em;"><?php echo contenly_tr('Butuh Bantuan Fitting / Ketersediaan?', 'Need Fit / Availability Help?'); ?></h2>
        <form method="post" style="display:grid;gap:12px;">
            <?php wp_nonce_field('wdc_gear_request', 'wdc_gear_nonce'); ?>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Peralatan', 'Gear'); ?>
                <select name="selected_gear" required style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
                    <option value=""><?php echo contenly_tr('Pilih peralatan', 'Choose gear'); ?></option>
                    <?php foreach ($gear as $item) :
                        $selected = ($prefill_item_id && (int)($item['id'] ?? 0) === $prefill_item_id) || ($prefill_item && strcasecmp($prefill_item, $item['title']) === 0);
                    ?>
                    <option value="<?php echo esc_attr($item['title']); ?>" <?php selected($selected); ?>><?php echo esc_html($item['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Jenis permintaan', 'Request type'); ?>
                <select name="request_type" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
                    <option value="Buy advice" selected><?php echo contenly_tr('Saran pembelian', 'Buy advice'); ?></option>
                    <option value="Fit check"><?php echo contenly_tr('Cek fitting', 'Fit check'); ?></option>
                    <option value="Availability check"><?php echo contenly_tr('Cek ketersediaan', 'Availability check'); ?></option>
                    <option value="Setup recommendation"><?php echo contenly_tr('Rekomendasi setup', 'Setup recommendation'); ?></option>
                </select>
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Catatan ukuran / fitting', 'Size / fit notes'); ?>
                <input name="size_notes" placeholder="<?php echo contenly_tr('Tinggi badan, berat badan, ukuran sepatu, masalah fitting masker...', 'Height, weight, shoe size, mask fit issue...'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Catatan', 'Notes'); ?>
                <textarea name="message" rows="4" placeholder="<?php echo contenly_tr('Anggaran, frekuensi menyelam, rencana kursus...', 'Budget, diving frequency, course plan...'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;resize:vertical;"></textarea>
            </label>
            <button type="submit" style="border:0;border-radius:999px;background:#4cc8ed;color:#06384d;padding:12px 16px;font-weight:950;cursor:pointer;"><?php echo contenly_tr('Minta Bantuan', 'Request Help'); ?></button>
        </form>
    </aside>
</div>

<?php if (!empty($gear_orders)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;">
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;letter-spacing:.03em;"><?php echo contenly_tr('Pesanan Peralatan', 'Gear Orders'); ?></h2>
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
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;"><?php echo contenly_tr('Aktivitas Peralatan Terbaru', 'Recent Gear Activity'); ?></h2>
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
