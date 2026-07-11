<?php
/**
 * Shared catalog helpers: image maps, featured-image backfill, home CPT cards.
 */
if (!defined('ABSPATH')) {
    exit;
}

function wdc_catalog_visible_meta_query() {
    return [
        'relation' => 'OR',
        [
            'key' => '_wdc_catalog_visible',
            'compare' => 'NOT EXISTS',
        ],
        [
            'key' => '_wdc_catalog_visible',
            'value' => '0',
            'compare' => '!=',
        ],
    ];
}

function wdc_course_asset_file($title_or_slug) {
    $key = strtolower((string) $title_or_slug);
    $map = [
        'underwater photography' => 'wdc-course-underwater-photography.webp',
        'photography' => 'wdc-course-underwater-photography.webp',
        'decompression' => 'wdc-course-decompression.webp',
        'deep diver' => 'wdc-course-deep-diver-real-v2.jpg',
        'deep-diver' => 'wdc-course-deep-diver-real-v2.jpg',
        'extended range' => 'wdc-course-divemaster-real.webp',
        'extended-range' => 'wdc-course-divemaster-real.webp',
        'trimix' => 'wdc-course-divemaster-real.webp',
        'intro to tech' => 'wdc-course-intro-tech.webp',
        'intro-to-tech' => 'wdc-course-intro-tech.webp',
        'advanced nitrox' => 'wdc-course-adv-nitrox.webp',
        'advanced-nitrox' => 'wdc-course-adv-nitrox.webp',
        'enriched air' => 'wdc-course-nitrox.webp',
        'enriched-air' => 'wdc-course-nitrox.webp',
        'nitrox' => 'wdc-course-nitrox.webp',
        'night' => 'wdc-course-discover-scuba.webp',
        'first aid' => 'wdc-course-decompression.webp',
        'cpr' => 'wdc-course-decompression.webp',
        'oxygen' => 'wdc-course-nitrox.webp',
        'full face' => 'wdc-course-intro-tech.webp',
        'full-face' => 'wdc-course-intro-tech.webp',
        'junior' => 'wdc-course-open-water-real.webp',
        'trial' => 'wdc-course-discover-scuba.webp',
        'discover' => 'wdc-course-discover-scuba.webp',
        'master scuba' => 'wdc-course-deep-diver-real-v2.jpg',
        'master-scuba' => 'wdc-course-deep-diver-real-v2.jpg',
        'instructor' => 'wdc-course-instructor-course-real.webp',
        'divemaster' => 'wdc-course-divemaster-real.webp',
        'rescue' => 'wdc-course-rescue-diver-real.webp',
        'advanced open' => 'wdc-course-advanced-open-water-real.webp',
        'advanced-open' => 'wdc-course-advanced-open-water-real.webp',
        'open water' => 'wdc-course-open-water-real.webp',
        'open-water' => 'wdc-course-open-water-real.webp',
    ];
    foreach ($map as $needle => $file) {
        if (strpos($key, $needle) !== false) {
            return $file;
        }
    }
    return 'wdc-home-hero-diving-clean3.webp';
}

function wdc_equipment_asset_file($title, $cat_slug = '') {
    $key = strtolower(trim($title . ' ' . $cat_slug));
    // Prefer PNG for sideload reliability (some .webp assets are invalid/HTML leftovers).
    $map = [
        'mask' => 'wdc-equipment-mask-real.png',
        'fin' => 'wdc-equipment-fins-real.png',
        'bcd' => 'wdc-equipment-bcd-real.png',
        'regulator' => 'wdc-equipment-regulator-real.png',
        'computer' => 'wdc-equipment-computer-real.png',
        'wetsuit' => 'wdc-equipment-wetsuit-real.png',
        'shorty' => 'wdc-equipment-wetsuit-real.png',
    ];
    foreach ($map as $needle => $file) {
        if (strpos($key, $needle) !== false) {
            // fall back if preferred missing
            if (wdc_theme_asset_path($file)) {
                return $file;
            }
            $webp = preg_replace('/\.png$/i', '.webp', $file);
            if ($webp && wdc_theme_asset_path($webp)) {
                return $webp;
            }
            return $file;
        }
    }
    return 'wdc-equipment-mask-real.png';
}

function wdc_theme_asset_path($file) {
    $file = basename(str_replace(['\\', '..'], '', (string) $file));
    if ($file === '') {
        return '';
    }
    $path = get_template_directory() . '/assets/' . $file;
    return file_exists($path) ? $path : '';
}

