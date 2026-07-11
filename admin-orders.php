<?php
/**
 * WDC Manual Orders System
 * Admin input pesanan manual (dari WA) + customer dashboard + invoice
 */

// ============================================================
// 1. Register CPT: wdc_order
// ============================================================
function wdc_register_order_cpt() {
    if (post_type_exists('wdc_order')) {
        return;
    }
    register_post_type('wdc_order', [
        'label' => 'Pesanan',
        'labels' => [
            'name'          => 'Pesanan',
            'singular_name' => 'Pesanan',
            'add_new'       => 'Tambah Pesanan',
            'add_new_item'  => 'Tambah Pesanan Baru',
            'edit_item'     => 'Edit Pesanan',
            'view_item'     => 'Lihat Pesanan',
            'search_items'  => 'Cari Pesanan',
        ],
        'public'       => false,
        'show_ui'      => false, // We use custom admin page
        'show_in_rest' => false,
        'supports'     => ['title', 'custom-fields'],
        'menu_icon'    => 'dashicons-cart',
    ]);

    // Order status taxonomy
    if (!taxonomy_exists('order_status')) {
        register_taxonomy('order_status', ['wdc_order'], [
            'label' => 'Status Pesanan',
            'public' => false,
            'show_ui' => false,
            'show_in_rest' => false,
            'hierarchical' => true,
        ]);
    }
}
add_action('init', 'wdc_register_order_cpt');

// ============================================================
// 2. Helper functions
// ============================================================
function wdc_generate_order_code() {
    return 'WDC-' . strtoupper(wp_generate_password(6, false));
}

function wdc_get_order_statuses() {
    return [
        'pending'    => ['label' => 'Menunggu Pembayaran', 'color' => '#f59e0b'],
        'paid'       => ['label' => 'Sudah Dibayar', 'color' => '#10b981'],
        'processing' => ['label' => 'Diproses', 'color' => '#3b82f6'],
        'shipped'    => ['label' => 'Dikirim', 'color' => '#8b5cf6'],
        'completed'  => ['label' => 'Selesai', 'color' => '#059669'],
        'cancelled'  => ['label' => 'Dibatalkan', 'color' => '#ef4444'],
    ];
}

function wdc_get_order_item_types() {
    return [
        'course'    => 'Kursus',
        'equipment' => 'Peralatan',
        'package'   => 'Paket',
        'other'     => 'Lainnya',
    ];
}

// ============================================================
// 3. Admin Menu
// ============================================================
function wdc_register_order_admin_menu() {
    // Menu "Input Pesanan" + "Semua Pesanan" disembunyikan.
    // Halaman tetap ada di file ini kalau nanti butuh restore.
    return;
}
// add_action('admin_menu', 'wdc_register_order_admin_menu');

