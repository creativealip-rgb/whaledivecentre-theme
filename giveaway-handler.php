<?php
/**
 * Giveaway Handler
 * AJAX endpoints for giveaway selection + Biteship shipping cost integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default giveaway items — edit here or use filter 'wdc_giveaway_items'
 */
function wdc_get_giveaway_items() {
    $items = [
        [
            'id'    => 'sticker-pack',
            'name'  => contenly_tr('Sticker Pack WDC', 'WDC Sticker Pack'),
            'desc'  => contenly_tr('Koleksi 5 sticker waterproof motif diving', '5-piece waterproof diving sticker collection'),
            'weight' => 50,   // grams
            'image'  => get_template_directory_uri() . '/assets/giveaway/sticker-pack.svg',
            'price'  => 0,
        ],
        [
            'id'    => 'lanyard',
            'name'  => contenly_tr('Tali Lanyard WDC', 'WDC Lanyard'),
            'desc'  => contenly_tr('Lanyard custom logo Whale Dive Centre', 'Custom Whale Dive Centre logo lanyard'),
            'weight' => 100,
            'image'  => get_template_directory_uri() . '/assets/giveaway/lanyard.svg',
            'price'  => 0,
        ],
        [
            'id'    => 'keychain',
            'name'  => contenly_tr('Gantungan Kunci WDC', 'WDC Keychain'),
            'desc'  => contenly_tr('Gantungan kunci akrilik motif paus', 'Acrylic whale-themed keychain'),
            'weight' => 30,
            'image'  => get_template_directory_uri() . '/assets/giveaway/keychain.svg',
            'price'  => 0,
        ],
    ];
    return apply_filters('wdc_giveaway_items', $items);
}

/**
 * Check if user already claimed giveaway
 */
function wdc_user_claimed_giveaway($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return false;
    }
    return (bool) get_user_meta($user_id, '_wdc_giveaway_claimed', true);
}

/**
 * Check if user is "new" — registered within X days
 * Filter: wdc_giveaway_new_user_days (default 30)
 */
function wdc_is_new_user($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return false;
    }
    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }
    $max_days = apply_filters('wdc_giveaway_new_user_days', 30);
    $registered = strtotime($user->user_registered);
    $days_since = (time() - $registered) / (60 * 60 * 24);
    return $days_since <= $max_days;
}

/**
 * AJAX: Get giveaway items
 */
add_action('wp_ajax_wdc_get_giveaway_items', function() {
    check_ajax_referer('wdc_giveaway_nonce', 'nonce');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => contenly_tr('Harus login dulu.', 'Please login first.')]);
    }

    $claimed = wdc_user_claimed_giveaway($user_id);
    $items   = wdc_get_giveaway_items();

    wp_send_json_success([
        'items'   => $items,
        'claimed' => $claimed,
    ]);
});

/**
 * AJAX: Check shipping cost via Biteship
 */
add_action('wp_ajax_wdc_check_shipping', function() {
    check_ajax_referer('wdc_giveaway_nonce', 'nonce');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => contenly_tr('Harus login dulu.', 'Please login first.')]);
    }

    $destination = sanitize_text_field($_POST['destination'] ?? '');
    $couriers    = sanitize_text_field($_POST['couriers'] ?? 'jne,jnt,sicepat');
    $item_ids    = array_map('sanitize_text_field', $_POST['item_ids'] ?? []);

    if (empty($destination)) {
        wp_send_json_error(['message' => contenly_tr('Masukkan kota tujuan.', 'Enter destination city.')]);
    }
    if (empty($item_ids)) {
        wp_send_json_error(['message' => contenly_tr('Pilih minimal 1 item.', 'Select at least 1 item.')]);
    }

    // Calculate total weight
    $all_items = wdc_get_giveaway_items();
    $total_weight = 0;
    $selected_items = [];
    foreach ($all_items as $item) {
        if (in_array($item['id'], $item_ids, true)) {
            $total_weight += $item['weight'];
            $selected_items[] = $item;
        }
    }

    if ($total_weight <= 0) {
        wp_send_json_error(['message' => contenly_tr('Item tidak valid.', 'Invalid items.')]);
    }

    // Resolve destination via Biteship
    $origin_data = wdc_biteship_get_origin();
    $dest_data   = wdc_biteship_resolve_area($destination);

    if (is_wp_error($dest_data)) {
        wp_send_json_error(['message' => $dest_data->get_error_message()]);
    }

    // Get shipping rates
    $rates = wdc_biteship_get_rates(
        $origin_data['area_id'],
        $dest_data['area_id'],
        $total_weight,
        $couriers
    );

    if (is_wp_error($rates)) {
        wp_send_json_error(['message' => $rates->get_error_message()]);
    }

    wp_send_json_success([
        'rates'          => $rates,
        'total_weight'   => $total_weight,
        'destination'    => $dest_data,
        'selected_items' => $selected_items,
    ]);
});