function wdc_theme_asset_url($file) {
    $file = basename(str_replace(['\\', '..'], '', (string) $file));
    if ($file === '') {
        return '';
    }
    return get_template_directory_uri() . '/assets/' . $file;
}

/**
 * Prefer featured image; else theme asset map.
 */
function wdc_catalog_image_url($post_id, $type = 'course') {
    $thumb = get_the_post_thumbnail_url($post_id, 'large');
    if ($thumb) {
        return $thumb;
    }
    $post = get_post($post_id);
    if (!$post) {
        return '';
    }
    if ($type === 'equipment') {
        $cats = wp_get_post_terms($post_id, 'equipment_category', ['fields' => 'slugs']);
        $cat = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : '';
        $file = wdc_equipment_asset_file($post->post_title, $cat);
    } else {
        $file = wdc_course_asset_file($post->post_name . ' ' . $post->post_title);
    }
    return wdc_theme_asset_url($file);
}

/**
 * Attach theme asset as featured image if missing.
 * @return int attachment ID or 0
 */
function wdc_ensure_featured_from_asset($post_id, $asset_file) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return 0;
    }
    if (has_post_thumbnail($post_id)) {
        return (int) get_post_thumbnail_id($post_id);
    }
    $src = wdc_theme_asset_path($asset_file);
    if ($src === '') {
        return 0;
    }

    // Reuse existing attachment by source meta if already sideloaded.
    $existing = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'numberposts' => 1,
        'meta_key' => '_wdc_source_asset',
        'meta_value' => basename($asset_file),
        'fields' => 'ids',
    ]);
    if ($existing) {
        set_post_thumbnail($post_id, (int) $existing[0]);
        return (int) $existing[0];
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = wp_tempnam(basename($src));
    if (!$tmp) {
        return 0;
    }
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
    if (!@copy($src, $tmp)) {
        @unlink($tmp);
        return 0;
    }

    $file_array = [
        'name' => basename($asset_file),
        'tmp_name' => $tmp,
    ];
    $attachment_id = media_handle_sideload($file_array, $post_id);
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        // Retry with alternate extension if present.
        $base = preg_replace('/\.(webp|png|jpg|jpeg)$/i', '', basename($asset_file));
        foreach (['png', 'webp', 'jpg'] as $ext) {
            $alt = $base . '.' . $ext;
            if ($alt === basename($asset_file)) {
                continue;
            }
            $alt_src = wdc_theme_asset_path($alt);
            if (!$alt_src) {
                continue;
            }
            $tmp2 = wp_tempnam(basename($alt_src));
            if (!$tmp2 || !@copy($alt_src, $tmp2)) {
                if ($tmp2) {
                    @unlink($tmp2);
                }
                continue;
            }
            $attachment_id = media_handle_sideload([
                'name' => basename($alt),
                'tmp_name' => $tmp2,
            ], $post_id);
            if (!is_wp_error($attachment_id)) {
                update_post_meta($attachment_id, '_wdc_source_asset', basename($alt));
                set_post_thumbnail($post_id, (int) $attachment_id);
                return (int) $attachment_id;
            }
            @unlink($tmp2);
        }
        return 0;
    }
    update_post_meta($attachment_id, '_wdc_source_asset', basename($asset_file));
    set_post_thumbnail($post_id, (int) $attachment_id);
    return (int) $attachment_id;
}

