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

    // Update activity feed + normalized progress
    wdc_giveaway_sync_activity($user_id, $order);

    // Notify admin
    $admin_email = get_option('admin_email');
    $item_names = implode(', ', $order['items'] ?? []);
    wp_mail(
        $admin_email,
        'Giveaway Payment Proof - ' . $order['order_id'],
        "User #{$user_id} uploaded payment proof for giveaway.\n\nOrder: {$order['order_id']}\nItems: {$item_names}\nShipping: Rp " . number_format($order['shipping_cost'], 0, ',', '.') . "\nCourier: {$order['courier']} {$order['service']}\nDestination: {$order['destination']}\n\nProof: {$proof_url}",
        ['Content-Type: text/plain; charset=UTF-8']
    );

    wp_send_json_success([
        'message' => contenly_tr('Bukti transfer diterima! Crew akan verifikasi.', 'Transfer proof received! Crew will verify.'),
    ]);
});

/* =========================================================================
   STATUS / TRACKING HELPERS
   ========================================================================= */

/**
 * Canonical giveaway status labels (user-facing).
 */
function wdc_giveaway_status_meta($status = '') {
    $map = [
        'awaiting_payment'  => [
            'label' => contenly_tr('Menunggu Pembayaran', 'Awaiting Payment'),
            'color' => '#b45309',
            'bg'    => '#fef3c7',
            'step'  => 1,
        ],
        'payment_uploaded'  => [
            'label' => contenly_tr('Bukti Transfer Dikirim', 'Payment Proof Uploaded'),
            'color' => '#1d4ed8',
            'bg'    => '#dbeafe',
            'step'  => 2,
        ],
        'verified'          => [
            'label' => contenly_tr('Pembayaran Diverifikasi', 'Payment Verified'),
            'color' => '#047857',
            'bg'    => '#d1fae5',
            'step'  => 3,
        ],
        'shipped'           => [
            'label' => contenly_tr('Barang Dikirim', 'Shipped'),
            'color' => '#6d28d9',
            'bg'    => '#ede9fe',
            'step'  => 4,
        ],
        'delivered'         => [
            'label' => contenly_tr('Selesai / Diterima', 'Delivered'),
            'color' => '#065f46',
            'bg'    => '#d1fae5',
            'step'  => 5,
        ],
        'cancelled'         => [
            'label' => contenly_tr('Dibatalkan', 'Cancelled'),
            'color' => '#991b1b',
            'bg'    => '#fee2e2',
            'step'  => 0,
        ],
    ];
    $status = sanitize_key((string) $status);
    return $map[$status] ?? [
        'label' => $status ?: contenly_tr('Unknown', 'Unknown'),
        'color' => '#475569',
        'bg'    => '#f1f5f9',
        'step'  => 0,
    ];
}

/**
 * Pipeline steps for progress UI.
 */
function wdc_giveaway_progress_steps() {
    return [
        'awaiting_payment' => contenly_tr('Klaim + Bayar Ongkir', 'Claim + Pay Shipping'),
        'payment_uploaded' => contenly_tr('Upload Bukti TF', 'Upload Transfer Proof'),
        'verified'         => contenly_tr('Admin Verifikasi', 'Admin Verified'),
        'shipped'          => contenly_tr('Dikirim + Resi', 'Shipped + Tracking'),
        'delivered'        => contenly_tr('Selesai', 'Delivered'),
    ];
}

/**
 * Build external tracking URL from courier + resi.
 */
function wdc_giveaway_tracking_url($courier = '', $resi = '') {
    $resi = trim((string) $resi);
    if ($resi === '') {
        return '';
    }
    $c = strtolower(preg_replace('/[^a-z0-9]+/i', '', (string) $courier));
    if (strpos($c, 'jne') !== false) {
        return 'https://www.jne.co.id/id/tracking/trace?awb=' . rawurlencode($resi);
    }
    if (strpos($c, 'jnt') !== false || strpos($c, 'jt') !== false) {
        return 'https://jet.co.id/track?awb=' . rawurlencode($resi);
    }
    if (strpos($c, 'sicepat') !== false) {
        return 'https://www.sicepat.com/checkAwb?awb=' . rawurlencode($resi);
    }
    if (strpos($c, 'anteraja') !== false) {
        return 'https://anteraja.id/tracking?awb=' . rawurlencode($resi);
    }
    if (strpos($c, 'pos') !== false) {
        return 'https://www.posindonesia.co.id/id/tracking?awb=' . rawurlencode($resi);
    }
    // Generic fallback: cekresi
    return 'https://cekresi.com/?noresi=' . rawurlencode($resi);
}