/**
 * AJAX: Submit giveaway order
 */
/**
 * Helper: store giveaway image upload (quote screenshot / payment proof)
 */
function wdc_giveaway_store_upload($file, $subdir = 'wdc-giveaway-proofs') {
    if (empty($file) || empty($file['tmp_name'])) {
        return new WP_Error('no_file', contenly_tr('File tidak ditemukan.', 'File not found.'));
    }
    if (!function_exists('wp_handle_upload')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $type = $file['type'] ?? '';
    if ($type && !in_array($type, $allowed, true)) {
        return new WP_Error('bad_type', contenly_tr('Format file tidak didukung. Pakai JPG/PNG.', 'Unsupported file format. Use JPG/PNG.'));
    }
    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        return new WP_Error('too_big', contenly_tr('File terlalu besar. Maks 5MB.', 'File too large. Max 5MB.'));
    }

    // Route upload into dedicated subdir under uploads/
    $subdir = trim((string) $subdir, '/');
    $filter = function($dirs) use ($subdir) {
        $dirs['subdir'] = '/' . $subdir;
        $dirs['path'] = trailingslashit($dirs['basedir']) . $subdir;
        $dirs['url'] = trailingslashit($dirs['baseurl']) . $subdir;
        return $dirs;
    };
    add_filter('upload_dir', $filter);

    $overrides = [
        'test_form' => false,
        'mimes' => [
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ],
    ];
    $result = wp_handle_upload($file, $overrides);
    remove_filter('upload_dir', $filter);

    if (!empty($result['error'])) {
        return new WP_Error('upload_fail', $result['error']);
    }
    if (empty($result['url']) || empty($result['file'])) {
        return new WP_Error('move_fail', contenly_tr('Gagal upload file.', 'File upload failed.'));
    }
    return [
        'url'  => $result['url'],
        'path' => $result['file'],
    ];
}

/**
 * Temporary external shipping checker URL (manual quote flow)
 */
function wdc_giveaway_external_ongkir_url() {
    return apply_filters('wdc_giveaway_external_ongkir_url', get_option('wdc_giveaway_external_ongkir_url', 'https://cekongkir.com/'));
}

/**
 * Origin label shown to members when checking external ongkir
 */
function wdc_giveaway_origin_label() {
    return apply_filters(
        'wdc_giveaway_origin_label',
        get_option('wdc_giveaway_origin_label', 'Jakarta Selatan (12240)')
    );
}

