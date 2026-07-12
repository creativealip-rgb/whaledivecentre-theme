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
        'email' => 'whaledivecentre@gmail.com',
        'phone' => '0821-2666-611',
        'phone_tel' => '+628212666611',
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
        'trust_text' => 'Gerbang kamu ke dunia bawah laut. Kami menggabungkan pelatihan selam profesional, peralatan berkualitas, bimbingan grup kecil, dan semangat konservasi laut.',
        'trust_label' => 'Dipercaya oleh',
        'partners' => "NAUI|naui.webp\nTDI|tdi.webp\nDAN|dan.webp\n---\nSherwood Scuba|sherwood.webp\nZeagle|zeagle.webp\nWaterproof|waterproof.webp\nShearwater Research|shearwater.webp\nBARE|bare.webp",
        'about_kicker' => 'Tentang Whale Dive Centre',
        'about_title' => 'Kantor Pusat NAUI Indonesia untuk pelatihan selam yang aman, profesional, dan berkelas dunia.',
        'about_text' => 'Didirikan pada 2008 di Jakarta, WDC berfokus pada pendidikan penyelam, keselamatan, eksplorasi bawah laut, dan pengembangan profesional diving Indonesia.',
        'about_cta1_label' => 'Kenali Tim',
        'about_cta1_url' => '#crew',
        'about_cta2_label' => 'Lihat Kursus',
        'about_cta2_url' => '/courses/',
        'about_intro_kicker' => 'Sejak 2008',
        'about_intro_title' => 'Standar internasional. Kepemimpinan lokal. Budaya keselamatan.',
        'about_intro_p1' => 'Whale Dive Centre (WDC) adalah salah satu institusi penyelaman terkemuka di Indonesia yang berkantor pusat di Jakarta. WDC menghadirkan pelatihan rekreasional, profesional, dan teknis dengan standar internasional.',
        'about_intro_p2' => 'Sebagai Kantor Pusat NAUI Indonesia serta pusat yang berafiliasi dengan NAUI, TDI, dan DAN, WDC membangun kompetensi, kepercayaan diri, dan kepemimpinan bawah air melalui instruktur berpengalaman dan pembelajaran berkelanjutan.',
        'contact_kicker' => 'Hubungi Kami',
        'contact_title' => 'Mulai percakapan dengan crew.',
        'contact_text' => 'Tanya jadwal kursus, ketersediaan peralatan, atau jalur sertifikasi. Kami balas dalam 24 jam.',
        'contact_form_kicker' => 'Hubungi Kami',
        'contact_form_title' => 'Mulai percakapan',
        'contact_success' => 'Terima kasih. Pesan Anda sudah terkirim dan crew akan membalas dalam 24 jam.',
        'contact_map_url' => 'https://maps.app.goo.gl/7A3Yo7gsaDCcS6xZ6',
        'contact_hours_note' => 'Senin - Sabtu, 09:00 - 18:00 WIB. Jadwal kursus dan perjalanan dikonfirmasi berdasarkan perjanjian.',
        'crew_kicker' => 'Leadership Team',
        'crew_title' => 'Profesional berpengalaman yang membangun ekosistem diving Indonesia.',
        'values_kicker' => 'Nilai Kerja',
        'values_title' => 'Safety, integrity, continuous learning.',
        'value_1_title' => 'Keselamatan',
        'value_1_text' => 'Setiap training, trip, dan rekomendasi dipandu oleh kesiapan diver, kondisi, dan standar konservatif.',
        'value_2_title' => 'Integritas',
        'value_2_text' => 'Progress diver dibangun dengan evaluasi jujur, bukan sertifikasi terburu-buru.',
        'value_3_title' => 'Pembelajaran berkelanjutan',
        'value_3_text' => 'WDC mendukung diver untuk terus naik level melalui edukasi, praktik, dan leadership.',
        'value_4_title' => 'Konservasi laut',
        'value_4_text' => 'Kami mendorong perilaku bawah air yang bertanggung jawab dan menghormati ekosistem laut.',
        'focus_kicker' => 'Fokus Kami',
        'focus_title' => 'Dari dive pertama sampai level profesional.',
        'focus_1_title' => 'Pelatihan rekreasional',
        'focus_1_text' => 'Membangun fondasi skill, buoyancy, buddy awareness, dan kepercayaan diri untuk diver baru.',
        'focus_2_title' => 'Pelatihan profesional',
        'focus_2_text' => 'Mengembangkan leadership, briefing, rescue awareness, dan standar kerja profesional.',
        'focus_3_title' => 'Budaya teknis & keselamatan',
        'focus_3_text' => 'Mendorong perencanaan konservatif, disiplin prosedur, dan keputusan yang sadar risiko.',
        'courses_title' => 'Kursus Selam Kami',
        'courses_sub' => 'Jalur terstruktur dari napas pertama di bawah air hingga kepemimpinan dive profesional.',
        'courses_cta_label' => 'Lihat Semua Kursus',
        'courses_cta_url' => '/courses/',
        'equip_title' => 'Peralatan Selam',
        'equip_sub' => 'Gear berkualitas untuk latihan, fun dive, dan kenyamanan bawah air yang lebih aman. Beli atau sewa melalui crew.',
        'articles_kicker' => 'Artikel Pilihan',
        'articles_title' => 'Cerita Dive & Catatan Laut',
        'articles_sub' => 'Bacaan pilihan untuk diver baru, pembeli gear, dan anggota komunitas yang peduli laut.',
        'articles_cta_label' => 'Baca Blog',
        'articles_cta_url' => '/blog/',
        'membership_kicker' => 'Portal Member',
        'membership_title' => 'Gabung Komunitas Whale Dive',
        'membership_text' => 'Lacak kursus, kelola sertifikasi, beli peralatan, dan terhubung dengan crew — semua dari dashboard member.',
        'membership_1_title' => 'Lacak Kursus',
        'membership_1_text' => 'Dari pendaftaran sampai sertifikasi',
        'membership_2_title' => 'Toko Peralatan',
        'membership_2_text' => 'Beli atau sewa gear online',
        'membership_3_title' => 'Portofolio Sertifikasi',
        'membership_3_text' => 'Semua kartu dive di satu tempat',
        'membership_cta_label' => 'Buat Akun Gratis',
        'membership_cta_url' => '/member-register/',
        // Courses page closing CTA
        'courses_page_cta_kicker' => 'Butuh saran kursus?',
        'courses_page_cta_title' => 'Crew bantu pilih jalur yang tepat.',
        'courses_page_cta_text' => 'Ceritakan level sertifikasi, target tanggal, dan tujuan kenyamanan — kami rekomendasikan kursus yang cocok.',
        'courses_page_cta_label' => 'Tanya Rencana Kursus',
        'courses_page_cta_url' => '/contact/',
        'courses_hero_cta_label' => 'Tanya Rencana Kursus',
        'courses_hero_cta_url' => '/contact/',
        // Equipment page closing CTA
        'equip_page_cta_kicker' => 'Butuh saran gear?',
        'equip_page_cta_title' => 'Crew bantu cari yang pas.',
        'equip_page_cta_text' => 'Ceritakan level sertifikasi, rencana dive, dan budget — kami rekomendasikan gear yang cocok.',
        'equip_page_cta_label' => 'Ajukan via Member',
        'equip_page_cta_url' => '',
        'equip_hero_cta_label' => 'Tanya Ketersediaan',
        'equip_hero_cta_url' => '/contact/',
        // Single course/equipment closing CTA copy
        'course_single_cta_kicker' => 'Siap saat kamu siap',
        'course_single_cta_title' => 'Daftar lewat akun member.',
        'course_single_cta_text' => 'Ajukan kursus dari dashboard. Crew follow-up setelah request masuk.',
        'equip_single_cta_kicker' => 'Siap saat kamu siap',
        'equip_single_cta_title' => 'Ajukan lewat akun member.',
        'equip_single_cta_text' => 'Ajukan gear dari dashboard. Crew follow-up size/stok setelah request masuk.',
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