/**
 * Sync giveaway order status into activity feed (_wdc_course_orders).
 */
function wdc_giveaway_sync_activity($user_id, $order) {
    $user_id = intval($user_id);
    if (!$user_id || !is_array($order)) {
        return;
    }
    $status = sanitize_key($order['status'] ?? 'awaiting_payment');
    $meta = wdc_giveaway_status_meta($status);
    $activity_status = [
        'awaiting_payment' => 'Awaiting Payment',
        'payment_uploaded' => 'Payment Uploaded',
        'verified'         => 'Verified',
        'shipped'          => 'Active',
        'delivered'        => 'Completed',
        'cancelled'        => 'Cancelled',
    ][$status] ?? 'Requested';

    $note = $meta['label'];
    if (!empty($order['tracking_number'])) {
        $note .= ' · Resi: ' . $order['tracking_number'];
    }
    if (!empty($order['admin_note'])) {
        $note .= ' · ' . $order['admin_note'];
    }

    $existing_orders = get_user_meta($user_id, '_wdc_course_orders', true);
    $existing_orders = is_array($existing_orders) ? $existing_orders : [];
    $found = false;
    foreach ($existing_orders as &$eo) {
        if (($eo['type'] ?? '') === 'giveaway' && ($eo['id'] ?? '') === ($order['order_id'] ?? '')) {
            $eo['status'] = $activity_status;
            $eo['admin_note'] = $note;
            $eo['amount'] = intval($order['shipping_cost'] ?? 0);
            $found = true;
            break;
        }
    }
    unset($eo);
    if (!$found) {
        $existing_orders[] = [
            'id'         => $order['order_id'] ?? '',
            'item'       => contenly_tr('Giveaway: ', 'Giveaway: ') . implode(', ', $order['items'] ?? []),
            'status'     => $activity_status,
            'admin_note' => $note,
            'type'       => 'giveaway',
            'amount'     => intval($order['shipping_cost'] ?? 0),
            'created_at' => $order['created_at'] ?? current_time('mysql'),
        ];
    }
    update_user_meta($user_id, '_wdc_course_orders', $existing_orders);
}

/**
 * Collect all claimed giveaway orders across members.
 */
function wdc_giveaway_collect_orders($status_filter = '') {
    $users = get_users([
        'meta_key' => '_wdc_giveaway_order',
        'fields'   => ['ID', 'user_login', 'user_email', 'display_name'],
        'number'   => 500,
    ]);
    $rows = [];
    foreach ($users as $u) {
        $order = get_user_meta($u->ID, '_wdc_giveaway_order', true);
        if (!is_array($order) || empty($order['order_id'])) {
            continue;
        }
        $st = sanitize_key($order['status'] ?? '');
        if ($status_filter && $st !== sanitize_key($status_filter)) {
            continue;
        }
        $order['user_id'] = intval($u->ID);
        $order['user_login'] = $u->user_login;
        $order['user_email'] = $u->user_email;
        $order['display_name'] = $u->display_name;
        $rows[] = $order;
    }
    usort($rows, function($a, $b) {
        return strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''));
    });
    return $rows;
}

/**
 * Update giveaway order fields for a user (admin).
 */