// ============================================================
// 4. Admin Page: Input Pesanan Manual
// ============================================================
function wdc_render_input_orders_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Not allowed');
    }

    $message = '';
    $order_code = '';

    // Handle form submission
    if (isset($_POST['wdc_create_order']) && wp_verify_nonce(sanitize_text_field($_POST['_wpnonce'] ?? ''), 'wdc_create_order')) {
        $customer_id   = intval($_POST['customer_id'] ?? 0);
        $customer_name = sanitize_text_field($_POST['customer_name'] ?? '');
        $customer_phone = sanitize_text_field($_POST['customer_phone'] ?? '');
        $customer_email = sanitize_email($_POST['customer_email'] ?? '');
        $item_type     = sanitize_text_field($_POST['item_type'] ?? 'other');
        $item_name     = sanitize_text_field($_POST['item_name'] ?? '');
        $quantity      = max(1, intval($_POST['quantity'] ?? 1));
        $unit_price    = max(0, intval($_POST['unit_price'] ?? 0));
        $total_price   = $quantity * $unit_price;
        $notes         = sanitize_textarea_field($_POST['notes'] ?? '');
        $status        = sanitize_text_field($_POST['order_status'] ?? 'pending');
        $order_code    = wdc_generate_order_code();

        $post_id = wp_insert_post([
            'post_title'   => $order_code . ' — ' . $item_name,
            'post_type'    => 'wdc_order',
            'post_status'  => 'publish',
            'post_content' => $notes,
            'meta_input'   => [
                '_wdc_order_code'       => $order_code,
                '_wdc_customer_id'      => $customer_id,
                '_wdc_customer_name'    => $customer_name,
                '_wdc_customer_phone'   => $customer_phone,
                '_wdc_customer_email'   => $customer_email,
                '_wdc_item_type'        => $item_type,
                '_wdc_item_name'        => $item_name,
                '_wdc_quantity'         => $quantity,
                '_wdc_unit_price'       => $unit_price,
                '_wdc_total_price'      => $total_price,
                '_wdc_order_status'     => $status,
                '_wdc_notes'            => $notes,
                '_wdc_created_by'       => get_current_user_id(),
            ],
        ]);

        if (!is_wp_error($post_id)) {
            $invoice_url = home_url('/invoice/' . $order_code . '/');
            $message = '<div class="notice notice-success"><p>✅ Pesanan <strong>' . esc_html($order_code) . '</strong> berhasil dibuat! ';
            $message .= 'Invoice: <a href="' . esc_url($invoice_url) . '" target="_blank">' . esc_url($invoice_url) . '</a></p></div>';
        } else {
            $message = '<div class="notice notice-error"><p>❌ Error: ' . esc_html($post_id->get_error_message()) . '</p></div>';
        }
    }

    // Get all users for dropdown
    $users = get_users(['fields' => ['ID', 'display_name', 'user_email'], 'orderby' => 'display_name']);

    echo '<div class="wrap">';
    echo '<h1>🛒 Input Pesanan Manual</h1>';
    echo '<p style="color:#64748b;font-size:14px;">Input pesanan dari WhatsApp atau transaksi langsung. Invoice otomatis dibuat.</p>';

    if ($message) {
        echo $message;
        if ($order_code) {
            echo '<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin:16px 0;">';
            echo '<p><strong>📱 Kirim link ini ke customer via WhatsApp:</strong></p>';
            echo '<div style="display:flex;gap:8px;align-items:center;">';
            echo '<input type="text" value="' . esc_url(home_url('/invoice/' . $order_code . '/')) . '" readonly style="flex:1;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;" id="wdc-invoice-link">';
            echo '<button onclick="navigator.clipboard.writeText(document.getElementById(\'wdc-invoice-link\').value);this.textContent=\'Copied!\'" class="button button-primary">📋 Copy</button>';
            echo '</div>';
            echo '</div>';
        }
    }

    echo '<form method="post" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin-top:20px;max-width:700px;">';
    wp_nonce_field('wdc_create_order');

    // Customer section
    echo '<h2 style="margin:0 0 16px;font-size:18px;">👤 Data Customer</h2>';
    echo '<table class="form-table"><tbody>';

    echo '<tr><th><label>Customer (WP User)</label></th>';
    echo '<td><select name="customer_id" id="wdc-customer-select" style="min-width:300px;">';
    echo '<option value="0">— Customer Baru (belum terdaftar) —</option>';
    foreach ($users as $u) {
        echo '<option value="' . $u->ID . '" data-name="' . esc_attr($u->display_name) . '" data-email="' . esc_attr($u->user_email) . '">' . esc_html($u->display_name) . ' (' . esc_html($u->user_email) . ')</option>';
    }
    echo '</select><p class="description">Pilih user yang sudah daftar, atau kosongkan untuk customer baru.</p></td></tr>';

    echo '<tr><th><label>Nama Lengkap *</label></th>';
    echo '<td><input type="text" name="customer_name" id="wdc-customer-name" required style="min-width:300px;" placeholder="Nama customer"></td></tr>';

    echo '<tr><th><label>No. HP / WhatsApp</label></th>';
    echo '<td><input type="tel" name="customer_phone" id="wdc-customer-phone" style="min-width:300px;" placeholder="08xxxxxxxxxx"></td></tr>';

    echo '<tr><th><label>Email</label></th>';
    echo '<td><input type="email" name="customer_email" id="wdc-customer-email" style="min-width:300px;" placeholder="email@customer.com"></td></tr>';

    echo '</tbody></table>';

    // Order items section
    echo '<h2 style="margin:24px 0 16px;font-size:18px;">📦 Detail Pesanan</h2>';
    echo '<table class="form-table"><tbody>';

    echo '<tr><th><label>Tipe Item *</label></th>';
    echo '<td><select name="item_type" required>';
    foreach (wdc_get_order_item_types() as $key => $label) {
        echo '<option value="' . $key . '">' . $label . '</option>';
    }
    echo '</select></td></tr>';

    echo '<tr><th><label>Nama Item *</label></th>';
    echo '<td><input type="text" name="item_name" required style="min-width:300px;" placeholder="Contoh: Open Water DBC Course / Masker Cressi"></td></tr>';

    echo '<tr><th><label>Jumlah</label></th>';
    echo '<td><input type="number" name="quantity" value="1" min="1" style="width:100px;"></td></tr>';

    echo '<tr><th><label>Harga Satuan (Rp) *</label></th>';
    echo '<td><input type="number" name="unit_price" required min="0" style="min-width:200px;" placeholder="500000"></td></tr>';

    echo '<tr><th><label>Status</label></th>';
    echo '<td><select name="order_status">';
    foreach (wdc_get_order_statuses() as $key => $info) {
        echo '<option value="' . $key . '">' . $info['label'] . '</option>';
    }
    echo '</select></td></tr>';

    echo '<tr><th><label>Catatan</label></th>';
    echo '<td><textarea name="notes" rows="3" style="min-width:300px;" placeholder="Catatan tambahan..."></textarea></td></tr>';

    echo '</tbody></table>';

    echo '<p><button type="submit" name="wdc_create_order" value="1" class="button button-primary button-hero">🛒 Buat Pesanan + Invoice</button></p>';
    echo '</form>';

    // Auto-fill customer fields when user is selected
    echo '<script>
    document.getElementById("wdc-customer-select").addEventListener("change", function() {
        var opt = this.options[this.selectedIndex];
        var name = opt.getAttribute("data-name") || "";
        var email = opt.getAttribute("data-email") || "";
        if (this.value !== "0") {
            document.getElementById("wdc-customer-name").value = name;
            document.getElementById("wdc-customer-email").value = email;
        }
    });
    </script>';

    echo '</div>';
}

