<?php
/**
 * WDC Site Content — admin-editable contact, home hero, testimonials.
 * No page builder. Form fields only.
 */
if (!defined('ABSPATH')) {
    exit;
}

function wdc_site_defaults() {
    return [
        'email' => 'info@whaledivecentre.com',
        'phone' => '(021) 27939068',
        'phone_tel' => '+622127939068',
        'address' => 'Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240',
        'hours' => 'Senin–Sabtu, 09.00–18.00',
        'instagram' => 'https://www.instagram.com/whaledivecentre.id/',
        'facebook' => 'https://www.facebook.com/whaledive.id/',
        'x' => 'https://x.com/whaledivecentre',
        'footer_kicker' => 'Siap dive?',
        'footer_blurb' => 'Pelatihan selam, trip komunitas, dukungan peralatan, dan pengalaman peduli laut untuk petualangan bawah air yang lebih aman.',
        'footer_cta_label' => 'Mulai Konsultasi',
        'footer_cta_url' => '/contact/',
        'hero_kicker' => 'Latihan selam Jakarta & komunitas diving',
        'hero_title' => "Mulai Tenang.\nDive Pede.",
        'hero_text' => 'Belajar, siapkan gear, dan rencanakan petualangan bawah air berikutnya bersama crew yang menjaga setiap dive tetap jelas, aman, dan peduli laut.',
        'hero_cta1_label' => 'Lihat Kursus',
        'hero_cta1_url' => '/courses/',
        'hero_cta2_label' => 'Tanya Crew',
        'hero_cta2_url' => '/contact/',
        'hero_proof_1_title' => 'Bersertifikat',
        'hero_proof_1_text' => 'dipandu instruktur',
        'hero_proof_2_title' => 'Grup kecil',
        'hero_proof_2_text' => 'progres skill lebih tenang',
        'hero_proof_3_title' => 'Rekreasional',
        'hero_proof_3_text' => 'jalur dive santai',
        'hero_card_kicker' => 'Intake berikutnya',
        'hero_card_title' => 'Open Water',
        'hero_card_text' => 'Kelas grup kecil, fitting gear, dan bimbingan instruktur yang tenang dari kolam ke laut.',
        'hero_card_cta_label' => 'Lihat jalur',
        'hero_card_cta_url' => '/courses/',
        'reviews_kicker' => 'Dipercaya Diver',
        'reviews_title' => 'Kata Komunitas Kami',
        'notify_emails' => '',
        'notify_crew' => '1',
        'notify_member' => '1',
        'member_reply_course' => 'Terima kasih. Permintaan kursus kamu sudah kami terima. Crew Whale Dive Centre akan follow-up untuk konfirmasi jadwal.',
        'member_reply_gear' => 'Terima kasih. Permintaan peralatan kamu sudah kami terima. Crew Whale Dive Centre akan bantu konfirmasi fitting / ketersediaan.',
    ];
}

function wdc_site_get($key = null, $default = null) {
    $defaults = wdc_site_defaults();
    $saved = get_option('wdc_site_settings', []);
    if (!is_array($saved)) {
        $saved = [];
    }
    $all = array_merge($defaults, $saved);
    if ($key === null) {
        return $all;
    }
    if (array_key_exists($key, $all) && $all[$key] !== '') {
        return $all[$key];
    }
    if ($default !== null) {
        return $default;
    }
    return $defaults[$key] ?? '';
}