function wdc_giveaway_admin_update_order($user_id, $order_id, $fields = []) {
    $user_id = intval($user_id);
    $order = get_user_meta($user_id, '_wdc_giveaway_order', true);
    if (!is_array($order) || ($order['order_id'] ?? '') !== $order_id) {
        return new WP_Error('not_found', 'Order not found for user.');
    }

    $allowed_status = ['awaiting_payment', 'payment_uploaded', 'verified', 'shipped', 'delivered', 'cancelled'];
    if (isset($fields['status'])) {
        $st = sanitize_key($fields['status']);
        if (!in_array($st, $allowed_status, true)) {
            return new WP_Error('bad_status', 'Invalid status.');
        }
        $order['status'] = $st;
        if ($st === 'verified' && empty($order['verified_at'])) {
            $order['verified_at'] = current_time('mysql');
        }
        if ($st === 'shipped' && empty($order['shipped_at'])) {
            $order['shipped_at'] = current_time('mysql');
        }
        if ($st === 'delivered' && empty($order['delivered_at'])) {
            $order['delivered_at'] = current_time('mysql');
        }
    }
    if (array_key_exists('tracking_number', $fields)) {
        $order['tracking_number'] = sanitize_text_field($fields['tracking_number']);
        if (!empty($order['tracking_number']) && empty($order['shipped_at'])) {
            // Auto-promote to shipped when resi filled and still earlier stage
            $st = $order['status'] ?? '';
            if (in_array($st, ['verified', 'payment_uploaded'], true)) {
                $order['status'] = 'shipped';
                $order['shipped_at'] = current_time('mysql');
            }
        }
    }
    if (array_key_exists('courier', $fields) && $fields['courier'] !== '') {
        $order['courier'] = sanitize_text_field($fields['courier']);
    }
    if (array_key_exists('service', $fields) && $fields['service'] !== '') {
        $order['service'] = sanitize_text_field($fields['service']);
    }
    if (array_key_exists('admin_note', $fields)) {
        $order['admin_note'] = sanitize_textarea_field($fields['admin_note']);
    }
    $order['updated_at'] = current_time('mysql');
    $order['tracking_url'] = wdc_giveaway_tracking_url($order['courier'] ?? '', $order['tracking_number'] ?? '');

    update_user_meta($user_id, '_wdc_giveaway_order', $order);
    wdc_giveaway_sync_activity($user_id, $order);
    return $order;
}

/**
 * Admin action handler for giveaway order updates.
 */