function wdc_backfill_catalog_featured_images($force = false) {
    if (!$force && get_option('wdc_catalog_thumbs_backfilled')) {
        return get_option('wdc_catalog_thumbs_stats', ['courses' => 0, 'equipment' => 0, 'skipped' => 0]);
    }

    $stats = ['courses' => 0, 'equipment' => 0, 'skipped' => 0, 'errors' => 0];

    $courses = get_posts([
        'post_type' => 'wm_course',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($courses as $id) {
        if (has_post_thumbnail($id) && !$force) {
            $stats['skipped']++;
            continue;
        }
        if ($force && has_post_thumbnail($id)) {
            // keep existing unless force and no real thumb needed; skip overwrite
            $stats['skipped']++;
            continue;
        }
        $post = get_post($id);
        $file = wdc_course_asset_file(($post->post_name ?? '') . ' ' . ($post->post_title ?? ''));
        $aid = wdc_ensure_featured_from_asset($id, $file);
        if ($aid) {
            $stats['courses']++;
        } else {
            $stats['errors']++;
        }
    }

    $equipment = get_posts([
        'post_type' => 'wm_equipment',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($equipment as $id) {
        if (has_post_thumbnail($id) && !$force) {
            $stats['skipped']++;
            continue;
        }
        if ($force && has_post_thumbnail($id)) {
            $stats['skipped']++;
            continue;
        }
        $post = get_post($id);
        $cats = wp_get_post_terms($id, 'equipment_category', ['fields' => 'slugs']);
        $cat = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : '';
        $file = wdc_equipment_asset_file($post->post_title ?? '', $cat);
        $aid = wdc_ensure_featured_from_asset($id, $file);
        if ($aid) {
            $stats['equipment']++;
        } else {
            $stats['errors']++;
        }
    }

    update_option('wdc_catalog_thumbs_backfilled', 1, false);
    update_option('wdc_catalog_thumbs_stats', $stats, false);
    return $stats;
}

// Auto-run once after theme load (admin or front) so catalog cards get thumbs.
add_action('init', function () {
    if (is_admin() && isset($_GET['wdc_backfill_thumbs']) && current_user_can('manage_options')) {
        // manual force path handled in admin page
        return;
    }
    if (!get_option('wdc_catalog_thumbs_backfilled')) {
        // defer heavy work slightly after init for front/admin
        add_action('wp_loaded', function () {
            if (!get_option('wdc_catalog_thumbs_backfilled')) {
                wdc_backfill_catalog_featured_images(false);
            }
        }, 30);
    }
}, 50);

// On save catalog item, if no featured image, auto-attach mapped asset.
add_action('save_post_wm_course', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (has_post_thumbnail($post_id)) {
        return;
    }
    $post = get_post($post_id);
    if (!$post || $post->post_status === 'auto-draft') {
        return;
    }
    $file = wdc_course_asset_file($post->post_name . ' ' . $post->post_title);
    wdc_ensure_featured_from_asset($post_id, $file);
}, 40);

add_action('save_post_wm_equipment', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (has_post_thumbnail($post_id)) {
        return;
    }
    $post = get_post($post_id);
    if (!$post || $post->post_status === 'auto-draft') {
        return;
    }
    $cats = wp_get_post_terms($post_id, 'equipment_category', ['fields' => 'slugs']);
    $cat = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : '';
    $file = wdc_equipment_asset_file($post->post_title, $cat);
    wdc_ensure_featured_from_asset($post_id, $file);
}, 40);

function wdc_get_home_courses($agency = 'NAUI', $limit = 4) {
    if (!post_type_exists('wm_course')) {
        return [];
    }
    $q = [
        'post_type' => 'wm_course',
        'post_status' => 'publish',
        'numberposts' => $limit,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'meta_query' => wdc_catalog_visible_meta_query(),
    ];
    if ($agency) {
        $q['tax_query'] = [[
            'taxonomy' => 'course_agency',
            'field' => 'name',
            'terms' => [$agency],
        ]];
    }
    $posts = get_posts($q);
    $items = [];
    foreach ($posts as $p) {
        $levels = wp_get_post_terms($p->ID, 'course_level', ['fields' => 'names']);
        $level = (!is_wp_error($levels) && !empty($levels)) ? $levels[0] : '';
        $duration = (string) get_post_meta($p->ID, '_wm_duration', true);
        $excerpt = trim(wp_strip_all_tags($p->post_excerpt ?: $p->post_content));
        if ($excerpt === '') {
            $excerpt = 'Lihat detail kursus dan ajukan lewat akun member.';
        } else {
            $excerpt = wp_trim_words($excerpt, 18, '...');
        }
        $items[] = [
            'id' => $p->ID,
            'title' => $p->post_title,
            'level' => $level,
            'duration' => $duration,
            'excerpt' => $excerpt,
            'url' => get_permalink($p) ?: home_url('/courses/' . $p->post_name . '/'),
            'image' => wdc_catalog_image_url($p->ID, 'course'),
        ];
    }
    return $items;
}

function wdc_get_home_equipment($limit = 4) {
    if (!post_type_exists('wm_equipment')) {
        return [];
    }
    // Prefer one item per category for variety.
    $categories = get_terms([
        'taxonomy' => 'equipment_category',
        'hide_empty' => true,
        'number' => $limit,
    ]);
    $items = [];
    if (!is_wp_error($categories) && $categories) {
        foreach ($categories as $cat) {
            if (count($items) >= $limit) {
                break;
            }
            $posts = get_posts([
                'post_type' => 'wm_equipment',
                'post_status' => 'publish',
                'numberposts' => 1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'meta_query' => wdc_catalog_visible_meta_query(),
                'tax_query' => [[
                    'taxonomy' => 'equipment_category',
                    'field' => 'term_id',
                    'terms' => [$cat->term_id],
                ]],
            ]);
            if (!$posts) {
                continue;
            }
            $p = $posts[0];
            $excerpt = trim(wp_strip_all_tags($p->post_excerpt ?: $p->post_content));
            if ($excerpt === '') {
                $excerpt = 'Crew-selected ' . strtolower($cat->name) . ' for training, comfort, and safer dive habits.';
            } else {
                $excerpt = wp_trim_words($excerpt, 14, '...');
            }
            $items[] = [
                'id' => $p->ID,
                'title' => $p->post_title,
                'category' => $cat->name,
                'excerpt' => $excerpt,
                'url' => get_permalink($p) ?: home_url('/equipment/' . $p->post_name . '/'),
                'image' => wdc_catalog_image_url($p->ID, 'equipment'),
            ];
        }
    }
    if (count($items) < $limit) {
        $more = get_posts([
            'post_type' => 'wm_equipment',
            'post_status' => 'publish',
            'numberposts' => $limit,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'meta_query' => wdc_catalog_visible_meta_query(),
            'post__not_in' => array_column($items, 'id'),
        ]);
        foreach ($more as $p) {
            if (count($items) >= $limit) {
                break;
            }
            $cats = wp_get_post_terms($p->ID, 'equipment_category', ['fields' => 'names']);
            $cat = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : 'Gear';
            $excerpt = trim(wp_strip_all_tags($p->post_excerpt ?: $p->post_content));
            if ($excerpt === '') {
                $excerpt = 'Crew-selected dive gear for training, comfort, and safer dive habits.';
            } else {
                $excerpt = wp_trim_words($excerpt, 14, '...');
            }
            $items[] = [
                'id' => $p->ID,
                'title' => $p->post_title,
                'category' => $cat,
                'excerpt' => $excerpt,
                'url' => get_permalink($p) ?: home_url('/equipment/' . $p->post_name . '/'),
                'image' => wdc_catalog_image_url($p->ID, 'equipment'),
            ];
        }
    }
    return $items;
}

function wdc_site_admin_menu_catalog_tools() {
    add_submenu_page(
        'wdc-site',
        'Catalog Images',
        'Catalog Images',
        'manage_options',
        'wdc-site-catalog-images',
        'wdc_render_catalog_images_page'
    );
}
add_action('admin_menu', 'wdc_site_admin_menu_catalog_tools', 20);

function wdc_render_catalog_images_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $result = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wdc_backfill_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_backfill_nonce'])), 'wdc_backfill_thumbs')) {
        delete_option('wdc_catalog_thumbs_backfilled');
        $result = wdc_backfill_catalog_featured_images(false);
    }
    $stats = get_option('wdc_catalog_thumbs_stats', []);
    $courses = get_posts(['post_type' => 'wm_course', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids']);
    $equip = get_posts(['post_type' => 'wm_equipment', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids']);
    $c_no = 0;
    $e_no = 0;
    foreach ($courses as $id) {
        if (!has_post_thumbnail($id)) {
            $c_no++;
        }
    }
    foreach ($equip as $id) {
        if (!has_post_thumbnail($id)) {
            $e_no++;
        }
    }
    echo '<div class="wrap"><h1>WDC Site — Catalog Images</h1>';
    if ($result) {
        echo '<div class="notice notice-success is-dismissible"><p>Backfill done. Courses set: ' . (int) ($result['courses'] ?? 0) . ', Equipment set: ' . (int) ($result['equipment'] ?? 0) . ', Skipped: ' . (int) ($result['skipped'] ?? 0) . ', Errors: ' . (int) ($result['errors'] ?? 0) . '.</p></div>';
    }
    echo '<p>Set featured image otomatis dari file tema <code>assets/</code> untuk course/equipment yang belum punya foto.</p>';
    echo '<p><strong>Status sekarang:</strong> Courses missing thumb: ' . (int) $c_no . ' / ' . count($courses) . '. Equipment missing thumb: ' . (int) $e_no . ' / ' . count($equip) . '.</p>';
    if (is_array($stats) && $stats) {
        echo '<p class="description">Last run: courses=' . (int) ($stats['courses'] ?? 0) . ', equipment=' . (int) ($stats['equipment'] ?? 0) . ', skipped=' . (int) ($stats['skipped'] ?? 0) . '.</p>';
    }
    echo '<form method="post">';
    wp_nonce_field('wdc_backfill_thumbs', 'wdc_backfill_nonce');
    submit_button('Backfill missing featured images');
    echo '</form></div>';
}