function wdc_site_url($path) {
    $path = trim((string) $path);
    if ($path === '') {
        return home_url('/');
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return home_url('/' . ltrim($path, '/'));
}

function wdc_register_testimonial_cpt() {
    if (post_type_exists('wdc_testimonial')) {
        return;
    }
    register_post_type('wdc_testimonial', [
        'labels' => [
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
            'add_new_item' => 'Add Testimonial',
            'edit_item' => 'Edit Testimonial',
            'all_items' => 'All Testimonials',
            'menu_name' => 'Testimonials',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'wdc-site',
        'menu_position' => 58,
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'show_in_rest' => false,
        'capability_type' => 'post',
    ]);
}
add_action('init', 'wdc_register_testimonial_cpt');

function wdc_site_admin_menu() {
    add_menu_page(
        'WDC Site',
        'WDC Site',
        'manage_options',
        'wdc-site',
        'wdc_render_site_settings_page',
        'dashicons-admin-site-alt3',
        58
    );
    add_submenu_page('wdc-site', 'Contact & Footer', 'Contact & Footer', 'manage_options', 'wdc-site', 'wdc_render_site_settings_page');
    add_submenu_page('wdc-site', 'Home Hero', 'Home Hero', 'manage_options', 'wdc-site-hero', 'wdc_render_home_hero_page');
    add_submenu_page('wdc-site', 'Notifications', 'Notifications', 'manage_options', 'wdc-site-notify', 'wdc_render_notifications_page');
    add_submenu_page(
        'wdc-site',
        'Testimonials',
        'Testimonials',
        'edit_posts',
        'edit.php?post_type=wdc_testimonial'
    );
    add_submenu_page(
        'wdc-site',
        'Add Testimonial',
        'Add Testimonial',
        'edit_posts',
        'post-new.php?post_type=wdc_testimonial'
    );
}
add_action('admin_menu', 'wdc_site_admin_menu');

function wdc_site_field($key, $label, $type = 'text', $help = '') {
    $value = wdc_site_get($key);
    echo '<tr><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td>';
    if ($type === 'textarea') {
        printf(
            '<textarea class="large-text" rows="4" id="%1$s" name="%1$s">%2$s</textarea>',
            esc_attr($key),
            esc_textarea($value)
        );
    } else {
        printf(
            '<input class="regular-text" type="%3$s" id="%1$s" name="%1$s" value="%2$s" style="width:min(520px,100%%)">',
            esc_attr($key),
            esc_attr($value),
            esc_attr($type)
        );
    }
    if ($help !== '') {
        echo '<p class="description">' . esc_html($help) . '</p>';
    }
    echo '</td></tr>';
}

function wdc_site_save_posted_keys($keys) {
    if (!current_user_can('manage_options')) {
        return false;
    }
    if (!isset($_POST['wdc_site_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_site_nonce'])), 'wdc_site_save')) {
        return false;
    }
    $current = get_option('wdc_site_settings', []);
    if (!is_array($current)) {
        $current = [];
    }
    foreach ($keys as $key) {
        if (in_array($key, ['notify_crew', 'notify_member'], true)) {
            $current[$key] = isset($_POST[$key]) ? '1' : '0';
            continue;
        }
        $raw = wp_unslash($_POST[$key] ?? '');
        if ($key === 'notify_emails') {
            $parts = preg_split('/[\s,;]+/', (string) $raw);
            $emails = [];
            foreach ($parts as $part) {
                $email = sanitize_email($part);
                if ($email && is_email($email)) {
                    $emails[] = $email;
                }
            }
            $current[$key] = implode(', ', array_values(array_unique($emails)));
            continue;
        }
        if (strpos($key, 'url') !== false || in_array($key, ['instagram', 'facebook', 'x', 'email'], true)) {
            $current[$key] = esc_url_raw($raw);
            if ($key === 'email') {
                $current[$key] = sanitize_email($raw);
            }
            if (in_array($key, ['hero_cta1_url', 'hero_cta2_url', 'hero_card_cta_url', 'footer_cta_url'], true)) {
                // allow relative paths
                $current[$key] = sanitize_text_field($raw);
            }
        } elseif (in_array($key, ['hero_title', 'hero_text', 'footer_blurb', 'hero_card_text', 'member_reply_course', 'member_reply_gear'], true)) {
            $current[$key] = sanitize_textarea_field($raw);
        } else {
            $current[$key] = sanitize_text_field($raw);
        }
    }
    update_option('wdc_site_settings', $current, false);
    return true;
}

function wdc_render_site_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $keys = [
        'email', 'phone', 'phone_tel', 'address', 'hours',
        'instagram', 'facebook', 'x',
        'footer_kicker', 'footer_blurb', 'footer_cta_label', 'footer_cta_url',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — Contact & Footer</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Isi kontak bisnis. Muncul di footer + halaman yang pakai helper kontak.</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    wdc_site_field('email', 'Email');
    wdc_site_field('phone', 'Phone display', 'text', 'Contoh: (021) 27939068');
    wdc_site_field('phone_tel', 'Phone tel link', 'text', 'Contoh: +622127939068');
    wdc_site_field('address', 'Address', 'textarea');
    wdc_site_field('hours', 'Business hours');
    wdc_site_field('instagram', 'Instagram URL', 'url');
    wdc_site_field('facebook', 'Facebook URL', 'url');
    wdc_site_field('x', 'X / Twitter URL', 'url');
    wdc_site_field('footer_kicker', 'Footer kicker');
    wdc_site_field('footer_blurb', 'Footer blurb', 'textarea');
    wdc_site_field('footer_cta_label', 'Footer CTA label');
    wdc_site_field('footer_cta_url', 'Footer CTA URL', 'text', 'Boleh path relatif: /contact/');
    echo '</tbody></table>';
    submit_button('Save Contact & Footer');
    echo '</form></div>';
}