function wdc_handle_giveaway_admin_update() {
    if (!current_user_can('manage_options')) {
        wp_die('Forbidden');
    }
    check_admin_referer('wdc_giveaway_admin_update');

    $user_id = intval($_POST['user_id'] ?? 0);
    $order_id = sanitize_text_field($_POST['order_id'] ?? '');
    $fields = [
        'status' => sanitize_key($_POST['status'] ?? ''),
        'tracking_number' => sanitize_text_field($_POST['tracking_number'] ?? ''),
        'courier' => sanitize_text_field($_POST['courier'] ?? ''),
        'service' => sanitize_text_field($_POST['service'] ?? ''),
        'admin_note' => sanitize_textarea_field($_POST['admin_note'] ?? ''),
    ];
    $result = wdc_giveaway_admin_update_order($user_id, $order_id, $fields);
    $redirect = add_query_arg([
        'page' => 'wdc-giveaway-orders',
        'updated' => is_wp_error($result) ? '0' : '1',
        'msg' => is_wp_error($result) ? rawurlencode($result->get_error_message()) : 'updated',
        'order_id' => $order_id,
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect);
    exit;
}
add_action('admin_post_wdc_giveaway_admin_update', 'wdc_handle_giveaway_admin_update');

/**
 * Render giveaway orders admin list.
 */
function wdc_render_giveaway_orders_admin() {
    if (!current_user_can('manage_options')) {
        wp_die('Forbidden');
    }
    $filter = sanitize_key($_GET['status'] ?? '');
    $orders = wdc_giveaway_collect_orders($filter);
    $counts = [
        'all' => count(wdc_giveaway_collect_orders('')),
        'payment_uploaded' => count(wdc_giveaway_collect_orders('payment_uploaded')),
        'verified' => count(wdc_giveaway_collect_orders('verified')),
        'shipped' => count(wdc_giveaway_collect_orders('shipped')),
        'delivered' => count(wdc_giveaway_collect_orders('delivered')),
        'awaiting_payment' => count(wdc_giveaway_collect_orders('awaiting_payment')),
    ];
    $all_items = wdc_get_giveaway_items();
    $item_map = [];
    foreach ($all_items as $it) {
        $item_map[$it['id']] = $it['name'];
    }
    $open_order = sanitize_text_field(wp_unslash($_GET['order_id'] ?? ''));
    ?>
    <div class="wrap wdc-gw-admin">
        <style>
            .wdc-gw-admin { max-width:none; }
            .wdc-gw-admin .wdc-gw-filters { float:none; margin:8px 0 16px; width:100%; }
            .wdc-gw-admin .wdc-gw-hint { color:#646970; margin:0 0 12px; }
            .wdc-gw-admin .wdc-gw-table-wrap {
                width:100%; background:#fff; border:1px solid #c3c4c7; border-radius:8px; overflow:hidden;
            }
            .wdc-gw-admin table.wdc-gw-table {
                width:100%; border-collapse:collapse; margin:0; table-layout:fixed;
            }
            .wdc-gw-admin table.wdc-gw-table th,
            .wdc-gw-admin table.wdc-gw-table td {
                padding:12px 14px; text-align:left; vertical-align:middle; border-bottom:1px solid #e2e4e7;
            }
            .wdc-gw-admin table.wdc-gw-table th {
                background:#f6f7f7; font-size:12px; text-transform:uppercase; letter-spacing:.03em; color:#1d2327;
            }
            .wdc-gw-admin table.wdc-gw-table tr.wdc-gw-row { cursor:pointer; }
            .wdc-gw-admin table.wdc-gw-table tr.wdc-gw-row:hover { background:#f0f6fc; }
            .wdc-gw-admin table.wdc-gw-table tr.wdc-gw-row.is-open { background:#eef5fb; }
            .wdc-gw-admin table.wdc-gw-table tr.wdc-gw-detail-row td {
                background:#fcfcfc; padding:0; border-bottom:1px solid #c3c4c7;
            }
            .wdc-gw-admin .wdc-gw-order { font-weight:700; color:#1d2327; }
            .wdc-gw-admin .wdc-gw-sub { color:#646970; font-size:12px; margin-top:2px; }
            .wdc-gw-admin .wdc-gw-badge {
                display:inline-block; padding:3px 10px; border-radius:999px;
                font-size:12px; font-weight:700; line-height:1.4; white-space:nowrap;
            }
            .wdc-gw-admin .col-order { width:16%; }
            .wdc-gw-admin .col-status { width:16%; }
            .wdc-gw-admin .col-member { width:20%; }
            .wdc-gw-admin .col-items { width:18%; }
            .wdc-gw-admin .col-ongkir { width:12%; }
            .wdc-gw-admin .col-date { width:12%; }
            .wdc-gw-admin .col-toggle { width:6%; text-align:right; }
            .wdc-gw-admin .wdc-gw-toggle {
                display:inline-flex; align-items:center; justify-content:center;
                min-width:28px; height:28px; border-radius:6px; background:#fff;
                border:1px solid #c3c4c7; color:#1d2327; font-weight:700;
            }
            .wdc-gw-admin tr.is-open .wdc-gw-toggle { background:#2271b1; border-color:#2271b1; color:#fff; }
            .wdc-gw-admin .wdc-gw-detail {
                display:none; padding:16px 18px; box-sizing:border-box;
            }
            .wdc-gw-admin tr.is-open + tr.wdc-gw-detail-row .wdc-gw-detail { display:block; }
            .wdc-gw-admin .wdc-gw-detail-grid {
                display:grid; grid-template-columns:minmax(280px,1.2fr) minmax(300px,1fr); gap:16px;
            }
            .wdc-gw-admin .wdc-gw-info {
                font-size:13px; line-height:1.7; color:#1d2327;
                background:#fff; border:1px solid #e2e4e7; border-radius:8px; padding:14px 16px;
            }
            .wdc-gw-admin .wdc-gw-info div { margin:0 0 6px; }
            .wdc-gw-admin .wdc-gw-info div:last-child { margin-bottom:0; }
            .wdc-gw-admin .wdc-gw-form {
                background:#fff; border:1px solid #e2e4e7; border-radius:8px; padding:14px 16px;
                display:grid; gap:10px; align-content:start;
            }
            .wdc-gw-admin .wdc-gw-form label {
                display:grid; gap:4px; font-size:12px; font-weight:600; color:#1d2327;
            }
            .wdc-gw-admin .wdc-gw-form input[type="text"],
            .wdc-gw-admin .wdc-gw-form select,
            .wdc-gw-admin .wdc-gw-form textarea { width:100%; max-width:100%; margin:0; }
            .wdc-gw-admin .wdc-gw-row2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
            .wdc-gw-admin .wdc-gw-help { margin:0; font-size:12px; color:#646970; line-height:1.45; }
            .wdc-gw-admin .wdc-gw-actions { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
            @media (max-width: 960px) {
                .wdc-gw-admin table.wdc-gw-table { table-layout:auto; }
                .wdc-gw-admin .wdc-gw-detail-grid { grid-template-columns:1fr; }
                .wdc-gw-admin .col-items, .wdc-gw-admin .col-date { display:none; }
            }
        </style>
        <h1><?php echo esc_html(contenly_tr('Giveaway Orders', 'Giveaway Orders')); ?></h1>
        <?php if (isset($_GET['updated'])) : ?>
            <div class="<?php echo ($_GET['updated'] === '1') ? 'notice notice-success' : 'notice notice-error'; ?> is-dismissible"><p><?php echo esc_html($_GET['msg'] ?? 'Done'); ?></p></div>
        <?php endif; ?>

        <ul class="subsubsub wdc-gw-filters">
            <?php
            $tabs = [
                '' => 'All (' . $counts['all'] . ')',
                'payment_uploaded' => 'Need Verify (' . $counts['payment_uploaded'] . ')',
                'verified' => 'Verified (' . $counts['verified'] . ')',
                'shipped' => 'Shipped (' . $counts['shipped'] . ')',
                'delivered' => 'Delivered (' . $counts['delivered'] . ')',
                'awaiting_payment' => 'Awaiting Pay (' . $counts['awaiting_payment'] . ')',
            ];
            $i = 0;
            foreach ($tabs as $key => $label) :
                $url = admin_url('admin.php?page=wdc-giveaway-orders' . ($key ? '&status=' . $key : ''));
                $cls = ($filter === $key || ($filter === '' && $key === '')) ? 'current' : '';
                echo ($i++ ? ' | ' : '') . '<li><a class="' . esc_attr($cls) . '" href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
            endforeach;
            ?>
        </ul>

        <?php if (empty($orders)) : ?>
            <p><?php echo esc_html(contenly_tr('Belum ada order giveaway.', 'No giveaway orders yet.')); ?></p>
        <?php else : ?>
            <p class="wdc-gw-hint">Tampil info penting saja. Klik baris untuk buka detail + update status/resi.</p>
            <div class="wdc-gw-table-wrap">
                <table class="wdc-gw-table">
                    <thead>
                        <tr>
                            <th class="col-order">Order</th>
                            <th class="col-status">Status</th>
                            <th class="col-member">Member / Penerima</th>
                            <th class="col-items">Items</th>
                            <th class="col-ongkir">Ongkir</th>
                            <th class="col-date">Tanggal</th>
                            <th class="col-toggle"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $idx => $o) :
                        $st = sanitize_key($o['status'] ?? '');
                        $meta = wdc_giveaway_status_meta($st);
                        $names = [];
                        foreach (($o['items'] ?? []) as $id) {
                            $names[] = $item_map[$id] ?? $id;
                        }
                        $track_url = !empty($o['tracking_url']) ? $o['tracking_url'] : wdc_giveaway_tracking_url($o['courier'] ?? '', $o['tracking_number'] ?? '');
                        $order_id = (string) ($o['order_id'] ?? '');
                        $is_open = ($open_order && $open_order === $order_id);
                        $row_id = 'wdc-gw-' . sanitize_html_class($order_id ?: ('row-' . $idx));
                        $member = $o['display_name'] ?: ($o['user_login'] ?? '-');
                        $recipient = $o['recipient_name'] ?? '-';
                        $items_short = implode(', ', $names) ?: '-';
                        if (function_exists('mb_strimwidth')) {
                            $items_short = mb_strimwidth($items_short, 0, 42, '…', 'UTF-8');
                        } elseif (strlen($items_short) > 42) {
                            $items_short = substr($items_short, 0, 39) . '...';
                        }
                        ?>
                        <tr class="wdc-gw-row<?php echo $is_open ? ' is-open' : ''; ?>" data-target="<?php echo esc_attr($row_id); ?>" tabindex="0">
                            <td class="col-order">
                                <div class="wdc-gw-order"><?php echo esc_html($order_id); ?></div>
                                <?php if (!empty($o['tracking_number'])) : ?>
                                    <div class="wdc-gw-sub">Resi: <?php echo esc_html($o['tracking_number']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="col-status">
                                <span class="wdc-gw-badge" style="background:<?php echo esc_attr($meta['bg']); ?>;color:<?php echo esc_attr($meta['color']); ?>;"><?php echo esc_html($meta['label']); ?></span>
                            </td>
                            <td class="col-member">
                                <div><?php echo esc_html($member); ?></div>
                                <div class="wdc-gw-sub"><?php echo esc_html($recipient); ?></div>
                            </td>
                            <td class="col-items"><?php echo esc_html($items_short); ?></td>
                            <td class="col-ongkir"><strong>Rp <?php echo number_format(intval($o['shipping_cost'] ?? 0), 0, ',', '.'); ?></strong></td>
                            <td class="col-date"><span class="wdc-gw-sub"><?php echo esc_html($o['created_at'] ?? '-'); ?></span></td>
                            <td class="col-toggle"><span class="wdc-gw-toggle" aria-hidden="true"><?php echo $is_open ? '−' : '+'; ?></span></td>
                        </tr>
                        <tr class="wdc-gw-detail-row" id="<?php echo esc_attr($row_id); ?>">
                            <td colspan="7">
                                <div class="wdc-gw-detail">
                                    <div class="wdc-gw-detail-grid">
                                        <div class="wdc-gw-info">
                                            <div><strong>Member:</strong> <?php echo esc_html($member); ?> · <?php echo esc_html($o['user_email'] ?? '-'); ?> · #<?php echo intval($o['user_id'] ?? 0); ?></div>
                                            <div><strong>Items:</strong> <?php echo esc_html(implode(', ', $names) ?: '-'); ?></div>
                                            <div><strong>Penerima:</strong> <?php echo esc_html($recipient); ?> · <?php echo esc_html($o['phone'] ?? '-'); ?></div>
                                            <div><strong>Alamat:</strong> <?php echo esc_html($o['address'] ?? '-'); ?><?php echo !empty($o['destination']) ? ', ' . esc_html($o['destination']) : ''; ?></div>
                                            <div><strong>Kurir:</strong> <?php echo esc_html(strtoupper($o['courier'] ?? '-') . ' ' . ($o['service'] ?? '')); ?></div>
                                            <?php if (!empty($o['quote_ss_url'])) : ?>
                                                <div><strong>SS Ongkir:</strong> <a href="<?php echo esc_url($o['quote_ss_url']); ?>" target="_blank" rel="noopener">lihat</a></div>
                                            <?php endif; ?>
                                            <?php if (!empty($o['proof_url'])) : ?>
                                                <div><strong>Bukti TF:</strong> <a href="<?php echo esc_url($o['proof_url']); ?>" target="_blank" rel="noopener">lihat</a> · paid Rp <?php echo number_format(intval($o['paid_amount'] ?? $o['shipping_cost'] ?? 0), 0, ',', '.'); ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($o['tracking_number'])) : ?>
                                                <div><strong>Resi:</strong> <code><?php echo esc_html($o['tracking_number']); ?></code>
                                                <?php if ($track_url) : ?> · <a href="<?php echo esc_url($track_url); ?>" target="_blank" rel="noopener">cek tracking</a><?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($o['admin_note'])) : ?>
                                                <div><strong>Catatan:</strong> <?php echo esc_html($o['admin_note']); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="wdc-gw-form">
                                            <input type="hidden" name="action" value="wdc_giveaway_admin_update">
                                            <?php wp_nonce_field('wdc_giveaway_admin_update'); ?>
                                            <input type="hidden" name="user_id" value="<?php echo intval($o['user_id']); ?>">
                                            <input type="hidden" name="order_id" value="<?php echo esc_attr($order_id); ?>">

                                            <label>Status
                                                <select name="status">
                                                    <?php foreach (['awaiting_payment','payment_uploaded','verified','shipped','delivered','cancelled'] as $opt) :
                                                        $om = wdc_giveaway_status_meta($opt); ?>
                                                        <option value="<?php echo esc_attr($opt); ?>" <?php selected($st, $opt); ?>><?php echo esc_html($om['label']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </label>
                                            <div class="wdc-gw-row2">
                                                <label>Kurir
                                                    <input type="text" name="courier" value="<?php echo esc_attr($o['courier'] ?? ''); ?>">
                                                </label>
                                                <label>Layanan
                                                    <input type="text" name="service" value="<?php echo esc_attr($o['service'] ?? ''); ?>">
                                                </label>
                                            </div>
                                            <label>No. Resi
                                                <input type="text" name="tracking_number" value="<?php echo esc_attr($o['tracking_number'] ?? ''); ?>" placeholder="Contoh: JP1234567890">
                                            </label>
                                            <label>Catatan Admin
                                                <textarea name="admin_note" rows="2"><?php echo esc_textarea($o['admin_note'] ?? ''); ?></textarea>
                                            </label>
                                            <p class="wdc-gw-help">Isi resi + set status <strong>Barang Dikirim</strong>. User cek progres + tracking di dashboard.</p>
                                            <div class="wdc-gw-actions">
                                                <button type="submit" class="button button-primary">Simpan Update</button>
                                                <?php if ($st === 'payment_uploaded') : ?>
                                                    <button type="submit" class="button" name="status" value="verified" onclick="this.form.status.value='verified'">Verifikasi Pembayaran</button>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <script>
            (function(){
              function closeAll(except){
                document.querySelectorAll('.wdc-gw-admin tr.wdc-gw-row.is-open').forEach(function(row){
                  if (except && row === except) return;
                  row.classList.remove('is-open');
                  var t = row.querySelector('.wdc-gw-toggle');
                  if (t) t.textContent = '+';
                });
              }
              function toggleRow(row){
                var open = row.classList.contains('is-open');
                closeAll(row);
                if (open) {
                  row.classList.remove('is-open');
                  var t = row.querySelector('.wdc-gw-toggle');
                  if (t) t.textContent = '+';
                } else {
                  row.classList.add('is-open');
                  var t2 = row.querySelector('.wdc-gw-toggle');
                  if (t2) t2.textContent = '−';
                  try { row.scrollIntoView({behavior:'smooth', block:'nearest'}); } catch(e) {}
                }
              }
              document.querySelectorAll('.wdc-gw-admin tr.wdc-gw-row').forEach(function(row){
                row.addEventListener('click', function(e){
                  if (e.target.closest('a,button,input,select,textarea,label,form')) return;
                  toggleRow(row);
                });
                row.addEventListener('keydown', function(e){
                  if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleRow(row);
                  }
                });
              });
              // keep forms clickable without collapsing parent quirks
              document.querySelectorAll('.wdc-gw-admin .wdc-gw-detail').forEach(function(detail){
                detail.addEventListener('click', function(e){ e.stopPropagation(); });
              });
            })();
            </script>
        <?php endif; ?>
    </div>
    <?php
}

/* =========================================================================
   ADMIN SETTINGS
   ========================================================================= */

add_action('admin_menu', function() {
    add_submenu_page(
        'contenly-member',
        contenly_tr('Giveaway Settings', 'Giveaway Settings'),
        contenly_tr('Giveaway Settings', 'Giveaway Settings'),
        'manage_options',
        'wdc-giveaway-settings',
        'wdc_render_giveaway_settings'
    );
    add_submenu_page(
        'contenly-member',
        contenly_tr('Giveaway Orders', 'Giveaway Orders'),
        contenly_tr('Giveaway Orders', 'Giveaway Orders'),
        'manage_options',
        'wdc-giveaway-orders',
        'wdc_render_giveaway_orders_admin'
    );
    // Also under WDC Members (main ops menu)
    add_submenu_page(
        'wdc-member-admin',
        contenly_tr('Giveaway Orders', 'Giveaway Orders'),
        contenly_tr('Giveaway Orders', 'Giveaway Orders'),
        'manage_options',
        'wdc-giveaway-orders',
        'wdc_render_giveaway_orders_admin'
    );
}, 20);

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
        <h1><?php echo contenly_tr('Pengaturan Giveaway', 'Giveaway Settings'); ?></h1>
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