/**
 * Language-aware site content.
 * Admin saves ID copy in wdc_site_settings. On EN pages, use English default
 * (or optional {$key}_en if filled) so saved ID text does not leak.
 */
function wdc_site_tr($key, $id_text, $en_text = null) {
    if ($en_text === null) {
        $en_text = $id_text;
    }
    $is_en = function_exists('contenly_is_english') && contenly_is_english();
    if ($is_en) {
        $en_key = $key . '_en';
        $saved = get_option('wdc_site_settings', []);
        if (is_array($saved) && !empty($saved[$en_key]) && is_string($saved[$en_key])) {
            return $saved[$en_key];
        }
        return $en_text;
    }
    return wdc_site_get($key, $id_text);
}

function wdc_site_url($path) {
    $path = trim((string) $path);
    if ($path === '') {
        return function_exists('contenly_localized_url') ? contenly_localized_url('/') : home_url('/');
    }
    if (preg_match('#^https?://#i', $path) || str_starts_with($path, 'mailto:') || str_starts_with($path, 'tel:')) {
        return $path;
    }
    // In-page anchors stay as-is.
    if (str_starts_with($path, '#')) {
        return $path;
    }
    if (function_exists('contenly_localized_url')) {
        return contenly_localized_url('/' . ltrim($path, '/'));
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
            'add_new' => 'Add New',
            'add_new_item' => 'Add Testimonial',
            'edit_item' => 'Edit Testimonial',
            // One clean sidebar label under WDC Site (not "All Testimonials").
            'all_items' => 'Testimonials',
            'menu_name' => 'Testimonials',
        ],
        'public' => false,
        'show_ui' => true,
        // CPT auto-adds list item under WDC Site. Do not also add manual submenu.
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
    add_submenu_page('wdc-site', 'Home Content', 'Home Content', 'manage_options', 'wdc-site-hero', 'wdc_render_home_hero_page');
    add_submenu_page('wdc-site', 'About Page', 'About Page', 'manage_options', 'wdc-site-about', 'wdc_render_about_page');
    add_submenu_page('wdc-site', 'Contact Page', 'Contact Page', 'manage_options', 'wdc-site-contact', 'wdc_render_contact_page');
    add_submenu_page('wdc-site', 'Courses & Equipment CTA', 'Courses & Equipment CTA', 'manage_options', 'wdc-site-cta', 'wdc_render_cta_page');
    add_submenu_page('wdc-site', 'Partners / Trust', 'Partners / Trust', 'manage_options', 'wdc-site-partners', 'wdc_render_partners_page');
    // Crew + Testimonials list come from CPT show_in_menu only.
    // "Add New" stays on list page button — not as extra sidebar items.
}
add_action('admin_menu', 'wdc_site_admin_menu');

/**
 * Hide auto "Add New" CPT submenus under WDC Site.
 * List screens already have "Add New" buttons.
 */