add_action('wp_ajax_wdc_submit_giveaway', function() {
    check_ajax_referer('wdc_giveaway_nonce', 'nonce');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => contenly_tr('Harus login dulu.', 'Please login first.')]);
    }

    // Check if already claimed
    if (wdc_user_claimed_giveaway($user_id)) {
        wp_send_json_error(['message' => contenly_tr('Kamu sudah pernah claim giveaway.', 'You have already claimed your giveaway.')]);
    }

    $item_ids      = array_map('sanitize_text_field', (array) ($_POST['item_ids'] ?? []));
    $courier       = sanitize_text_field($_POST['courier'] ?? '');
    $service       = sanitize_text_field($_POST['service'] ?? '');
    $shipping_cost = intval(preg_replace('/\D+/', '', (string) ($_POST['shipping_cost'] ?? '0')));
    $destination   = sanitize_text_field($_POST['destination'] ?? '');
    $dest_area_id  = sanitize_text_field($_POST['dest_area_id'] ?? '');
    $address       = sanitize_textarea_field($_POST['address'] ?? '');
    $phone         = sanitize_text_field($_POST['phone'] ?? '');
    $name          = sanitize_text_field($_POST['recipient_name'] ?? '');
    $quote_source  = esc_url_raw($_POST['quote_source'] ?? '');
    $quote_notes   = sanitize_textarea_field($_POST['quote_notes'] ?? '');

    if (empty($item_ids) || empty($courier) || $shipping_cost < 1000) {
        wp_send_json_error(['message' => contenly_tr('Pilih item, isi kurir, dan ongkir minimal Rp1.000 sesuai SS cek ongkir.', 'Select items, fill courier, and shipping cost min Rp1,000 matching your quote screenshot.')]);
    }
    if (empty($address) || empty($phone) || empty($name) || empty($destination)) {
        wp_send_json_error(['message' => contenly_tr('Isi nama, HP, kota tujuan, dan alamat lengkap.', 'Fill name, phone, destination city, and full address.')]);
    }
    if (empty($_FILES['shipping_quote_ss'])) {
        wp_send_json_error(['message' => contenly_tr('Upload screenshot cek ongkir dulu.', 'Upload shipping quote screenshot first.')]);
    }

    // Validate items
    $all_items = wdc_get_giveaway_items();
    $valid_ids = array_column($all_items, 'id');
    foreach ($item_ids as $id) {
        if (!in_array($id, $valid_ids, true)) {
            wp_send_json_error(['message' => contenly_tr('Item tidak valid.', 'Invalid item.')]);
        }
    }

    $quote_upload = wdc_giveaway_store_upload($_FILES['shipping_quote_ss'], 'wdc-giveaway-quotes');
    if (is_wp_error($quote_upload)) {
        wp_send_json_error(['message' => $quote_upload->get_error_message()]);
    }

    // Generate order ID
    $order_id = 'GW-' . strtoupper(substr(md5($user_id . time()), 0, 8));

    // Save order (manual external quote flow)
    $order = [
        'order_id'         => $order_id,
        'user_id'          => $user_id,
        'items'            => $item_ids,
        'courier'          => $courier,
        'service'          => $service ?: 'manual',
        'shipping_cost'    => $shipping_cost,
        'destination'      => $destination,
        'dest_area_id'     => $dest_area_id,
        'address'          => $address,
        'phone'            => $phone,
        'recipient_name'   => $name,
        'quote_source'     => $quote_source,
        'quote_notes'      => $quote_notes,
        'quote_ss_url'     => $quote_upload['url'],
        'quote_ss_path'    => $quote_upload['path'],
        'shipping_mode'    => 'external_manual',
        'status'           => 'awaiting_payment',
        'created_at'       => current_time('mysql'),
    ];

    update_user_meta($user_id, '_wdc_giveaway_order', $order);
    update_user_meta($user_id, '_wdc_giveaway_claimed', true);

    // Also add to course_orders for dashboard activity tracking
    $existing_orders = get_user_meta($user_id, '_wdc_course_orders', true);
    $existing_orders = is_array($existing_orders) ? $existing_orders : [];
    $existing_orders[] = [
        'id'         => $order_id,
        'item'       => contenly_tr('Giveaway: ', 'Giveaway: ') . implode(', ', $item_ids),
        'status'     => 'Awaiting Payment',
        'admin_note' => contenly_tr('Menunggu TF ongkir sesuai SS quote', 'Waiting shipping TF matching quote screenshot'),
        'type'       => 'giveaway',
        'amount'     => $shipping_cost,
        'created_at' => current_time('mysql'),
    ];
    update_user_meta($user_id, '_wdc_course_orders', $existing_orders);

    wp_send_json_success([
        'order_id' => $order_id,
        'shipping_cost' => $shipping_cost,
        'message'  => contenly_tr('Giveaway diklaim. Transfer ongkir sesuai nominal SS, lalu upload bukti.', 'Giveaway claimed. Transfer shipping exactly as quote, then upload proof.'),
        'checkout_url' => add_query_arg([
            'type'  => 'giveaway',
            'order' => $order_id,
        ], contenly_localized_url('/giveaway-checkout/')),
    ]);
});