function wdc_render_home_hero_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $keys = [
        'hero_kicker', 'hero_title', 'hero_text',
        'hero_cta1_label', 'hero_cta1_url', 'hero_cta2_label', 'hero_cta2_url',
        'hero_proof_1_title', 'hero_proof_1_text',
        'hero_proof_2_title', 'hero_proof_2_text',
        'hero_proof_3_title', 'hero_proof_3_text',
        'hero_card_kicker', 'hero_card_title', 'hero_card_text',
        'hero_card_cta_label', 'hero_card_cta_url',
        'reviews_kicker', 'reviews_title',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — Home Hero</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Edit copy hero homepage + heading section testimoni. Baris baru di title jadi line break.</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    wdc_site_field('hero_kicker', 'Hero kicker');
    wdc_site_field('hero_title', 'Hero title', 'textarea', 'Pakai Enter untuk baris baru');
    wdc_site_field('hero_text', 'Hero text', 'textarea');
    wdc_site_field('hero_cta1_label', 'Primary CTA label');
    wdc_site_field('hero_cta1_url', 'Primary CTA URL');
    wdc_site_field('hero_cta2_label', 'Secondary CTA label');
    wdc_site_field('hero_cta2_url', 'Secondary CTA URL');
    wdc_site_field('hero_proof_1_title', 'Proof 1 title');
    wdc_site_field('hero_proof_1_text', 'Proof 1 text');
    wdc_site_field('hero_proof_2_title', 'Proof 2 title');
    wdc_site_field('hero_proof_2_text', 'Proof 2 text');
    wdc_site_field('hero_proof_3_title', 'Proof 3 title');
    wdc_site_field('hero_proof_3_text', 'Proof 3 text');
    wdc_site_field('hero_card_kicker', 'Side card kicker');
    wdc_site_field('hero_card_title', 'Side card title');
    wdc_site_field('hero_card_text', 'Side card text', 'textarea');
    wdc_site_field('hero_card_cta_label', 'Side card CTA label');
    wdc_site_field('hero_card_cta_url', 'Side card CTA URL');
    wdc_site_field('reviews_kicker', 'Reviews section kicker');
    wdc_site_field('reviews_title', 'Reviews section title');
    echo '</tbody></table>';
    submit_button('Save Home Hero');
    echo '</form>';
    echo '<p><a class="button" href="' . esc_url(home_url('/')) . '" target="_blank" rel="noopener">Preview homepage</a></p>';
    echo '</div>';
}

function wdc_testimonial_meta_boxes() {
    add_meta_box('wdc_testimonial_details', 'Testimonial Details', 'wdc_render_testimonial_meta_box', 'wdc_testimonial', 'normal', 'high');
}
add_action('add_meta_boxes', 'wdc_testimonial_meta_boxes');

function wdc_render_testimonial_meta_box($post) {
    wp_nonce_field('wdc_save_testimonial', 'wdc_testimonial_nonce');
    $role = get_post_meta($post->ID, '_wdc_role', true);
    $stars = get_post_meta($post->ID, '_wdc_stars', true);
    if ($stars === '') {
        $stars = '5';
    }
    echo '<p><label><strong>Role / course</strong><br><input type="text" name="_wdc_role" value="' . esc_attr($role) . '" class="widefat" placeholder="Open Water Diver"></label></p>';
    echo '<p><label><strong>Stars (1-5)</strong><br><input type="number" min="1" max="5" name="_wdc_stars" value="' . esc_attr($stars) . '" style="width:100px"></label></p>';
    echo '<p class="description">Title = nama. Content/editor = quote. Featured image opsional.</p>';
}

function wdc_save_testimonial_meta($post_id, $post) {
    if ($post->post_type !== 'wdc_testimonial') {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['wdc_testimonial_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_testimonial_nonce'])), 'wdc_save_testimonial')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    $role = sanitize_text_field(wp_unslash($_POST['_wdc_role'] ?? ''));
    $stars = max(1, min(5, (int) ($_POST['_wdc_stars'] ?? 5)));
    update_post_meta($post_id, '_wdc_role', $role);
    update_post_meta($post_id, '_wdc_stars', (string) $stars);
}
add_action('save_post', 'wdc_save_testimonial_meta', 10, 2);