function wdc_prune_site_cpt_submenus() {
    remove_submenu_page('wdc-site', 'post-new.php?post_type=wdc_crew');
    remove_submenu_page('wdc-site', 'post-new.php?post_type=wdc_testimonial');
}
add_action('admin_menu', 'wdc_prune_site_cpt_submenus', 999);

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
            if (in_array($key, ['hero_cta1_url', 'hero_cta2_url', 'hero_card_cta_url', 'footer_cta_url', 'about_cta1_url', 'about_cta2_url', 'membership_cta_url', 'courses_cta_url', 'articles_cta_url', 'courses_page_cta_url', 'courses_hero_cta_url', 'equip_page_cta_url', 'equip_hero_cta_url'], true)) {
                // allow relative paths
                $current[$key] = sanitize_text_field($raw);
            }
        } elseif (in_array($key, [
            'hero_title', 'hero_text', 'footer_blurb', 'hero_card_text',
            'member_reply_course', 'member_reply_gear',
            'trust_text', 'partners',
            'about_title', 'about_text', 'about_intro_title', 'about_intro_p1', 'about_intro_p2',
            'contact_title', 'contact_text', 'contact_success', 'contact_hours_note',
            'crew_title', 'values_title',
            'value_1_text', 'value_2_text', 'value_3_text', 'value_4_text',
            'focus_title', 'focus_1_text', 'focus_2_text', 'focus_3_text',
            'courses_sub', 'equip_sub', 'articles_sub', 'articles_title',
            'membership_title', 'membership_text',
            'membership_1_text', 'membership_2_text', 'membership_3_text',
            'courses_page_cta_text', 'equip_page_cta_text',
            'course_single_cta_text', 'equip_single_cta_text',
            'courses_page_cta_title', 'equip_page_cta_title',
            'course_single_cta_title', 'equip_single_cta_title',
        ], true)) {
            $current[$key] = sanitize_textarea_field($raw);
        } elseif ($key === 'contact_map_url') {
            $current[$key] = esc_url_raw($raw) ?: sanitize_text_field($raw);
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
    wdc_site_field('phone', 'Phone / WhatsApp display', 'text', 'Contoh: 0821-2666-611');
    wdc_site_field('phone_tel', 'Phone / WhatsApp tel link', 'text', 'Format internasional tanpa spasi. Contoh: +628212666611');
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
        'focus_kicker', 'focus_title',
        'focus_1_title', 'focus_1_text',
        'focus_2_title', 'focus_2_text',
        'focus_3_title', 'focus_3_text',
        'courses_title', 'courses_sub', 'courses_cta_label', 'courses_cta_url',
        'equip_title', 'equip_sub',
        'reviews_kicker', 'reviews_title',
        'articles_kicker', 'articles_title', 'articles_sub', 'articles_cta_label', 'articles_cta_url',
        'membership_kicker', 'membership_title', 'membership_text',
        'membership_1_title', 'membership_1_text',
        'membership_2_title', 'membership_2_text',
        'membership_3_title', 'membership_3_text',
        'membership_cta_label', 'membership_cta_url',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — Home Content</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Edit copy homepage: hero, focus, courses/equipment headings, reviews, articles, membership. Baris baru di hero title jadi line break.</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    echo '<tr><th colspan="2"><h2 style="margin:12px 0 0">Hero</h2></th></tr>';
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
    echo '<tr><th colspan="2"><h2 style="margin:18px 0 0">Focus areas</h2></th></tr>';
    wdc_site_field('focus_kicker', 'Focus kicker');
    wdc_site_field('focus_title', 'Focus title', 'textarea');
    wdc_site_field('focus_1_title', 'Focus 1 title');
    wdc_site_field('focus_1_text', 'Focus 1 text', 'textarea');
    wdc_site_field('focus_2_title', 'Focus 2 title');
    wdc_site_field('focus_2_text', 'Focus 2 text', 'textarea');
    wdc_site_field('focus_3_title', 'Focus 3 title');
    wdc_site_field('focus_3_text', 'Focus 3 text', 'textarea');
    echo '<tr><th colspan="2"><h2 style="margin:18px 0 0">Courses / Equipment headings</h2></th></tr>';
    wdc_site_field('courses_title', 'Courses title');
    wdc_site_field('courses_sub', 'Courses subtitle', 'textarea');
    wdc_site_field('courses_cta_label', 'Courses CTA label');
    wdc_site_field('courses_cta_url', 'Courses CTA URL');
    wdc_site_field('equip_title', 'Equipment title');
    wdc_site_field('equip_sub', 'Equipment subtitle', 'textarea');
    echo '<tr><th colspan="2"><h2 style="margin:18px 0 0">Reviews / Articles</h2></th></tr>';
    wdc_site_field('reviews_kicker', 'Reviews section kicker');
    wdc_site_field('reviews_title', 'Reviews section title');
    wdc_site_field('articles_kicker', 'Articles kicker');
    wdc_site_field('articles_title', 'Articles title', 'textarea');
    wdc_site_field('articles_sub', 'Articles subtitle', 'textarea');
    wdc_site_field('articles_cta_label', 'Articles CTA label');
    wdc_site_field('articles_cta_url', 'Articles CTA URL');
    echo '<tr><th colspan="2"><h2 style="margin:18px 0 0">Membership CTA</h2></th></tr>';
    wdc_site_field('membership_kicker', 'Membership kicker');
    wdc_site_field('membership_title', 'Membership title', 'textarea');
    wdc_site_field('membership_text', 'Membership text', 'textarea');
    wdc_site_field('membership_1_title', 'Benefit 1 title');
    wdc_site_field('membership_1_text', 'Benefit 1 text', 'textarea');
    wdc_site_field('membership_2_title', 'Benefit 2 title');
    wdc_site_field('membership_2_text', 'Benefit 2 text', 'textarea');
    wdc_site_field('membership_3_title', 'Benefit 3 title');
    wdc_site_field('membership_3_text', 'Benefit 3 text', 'textarea');
    wdc_site_field('membership_cta_label', 'Membership CTA label');
    wdc_site_field('membership_cta_url', 'Membership CTA URL');
    echo '</tbody></table>';
    submit_button('Save Home Content');
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


/**
 * Recipient list for contact/admin commerce mail only.
 * Request email notifications are intentionally disabled.
 */
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


function wdc_render_about_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $keys = [
        'about_kicker', 'about_title', 'about_text',
        'about_cta1_label', 'about_cta1_url', 'about_cta2_label', 'about_cta2_url',
        'about_intro_kicker', 'about_intro_title', 'about_intro_p1', 'about_intro_p2',
        'crew_kicker', 'crew_title',
        'values_kicker', 'values_title',
        'value_1_title', 'value_1_text',
        'value_2_title', 'value_2_text',
        'value_3_title', 'value_3_text',
        'value_4_title', 'value_4_text',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — About Page</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Edit hero + intro About. Crew profiles tetap di template (foto/nama fixed dulu).</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    wdc_site_field('about_kicker', 'Hero kicker');
    wdc_site_field('about_title', 'Hero title', 'textarea');
    wdc_site_field('about_text', 'Hero text', 'textarea');
    wdc_site_field('about_cta1_label', 'CTA 1 label');
    wdc_site_field('about_cta1_url', 'CTA 1 URL', 'text', 'Boleh #crew atau /courses/');
    wdc_site_field('about_cta2_label', 'CTA 2 label');
    wdc_site_field('about_cta2_url', 'CTA 2 URL');
    wdc_site_field('about_intro_kicker', 'Intro kicker');
    wdc_site_field('about_intro_title', 'Intro title', 'textarea');
    wdc_site_field('about_intro_p1', 'Intro paragraph 1', 'textarea');
    wdc_site_field('about_intro_p2', 'Intro paragraph 2', 'textarea');
    echo '<tr><th colspan="2"><h2 style="margin:18px 0 0">Crew section</h2></th></tr>';
    wdc_site_field('crew_kicker', 'Crew kicker');
    wdc_site_field('crew_title', 'Crew title', 'textarea');
    echo '<tr><td colspan="2"><p class="description">Profil crew di menu <strong>WDC Site → Crew</strong> (nama, role, bio, foto). Pakai tombol Add New di halaman list.</p></td></tr>';
    echo '<tr><th colspan="2"><h2 style="margin:18px 0 0">Values section</h2></th></tr>';
    wdc_site_field('values_kicker', 'Values kicker');
    wdc_site_field('values_title', 'Values title', 'textarea');
    wdc_site_field('value_1_title', 'Value 1 title');
    wdc_site_field('value_1_text', 'Value 1 text', 'textarea');
    wdc_site_field('value_2_title', 'Value 2 title');
    wdc_site_field('value_2_text', 'Value 2 text', 'textarea');
    wdc_site_field('value_3_title', 'Value 3 title');
    wdc_site_field('value_3_text', 'Value 3 text', 'textarea');
    wdc_site_field('value_4_title', 'Value 4 title');
    wdc_site_field('value_4_text', 'Value 4 text', 'textarea');
    echo '</tbody></table>';
    submit_button('Save About Page');
    echo '</form>';
    echo '<p><a class="button" href="' . esc_url(home_url('/about/')) . '" target="_blank" rel="noopener">Preview About</a></p>';
    echo '</div>';
}


function wdc_render_cta_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $keys = [
        'courses_page_cta_kicker', 'courses_page_cta_title', 'courses_page_cta_text', 'courses_page_cta_label', 'courses_page_cta_url',
        'courses_hero_cta_label', 'courses_hero_cta_url',
        'equip_page_cta_kicker', 'equip_page_cta_title', 'equip_page_cta_text', 'equip_page_cta_label', 'equip_page_cta_url',
        'equip_hero_cta_label', 'equip_hero_cta_url',
        'course_single_cta_kicker', 'course_single_cta_title', 'course_single_cta_text',
        'equip_single_cta_kicker', 'equip_single_cta_title', 'equip_single_cta_text',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — Courses & Equipment CTA</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Edit copy + link tombol CTA untuk halaman Courses / Equipment (hero tanya + closing card). Single course/equipment hanya copy; tombol single tetap pakai CTA label per item + member login flow.</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<h2>Courses page</h2><table class="form-table" role="presentation"><tbody>';
    wdc_site_field('courses_hero_cta_label', 'Hero button label');
    wdc_site_field('courses_hero_cta_url', 'Hero button URL', 'text', 'Default: /contact/');
    wdc_site_field('courses_page_cta_kicker', 'Closing CTA kicker');
    wdc_site_field('courses_page_cta_title', 'Closing CTA title', 'textarea');
    wdc_site_field('courses_page_cta_text', 'Closing CTA text', 'textarea');
    wdc_site_field('courses_page_cta_label', 'Closing CTA button label');
    wdc_site_field('courses_page_cta_url', 'Closing CTA button URL', 'text', 'Default: /contact/');
    echo '</tbody></table>';

    echo '<h2>Equipment page</h2><table class="form-table" role="presentation"><tbody>';
    wdc_site_field('equip_hero_cta_label', 'Hero button label');
    wdc_site_field('equip_hero_cta_url', 'Hero button URL', 'text', 'Default: /contact/');
    wdc_site_field('equip_page_cta_kicker', 'Closing CTA kicker');
    wdc_site_field('equip_page_cta_title', 'Closing CTA title', 'textarea');
    wdc_site_field('equip_page_cta_text', 'Closing CTA text', 'textarea');
    wdc_site_field('equip_page_cta_label', 'Closing CTA button label');
    wdc_site_field('equip_page_cta_url', 'Closing CTA button URL', 'text', 'Kosongkan = pakai member action URL (login/dashboard). Isi /contact/ kalau mau ke contact.');
    echo '</tbody></table>';

    echo '<h2>Single course / equipment closing copy</h2><table class="form-table" role="presentation"><tbody>';
    wdc_site_field('course_single_cta_kicker', 'Course single kicker');
    wdc_site_field('course_single_cta_title', 'Course single title', 'textarea');
    wdc_site_field('course_single_cta_text', 'Course single text', 'textarea');
    wdc_site_field('equip_single_cta_kicker', 'Equipment single kicker');
    wdc_site_field('equip_single_cta_title', 'Equipment single title', 'textarea');
    wdc_site_field('equip_single_cta_text', 'Equipment single text', 'textarea');
    echo '</tbody></table>';
    submit_button('Save Courses & Equipment CTA');
    echo '</form>';
    echo '<p>';
    echo '<a class="button" href="' . esc_url(home_url('/courses/')) . '" target="_blank" rel="noopener">Preview Courses</a> ';
    echo '<a class="button" href="' . esc_url(home_url('/equipment/')) . '" target="_blank" rel="noopener">Preview Equipment</a>';
    echo '</p></div>';
}

function wdc_render_contact_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $keys = [
        'contact_kicker', 'contact_title', 'contact_text',
        'contact_form_kicker', 'contact_form_title',
        'contact_success', 'contact_map_url', 'contact_hours_note',
    ];
    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $saved = wdc_site_save_posted_keys($keys);
    }
    echo '<div class="wrap"><h1>WDC Site — Contact Page</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    echo '<p>Copy halaman Contact. Email/phone/alamat ambil dari Contact & Footer.</p>';
    echo '<form method="post">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    wdc_site_field('contact_kicker', 'Hero kicker');
    wdc_site_field('contact_title', 'Hero title', 'textarea');
    wdc_site_field('contact_text', 'Hero text', 'textarea');
    wdc_site_field('contact_form_kicker', 'Form section kicker');
    wdc_site_field('contact_form_title', 'Form section title');
    wdc_site_field('contact_success', 'Success message', 'textarea');
    wdc_site_field('contact_hours_note', 'Business hours note', 'textarea', 'Tampil di kartu jam operasional Contact');
    wdc_site_field('contact_map_url', 'Google Maps URL', 'url');
    echo '</tbody></table>';
    submit_button('Save Contact Page');
    echo '</form>';
    echo '<p><a class="button" href="' . esc_url(home_url('/contact/')) . '" target="_blank" rel="noopener">Preview Contact</a></p>';
    echo '</div>';
}