/**
 * AJAX: Search Biteship areas (for autocomplete)
 */
add_action('wp_ajax_wdc_search_area', function() {
    check_ajax_referer('wdc_giveaway_nonce', 'nonce');

    $query = sanitize_text_field($_POST['query'] ?? '');
    if (strlen($query) < 3) {
        wp_send_json_error(['message' => 'Min 3 karakter.']);
    }

    $results = wdc_biteship_search_area($query);
    if (is_wp_error($results)) {
        wp_send_json_error(['message' => $results->get_error_message()]);
    }

    wp_send_json_success(['areas' => $results]);
});


/**
 * Fallback city search when Biteship API key missing.
 */
function wdc_giveaway_fallback_search_area($query) {
    $q = strtolower(trim((string) $query));
    $catalog = [
        ['id' => 'FB-JKT-PUSAT', 'name' => 'Jakarta Pusat', 'postal_code' => '10110', 'admin1_name' => 'DKI Jakarta', 'admin2_name' => 'Jakarta Pusat', 'country_name' => 'Indonesia'],
        ['id' => 'FB-JKT-SEL', 'name' => 'Jakarta Selatan', 'postal_code' => '12120', 'admin1_name' => 'DKI Jakarta', 'admin2_name' => 'Jakarta Selatan', 'country_name' => 'Indonesia'],
        ['id' => 'FB-BDG', 'name' => 'Bandung', 'postal_code' => '40111', 'admin1_name' => 'Jawa Barat', 'admin2_name' => 'Kota Bandung', 'country_name' => 'Indonesia'],
        ['id' => 'FB-SBY', 'name' => 'Surabaya', 'postal_code' => '60111', 'admin1_name' => 'Jawa Timur', 'admin2_name' => 'Kota Surabaya', 'country_name' => 'Indonesia'],
        ['id' => 'FB-YGY', 'name' => 'Yogyakarta', 'postal_code' => '55111', 'admin1_name' => 'DI Yogyakarta', 'admin2_name' => 'Kota Yogyakarta', 'country_name' => 'Indonesia'],
        ['id' => 'FB-SMG', 'name' => 'Semarang', 'postal_code' => '50111', 'admin1_name' => 'Jawa Tengah', 'admin2_name' => 'Kota Semarang', 'country_name' => 'Indonesia'],
        ['id' => 'FB-MDN', 'name' => 'Medan', 'postal_code' => '20111', 'admin1_name' => 'Sumatera Utara', 'admin2_name' => 'Kota Medan', 'country_name' => 'Indonesia'],
        ['id' => 'FB-DPS', 'name' => 'Denpasar', 'postal_code' => '80111', 'admin1_name' => 'Bali', 'admin2_name' => 'Kota Denpasar', 'country_name' => 'Indonesia'],
        ['id' => 'FB-MKS', 'name' => 'Makassar', 'postal_code' => '90111', 'admin1_name' => 'Sulawesi Selatan', 'admin2_name' => 'Kota Makassar', 'country_name' => 'Indonesia'],
        ['id' => 'FB-BPN', 'name' => 'Balikpapan', 'postal_code' => '76111', 'admin1_name' => 'Kalimantan Timur', 'admin2_name' => 'Kota Balikpapan', 'country_name' => 'Indonesia'],
    ];
    $out = [];
    foreach ($catalog as $area) {
        $hay = strtolower($area['name'] . ' ' . $area['admin2_name'] . ' ' . $area['admin1_name'] . ' ' . $area['postal_code']);
        if ($q === '' || strpos($hay, $q) !== false) {
            $out[] = $area;
        }
    }
    return $out ?: array_slice($catalog, 0, 5);
}