function wdc_get_testimonials($limit = 3) {
    $posts = get_posts([
        'post_type' => 'wdc_testimonial',
        'post_status' => 'publish',
        'numberposts' => $limit,
        'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
    ]);
    $items = [];
    foreach ($posts as $p) {
        $items[] = [
            'name' => $p->post_title,
            'role' => get_post_meta($p->ID, '_wdc_role', true),
            'stars' => (int) (get_post_meta($p->ID, '_wdc_stars', true) ?: 5),
            'quote' => trim(wp_strip_all_tags($p->post_content)),
        ];
    }
    if ($items) {
        return $items;
    }
    // fallback defaults so homepage never empty
    return [
        [
            'name' => 'Sarah M.',
            'role' => 'Open Water Diver',
            'stars' => 5,
            'quote' => 'Kursus open water pertamaku terasa aman dan tenang berkat crew-nya. Grup kecil, instruktur sabar, dan kondisi gear sangat bagus.',
        ],
        [
            'name' => 'Marco R.',
            'role' => 'PADI Divemaster',
            'stars' => 5,
            'quote' => 'Pelatihan serius dengan crew Jakarta yang tenang. Jalur Divemaster-ku terasa terstruktur, jujur, dan fokus pada kepemimpinan dive yang nyata.',
        ],
        [
            'name' => 'Ayu P.',
            'role' => 'Diver Aktif',
            'stars' => 5,
            'quote' => 'Beli masker dan fins pertamaku di sini. Crew-nya bantu cari ukuran yang pas sebelum aku masuk ke air. Pelayanan oke banget.',
        ],
    ];
}

function wdc_seed_default_testimonials() {
    if (get_option('wdc_testimonials_seeded')) {
        return;
    }
    if (wp_count_posts('wdc_testimonial')->publish > 0) {
        update_option('wdc_testimonials_seeded', 1, false);
        return;
    }
    $defaults = [
        ['Sarah M.', 'Open Water Diver', 'Kursus open water pertamaku terasa aman dan tenang berkat crew-nya. Grup kecil, instruktur sabar, dan kondisi gear sangat bagus.'],
        ['Marco R.', 'PADI Divemaster', 'Pelatihan serius dengan crew Jakarta yang tenang. Jalur Divemaster-ku terasa terstruktur, jujur, dan fokus pada kepemimpinan dive yang nyata.'],
        ['Ayu P.', 'Diver Aktif', 'Beli masker dan fins pertamaku di sini. Crew-nya bantu cari ukuran yang pas sebelum aku masuk ke air. Pelayanan oke banget.'],
    ];
    foreach ($defaults as $i => $row) {
        $id = wp_insert_post([
            'post_type' => 'wdc_testimonial',
            'post_status' => 'publish',
            'post_title' => $row[0],
            'post_content' => $row[2],
            'menu_order' => $i + 1,
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wdc_role', $row[1]);
            update_post_meta($id, '_wdc_stars', '5');
        }
    }
    update_option('wdc_testimonials_seeded', 1, false);
}
add_action('init', 'wdc_seed_default_testimonials', 30);

function wdc_testimonial_columns($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['wdc_role'] = 'Role';
            $new['wdc_stars'] = 'Stars';
        }
    }
    return $new;
}
add_filter('manage_wdc_testimonial_posts_columns', 'wdc_testimonial_columns');

function wdc_testimonial_column_content($column, $post_id) {
    if ($column === 'wdc_role') {
        echo esc_html(get_post_meta($post_id, '_wdc_role', true) ?: '—');
    }
    if ($column === 'wdc_stars') {
        echo esc_html(get_post_meta($post_id, '_wdc_stars', true) ?: '5');
    }
}
add_action('manage_wdc_testimonial_posts_custom_column', 'wdc_testimonial_column_content', 10, 2);


function wdc_render_notifications_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $keys = [
        'notify_emails',
        'notify_crew',
        'notify_member',
        'member_reply_course',
        'member_reply_gear',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — Notifications</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Email otomatis saat member ajukan kursus / peralatan. Kosongkan notify emails = pakai WP Admin Email + contact email.</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    wdc_site_field('notify_emails', 'Crew notify emails', 'text', 'Pisah koma. Contoh: crew@whaledivecentre.com, ops@whaledivecentre.com');
    $crew_on = wdc_site_get('notify_crew', '1') === '1';
    $member_on = wdc_site_get('notify_member', '1') === '1';
    echo '<tr><th scope="row">Kirim ke crew</th><td><label><input type="checkbox" name="notify_crew" value="1"' . checked($crew_on, true, false) . '> Aktifkan email ke crew saat request masuk</label></td></tr>';
    echo '<tr><th scope="row">Auto-reply member</th><td><label><input type="checkbox" name="notify_member" value="1"' . checked($member_on, true, false) . '> Aktifkan email konfirmasi ke member</label></td></tr>';
    wdc_site_field('member_reply_course', 'Auto-reply teks (kursus)', 'textarea');
    wdc_site_field('member_reply_gear', 'Auto-reply teks (peralatan)', 'textarea');
    echo '</tbody></table>';
    submit_button('Save Notifications');
    echo '</form></div>';
}