// ============================================================
// 5. Admin Page: Semua Pesanan
// ============================================================
function wdc_render_all_orders_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Not allowed');
    }

    // Handle status update
    if (isset($_POST['wdc_update_status']) && wp_verify_nonce(sanitize_text_field($_POST['_wpnonce'] ?? ''), 'wdc_update_status')) {
        $post_id = intval($_POST['order_id'] ?? 0);
        $new_status = sanitize_text_field($_POST['new_status'] ?? '');
        if ($post_id && $new_status) {
            update_post_meta($post_id, '_wdc_order_status', $new_status);
            echo '<div class="notice notice-success"><p>Status updated.</p></div>';
        }
    }

    $orders = get_posts([
        'post_type'      => 'wdc_order',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    $statuses = wdc_get_order_statuses();

    echo '<div class="wrap">';
    echo '<h1>📋 Semua Pesanan</h1>';

    if (!$orders) {
        echo '<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:40px;text-align:center;">';
        echo '<p style="font-size:48px;margin:0;">📦</p>';
        echo '<h3>Belum ada pesanan</h3>';
        echo '<p>Mulai dari <a href="' . admin_url('admin.php?page=wdc-input-orders') . '">Input Pesanan</a>.</p>';
        echo '</div>';
        echo '</div>';
        return;
    }

    echo '<table class="wp-list-table widefat fixed striped" style="margin-top:16px;">';
    echo '<thead><tr>';
    echo '<th style="width:120px;">Kode</th>';
    echo '<th>Customer</th>';
    echo '<th>Item</th>';
    echo '<th style="width:120px;">Jumlah</th>';
    echo '<th style="width:140px;">Total</th>';
    echo '<th style="width:150px;">Status</th>';
    echo '<th style="width:100px;">Invoice</th>';
    echo '<th style="width:120px;">Tanggal</th>';
    echo '</tr></thead><tbody>';

    foreach ($orders as $order) {
        $code     = get_post_meta($order->ID, '_wdc_order_code', true);
        $name     = get_post_meta($order->ID, '_wdc_customer_name', true);
        $phone    = get_post_meta($order->ID, '_wdc_customer_phone', true);
        $item     = get_post_meta($order->ID, '_wdc_item_name', true);
        $qty      = get_post_meta($order->ID, '_wdc_quantity', true);
        $total    = get_post_meta($order->ID, '_wdc_total_price', true);
        $status   = get_post_meta($order->ID, '_wdc_order_status', true) ?: 'pending';
        $s_info   = $statuses[$status] ?? ['label' => $status, 'color' => '#6b7280'];

        echo '<tr>';
        echo '<td><strong>' . esc_html($code) . '</strong></td>';
        echo '<td>' . esc_html($name);
        if ($phone) {
            echo '<br><small style="color:#64748b;">📱 ' . esc_html($phone) . '</small>';
        }
        echo '</td>';
        echo '<td>' . esc_html($item) . '</td>';
        echo '<td>' . intval($qty) . '</td>';
        echo '<td>Rp ' . number_format($total, 0, ',', '.') . '</td>';
        echo '<td>';
        echo '<form method="post" style="display:flex;gap:4px;">';
        wp_nonce_field('wdc_update_status');
        echo '<input type="hidden" name="order_id" value="' . $order->ID . '">';
        echo '<select name="new_status" style="font-size:12px;padding:4px;">';
        foreach ($statuses as $key => $info) {
            echo '<option value="' . $key . '"' . selected($status, $key, false) . '>' . $info['label'] . '</option>';
        }
        echo '</select>';
        echo '<button type="submit" name="wdc_update_status" value="1" class="button button-small">✓</button>';
        echo '</form>';
        echo '</td>';
        echo '<td><a href="' . home_url('/invoice/' . $code . '/') . '" target="_blank">📄 Lihat</a></td>';
        echo '<td>' . get_the_date('d M Y', $order) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

// ============================================================
// 6. Invoice Page (Public — shareable via WA)
// ============================================================
function wdc_register_invoice_rewrite() {
    add_rewrite_rule('^invoice/([A-Z0-9-]+)/?$', 'index.php?wdc_invoice=$matches[1]', 'top');
    add_rewrite_tag('%wdc_invoice%', '([A-Z0-9-]+)');
}
add_action('init', 'wdc_register_invoice_rewrite');

function wdc_template_redirect_invoice() {
    $invoice_code = get_query_var('wdc_invoice');
    if (!$invoice_code) {
        return;
    }

    // Find order by code
    $orders = get_posts([
        'post_type'      => 'wdc_order',
        'meta_key'       => '_wdc_order_code',
        'meta_value'     => $invoice_code,
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    ]);

    if (!$orders) {
        wp_die('Invoice tidak ditemukan.', 'Not Found', ['response' => 404]);
    }

    $order = $orders[0];
    $order_id = $order->ID;

    // Gather data
    $code      = get_post_meta($order_id, '_wdc_order_code', true);
    $c_name    = get_post_meta($order_id, '_wdc_customer_name', true);
    $c_phone   = get_post_meta($order_id, '_wdc_customer_phone', true);
    $c_email   = get_post_meta($order_id, '_wdc_customer_email', true);
    $item_type = get_post_meta($order_id, '_wdc_item_type', true);
    $item_name = get_post_meta($order_id, '_wdc_item_name', true);
    $qty       = get_post_meta($order_id, '_wdc_quantity', true);
    $unit      = get_post_meta($order_id, '_wdc_unit_price', true);
    $total     = get_post_meta($order_id, '_wdc_total_price', true);
    $status    = get_post_meta($order_id, '_wdc_order_status', true) ?: 'pending';
    $notes     = get_post_meta($order_id, '_wdc_notes', true);
    $created   = get_the_date('d F Y H:i', $order);

    $statuses = wdc_get_order_statuses();
    $s_info = $statuses[$status] ?? ['label' => $status, 'color' => '#6b7280'];
    $type_labels = wdc_get_order_item_types();

    // Bank accounts
    $bank_accounts = get_option('wm_bank_accounts', [
        ['bank' => 'BCA', 'account_name' => 'Whale Dive Centre', 'account_number' => '1234567890'],
    ]);

    // Render invoice
    status_header(200);
    nocache_headers();
    ?><!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo esc_html($code); ?> — Whale Dive Centre</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f0f9ff; color: #0f172a; min-height: 100vh; }
        .invoice-box { max-width: 520px; margin: 40px auto; background: #fff; border-radius: 20px; box-shadow: 0 12px 40px rgba(15,23,42,.08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #06384d, #0b617c); color: #fff; padding: 32px 28px; text-align: center; }
        .header h1 { font-size: 22px; font-weight: 900; margin-bottom: 4px; }
        .header p { font-size: 13px; opacity: .8; }
        .body { padding: 28px; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
        .section { margin-bottom: 24px; }
        .section h3 { font-size: 14px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 700; text-align: right; }
        .total-row { display: flex; justify-content: space-between; padding: 16px 0; border-top: 2px solid #06384d; margin-top: 8px; }
        .total-row .info-label { font-size: 16px; font-weight: 800; color: #0f172a; }
        .total-row .info-value { font-size: 22px; font-weight: 900; color: #0b617c; }
        .bank-section { background: #f8fafc; border-radius: 12px; padding: 20px; }
        .bank-item { padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
        .bank-item:last-child { border-bottom: none; }
        .bank-name { font-weight: 800; font-size: 15px; color: #06384d; }
        .bank-detail { font-size: 13px; color: #64748b; }
        .bank-number { font-family: monospace; font-size: 16px; font-weight: 700; color: #0f172a; user-select: all; }
        .footer { text-align: center; padding: 20px 28px; background: #f8fafc; font-size: 12px; color: #94a3b8; }
        .notes { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; font-size: 13px; color: #92400e; }
        .wa-btn { display: inline-flex; align-items: center; gap: 8px; background: #25d366; color: #fff; padding: 14px 28px; border-radius: 12px; font-size: 16px; font-weight: 800; text-decoration: none; margin-top: 20px; }
        .copy-btn { background: #06384d; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; font-weight: 700; }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="header">
        <h1>🐋 Whale Dive Centre</h1>
        <p>Invoice</p>
    </div>
    <div class="body">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <div>
                <div style="font-size:20px;font-weight:900;"><?php echo esc_html($code); ?></div>
                <div style="font-size:13px;color:#64748b;"><?php echo esc_html($created); ?></div>
            </div>
            <span class="badge" style="background:<?php echo $s_info['color']; ?>22;color:<?php echo $s_info['color']; ?>;"><?php echo esc_html($s_info['label']); ?></span>
        </div>

        <div class="section">
            <h3>Customer</h3>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value"><?php echo esc_html($c_name); ?></span>
            </div>
            <?php if ($c_phone) : ?>
            <div class="info-row">
                <span class="info-label">WhatsApp</span>
                <span class="info-value"><?php echo esc_html($c_phone); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($c_email) : ?>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo esc_html($c_email); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Pesanan</h3>
            <div class="info-row">
                <span class="info-label">Tipe</span>
                <span class="info-value"><?php echo esc_html($type_labels[$item_type] ?? $item_type); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Item</span>
                <span class="info-value"><?php echo esc_html($item_name); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah</span>
                <span class="info-value"><?php echo intval($qty); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Harga Satuan</span>
                <span class="info-value">Rp <?php echo number_format($unit, 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span class="info-label">Total</span>
                <span class="info-value">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
            </div>
        </div>

        <?php if ($notes) : ?>
        <div class="section">
            <div class="notes">📝 <?php echo esc_html($notes); ?></div>
        </div>
        <?php endif; ?>

        <?php if ($status === 'pending') : ?>
        <div class="section">
            <h3>Transfer ke</h3>
            <div class="bank-section">
                <?php foreach ($bank_accounts as $acc) : ?>
                <div class="bank-item">
                    <div class="bank-name"><?php echo esc_html($acc['bank']); ?></div>
                    <div class="bank-number"><?php echo esc_html($acc['account_number']); ?>
                        <button class="copy-btn" onclick="navigator.clipboard.writeText('<?php echo esc_attr($acc['account_number']); ?>');this.textContent='Copied!'">Copy</button>
                    </div>
                    <div class="bank-detail">a.n. <?php echo esc_html($acc['account_name']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div style="text-align:center;">
            <a href="https://wa.me/<?php echo preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $c_phone)); ?>?text=<?php echo urlencode('Halo ' . $c_name . '! Invoice ' . $code . ' dari Whale Dive Centre: ' . home_url('/invoice/' . $code . '/')); ?>" class="wa-btn" target="_blank">
                💬 Kirim via WhatsApp
            </a>
        </div>
    </div>
    <div class="footer">
        Whale Dive Centre · <?php echo home_url(); ?><br>
        Terima kasih telah mempercayai kami! 🐋
    </div>
</div>
</body>
</html><?php
    exit;
}
add_action('template_redirect', 'wdc_template_redirect_invoice');

// Flush rewrite rules on activation
function wdc_flush_rewrite_on_order_init() {
    if (!get_option('wdc_order_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('wdc_order_rewrite_flushed', 1);
    }
}
add_action('init', 'wdc_flush_rewrite_on_order_init', 999);