/**
 * Normalize one partner source token.
 * Supports:
 * - theme asset filename: naui.webp
 * - media attachment: id:123
 * - full URL: https://...
 */
function wdc_partner_normalize_source($source) {
    $source = trim((string) $source);
    if ($source === '') {
        return '';
    }
    if (preg_match('#^id:(\d+)$#i', $source, $m)) {
        return 'id:' . absint($m[1]);
    }
    if (preg_match('#^https?://#i', $source) || strpos($source, '//') === 0) {
        return esc_url_raw($source);
    }
    // legacy theme asset filename only
    $file = basename(str_replace(['\\', '..'], '', $source));
    return $file;
}

/**
 * Public image URL for one partner item.
 */
function wdc_partner_image_url($partner) {
    $source = '';
    if (is_array($partner)) {
        $source = (string) ($partner['source'] ?? $partner['file'] ?? '');
    } else {
        $source = (string) $partner;
    }
    $source = wdc_partner_normalize_source($source);
    if ($source === '') {
        return '';
    }
    if (preg_match('#^id:(\d+)$#i', $source, $m)) {
        $url = wp_get_attachment_image_url(absint($m[1]), 'full');
        return $url ? $url : '';
    }
    if (preg_match('#^https?://#i', $source) || strpos($source, '//') === 0) {
        return $source;
    }
    return get_template_directory_uri() . '/assets/partners/' . ltrim($source, '/');
}