function wdc_request_notify_recipients() {
    $raw = (string) wdc_site_get('notify_emails', '');
    $emails = [];
    if ($raw !== '') {
        foreach (preg_split('/[\s,;]+/', $raw) as $part) {
            $email = sanitize_email($part);
            if ($email && is_email($email)) {
                $emails[] = $email;
            }
        }
    }
    if (!$emails) {
        $admin = sanitize_email((string) get_option('admin_email'));
        if ($admin && is_email($admin)) {
            $emails[] = $admin;
        }
        $contact = sanitize_email((string) wdc_site_get('email'));
        if ($contact && is_email($contact)) {
            $emails[] = $contact;
        }
    }
    return array_values(array_unique($emails));
}

/**
 * Notify crew + member after course/gear request is saved.
 *
 * @param string $type 'course'|'gear'
 * @param int    $user_id
 * @param array  $payload request fields
 * @return array{crew:bool,member:bool}
 */
function wdc_notify_request($type, $user_id, array $payload) {
    $type = $type === 'gear' ? 'gear' : 'course';
    $user = get_userdata($user_id);
    $result = ['crew' => false, 'member' => false];
    if (!$user) {
        return $result;
    }

    $item = $type === 'course'
        ? (string) ($payload['course'] ?? 'Course')
        : (string) ($payload['gear'] ?? 'Gear');
    $display = $user->display_name ?: $user->user_login;
    $member_email = $user->user_email ?: '-';
    $created = (string) ($payload['created_at'] ?? current_time('mysql'));
    $message = trim((string) ($payload['message'] ?? ''));

    $lines = [
        '<strong>New WDC ' . ($type === 'course' ? 'course' : 'equipment') . ' request</strong>',
        'Item: <strong>' . esc_html($item) . '</strong>',
        'Member: ' . esc_html($display) . ' (#' . (int) $user_id . ')',
        'Email: ' . esc_html($member_email),
        'Submitted: ' . esc_html($created),
    ];
    if ($type === 'course') {
        $lines[] = 'Preferred date: ' . esc_html((string) ($payload['preferred_date'] ?: 'Flexible'));
        $lines[] = 'Experience: ' . esc_html((string) ($payload['experience'] ?? 'Not specified'));
        if (!empty($payload['item_id'])) {
            $lines[] = 'Item ID: ' . (int) $payload['item_id'];
        }
    } else {
        $lines[] = 'Request type: ' . esc_html((string) ($payload['request_type'] ?? 'Buy advice'));
        $lines[] = 'Size notes: ' . esc_html((string) ($payload['size_notes'] ?: '-'));
    }
    $lines[] = 'Message: ' . esc_html($message !== '' ? $message : '-');
    $lines[] = 'Open WP Admin → Users / member meta to follow up.';
    $body = implode('<br>', $lines);

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    if ($member_email && is_email($member_email)) {
        $headers[] = 'Reply-To: ' . $display . ' <' . $member_email . '>';
    }

    if (wdc_site_get('notify_crew', '1') === '1') {
        $recipients = wdc_request_notify_recipients();
        if ($recipients) {
            $subject = '[WDC] ' . ($type === 'course' ? 'Course request' : 'Gear request') . ': ' . $item;
            $result['crew'] = (bool) wp_mail($recipients, $subject, wpautop($body), $headers);
        }
    }

    if (wdc_site_get('notify_member', '1') === '1' && $member_email && is_email($member_email)) {
        $reply = $type === 'course'
            ? (string) wdc_site_get('member_reply_course')
            : (string) wdc_site_get('member_reply_gear');
        if ($reply === '') {
            $reply = $type === 'course'
                ? 'Terima kasih. Permintaan kursus kamu sudah kami terima.'
                : 'Terima kasih. Permintaan peralatan kamu sudah kami terima.';
        }
        $member_body = esc_html($reply)
            . '<br><br>Detail:<br>'
            . 'Item: <strong>' . esc_html($item) . '</strong><br>'
            . 'Waktu: ' . esc_html($created);
        $subject = $type === 'course'
            ? 'Permintaan kursus diterima — Whale Dive Centre'
            : 'Permintaan peralatan diterima — Whale Dive Centre';
        $result['member'] = (bool) wp_mail($member_email, $subject, wpautop($member_body), ['Content-Type: text/html; charset=UTF-8']);
    }

    return $result;
}