/**
 * Fallback flat shipping rates when Biteship API key missing.
 */
function wdc_giveaway_fallback_rates($weight_grams, $dest_area_id = '') {
    $weight = max(1, intval($weight_grams));
    $zone = 1;
    $id = strtoupper((string) $dest_area_id);
    if (strpos($id, 'FB-DPS') !== false || strpos($id, 'FB-MKS') !== false || strpos($id, 'FB-BPN') !== false || strpos($id, 'FB-MDN') !== false) {
        $zone = 3;
    } elseif (strpos($id, 'FB-JKT') === false && $id !== '') {
        $zone = 2;
    }
    $base = [1 => 12000, 2 => 18000, 3 => 28000][$zone];
    $extra = (int) ceil(max(0, $weight - 250) / 250) * 3000;
    $reg = $base + $extra;
    return [
        [
            'courier' => 'JNE',
            'courier_code' => 'jne',
            'service' => 'REG',
            'service_code' => 'reg',
            'cost' => $reg,
            'etd' => '2-4 hari',
            'description' => 'Estimasi ongkir fallback',
        ],
        [
            'courier' => 'J&T',
            'courier_code' => 'jnt',
            'service' => 'EZ',
            'service_code' => 'ez',
            'cost' => max(10000, $reg - 2000),
            'etd' => '2-3 hari',
            'description' => 'Estimasi ongkir fallback',
        ],
        [
            'courier' => 'SiCepat',
            'courier_code' => 'sicepat',
            'service' => 'REG',
            'service_code' => 'reg',
            'cost' => $reg + 1500,
            'etd' => '2-4 hari',
            'description' => 'Estimasi ongkir fallback',
        ],
    ];
}

/* =========================================================================
   BITESHIP API HELPERS
   ========================================================================= */

/**
 * Get Biteship API key from options
 */
function wdc_biteship_api_key() {
    return get_option('wdc_biteship_api_key', '');
}

/**
 * Get origin config
 */
function wdc_biteship_get_origin() {
    $area_id = get_option('wdc_biteship_origin_area_id', '');
    $postal  = get_option('wdc_biteship_origin_postal', '10110');

    if (!$area_id) {
        // Default: Jakarta Pusat
        $area_id = 'IDNC4'; // Biteship area ID for Jakarta Pusat
    }

    return [
        'area_id'    => $area_id,
        'postal_code' => $postal,
    ];
}

/**
 * Search Biteship areas
 */
function wdc_biteship_search_area($query) {
    $api_key = wdc_biteship_api_key();
    if (!$api_key) {
        // Demo/fallback areas so giveaway still works without Biteship key.
        return wdc_giveaway_fallback_search_area($query);
    }

    $response = wp_remote_get(
        'https://api.biteship.com/v1/maps/areas?input=' . urlencode($query) . '&type=single',
        [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json',
            ],
            'timeout' => 10,
        ]
    );

    if (is_wp_error($response)) {
        return new WP_Error('api_error', contenly_tr('Gagal menghubungi server ongkir.', 'Failed to connect to shipping server.'));
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($body['success']) || !$body['success']) {
        return new WP_Error('api_error', $body['error'] ?? contenly_tr('Gagal mencari area.', 'Area search failed.'));
    }

    $areas = [];
    if (!empty($body['areas'])) {
        foreach ($body['areas'] as $area) {
            $areas[] = [
                'id'           => $area['id'],
                'name'         => $area['name'],
                'postal_code'  => $area['postal_code'] ?? '',
                'country_name' => $area['country_name'] ?? 'Indonesia',
                'admin1_name'  => $area['admin1_name'] ?? '',
                'admin2_name'  => $area['admin2_name'] ?? '',
            ];
        }
    }

    return $areas;
}

/**
 * Resolve single area by name
 */