/**
 * Preview URL for admin partner builder.
 */
function wdc_partner_preview_url($source) {
    return wdc_partner_image_url(['source' => $source]);
}

/**
 * Serialize partner rows to storage string.
 * Line format: Name|source
 * Row break: ---
 */
function wdc_partners_serialize_rows($rows) {
    $out = [];
    $first = true;
    foreach ($rows as $row) {
        if (!$first) {
            $out[] = '---';
        }
        $first = false;
        foreach ($row as $item) {
            $name = trim((string) ($item['name'] ?? ''));
            $source = wdc_partner_normalize_source($item['source'] ?? $item['file'] ?? '');
            if ($name === '' || $source === '') {
                continue;
            }
            $out[] = $name . '|' . $source;
        }
    }
    return implode("\n", $out);
}

function wdc_render_partners_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $saved = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wdc_site_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_site_nonce'])), 'wdc_site_save')) {
        $current = get_option('wdc_site_settings', []);
        if (!is_array($current)) {
            $current = [];
        }
        $current['trust_text'] = sanitize_textarea_field(wp_unslash($_POST['trust_text'] ?? ''));
        $current['trust_label'] = sanitize_text_field(wp_unslash($_POST['trust_label'] ?? ''));

        // Builder payload preferred; fallback to raw textarea.
        $rows_in = [];
        if (!empty($_POST['wdc_partner_rows']) && is_array($_POST['wdc_partner_rows'])) {
            foreach ($_POST['wdc_partner_rows'] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $items = [];
                foreach ($row as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $name = sanitize_text_field(wp_unslash($item['name'] ?? ''));
                    $source = wdc_partner_normalize_source(wp_unslash($item['source'] ?? ''));
                    if ($name === '' || $source === '') {
                        continue;
                    }
                    $items[] = ['name' => $name, 'source' => $source];
                }
                if ($items) {
                    $rows_in[] = $items;
                }
            }
            $current['partners'] = wdc_partners_serialize_rows($rows_in);
        } else {
            $current['partners'] = sanitize_textarea_field(wp_unslash($_POST['partners'] ?? ''));
        }

        update_option('wdc_site_settings', $current, false);
        $saved = true;
    }

    $rows = wdc_get_partner_rows();
    if (!$rows) {
        $rows = [[['name' => '', 'source' => '', 'file' => '']]];
    }

    echo '<div class="wrap wdc-partners-admin"><h1>WDC Site — Partners / Trust</h1>';
    if ($saved) {
        echo '<div class="notice notice-success is-dismissible"><p>Saved. Hard refresh homepage (Ctrl+F5) kalau logo belum berubah.</p></div>';
    }
    echo '<p>Atur teks trust bar + logo partner. <strong>Pilih logo dari Media Library</strong> — tidak perlu ketik nama file.</p>';
    echo '<form method="post" id="wdc-partners-form">';
    wp_nonce_field('wdc_site_save', 'wdc_site_nonce');
    echo '<table class="form-table" role="presentation"><tbody>';
    wdc_site_field('trust_text', 'Trust bar text', 'textarea');
    wdc_site_field('trust_label', 'Trust label');
    echo '</tbody></table>';

    echo '<h2 style="margin-top:22px">Partner logos</h2>';
    echo '<p class="description" style="margin-top:0">Tambah logo per baris. Tombol “Baris baru” = pindah ke baris logo berikutnya di homepage.</p>';
    echo '<div id="wdc-partner-builder" class="wdc-partner-builder">';

    foreach ($rows as $ri => $row) {
        echo '<div class="wdc-partner-row" data-row="' . esc_attr((string) $ri) . '">';
        echo '<div class="wdc-partner-row-head"><strong>Baris ' . esc_html((string) ($ri + 1)) . '</strong>';
        echo '<button type="button" class="button-link-delete wdc-partner-remove-row">Hapus baris</button></div>';
        echo '<div class="wdc-partner-items">';
        foreach ($row as $ii => $item) {
            $name = (string) ($item['name'] ?? '');
            $source = (string) ($item['source'] ?? $item['file'] ?? '');
            $preview = wdc_partner_preview_url($source);
            echo '<div class="wdc-partner-item">';
            echo '<div class="wdc-partner-preview">' . ($preview ? '<img src="' . esc_url($preview) . '" alt="">' : '<span>No logo</span>') . '</div>';
            echo '<div class="wdc-partner-fields">';
            echo '<label>Nama partner<br><input type="text" class="regular-text wdc-partner-name" name="wdc_partner_rows[' . esc_attr((string) $ri) . '][' . esc_attr((string) $ii) . '][name]" value="' . esc_attr($name) . '" placeholder="NAUI"></label>';
            echo '<input type="hidden" class="wdc-partner-source" name="wdc_partner_rows[' . esc_attr((string) $ri) . '][' . esc_attr((string) $ii) . '][source]" value="' . esc_attr($source) . '">';
            echo '<div class="wdc-partner-actions">';
            echo '<button type="button" class="button wdc-partner-pick">Pilih dari Media</button> ';
            echo '<button type="button" class="button-link-delete wdc-partner-remove-item">Hapus</button>';
            echo '</div></div></div>';
        }
        echo '</div>';
        echo '<p><button type="button" class="button wdc-partner-add-item">+ Tambah logo di baris ini</button></p>';
        echo '</div>';
    }

    echo '</div>';
    echo '<p style="margin-top:12px">';
    echo '<button type="button" class="button button-secondary" id="wdc-partner-add-row">+ Baris logo baru</button> ';
    submit_button('Save Partners / Trust', 'primary', 'submit', false);
    echo '</p>';

    // Keep raw textarea hidden for advanced/debug + JS fallback sync.
    echo '<details style="margin-top:18px;max-width:820px"><summary>Advanced: raw format (opsional)</summary>';
    echo '<p class="description">Boleh pakai Media picker di atas. Format lama tetap didukung: <code>Nama|file.webp</code>, <code>Nama|id:123</code>, atau <code>Nama|https://...</code>. Baris <code>---</code> = baris baru.</p>';
    echo '<textarea class="large-text code" rows="8" id="partners" name="partners">' . esc_textarea((string) wdc_site_get('partners', '')) . '</textarea>';
    echo '</details>';
    echo '</form>';

    // theme assets list still helpful
    $files = [];
    $dir = get_template_directory() . '/assets/partners';
    if (is_dir($dir)) {
        foreach (scandir($dir) as $f) {
            if ($f === '.' || $f === '..') {
                continue;
            }
            if (preg_match('/\.(webp|png|jpg|jpeg|svg)$/i', $f)) {
                $files[] = $f;
            }
        }
    }
    if ($files) {
        echo '<p class="description" style="margin-top:16px;max-width:820px"><strong>Fallback theme assets:</strong> ' . esc_html(implode(', ', $files)) . ' (boleh tetap dipakai lewat raw format).</p>';
    }

    ?>
    <style>
      .wdc-partners-admin .wdc-partner-builder{display:grid;gap:16px;max-width:920px;margin-top:10px}
      .wdc-partners-admin .wdc-partner-row{background:#fff;border:1px solid #dcdcde;border-radius:12px;padding:14px 16px}
      .wdc-partners-admin .wdc-partner-row-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
      .wdc-partners-admin .wdc-partner-items{display:grid;gap:10px}
      .wdc-partners-admin .wdc-partner-item{display:grid;grid-template-columns:88px 1fr;gap:12px;align-items:center;padding:10px;border:1px solid #e2e4e7;border-radius:10px;background:#f8fafc}
      .wdc-partners-admin .wdc-partner-preview{width:88px;height:64px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px dashed #c3c4c7;border-radius:8px;overflow:hidden}
      .wdc-partners-admin .wdc-partner-preview img{max-width:100%;max-height:100%;object-fit:contain;display:block}
      .wdc-partners-admin .wdc-partner-preview span{font-size:11px;color:#646970}
      .wdc-partners-admin .wdc-partner-fields label{display:block;font-size:12px;font-weight:600;margin:0 0 6px}
      .wdc-partners-admin .wdc-partner-actions{display:flex;gap:10px;align-items:center;margin-top:8px;flex-wrap:wrap}
      @media (max-width:782px){
        .wdc-partners-admin .wdc-partner-item{grid-template-columns:1fr}
        .wdc-partners-admin .wdc-partner-preview{width:100%;height:80px}
      }
    </style>
    <script>
    (function($){
      if (typeof wp === 'undefined' || !wp.media) { return; }
      var $builder = $('#wdc-partner-builder');
      if (!$builder.length) return;

      function reindex(){
        $builder.find('.wdc-partner-row').each(function(ri){
          var $row = $(this);
          $row.attr('data-row', ri);
          $row.find('.wdc-partner-row-head strong').text('Baris ' + (ri+1));
          $row.find('.wdc-partner-item').each(function(ii){
            var $item = $(this);
            $item.find('.wdc-partner-name').attr('name', 'wdc_partner_rows['+ri+']['+ii+'][name]');
            $item.find('.wdc-partner-source').attr('name', 'wdc_partner_rows['+ri+']['+ii+'][source]');
          });
        });
        syncRaw();
      }

      function itemHtml(ri, ii){
        return ''+
          '<div class="wdc-partner-item">'+
            '<div class="wdc-partner-preview"><span>No logo</span></div>'+
            '<div class="wdc-partner-fields">'+
              '<label>Nama partner<br><input type="text" class="regular-text wdc-partner-name" name="wdc_partner_rows['+ri+']['+ii+'][name]" value="" placeholder="NAUI"></label>'+
              '<input type="hidden" class="wdc-partner-source" name="wdc_partner_rows['+ri+']['+ii+'][source]" value="">'+
              '<div class="wdc-partner-actions">'+
                '<button type="button" class="button wdc-partner-pick">Pilih dari Media</button> '+
                '<button type="button" class="button-link-delete wdc-partner-remove-item">Hapus</button>'+
              '</div>'+
            '</div>'+
          '</div>';
      }

      function rowHtml(ri){
        return ''+
          '<div class="wdc-partner-row" data-row="'+ri+'">'+
            '<div class="wdc-partner-row-head"><strong>Baris '+(ri+1)+'</strong>'+
            '<button type="button" class="button-link-delete wdc-partner-remove-row">Hapus baris</button></div>'+
            '<div class="wdc-partner-items">'+itemHtml(ri,0)+'</div>'+
            '<p><button type="button" class="button wdc-partner-add-item">+ Tambah logo di baris ini</button></p>'+
          '</div>';
      }

      function syncRaw(){
        var lines = [];
        $builder.find('.wdc-partner-row').each(function(ri){
          if (ri > 0) lines.push('---');
          $(this).find('.wdc-partner-item').each(function(){
            var name = ($(this).find('.wdc-partner-name').val() || '').trim();
            var source = ($(this).find('.wdc-partner-source').val() || '').trim();
            if (name && source) lines.push(name + '|' + source);
          });
        });
        $('#partners').val(lines.join('\n'));
      }

      $builder.on('click', '.wdc-partner-pick', function(e){
        e.preventDefault();
        var $item = $(this).closest('.wdc-partner-item');
        var frame = wp.media({
          title: 'Pilih logo partner',
          button: { text: 'Pakai logo ini' },
          multiple: false,
          library: { type: 'image' }
        });
        frame.on('select', function(){
          var att = frame.state().get('selection').first().toJSON();
          var url = (att.sizes && att.sizes.medium && att.sizes.medium.url) ? att.sizes.medium.url : att.url;
          $item.find('.wdc-partner-source').val('id:' + att.id);
          $item.find('.wdc-partner-preview').html('<img src="'+url+'" alt="">');
          if (!$item.find('.wdc-partner-name').val()) {
            var n = (att.title || att.filename || 'Partner').replace(/\.[^.]+$/, '');
            $item.find('.wdc-partner-name').val(n);
          }
          syncRaw();
        });
        frame.open();
      });

      $builder.on('click', '.wdc-partner-add-item', function(e){
        e.preventDefault();
        var $row = $(this).closest('.wdc-partner-row');
        var ri = $builder.find('.wdc-partner-row').index($row);
        var ii = $row.find('.wdc-partner-item').length;
        $row.find('.wdc-partner-items').append(itemHtml(ri, ii));
        reindex();
      });

      $builder.on('click', '.wdc-partner-remove-item', function(e){
        e.preventDefault();
        var $row = $(this).closest('.wdc-partner-row');
        $(this).closest('.wdc-partner-item').remove();
        if (!$row.find('.wdc-partner-item').length) {
          $row.find('.wdc-partner-items').append(itemHtml(0,0));
        }
        reindex();
      });

      $builder.on('click', '.wdc-partner-remove-row', function(e){
        e.preventDefault();
        if ($builder.find('.wdc-partner-row').length <= 1) {
          // clear only
          var $row = $(this).closest('.wdc-partner-row');
          $row.find('.wdc-partner-items').html(itemHtml(0,0));
          reindex();
          return;
        }
        $(this).closest('.wdc-partner-row').remove();
        reindex();
      });

      $('#wdc-partner-add-row').on('click', function(e){
        e.preventDefault();
        var ri = $builder.find('.wdc-partner-row').length;
        $builder.append(rowHtml(ri));
        reindex();
      });

      $builder.on('input change', '.wdc-partner-name', syncRaw);
      $('#wdc-partners-form').on('submit', function(){ reindex(); });
      reindex();
    })(jQuery);
    </script>
    <?php
    echo '</div>';
}

/**
 * Parse partner list into rows of items.
 * @return array<int, array<int, array{name:string,source:string,file:string}>>
 */
function wdc_get_partner_rows() {
    $raw = (string) wdc_site_get('partners', '');
    $rows = [[]];
    $ri = 0;
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        if ($line === '---') {
            if (!empty($rows[$ri])) {
                $ri++;
                $rows[$ri] = [];
            }
            continue;
        }
        $parts = array_map('trim', explode('|', $line, 2));
        $name = $parts[0] ?? '';
        $source = wdc_partner_normalize_source($parts[1] ?? '');
        if ($name === '' || $source === '') {
            continue;
        }
        $rows[$ri][] = [
            'name' => $name,
            'source' => $source,
            // keep file key for older callers
            'file' => $source,
        ];
    }
    // drop empty trailing
    $rows = array_values(array_filter($rows, function ($r) { return !empty($r); }));
    if (!$rows) {
        $rows = [[
            ['name' => 'NAUI', 'source' => 'naui.webp', 'file' => 'naui.webp'],
            ['name' => 'TDI', 'source' => 'tdi.webp', 'file' => 'tdi.webp'],
            ['name' => 'DAN', 'source' => 'dan.webp', 'file' => 'dan.webp'],
        ], [
            ['name' => 'Sherwood Scuba', 'source' => 'sherwood.webp', 'file' => 'sherwood.webp'],
            ['name' => 'Zeagle', 'source' => 'zeagle.webp', 'file' => 'zeagle.webp'],
            ['name' => 'Waterproof', 'source' => 'waterproof.webp', 'file' => 'waterproof.webp'],
            ['name' => 'Shearwater Research', 'source' => 'shearwater.webp', 'file' => 'shearwater.webp'],
            ['name' => 'BARE', 'source' => 'bare.webp', 'file' => 'bare.webp'],
        ]];
    }
    return $rows;
}

function wdc_contact_inquiry_recipient() {
    if (function_exists('wdc_request_notify_recipients')) {
        $list = wdc_request_notify_recipients();
        if ($list) {
            return $list;
        }
    }
    $email = function_exists('wdc_site_get') ? sanitize_email((string) wdc_site_get('email')) : '';
    if ($email && is_email($email)) {
        return $email;
    }
    return get_option('admin_email') ?: 'info@whaledivecentre.com';
}


function wdc_register_crew_cpt() {
    if (post_type_exists('wdc_crew')) {
        return;
    }
    register_post_type('wdc_crew', [
        'labels' => [
            'name' => 'Crew',
            'singular_name' => 'Crew Profile',
            'add_new' => 'Add New',
            'add_new_item' => 'Add Crew Profile',
            'edit_item' => 'Edit Crew Profile',
            // One clean sidebar label under WDC Site (not "All Crew" + "Crew Profiles").
            'all_items' => 'Crew',
            'menu_name' => 'Crew',
        ],
        'public' => false,
        'show_ui' => true,
        // CPT auto-adds list item under WDC Site. Do not also add manual submenu.
        'show_in_menu' => 'wdc-site',
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'show_in_rest' => false,
        'capability_type' => 'post',
    ]);
}
add_action('init', 'wdc_register_crew_cpt');

function wdc_crew_meta_boxes() {
    add_meta_box('wdc_crew_details', 'Crew Details', 'wdc_render_crew_meta_box', 'wdc_crew', 'normal', 'high');
}
add_action('add_meta_boxes', 'wdc_crew_meta_boxes');

function wdc_render_crew_meta_box($post) {
    wp_nonce_field('wdc_save_crew', 'wdc_crew_nonce');
    $role = get_post_meta($post->ID, '_wdc_role', true);
    $asset = get_post_meta($post->ID, '_wdc_asset', true);
    echo '<p><label><strong>Role / credentials</strong><br><input type="text" name="_wdc_role" value="' . esc_attr($role) . '" class="widefat" placeholder="NAUI Instructor"></label></p>';
    echo '<p><label><strong>Fallback image filename (optional)</strong><br><input type="text" name="_wdc_asset" value="' . esc_attr($asset) . '" class="widefat" placeholder="wdc-about-ebram-pool.jpg"></label></p>';
    echo '<p class="description">Title = nama. Editor = bio. Featured image = foto utama. Kalau featured image kosong, pakai file di <code>assets/</code> dari fallback filename.</p>';
    echo '<p class="description">Urutan tampil: Order (page attributes) naik dulu.</p>';
}

function wdc_save_crew_meta($post_id, $post) {
    if ($post->post_type !== 'wdc_crew') {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['wdc_crew_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_crew_nonce'])), 'wdc_save_crew')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    update_post_meta($post_id, '_wdc_role', sanitize_text_field(wp_unslash($_POST['_wdc_role'] ?? '')));
    $asset = sanitize_file_name(wp_unslash($_POST['_wdc_asset'] ?? ''));
    update_post_meta($post_id, '_wdc_asset', $asset);
}
add_action('save_post', 'wdc_save_crew_meta', 10, 2);