function wdc_biteship_resolve_area($query) {
    $areas = wdc_biteship_search_area($query);
    if (is_wp_error($areas)) {
        return $areas;
    }
    if (empty($areas)) {
        return new WP_Error('not_found', contenly_tr('Kota tidak ditemukan. Coba nama kota atau kodepos.', 'City not found. Try city name or postal code.'));
    }
    return $areas[0]; // Return first match
}

/**
 * Get shipping rates from Biteship
 */
function wdc_biteship_get_rates($origin_area_id, $dest_area_id, $weight_grams, $couriers = 'jne,jnt,sicepat') {
    $api_key = wdc_biteship_api_key();
    if (!$api_key) {
        return wdc_giveaway_fallback_rates($weight_grams, $dest_area_id);
    }

    $body = [
        'origin_area_id'      => $origin_area_id,
        'destination_area_id' => $dest_area_id,
        'couriers'            => $couriers,
        'items'               => [
            [
                'name'     => 'Giveaway Items',
                'value'    => 0,
                'weight'   => $weight_grams,
                'quantity' => 1,
            ],
        ],
    ];

    $response = wp_remote_post('https://api.biteship.com/v1/rates/couriers', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        ],
        'body'    => wp_json_encode($body),
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', contenly_tr('Gagal menghitung ongkir.', 'Failed to calculate shipping.'));
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($data['success']) || !$data['success']) {
        return new WP_Error('api_error', $data['error'] ?? contenly_tr('Gagal menghitung ongkir.', 'Shipping calculation failed.'));
    }

    $rates = [];
    if (!empty($data['pricing'])) {
        foreach ($data['pricing'] as $rate) {
            $rates[] = [
                'courier'     => $rate['courier_name'] ?? '',
                'courier_code' => $rate['courier_code'] ?? '',
                'service'     => $rate['courier_service_name'] ?? '',
                'service_code' => $rate['courier_service_code'] ?? '',
                'cost'        => intval($rate['price'] ?? 0),
                'etd'         => $rate['duration'] ?? '',
                'description' => $rate['description'] ?? '',
            ];
        }
    }

    // Sort by cheapest
    usort($rates, function($a, $b) {
        return $a['cost'] <=> $b['cost'];
    });

    return $rates;
}

/* =========================================================================
   PAYMENT PROOF UPLOAD
   ========================================================================= */

add_action('wp_ajax_wdc_upload_giveaway_payment', function() {
    check_ajax_referer('wdc_giveaway_nonce', 'nonce');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => contenly_tr('Harus login dulu.', 'Please login first.')]);
    }

    $order = get_user_meta($user_id, '_wdc_giveaway_order', true);
    if (!$order || !is_array($order)) {
        wp_send_json_error(['message' => contenly_tr('Order tidak ditemukan.', 'Order not found.')]);
    }

    $expected = intval($order['shipping_cost'] ?? 0);
    $paid_amount = intval(preg_replace('/\D+/', '', (string) ($_POST['paid_amount'] ?? '0')));
    if ($expected <= 0) {
        wp_send_json_error(['message' => contenly_tr('Nominal ongkir order tidak valid.', 'Order shipping amount invalid.')]);
    }
    if ($paid_amount !== $expected) {
        wp_send_json_error([
            'message' => sprintf(
                contenly_tr('Nominal transfer harus sama dengan ongkir: Rp %s', 'Transfer amount must match shipping: Rp %s'),
                number_format($expected, 0, ',', '.')
            ),
            'expected' => $expected,
            'paid' => $paid_amount,
        ]);
    }

    // Handle file upload
    if (empty($_FILES['payment_proof'])) {
        wp_send_json_error(['message' => contenly_tr('Upload bukti transfer.', 'Upload transfer proof.')]);
    }

    $stored = wdc_giveaway_store_upload($_FILES['payment_proof'], 'wdc-giveaway-proofs');
    if (is_wp_error($stored)) {
        wp_send_json_error(['message' => $stored->get_error_message()]);
    }

    $proof_url = $stored['url'];
    $filepath = $stored['path'];
    $notes = sanitize_textarea_field($_POST['notes'] ?? '');

    // Update order
    $order['status'] = 'payment_uploaded';
    $order['proof_url'] = $proof_url;
    $order['proof_file'] = $filepath;
    $order['notes'] = $notes;
    $order['paid_amount'] = $paid_amount;
    $order['payment_uploaded_at'] = current_time('mysql');
    update_user_meta($user_id, '_wdc_giveaway_order', $order);

    // Update activity feed
    $existing_orders = get_user_meta($user_id, '_wdc_course_orders', true);
    $existing_orders = is_array($existing_orders) ? $existing_orders : [];
    foreach ($existing_orders as &$eo) {
        if (isset($eo['type']) && $eo['type'] === 'giveaway' && ($eo['id'] ?? '') === $order['order_id']) {
            $eo['status'] = 'Payment Uploaded';
            break;
        }
    }
    unset($eo);
    update_user_meta($user_id, '_wdc_course_orders', $existing_orders);

    // Notify admin
    $admin_email = get_option('admin_email');
    $item_names = implode(', ', $order['items'] ?? []);
    wp_mail(
        $admin_email,
        '🎁 Giveaway Payment Proof - ' . $order['order_id'],
        "User #{$user_id} uploaded payment proof for giveaway.\n\nOrder: {$order['order_id']}\nItems: {$item_names}\nShipping: Rp " . number_format($order['shipping_cost'], 0, ',', '.') . "\nCourier: {$order['courier']} {$order['service']}\nDestination: {$order['destination']}\n\nProof: {$proof_url}",
        ['Content-Type: text/plain; charset=UTF-8']
    );

    wp_send_json_success([
        'message' => contenly_tr('Bukti transfer diterima! Crew akan verifikasi.', 'Transfer proof received! Crew will verify.'),
    ]);
});

/* =========================================================================
   ADMIN SETTINGS
   ========================================================================= */

add_action('admin_menu', function() {
    add_submenu_page(
        'contenly-member',
        contenly_tr('Giveaway Settings', 'Giveaway Settings'),
        contenly_tr('🎁 Giveaway', '🎁 Giveaway'),
        'manage_options',
        'wdc-giveaway-settings',
        'wdc_render_giveaway_settings'
    );
});

function wdc_render_giveaway_settings() {
    if (isset($_POST['wdc_save_giveaway_settings']) && check_admin_referer('wdc_giveaway_settings')) {
        update_option('wdc_biteship_api_key', sanitize_text_field($_POST['wdc_biteship_api_key'] ?? ''));
        update_option('wdc_biteship_origin_area_id', sanitize_text_field($_POST['wdc_biteship_origin_area_id'] ?? ''));
        update_option('wdc_biteship_origin_postal', sanitize_text_field($_POST['wdc_biteship_origin_postal'] ?? '10110'));
        update_option('wdc_giveaway_enabled', isset($_POST['wdc_giveaway_enabled']) ? '1' : '0');
        update_option('wdc_giveaway_external_ongkir_url', esc_url_raw($_POST['wdc_giveaway_external_ongkir_url'] ?? 'https://cekongkir.com/'));
        update_option('wdc_giveaway_origin_label', sanitize_text_field($_POST['wdc_giveaway_origin_label'] ?? 'Jakarta Selatan (12240)'));
        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    $api_key    = get_option('wdc_biteship_api_key', '');
    $origin_id  = get_option('wdc_biteship_origin_area_id', '');
    $origin_zip = get_option('wdc_biteship_origin_postal', '10110');
    $enabled    = get_option('wdc_giveaway_enabled', '1');
    $external_url = get_option('wdc_giveaway_external_ongkir_url', 'https://cekongkir.com/');
    $origin_label = get_option('wdc_giveaway_origin_label', 'Jakarta Selatan (12240)');
    ?>
    <div class="wrap">
        <h1>🎁 <?php echo contenly_tr('Pengaturan Giveaway', 'Giveaway Settings'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('wdc_giveaway_settings'); ?>

            <table class="form-table">
                <tr>
                    <th><?php echo contenly_tr('Aktifkan Giveaway', 'Enable Giveaway'); ?></th>
                    <td><label><input type="checkbox" name="wdc_giveaway_enabled" value="1" <?php checked($enabled, '1'); ?>> <?php echo contenly_tr('Tampilkan giveaway di dashboard member', 'Show giveaway on member dashboard'); ?></label></td>
                </tr>
                <tr>
                    <th><?php echo contenly_tr('Link Cek Ongkir Eksternal', 'External Ongkir Checker URL'); ?></th>
                    <td>
                        <input type="url" name="wdc_giveaway_external_ongkir_url" value="<?php echo esc_attr($external_url); ?>" class="regular-text" placeholder="https://cekongkir.com/">
                        <p class="description"><?php echo contenly_tr('Sementara: user dicek ongkir di web ini, lalu input nominal + upload SS.', 'Temporary: user checks shipping on this site, then inputs amount + uploads screenshot.'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><?php echo contenly_tr('Label Asal Pengiriman', 'Shipping Origin Label'); ?></th>
                    <td>
                        <input type="text" name="wdc_giveaway_origin_label" value="<?php echo esc_attr($origin_label); ?>" class="regular-text" placeholder="Jakarta Selatan (12240)">
                        <p class="description"><?php echo contenly_tr('Ditampilkan ke member saat cek ongkir di web luar.', 'Shown to members when checking shipping externally.'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th>Biteship API Key</th>
                    <td>
                        <input type="text" name="wdc_biteship_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" placeholder="biteship_live_xxx">
                        <p class="description">Dapatkan di <a href="https://biteship.com" target="_blank">biteship.com</a> → Settings → API Keys</p>
                    </td>
                </tr>
                <tr>
                    <th><?php echo contenly_tr('Origin Area ID (Biteship)', 'Origin Area ID (Biteship)'); ?></th>
                    <td>
                        <input type="text" name="wdc_biteship_origin_area_id" value="<?php echo esc_attr($origin_id); ?>" class="regular-text" placeholder="IDNC4">
                        <p class="description">Cari di <a href="https://api.biteship.com/v1/maps/areas?input=Jakarta&type=single" target="_blank">Biteship Area API</a>. Default: IDNC4 (Jakarta Pusat)</p>
                    </td>
                </tr>
                <tr>
                    <th><?php echo contenly_tr('Kode Pos Asal', 'Origin Postal Code'); ?></th>
                    <td><input type="text" name="wdc_biteship_origin_postal" value="<?php echo esc_attr($origin_zip); ?>" class="regular-text" placeholder="10110"></td>
                </tr>
            </table>

            <h2><?php echo contenly_tr('Item Giveaway Saat Ini', 'Current Giveaway Items'); ?></h2>
            <p class="description"><?php echo contenly_tr('Item di-hardcode di giveaway-handler.php. Filter: wdc_giveaway_items', 'Items are hardcoded in giveaway-handler.php. Filter: wdc_giveaway_items'); ?></p>
            <table class="widefat">
                <thead>
                    <tr><th>ID</th><th>Nama</th><th>Berat (g)</th></tr>
                </thead>
                <tbody>
                <?php foreach (wdc_get_giveaway_items() as $item) : ?>
                    <tr>
                        <td><code><?php echo esc_html($item['id']); ?></code></td>
                        <td><?php echo esc_html($item['name']); ?></td>
                        <td><?php echo esc_html($item['weight']); ?>g</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p class="submit"><input type="submit" name="wdc_save_giveaway_settings" class="button-primary" value="<?php echo esc_attr(contenly_tr('Simpan', 'Save')); ?>"></p>
        </form>
    </div>
    <?php
}

/**
 * Register AJAX nonces for frontend
 */
add_action('wp_enqueue_scripts', function() {
    if (!is_user_logged_in()) {
        return;
    }
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'wdcGiveawayAjax', [
        'nonce'  => wp_create_nonce('wdc_giveaway_nonce'),
        'ajaxurl' => admin_url('admin-ajax.php'),
    ]);
});