function wdc_default_crew_profiles() {
    return [
        [
            'name' => 'Ebram Harimurti',
            'role' => 'NAUI Course Director, NAUI Rep. Indonesia, TDI Instructor, DAN Instructor Trainer',
            'bio' => 'Penyelam profesional Indonesia sejak 1998 dengan pengalaman lebih dari dua dekade di diving, marine tourism, dan underwater operations. Ketua Umum IDCA serta Ketua Indonesia Divers Rescue Team (IDRT), berfokus pada profesionalisme, keselamatan, search and rescue, dan konservasi laut.',
            'asset' => 'wdc-about-ebram-pool.jpg',
            'order' => 1,
        ],
        [
            'name' => 'Mimi Amilia',
            'role' => 'NAUI Instructor, DAN Instructor, TDI Diver',
            'bio' => 'Penyelam profesional sejak 2012 yang aktif dalam penyelaman rekreasi, edukasi, konservasi laut, dan pengembangan komunitas. Ketua Umum KP3I, mendorong partisipasi, kompetensi, dan kepemimpinan perempuan dalam industri penyelaman nasional.',
            'asset' => 'wdc-about-mimi-pool.jpg',
            'order' => 2,
        ],
        [
            'name' => 'Jovan Lesmana',
            'role' => 'NAUI Instructor',
            'bio' => 'Penyelam profesional Indonesia sejak 2010 dengan pengalaman penyelaman rekreasi, eksplorasi bawah laut, dan operasional diving. Aktif mempromosikan keselamatan, etika penyelaman, dan pelestarian ekosistem laut untuk generasi penyelam berikutnya.',
            'asset' => 'wdc-about-jovan.jpg',
            'order' => 3,
        ],
    ];
}

function wdc_seed_default_crew() {
    if (get_option('wdc_crew_seeded')) {
        return;
    }
    if (!post_type_exists('wdc_crew')) {
        return;
    }
    $count = (int) wp_count_posts('wdc_crew')->publish;
    if ($count > 0) {
        update_option('wdc_crew_seeded', 1, false);
        return;
    }
    foreach (wdc_default_crew_profiles() as $row) {
        $id = wp_insert_post([
            'post_type' => 'wdc_crew',
            'post_status' => 'publish',
            'post_title' => $row['name'],
            'post_content' => $row['bio'],
            'menu_order' => (int) $row['order'],
        ], true);
        if (!is_wp_error($id)) {
            update_post_meta($id, '_wdc_role', $row['role']);
            update_post_meta($id, '_wdc_asset', $row['asset']);
        }
    }
    update_option('wdc_crew_seeded', 1, false);
}
add_action('init', 'wdc_seed_default_crew', 40);

function wdc_get_crew_profiles() {
    $posts = get_posts([
        'post_type' => 'wdc_crew',
        'post_status' => 'publish',
        'numberposts' => 20,
        'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
    ]);
    $items = [];
    foreach ($posts as $p) {
        $asset = (string) get_post_meta($p->ID, '_wdc_asset', true);
        $img = get_the_post_thumbnail_url($p->ID, 'large');
        if (!$img && $asset) {
            $img = get_template_directory_uri() . '/assets/' . ltrim($asset, '/');
        }
        $items[] = [
            'name' => $p->post_title,
            'role' => (string) get_post_meta($p->ID, '_wdc_role', true),
            'bio' => trim(wp_strip_all_tags($p->post_content)),
            'image' => $img ?: '',
            'alt' => $p->post_title,
        ];
    }
    if ($items) {
        return $items;
    }
    // fallback defaults if none published
    $fallback = [];
    foreach (wdc_default_crew_profiles() as $row) {
        $fallback[] = [
            'name' => $row['name'],
            'role' => $row['role'],
            'bio' => $row['bio'],
            'image' => get_template_directory_uri() . '/assets/' . $row['asset'],
            'alt' => $row['name'],
        ];
    }
    return $fallback;
}

function wdc_crew_columns($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['wdc_role'] = 'Role';
            $new['wdc_asset'] = 'Fallback image';
        }
    }
    return $new;
}
add_filter('manage_wdc_crew_posts_columns', 'wdc_crew_columns');

function wdc_crew_column_content($column, $post_id) {
    if ($column === 'wdc_role') {
        echo esc_html(get_post_meta($post_id, '_wdc_role', true) ?: '—');
    }
    if ($column === 'wdc_asset') {
        echo esc_html(get_post_meta($post_id, '_wdc_asset', true) ?: '—');
    }
}
add_action('manage_wdc_crew_posts_custom_column', 'wdc_crew_column_content', 10, 2);

