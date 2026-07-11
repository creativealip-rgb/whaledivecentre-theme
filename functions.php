<?php
/**
 * Contenly Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load plugin integration for reviews
require_once get_template_directory() . '/plugin-integration.php';
require_once get_template_directory() . '/membership-plans.php';
require_once get_template_directory() . '/manual-payment-handler.php';
require_once get_template_directory() . '/giveaway-handler.php';
require_once get_template_directory() . '/admin-orders.php';

// Load theme helper functions/tags
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/wdc-site-content.php';
require_once get_template_directory() . '/inc/wdc-catalog-helpers.php';

/**
 * Enqueue theme styles and scripts
 */
function contenly_enqueue_scripts() {
    // Theme stylesheet
    wp_enqueue_style('contenly-style', get_template_directory_uri() . '/style.css', [], '2.3.52');
    wp_add_inline_style('contenly-style', '.wd-header .gt-lang-switcher{margin-right:10px!important}.wd-header .wd-nav-member{margin-left:8px!important}');
    
    // Google Fonts
    wp_enqueue_style('contenly-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', [], null);
    
    // jQuery
    wp_enqueue_script('jquery');
    
    // Member AJAX - inline script with localized data.
    $member_ajax_config = [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wdc_member_nonce'),
        'i18n' => [
            'requestError' => 'Request failed',
            'processing' => 'Processing...'
        ]
    ];

    wp_add_inline_script('jquery', 'var wdcMemberAjax = ' . wp_json_encode($member_ajax_config) . ';', 'before');
    
    // Main theme JavaScript
    wp_enqueue_script('contenly-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], '1.0.7', true);
    
    // Media uploader for story pages (wp_editor + image upload)
    if (is_page_template('page-cerita-kamu.php') && is_user_logged_in()) {
        wp_enqueue_media();
    }
}
add_action('wp_enqueue_scripts', 'contenly_enqueue_scripts');

/**
 * Normalize menu item objects to avoid walker warnings on malformed items.
 */
function contenly_normalize_nav_menu_item($menu_item) {
    if (is_object($menu_item) && !isset($menu_item->current)) {
        $menu_item->current = false;
    }

    return $menu_item;
}
add_filter('wp_setup_nav_menu_item', 'contenly_normalize_nav_menu_item', 5);

function contenly_normalize_nav_menu_items($items) {
    if (!is_array($items)) {
        return $items;
    }

    foreach ($items as $item) {
        if (is_object($item) && !isset($item->current)) {
            $item->current = false;
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'contenly_normalize_nav_menu_items', 5);

/**
 * Register course taxonomies used by the local course templates.
 */
function wdc_register_course_taxonomies() {
    if (!post_type_exists('wm_course')) {
        register_post_type('wm_course', [
            'label' => 'Courses',
            'labels' => [
                'name' => 'Courses',
                'singular_name' => 'Course',
                'add_new_item' => 'Add New Course',
                'edit_item' => 'Edit Course',
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'course'],
            'menu_icon' => 'dashicons-welcome-learn-more',
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
        ]);
    }

    if (!post_type_exists('wm_equipment')) {
        register_post_type('wm_equipment', [
            'label' => 'Equipment',
            'labels' => [
                'name' => 'Equipment',
                'singular_name' => 'Equipment Item',
                'add_new_item' => 'Add New Equipment',
                'edit_item' => 'Edit Equipment',
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'equipment-item'],
            'menu_icon' => 'dashicons-products',
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
        ]);
    }

    if (!post_type_exists('wm_equipment')) {
        register_post_type('wm_equipment', [
            'label' => 'Equipment',
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'equipment'],
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-products',
        ]);
    }

    if (!taxonomy_exists('equipment_category')) {
        register_taxonomy('equipment_category', ['wm_equipment'], [
            'label' => 'Equipment Categories',
            'public' => true,
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'equipment-category'],
            'show_in_rest' => true,
        ]);
    }

    if (!taxonomy_exists('course_level')) {
        register_taxonomy('course_level', ['wm_course'], [
            'label' => 'Course Levels',
            'public' => true,
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'course-level'],
            'show_in_rest' => true,
        ]);
    }

    if (!taxonomy_exists('course_agency')) {
        register_taxonomy('course_agency', ['wm_course'], [
            'label' => 'Course Agencies',
            'public' => true,
            'hierarchical' => false,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'course-agency'],
            'show_in_rest' => true,
        ]);
    }

    if (!taxonomy_exists('equipment_category')) {
        register_taxonomy('equipment_category', ['wm_equipment'], [
            'label' => 'Equipment Categories',
            'public' => true,
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'equipment-category'],
            'show_in_rest' => true,
        ]);
    }

    if (!taxonomy_exists('equipment_brand')) {
        register_taxonomy('equipment_brand', ['wm_equipment'], [
            'label' => 'Equipment Brands',
            'public' => true,
            'hierarchical' => false,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'equipment-brand'],
            'show_in_rest' => true,
        ]);
    }
}
add_action('init', 'wdc_register_course_taxonomies', 20);

/**
 * Register Informasi & Cerita Kamu CPTs
 */
function wdc_register_member_cpts() {
    // Informasi CPT
    if (!post_type_exists('wdc_info')) {
        register_post_type('wdc_info', [
            'label' => 'Informasi',
            'labels' => [
                'name' => 'Informasi',
                'singular_name' => 'Info',
                'add_new_item' => 'Tambah Info Baru',
                'edit_item' => 'Edit Info',
                'all_items' => 'Semua Informasi',
                'view_item' => 'Lihat Info',
                'search_items' => 'Cari Informasi',
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'informasi'],
            'menu_icon' => 'dashicons-megaphone',
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
        ]);
    }

    if (!taxonomy_exists('info_type')) {
        register_taxonomy('info_type', ['wdc_info'], [
            'label' => 'Tipe Informasi',
            'labels' => [
                'name' => 'Tipe Informasi',
                'singular_name' => 'Tipe',
                'add_new_item' => 'Tambah Tipe Baru',
            ],
            'public' => true,
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'info-type'],
            'show_in_rest' => true,
        ]);
        // Seed default terms
        if (!get_option('wdc_info_types_seeded')) {
            $defaults = ['1st Giveaway', 'Event', 'Trip', 'Update NAUI/WDC/TDI/DAN'];
            foreach ($defaults as $d) {
                if (!term_exists($d, 'info_type')) {
                    wp_insert_term($d, 'info_type');
                }
            }
            update_option('wdc_info_types_seeded', 1);
        }
    }

    // Cerita Kamu CPT
    if (!post_type_exists('wdc_story')) {
        register_post_type('wdc_story', [
            'label' => 'Cerita Kamu',
            'labels' => [
                'name' => 'Cerita Kamu',
                'singular_name' => 'Cerita',
                'add_new_item' => 'Tambah Cerita',
                'edit_item' => 'Edit Cerita',
                'all_items' => 'Semua Cerita',
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'cerita'],
            'menu_icon' => 'dashicons-book-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_rest' => true,
        ]);
    }

    if (!taxonomy_exists('story_type')) {
        register_taxonomy('story_type', ['wdc_story'], [
            'label' => 'Jenis Cerita',
            'labels' => [
                'name' => 'Jenis Cerita',
                'singular_name' => 'Jenis',
            ],
            'public' => true,
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'story-type'],
            'show_in_rest' => true,
        ]);
        if (!get_option('wdc_story_types_seeded')) {
            foreach (['Cerita Kursus', 'Cerita Trip'] as $d) {
                if (!term_exists($d, 'story_type')) {
                    wp_insert_term($d, 'story_type');
                }
            }
            update_option('wdc_story_types_seeded', 1);
        }
    }
}
add_action('init', 'wdc_register_member_cpts', 21);

/**
 * Rewrite rules for member pages
 */
add_action('init', function() {
    add_rewrite_rule('^informasi/?$', 'index.php?pagename=informasi', 'top');
    add_rewrite_rule('^cerita-kamu/?$', 'index.php?pagename=cerita-kamu', 'top');
});

/* WDC legacy member-dashboard redirect 2026-07-11 */
add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }
    if (is_page('member-dashboard')) {
        $target = function_exists('contenly_localized_url')
            ? contenly_localized_url('/dashboard/')
            : home_url('/dashboard/');
        wp_safe_redirect($target, 301);
        exit;
    }
}, 1);


/**
 * Handle story submission from frontend (with image uploads + rich content)
 */
add_action('init', function() {
    if ((($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') && isset($_POST['wdc_story_nonce']) && is_user_logged_in()) {
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_story_nonce'])), 'wdc_story_submit')) {
            return;
        }
        $title = sanitize_text_field(wp_unslash($_POST['story_title'] ?? ''));
        $content = wp_kses_post(wp_unslash($_POST['story_content'] ?? ''));
        $type = sanitize_text_field(wp_unslash($_POST['story_type'] ?? 'Cerita Kursus'));
        $user = wp_get_current_user();

        if ($title && $content) {
            $post_id = wp_insert_post([
                'post_type' => 'wdc_story',
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'pending',
                'post_author' => $user->ID,
            ]);
            if ($post_id && !is_wp_error($post_id)) {
                wp_set_object_terms($post_id, $type, 'story_type');
                update_post_meta($post_id, '_wdc_story_author_name', $user->display_name);
                update_post_meta($post_id, '_wdc_story_approved', '0');

                // Handle image uploads
                if (!empty($_FILES['story_images']['name'][0])) {
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';

                    $gallery_ids = [];
                    $files = $_FILES['story_images'];
                    for ($i = 0; $i < count($files['name']); $i++) {
                        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                        $file = [
                            'name'     => $files['name'][$i],
                            'type'     => $files['type'][$i],
                            'tmp_name' => $files['tmp_name'][$i],
                            'error'    => $files['error'][$i],
                            'size'     => $files['size'][$i],
                        ];
                        $_FILES['story_image_single'] = $file;
                        $attachment_id = media_handle_upload('story_image_single', $post_id);
                        if (!is_wp_error($attachment_id)) {
                            $gallery_ids[] = $attachment_id;
                        }
                        unset($_FILES['story_image_single']);
                    }

                    if ($gallery_ids) {
                        update_post_meta($post_id, '_wdc_story_gallery', $gallery_ids);
                        // Set first image as featured image
                        set_post_thumbnail($post_id, $gallery_ids[0]);
                    }
                }

                wp_redirect(add_query_arg(['submitted' => '1'], contenly_localized_url('/cerita-kamu/')));
                exit;
            }
        }
    }
});

/**
 * Add meta box for story approval
 */
add_action('add_meta_boxes', function() {
    add_meta_box('wdc_story_approval', 'Approval Status', function($post) {
        $approved = get_post_meta($post->ID, '_wdc_story_approved', true);
        wp_nonce_field('wdc_story_approval', 'wdc_story_approval_nonce');
        echo '<label><input type="checkbox" name="wdc_story_approved" value="1" ' . checked($approved, '1', false) . '> Approved for publication</label>';
        echo '<p class="description">Centang untuk approve cerita ini. Story akan tampil di halaman Cerita Kamu.</p>';
    }, 'wdc_story', 'side', 'high');
});

add_action('save_post_wdc_story', function($post_id) {
    if (!isset($_POST['wdc_story_approval_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_story_approval_nonce'])), 'wdc_story_approval')) {
        return;
    }
    $approved = isset($_POST['wdc_story_approved']) ? '1' : '0';
    update_post_meta($post_id, '_wdc_story_approved', $approved);
    if ($approved === '1') {
        wp_update_post(['ID' => $post_id, 'post_status' => 'publish']);
    }
});

/**
 * Theme setup
 */
function contenly_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    load_theme_textdomain('contenly', get_template_directory() . '/languages');
    register_nav_menus([
        'primary' => __('Primary Menu', 'contenly'),
        'mobile'  => __('Mobile Menu', 'contenly'),
    ]);
}
add_action('after_setup_theme', 'contenly_theme_setup');

function contenly_current_lang() {
    $request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (0 === strpos($request_path, '/en')) {
        return 'en';
    }

    // Only use Polylang if it has a language prefix in the URL
    // Without prefix, default to 'id' (matches live behavior without Polylang)
    return 'id';
}

function contenly_is_english() {
    return 'en' === contenly_current_lang();
}

function contenly_all_language_post_args($args = []) {
    if (!is_array($args)) {
        $args = [];
    }

    if (!array_key_exists('lang', $args)) {
        $args['lang'] = '';
    }

    return $args;
}

function contenly_tr($id_text, $en_text = null) {
    if (null === $en_text) {
        $en_text = $id_text;
    }

    return contenly_is_english() ? $en_text : $id_text;
}

function contenly_localized_url($path = '/', $lang = null) {
    $lang = $lang ?: contenly_current_lang();
    $path = (string) $path;
    $anchor = '';

    if (false !== strpos($path, '#')) {
        [$path, $anchor] = explode('#', $path, 2);
        $anchor = '#' . $anchor;
    }

    $normalized_path = '/' . trim($path, '/');
    if ('/' === $normalized_path) {
        $normalized_path = '/';
    }

    $alias_map = [
        '/about' => ['id' => '/about/', 'en' => '/en/about/'],
        '/contact' => ['id' => '/kontak/', 'en' => '/en/contact/'],
        '/tour-packages' => ['id' => '/paket-tour/', 'en' => '/en/tour-packages/'],
        '/blog' => ['id' => '/blog/', 'en' => '/en/journal/'],
        '/home' => ['id' => '/', 'en' => '/en/'],
    ];

    $path_key = rtrim($normalized_path, '/');
    if ('' === $path_key) {
        $path_key = '/';
    }

    if (isset($alias_map[$path_key][$lang])) {
        $target = $alias_map[$path_key][$lang];
        return home_url($target) . $anchor;
    }

    if ('/' === $normalized_path) {
        return contenly_front_page_root_url($lang) . $anchor;
    }

    if (function_exists('pll_home_url')) {
        $home = pll_home_url($lang);
        if ('/' === $normalized_path) {
            return trailingslashit($home) . $anchor;
        }
    }

    $slug = trim($normalized_path, '/');
    if ($slug && function_exists('pll_get_post')) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page) {
            $translated_id = pll_get_post($page->ID, $lang);
            if ($translated_id) {
                return get_permalink($translated_id) . $anchor;
            }
        }
    }

    if ('/' === $normalized_path) {
        return home_url('/' . ($lang === 'en' ? 'en/' : '')) . $anchor;
    }

    if ('en' === $lang) {
        return home_url('/en/' . trim($normalized_path, '/') . '/') . $anchor;
    }

    return home_url($normalized_path . '/') . $anchor;
}

function contenly_localized_tour_permalink($post, $lang = null) {
    $post = get_post($post);
    if (!$post || 'tour' !== $post->post_type) {
        return '';
    }

    $lang = $lang ?: contenly_requested_lang();
    $slug = $post->post_name;
    if ('en' === $lang) {
        return home_url('/en/tours/' . $slug . '/');
    }

    return home_url('/tours/' . $slug . '/');
}

function contenly_filter_tour_permalink($permalink, $post) {
    if (is_object($post) && isset($post->post_type) && 'tour' === $post->post_type) {
        $localized = contenly_localized_tour_permalink($post);
        if ($localized) {
            return $localized;
        }
    }

    return $permalink;
}
add_filter('post_type_link', 'contenly_filter_tour_permalink', 20, 2);

function contenly_add_en_tour_rewrite_rule() {
    add_rewrite_rule('^en/tours/([^/]+)/?$', 'index.php?post_type=tour&name=$matches[1]', 'top');
}
add_action('init', 'contenly_add_en_tour_rewrite_rule', 20);

function contenly_menu_route_key_from_path($path) {
    $normalized = '/' . trim((string) $path, '/');
    if ('/' === $normalized || '/home' === rtrim($normalized, '/')) {
        return 'home';
    }

    $normalized = trailingslashit($normalized);
    $route_map = [
        'home' => ['/', '/en/', '/home/'],
        'about' => ['/tentang/', '/about/', '/en/about/'],
        'contact' => ['/kontak/', '/contact/', '/en/contact/'],
        'tour-packages' => ['/paket-tour/', '/tour-packages/', '/en/tour-packages/'],
        'blog' => ['/blog/', '/journal/', '/en/journal/'],
        'login' => ['/login/', '/en/login/'],
        'register' => ['/register/', '/en/register/'],
        'dashboard' => ['/dashboard/', '/en/dashboard/'],
        'membership' => ['/membership/', '/en/membership/'],
        'settings' => ['/settings/', '/en/settings/'],
        'wishlist' => ['/wishlist/', '/en/wishlist/'],
        'reviews' => ['/reviews/', '/en/reviews/'],
        'notifications' => ['/notifications/', '/en/notifications/'],
        'rewards' => ['/rewards/', '/en/rewards/'],
        'my-travels' => ['/my-travels/', '/en/my-travels/'],
        'checkout-success' => ['/checkout-success/', '/en/checkout-success/'],
        'giveaway-checkout' => ['/giveaway-checkout/', '/en/giveaway-checkout/'],
    ];

    foreach ($route_map as $key => $paths) {
        if (in_array($normalized, $paths, true)) {
            return $key;
        }
    }

    return null;
}

function contenly_url_for_route_key($route_key, $lang) {
    $route_targets = [
        'home' => ['id' => '/', 'en' => '/en/'],
        'about' => ['id' => '/about/', 'en' => '/en/about/'],
        'contact' => ['id' => '/kontak/', 'en' => '/en/contact/'],
        'tour-packages' => ['id' => '/paket-tour/', 'en' => '/en/tour-packages/'],
        'blog' => ['id' => '/blog/', 'en' => '/en/journal/'],
        'login' => ['id' => '/login/', 'en' => '/en/login/'],
        'register' => ['id' => '/register/', 'en' => '/en/register/'],
        'dashboard' => ['id' => '/dashboard/', 'en' => '/en/dashboard/'],
        'membership' => ['id' => '/membership/', 'en' => '/en/membership/'],
        'settings' => ['id' => '/settings/', 'en' => '/en/settings/'],
        'wishlist' => ['id' => '/wishlist/', 'en' => '/en/wishlist/'],
        'reviews' => ['id' => '/reviews/', 'en' => '/en/reviews/'],
        'notifications' => ['id' => '/notifications/', 'en' => '/en/notifications/'],
        'rewards' => ['id' => '/rewards/', 'en' => '/en/rewards/'],
        'my-travels' => ['id' => '/my-travels/', 'en' => '/en/my-travels/'],
        'checkout-success' => ['id' => '/checkout-success/', 'en' => '/en/checkout-success/'],
        'giveaway-checkout' => ['id' => '/giveaway-checkout/', 'en' => '/en/giveaway-checkout/'],
    ];

    if (isset($route_targets[$route_key][$lang])) {
        return home_url($route_targets[$route_key][$lang]);
    }

    return '';
}

function contenly_localize_menu_item_title($title, $route_key, $lang) {
    $labels = [
        'home' => ['id' => 'Home', 'en' => 'Home'],
        'about' => ['id' => 'Tentang', 'en' => 'About Us'],
        'contact' => ['id' => 'Kontak', 'en' => 'Contact'],
        'tour-packages' => ['id' => 'Paket Tour', 'en' => 'Tour Packages'],
        'blog' => ['id' => 'Blog', 'en' => 'Blog'],
        'login' => ['id' => 'Login', 'en' => 'Login'],
        'register' => ['id' => 'Daftar', 'en' => 'Register'],
        'dashboard' => ['id' => 'Dashboard', 'en' => 'Dashboard'],
        'membership' => ['id' => 'Membership', 'en' => 'Membership'],
        'settings' => ['id' => 'Pengaturan', 'en' => 'Settings'],
        'wishlist' => ['id' => 'Wishlist', 'en' => 'Wishlist'],
        'reviews' => ['id' => 'Ulasan', 'en' => 'Reviews'],
        'notifications' => ['id' => 'Notifikasi', 'en' => 'Notifications'],
        'rewards' => ['id' => 'Dive Rewards', 'en' => 'Dive Rewards'],
        'my-travels' => ['id' => 'Dive Saya', 'en' => 'My Dives'],
        'checkout-success' => ['id' => 'Pembayaran Berhasil', 'en' => 'Payment Successful'],
        'giveaway-checkout' => ['id' => 'Checkout Giveaway', 'en' => 'Giveaway Checkout'],
    ];

    if ($route_key && isset($labels[$route_key][$lang])) {
        return $labels[$route_key][$lang];
    }

    return $title;
}

function contenly_get_switcher_target_url($target_lang) {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    $request_path = parse_url($request_uri, PHP_URL_PATH) ?: '/';
    $query_string = parse_url($request_uri, PHP_URL_QUERY) ?: '';

    if (is_singular('tour')) {
        $tour_url = contenly_localized_tour_permalink(get_queried_object_id(), $target_lang);
        if ($tour_url) {
            return $tour_url;
        }
    }

    if ((is_page() || is_singular('post')) && function_exists('pll_get_post')) {
        $object_id = get_queried_object_id();
        if ($object_id) {
            $translated_id = pll_get_post($object_id, $target_lang);
            if ($translated_id) {
                $translated_url = get_permalink($translated_id);
                if ($translated_url) {
                    return $query_string ? add_query_arg(wp_parse_args($query_string), $translated_url) : $translated_url;
                }
            }
        }
    }

    $route_key = contenly_menu_route_key_from_path($request_path);
    if ($route_key) {
        $mapped_url = contenly_url_for_route_key($route_key, $target_lang);
        if ($mapped_url) {
            return $query_string ? add_query_arg(wp_parse_args($query_string), $mapped_url) : $mapped_url;
        }
    }

    if ('en' === $target_lang) {
        if (0 === strpos($request_path, '/en/')) {
            return home_url($request_path . ($query_string ? '?' . $query_string : ''));
        }

        $fallback_path = '/' . ltrim($request_path, '/');
        return home_url('/en' . ('/' === $fallback_path ? '/' : $fallback_path) . ($query_string ? '?' . $query_string : ''));
    }

    if (0 === strpos($request_path, '/en/')) {
        $fallback_path = '/' . ltrim(substr($request_path, 3), '/');
        if ('//' === $fallback_path) {
            $fallback_path = '/';
        }
        return home_url($fallback_path . ($query_string ? '?' . $query_string : ''));
    }

    return home_url($request_path . ($query_string ? '?' . $query_string : ''));
}

function contenly_localize_primary_nav_menu_items($items, $args) {
    if (!is_array($items) || !is_object($args) || empty($args->theme_location) || !in_array($args->theme_location, ['primary', 'mobile'], true)) {
        return $items;
    }

    $lang = contenly_requested_lang();
    $request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    foreach ($items as $item) {
        if (!is_object($item) || empty($item->url)) {
            continue;
        }

        $item_path = parse_url($item->url, PHP_URL_PATH) ?: '/';
        $route_key = contenly_menu_route_key_from_path($item_path);
        if ($route_key) {
            $localized_url = contenly_url_for_route_key($route_key, $lang);
            if ($localized_url) {
                $item->url = $localized_url;
            }
            $item->title = contenly_localize_menu_item_title(wp_strip_all_tags($item->title), $route_key, $lang);
        }

        $current_key = contenly_menu_route_key_from_path($request_path);
        if ($route_key && $current_key && $route_key === $current_key) {
            $item->current = true;
            $item->classes = is_array($item->classes) ? $item->classes : [];
            foreach (['current-menu-item', 'current_page_item'] as $active_class) {
                if (!in_array($active_class, $item->classes, true)) {
                    $item->classes[] = $active_class;
                }
            }
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'contenly_localize_primary_nav_menu_items', 20, 2);

function contenly_filter_nav_menu_item_title($title, $item, $args, $depth) {
    if (!is_object($args) || empty($args->theme_location) || !in_array($args->theme_location, ['primary', 'mobile'], true) || !is_object($item) || empty($item->url)) {
        return $title;
    }

    $route_key = contenly_menu_route_key_from_path(parse_url($item->url, PHP_URL_PATH) ?: '/');
    return contenly_localize_menu_item_title(wp_strip_all_tags($title), $route_key, contenly_requested_lang());
}
add_filter('nav_menu_item_title', 'contenly_filter_nav_menu_item_title', 20, 4);

function wdc_redirect_legacy_contact_aliases() {
    $request_path = trailingslashit(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    if ('/kontak/' === $request_path) {
        wp_safe_redirect(home_url('/about/#contact-form'), 301);
        exit;
    }
}
add_action('template_redirect', 'wdc_redirect_legacy_contact_aliases', 1);

function contenly_render_language_switcher($class = '') {
    $class_attr = trim('gt-lang-switcher ' . $class);
    $current = contenly_requested_lang();

    return '<div class="' . esc_attr($class_attr) . '" aria-label="Language switcher">'
        . '<a href="' . esc_url(contenly_get_switcher_target_url('id')) . '" class="gt-lang-link' . ('id' === $current ? ' is-active' : '') . '" hreflang="id-ID">ID</a>'
        . '<a href="' . esc_url(contenly_get_switcher_target_url('en')) . '" class="gt-lang-link' . ('en' === $current ? ' is-active' : '') . '" hreflang="en-US">EN</a>'
        . '</div>';
}


function contenly_render_public_footer() {
    $home = esc_url(home_url('/'));
    $courses = esc_url(home_url('/courses/'));
    $equipment = esc_url(home_url('/equipment/'));
    $about = esc_url(home_url('/about/'));
    $blog = esc_url(home_url('/blog/'));
    $contact = esc_url(home_url('/contact/'));
    $conservation = esc_url(home_url('/conservation/'));
    $testimonials = esc_url(home_url('/testimonials/'));
    $year = esc_html(date('Y'));
    ?>
  <footer id="site-footer" class="wd-footer">
    <div class="wd-shell">
      <div class="wd-footer-top">
        <div class="wd-footer-brand">
          <span class="wd-footer-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('footer_kicker', contenly_tr('Siap dive?', 'Ready to dive?')) : contenly_tr('Siap dive?', 'Ready to dive?')); ?></span>
          <h2>Whale Dive Centre</h2>
          <p><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('footer_blurb', contenly_tr('Pelatihan selam, trip komunitas, dukungan peralatan, dan pengalaman peduli laut untuk petualangan bawah air yang lebih aman.', 'Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.')) : contenly_tr('Pelatihan selam, trip komunitas, dukungan peralatan, dan pengalaman peduli laut untuk petualangan bawah air yang lebih aman.', 'Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.')); ?></p>
          <a class="wd-btn alt" href="<?php echo esc_url(function_exists('wdc_site_url') ? wdc_site_url(wdc_site_get('footer_cta_url', '/contact/')) : $contact); ?>"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('footer_cta_label', contenly_tr('Mulai Konsultasi', 'Start Inquiry')) : contenly_tr('Mulai Konsultasi', 'Start Inquiry')); ?></a>
        </div>
        <nav class="wd-footer-col" aria-label="<?php echo esc_attr(contenly_tr('Jelajahi', 'Explore')); ?>">
          <h3><?php echo esc_html(contenly_tr('Jelajahi', 'Explore')); ?></h3>
          <a href="<?php echo $courses; ?>"><?php echo esc_html(contenly_tr('Kursus Selam', 'Dive Courses')); ?></a>
          <a href="<?php echo $equipment; ?>"><?php echo esc_html(contenly_tr('Peralatan Selam', 'Scuba Equipment')); ?></a>
          <a href="<?php echo $testimonials; ?>"><?php echo esc_html(contenly_tr('Testimoni', 'Testimonials')); ?></a>
          <a href="<?php echo $conservation; ?>"><?php echo esc_html(contenly_tr('Konservasi', 'Conservation')); ?></a>
          <a href="<?php echo $about; ?>"><?php echo esc_html(contenly_tr('Tentang Kami', 'About Us')); ?></a>
          <a href="<?php echo $blog; ?>">Blog</a>
        </nav>
        <nav class="wd-footer-col" aria-label="<?php echo esc_attr(contenly_tr('Kursus', 'Courses')); ?>">
          <h3><?php echo esc_html(contenly_tr('Kursus', 'Courses')); ?></h3>
          <a href="<?php echo esc_url(home_url('/courses/open-water-scuba-diver/')); ?>">Open Water Scuba Diver</a>
          <a href="<?php echo esc_url(home_url('/courses/advanced-open-water-diver/')); ?>">Advanced Open Water</a>
          <a href="<?php echo esc_url(home_url('/courses/rescue-scuba-diver/')); ?>">Rescue Scuba Diver</a>
          <a href="<?php echo esc_url(home_url('/courses/divemaster/')); ?>">Divemaster</a>
          <a href="<?php echo esc_url(home_url('/courses/instructor/')); ?>"><?php echo esc_html(contenly_tr('Instruktur', 'Instructor')); ?></a>
          <a href="<?php echo esc_url(home_url('/courses/intro-to-tech/')); ?>">Technical Diver</a>
          <a href="<?php echo $courses; ?>"><?php echo esc_html(contenly_tr('Lihat Semua Kursus', 'View All Courses')); ?></a>
        </nav>
        <div class="wd-footer-col">
          <h3><?php echo esc_html(contenly_tr('Kontak', 'Contact')); ?></h3>
          <?php
          $wdc_email = function_exists('wdc_site_get') ? wdc_site_get('email') : 'info@whaledivecentre.com';
          $wdc_phone = function_exists('wdc_site_get') ? wdc_site_get('phone') : '(021) 27939068';
          $wdc_phone_tel = function_exists('wdc_site_get') ? wdc_site_get('phone_tel') : '+622127939068';
          $wdc_address = function_exists('wdc_site_get') ? wdc_site_get('address') : 'Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240';
          $wdc_hours = function_exists('wdc_site_get') ? wdc_site_get('hours') : '';
          $wdc_ig = function_exists('wdc_site_get') ? wdc_site_get('instagram') : 'https://www.instagram.com/whaledivecentre.id/';
          $wdc_fb = function_exists('wdc_site_get') ? wdc_site_get('facebook') : 'https://www.facebook.com/whaledive.id/';
          $wdc_x = function_exists('wdc_site_get') ? wdc_site_get('x') : 'https://x.com/whaledivecentre';
          ?>
          <?php if ($wdc_email) : ?><p>Email: <a href="mailto:<?php echo esc_attr($wdc_email); ?>"><?php echo esc_html($wdc_email); ?></a></p><?php endif; ?>
          <?php if ($wdc_phone) : ?><p><?php echo esc_html(contenly_tr('Telepon', 'Phone')); ?>: <a href="tel:<?php echo esc_attr($wdc_phone_tel ?: preg_replace('/\D+/', '', $wdc_phone)); ?>"><?php echo esc_html($wdc_phone); ?></a></p><?php endif; ?>
          <?php if ($wdc_address) : ?><p><?php echo esc_html($wdc_address); ?></p><?php endif; ?>
          <?php if ($wdc_hours) : ?><p><?php echo esc_html($wdc_hours); ?></p><?php endif; ?>
          <div class="wd-social">
            <?php if ($wdc_ig) : ?><a href="<?php echo esc_url($wdc_ig); ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a><?php endif; ?>
            <?php if ($wdc_fb) : ?><a href="<?php echo esc_url($wdc_fb); ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a><?php endif; ?>
            <?php if ($wdc_x) : ?><a href="<?php echo esc_url($wdc_x); ?>" target="_blank" rel="noopener" aria-label="X (Twitter)"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a><?php endif; ?>
          </div>
        </div>
      </div>
      <div class="wd-footer-bottom">
        <span>&copy; <?php echo $year; ?> Whale Dive Centre. <?php echo esc_html(contenly_tr('Hak cipta dilindungi.', 'All rights reserved.')); ?></span>
        <span><?php echo esc_html(contenly_tr('Jalur pelatihan NAUI / TDI / DAN', 'NAUI / TDI / DAN training pathways')); ?></span>
      </div>
    </div>
  </footer>
  <script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&(path==='/'||path==='/en/'||path==='/home/'||path==='/en/home/'))||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script>
    <?php
}

function contenly_render_public_header() {
    if (is_user_logged_in() && !headers_sent()) {
        nocache_headers();
    }
    $brand_logo = esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b');
    $home_url = esc_url(contenly_localized_url('/home/'));
    $courses_url = esc_url(contenly_localized_url('/courses/'));
    $equipment_url = esc_url(contenly_localized_url('/equipment/'));
    $about_url = esc_url(contenly_localized_url('/about/'));
    $blog_url = esc_url(contenly_localized_url('/blog/'));
    $member_url = is_user_logged_in() ? esc_url(contenly_localized_url('/dashboard/')) : esc_url(contenly_localized_url('/member-login/'));
    $member_label = is_user_logged_in() ? contenly_tr('Dashboard', 'Dashboard') : contenly_tr('Masuk', 'Login');
    $request_path = trailingslashit(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $active_key = ($request_path === '/' || $request_path === '/en/' || $request_path === '/home/' || $request_path === '/en/home/') ? 'home' : '';
    if (false !== strpos($request_path, '/courses/') || false !== strpos($request_path, '/course/')) {
        $active_key = 'courses';
    } elseif (false !== strpos($request_path, '/equipment/')) {
        $active_key = 'equipment';
    } elseif (false !== strpos($request_path, '/about/') || false !== strpos($request_path, '/tentang/')) {
        $active_key = 'about';
    } elseif (false !== strpos($request_path, '/blog/') || false !== strpos($request_path, '/journal/') || is_singular('post')) {
        $active_key = 'blog';
    } elseif (false !== strpos($request_path, '/testimonials/')) {
        $active_key = 'testimonials';
    } elseif (false !== strpos($request_path, '/contact/')) {
        $active_key = 'contact';
    }
    $active_class = function($key) use ($active_key) {
        return $key === $active_key ? ' class="is-active" aria-current="page"' : '';
    };

    $close_label = esc_attr(contenly_tr('Tutup menu', 'Close menu'));
    $open_label = esc_attr(contenly_tr('Buka menu', 'Open menu'));
    $brand_name = esc_html('Whale Dive');
    $menu_kicker = esc_html(contenly_tr('Navigasi', 'Navigate'));

    echo '<header class="wd-header"><div class="wd-shell"><div class="wd-nav">'
        . '<a class="wd-brand" href="' . $home_url . '"><img class="wd-brand-logo" src="' . $brand_logo . '" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a>'
        . '<button class="wd-hamburger" type="button" aria-label="' . $open_label . '" aria-expanded="false" aria-controls="wd-mobile-menu"><span></span><span></span><span></span></button>'
        . '<div class="wd-menu-backdrop" data-wd-menu-close hidden></div>'
        . '<nav class="wd-menu" id="wd-mobile-menu" aria-label="' . esc_attr(contenly_tr('Navigasi utama', 'Primary navigation')) . '">'
        . '<div class="wd-menu-panel-head">'
        . '<div class="wd-menu-panel-brand"><img src="' . $brand_logo . '" alt=""><div class="wd-menu-panel-copy"><span class="wd-menu-kicker">' . $menu_kicker . '</span><strong>' . $brand_name . '</strong></div></div>'
        . '<button type="button" class="wd-menu-close" data-wd-menu-close aria-label="' . $close_label . '"><span></span></button>'
        . '</div>'
        . '<div class="wd-menu-links">'
        . '<a href="' . $home_url . '" data-nav="home"' . $active_class('home') . '><span>' . esc_html(contenly_tr('Beranda', 'Home')) . '</span></a>'
        . '<a href="' . $courses_url . '" data-nav="courses"' . $active_class('courses') . '><span>' . esc_html(contenly_tr('Kursus', 'Courses')) . '</span></a>'
        . '<a href="' . $equipment_url . '" data-nav="equipment"' . $active_class('equipment') . '><span>' . esc_html(contenly_tr('Peralatan', 'Equipment')) . '</span></a>'
        . '<a href="' . $about_url . '" data-nav="about"' . $active_class('about') . '><span>' . esc_html(contenly_tr('Tentang', 'About')) . '</span></a>'
        . '<a href="' . $blog_url . '" data-nav="blog"' . $active_class('blog') . '><span>' . esc_html(contenly_tr('Blog', 'Blog')) . '</span></a>'
        . '</div>'
        . '<div class="wd-menu-panel-foot">'
        . '<div class="wd-menu-foot-label">' . esc_html(contenly_tr('Bahasa', 'Language')) . '</div>'
        . contenly_render_language_switcher('wd-lang-switcher')
        . '<a href="' . $member_url . '" class="wd-nav-member">' . esc_html($member_label) . '</a>'
        . '</div>'
        . '</nav></div></div></header>';
}

function wdc_public_mobile_and_call_cleanup() {
    if (is_admin()) {
        return;
    }
    ?>
    <script id="wdc-public-nav-call-cleanup">
    document.addEventListener('DOMContentLoaded', function(){
      var nav = document.querySelector('.wd-nav');
      var toggle = document.querySelector('.wd-hamburger');
      var menu = document.querySelector('.wd-menu');
      var backdrop = document.querySelector('.wd-menu-backdrop');
      function setMenuOpen(open){
        if(!nav || !toggle || !menu) return;
        document.body.classList.toggle('wd-menu-open', open);
        nav.classList.toggle('wd-menu-open', open);
        menu.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if(open){
          if(backdrop){
            if(backdrop.parentElement !== document.body){ document.body.appendChild(backdrop); }
            backdrop.removeAttribute('hidden');
          }
          if(menu.parentElement !== document.body){ document.body.appendChild(menu); }
        } else {
          if(backdrop){
            backdrop.setAttribute('hidden', 'hidden');
            if(nav){ nav.appendChild(backdrop); }
          }
          if(nav && menu.parentElement === document.body){ nav.appendChild(menu); }
        }
        document.documentElement.classList.toggle('wd-menu-lock', open);
        document.body.style.overflow = open ? 'hidden' : '';
      }
      if(nav && toggle && menu){
        toggle.addEventListener('click', function(e){
          e.preventDefault();
          e.stopPropagation();
          setMenuOpen(!document.body.classList.contains('wd-menu-open'));
        });
        document.querySelectorAll('[data-wd-menu-close]').forEach(function(el){
          el.addEventListener('click', function(){ setMenuOpen(false); });
        });
        menu.querySelectorAll('a').forEach(function(link){
          link.addEventListener('click', function(){ setMenuOpen(false); });
        });
        document.addEventListener('keydown', function(e){
          if(e.key === 'Escape') setMenuOpen(false);
        });
      }
      var callLinks = Array.prototype.slice.call(document.querySelectorAll('a[aria-label="Call Whale Dive Centre"], a[aria-label*="Call Whale Dive"]'));
      callLinks.slice(1).forEach(function(link){ link.remove(); });
    });
    </script>
    <style id="wdc-public-mobile-call-cleanup-css">
      @media(max-width:760px){
        .wd-nav.wd-menu-open .wd-menu{opacity:1!important;visibility:visible!important;transform:translateY(0)!important}
        .wd-nav .gt-lang-switcher{justify-content:flex-start!important;margin:4px 0!important}
        .wd-nav .wd-nav-member{display:inline-flex!important;justify-content:center!important;min-height:42px!important}
        a[aria-label="Call Whale Dive Centre"],a[aria-label*="Call Whale Dive"]{right:14px!important;bottom:14px!important;z-index:900!important}
      }
    </style>
    <?php
}
add_action('wp_footer', 'wdc_public_mobile_and_call_cleanup', 5);

function contenly_requested_lang() {
    $request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (0 === strpos($request_path, '/en')) {
        return 'en';
    }

    if (function_exists('pll_current_language')) {
        $current = pll_current_language('slug');
        if ($current) {
            return $current;
        }
    }

    return 'id';
}

function contenly_local_en_template_map() {
    return [
        '/en/' => 'index.php',
        '/en/courses/' => 'page-courses.php',
        '/en/equipment/' => 'page-equipment.php',
        '/en/journal/' => 'page-blog.php',
        '/en/about/' => 'page-about.php',
        '/en/contact/' => 'page-about.php',
        '/en/member-login/' => 'page-login.php',
        '/en/member-register/' => 'page-register.php',
    ];
}

function contenly_local_en_source_slug_map() {
    return [
        '/en/' => 'home',
        '/en/courses/' => 'courses',
        '/en/equipment/' => 'equipment',
        '/en/journal/' => 'blog',
        '/en/about/' => 'about',
        '/en/contact/' => 'about',
        '/en/member-login/' => 'member-login',
        '/en/member-register/' => 'member-register',
    ];
}

function contenly_current_en_source_page() {
    $request_path = trailingslashit(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $slug_map = contenly_local_en_source_slug_map();
    if (empty($slug_map[$request_path])) {
        return null;
    }

    if ('/en/' === $request_path) {
        $front_id = (int) get_option('page_on_front');
        if ($front_id) {
            return get_post($front_id);
        }

        return get_page_by_path('sample-page') ?: get_page_by_path('about');
    }

    return get_page_by_path($slug_map[$request_path]);
}

function contenly_current_en_template() {
    $request_path = trailingslashit(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $map = contenly_local_en_template_map();
    return $map[$request_path] ?? '';
}

add_filter('pre_handle_404', function($preempt, $wp_query) {
    if (contenly_current_en_template()) {
        $source_page = contenly_current_en_source_page();
        if ($source_page) {
            $wp_query->queried_object = $source_page;
            $wp_query->queried_object_id = $source_page->ID;
            $wp_query->post = $source_page;
            $wp_query->posts = [$source_page];
            $wp_query->post_count = 1;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
        }
        $wp_query->is_404 = false;
        status_header(200);
        return true;
    }
    return $preempt;
}, 1, 2);

add_filter('template_include', function($template) {
    $en_template = contenly_current_en_template();
    if ($en_template) {
        $source_page = contenly_current_en_source_page();
        if ($source_page) {
            global $post;
            $post = $source_page;
            setup_postdata($post);
        }
        $candidate = get_stylesheet_directory() . '/' . $en_template;
        if (file_exists($candidate)) {
            status_header(200);
            return $candidate;
        }
    }
    return $template;
}, 1);

add_filter('body_class', function($classes) {
    if ('index.php' === contenly_current_en_template()) {
        $classes[] = 'home';
    }
    return array_values(array_unique($classes));
}, 20);

function contenly_public_page_slugs() {
    return ['beranda', 'home', 'tentang', 'about', 'kontak', 'contact', 'paket-tour', 'tour-packages', 'blog', 'journal'];
}

function contenly_private_page_slugs() {
    return ['sample-page', 'travel-dashboard', 'daftar-travel', 'login', 'register', 'membership', 'checkout', 'checkout-success', 'checkout-manual', 'payment-pending', 'my-account', 'my-bookings', 'dashboard', 'my-travels', 'wishlist', 'reviews', 'rewards', 'notifications', 'profile', 'settings', 'travel-story', 'booking-detail', 'giveaway-checkout'];
}

function contenly_blog_category_map() {
    return [
        'cerita-perjalanan' => ['id' => 'Cerita Perjalanan', 'en' => 'Travel Stories'],
        'tips-liburan' => ['id' => 'Tips Liburan', 'en' => 'Travel Tips'],
        'itinerary' => ['id' => 'Itinerary', 'en' => 'Itinerary'],
        'kuliner-lokal-experience' => ['id' => 'Kuliner & Lokal Experience', 'en' => 'Food & Local Experience'],
        'travel-internasional' => ['id' => 'Travel Internasional', 'en' => 'International Travel'],
        'uncategorized' => ['id' => 'Artikel', 'en' => 'Article'],
        'uncategorized-en' => ['id' => 'Artikel', 'en' => 'Article'],
    ];
}

function contenly_blog_category_label($term_or_slug, $lang = null) {
    $lang = $lang ?: contenly_requested_lang();
    $slug = '';
    $fallback_name = '';

    if (is_object($term_or_slug)) {
        $slug = isset($term_or_slug->slug) ? (string) $term_or_slug->slug : '';
        $fallback_name = isset($term_or_slug->name) ? (string) $term_or_slug->name : '';
    } else {
        $slug = sanitize_title((string) $term_or_slug);
        $fallback_name = (string) $term_or_slug;
    }

    $map = contenly_blog_category_map();
    if ($slug && isset($map[$slug][$lang])) {
        return $map[$slug][$lang];
    }

    return $fallback_name ?: ($slug ? ucwords(str_replace('-', ' ', $slug)) : '');
}

function contenly_localized_blog_category_url($slug, $lang = null) {
    $lang = $lang ?: contenly_requested_lang();
    $base = contenly_localized_url('/blog/', $lang);
    $slug = sanitize_title((string) $slug);

    if (!$slug || 'all' === $slug) {
        return $base;
    }

    return add_query_arg('category', $slug, $base);
}

function contenly_is_public_marketing_page($post = null) {
    $post = get_post($post ?: get_queried_object_id());
    if (!$post || 'page' !== $post->post_type) {
        return false;
    }

    return in_array($post->post_name, contenly_public_page_slugs(), true);
}

function contenly_filter_wp_robots($robots) {
    if (!is_array($robots)) {
        $robots = [];
    }

    if (is_author() || is_tag() || is_search() || is_post_type_archive('destination') || is_singular('destination')) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
        return $robots;
    }

    if (is_category()) {
        $term = get_queried_object();
        if ($term && isset($term->slug) && 'uncategorized' === $term->slug) {
            $robots['noindex'] = true;
            $robots['nofollow'] = true;
        }
        return $robots;
    }

    if (is_page() && !contenly_is_public_marketing_page()) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }

    return $robots;
}
add_filter('wp_robots', 'contenly_filter_wp_robots', 20);

function contenly_filter_sitemaps_add_provider($provider, $name) {
    if ('users' === $name) {
        return false;
    }

    return $provider;
}
add_filter('wp_sitemaps_add_provider', 'contenly_filter_sitemaps_add_provider', 20, 2);

function contenly_filter_sitemap_post_types($post_types) {
    if (isset($post_types['destination'])) {
        unset($post_types['destination']);
    }

    return $post_types;
}
add_filter('wp_sitemaps_post_types', 'contenly_filter_sitemap_post_types', 20);

function contenly_filter_sitemap_taxonomies($taxonomies) {
    unset($taxonomies['category'], $taxonomies['post_tag']);
    return $taxonomies;
}
add_filter('wp_sitemaps_taxonomies', 'contenly_filter_sitemap_taxonomies', 20);

function contenly_filter_sitemap_posts_query_args($args, $post_type) {
    if ('page' === $post_type) {
        $allowed_ids = get_posts([
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'suppress_filters' => false,
            'lang' => 'all',
            'post_name__in' => contenly_public_page_slugs(),
        ]);

        $args['post__in'] = !empty($allowed_ids) ? array_map('intval', $allowed_ids) : [0];
        $args['orderby'] = 'post__in';
    }

    return $args;
}
add_filter('wp_sitemaps_posts_query_args', 'contenly_filter_sitemap_posts_query_args', 20, 2);

function contenly_translate_page_option_for_language($value) {
    $page_id = (int) $value;
    if (!$page_id || !function_exists('pll_get_post')) {
        return $value;
    }

    $lang = contenly_requested_lang();
    $translated_id = pll_get_post($page_id, $lang);
    return $translated_id ?: $value;
}
add_filter('option_page_on_front', 'contenly_translate_page_option_for_language', 20);
add_filter('option_page_for_posts', 'contenly_translate_page_option_for_language', 20);

function contenly_get_raw_page_option($option_name) {
    global $wpdb;

    $value = $wpdb->get_var($wpdb->prepare(
        "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
        $option_name
    ));

    return (int) $value;
}

function contenly_front_page_root_url($lang) {
    $base = untrailingslashit(get_option('home'));
    return 'en' === $lang ? $base . '/en/' : $base . '/';
}

function contenly_current_request_url() {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($request_uri, PHP_URL_PATH) ?: '/';
    $query = parse_url($request_uri, PHP_URL_QUERY);

    if ('/' === $path || '/en' === rtrim($path, '/')) {
        $url = contenly_front_page_root_url(contenly_requested_lang());
    } else {
        $url = home_url(trailingslashit(ltrim($path, '/')));
    }

    if ($query) {
        $url .= '?' . $query;
    }

    return $url;
}

function contenly_filter_front_page_permalink($url, $post_id) {
    if (!function_exists('pll_get_post')) {
        return $url;
    }

    $raw_front_id = contenly_get_raw_page_option('page_on_front');
    if (!$raw_front_id) {
        return $url;
    }

    $front_ids = array_filter([
        $raw_front_id,
        pll_get_post($raw_front_id, 'id'),
        pll_get_post($raw_front_id, 'en'),
    ]);

    if (!in_array((int) $post_id, array_map('intval', $front_ids), true)) {
        return $url;
    }

    $lang = 'id';
    if (function_exists('pll_get_post_language')) {
        $lang = pll_get_post_language($post_id, 'slug') ?: 'id';
    }

    return contenly_front_page_root_url($lang);
}
add_filter('page_link', 'contenly_filter_front_page_permalink', 20, 2);

function contenly_keep_en_root_canonical($redirect_url, $requested_url) {
    $request_path = parse_url($requested_url, PHP_URL_PATH) ?: '/';
    if ('/en' === rtrim($request_path, '/')) {
        return false;
    }

    return $redirect_url;
}
add_filter('redirect_canonical', 'contenly_keep_en_root_canonical', 20, 2);

function contenly_fix_front_page_hreflangs($hreflangs) {
    if (!is_front_page() || !is_array($hreflangs)) {
        return $hreflangs;
    }

    foreach ($hreflangs as $lang => $url) {
        $normalized_lang = strtolower((string) $lang);
        if (0 === strpos($normalized_lang, 'en')) {
            $hreflangs[$lang] = contenly_front_page_root_url('en');
        } elseif ('x-default' !== $normalized_lang) {
            $hreflangs[$lang] = contenly_front_page_root_url('id');
        }
    }

    return $hreflangs;
}
add_filter('pll_rel_hreflang_attributes', 'contenly_fix_front_page_hreflangs');

/**
 * Hide WP admin bar on public-facing pages (tour, member, etc.)
 */
if (!is_admin()) {
    add_filter('show_admin_bar', '__return_false');
}

/**
 * Custom document titles for key public and member pages.
 */
function contenly_custom_document_title($title) {
    if (is_front_page() || is_home()) {
        return contenly_tr('Whale Dive Centre - Dive Beyond the Surface', 'Whale Dive Centre - Jakarta Diving Community & Academy');
    }

    $title_checks = [
        [['is_page_template', 'page-about.php'], ['Tentang Kami', 'About Us']],
        [['is_page_template', 'page-contact.php'], ['Kontak', 'Contact']],
        [['is_page_template', 'page-blog.php'], ['Blog', 'Blog']],
        [['is_page_template', 'page-tours.php'], ['Paket Tour', 'Tour Packages']],
        [['is_page_template', 'page-login.php'], ['Login', 'Login']],
        [['is_page_template', 'page-register.php'], ['Daftar', 'Register']],
        [['is_page_template', 'page-booking-detail.php'], ['Detail Booking', 'Booking Details']],
        [['is_page_template', 'page-checkout.php'], ['Pembayaran Booking', 'Booking Payment']],
        [['is_page_template', 'page-checkout-success.php'], ['Pembayaran Berhasil', 'Payment Successful']],
        [['is_page_template', 'page-dashboard.php'], ['Dashboard Member', 'Member Dashboard']],
        [['is_page_template', 'page-my-courses.php'], ['Kursus Saya', 'My Courses']],
        [['is_page_template', 'page-my-gear.php'], ['Peralatan Saya', 'My Gear']],
        [['is_page_template', 'page-my-travels.php'], ['Dive Saya', 'My Dives']],
        [['is_page_template', 'page-wishlist.php'], ['Gear Wishlist', 'Gear Wishlist']],
        [['is_page_template', 'page-reviews.php'], ['Dive Review Saya', 'My Dive Reviews']],
        [['is_page_template', 'page-travel-story.php'], ['Dive Story', 'Dive Story']],
        [['is_page_template', 'page-rewards.php'], ['Dive Rewards & Poin', 'Dive Rewards & Points']],
        [['is_page_template', 'page-membership.php'], ['Tier Member', 'Member Tier']],
        [['is_page_template', 'page-notifications.php'], ['Notifikasi', 'Notifications']],
        [['is_page_template', 'page-settings.php'], ['Pengaturan Akun', 'Account Settings']],
        [['is_page', 'about'], ['Tentang Kami', 'About Us']],
        [['is_page', 'contact'], ['Kontak', 'Contact']],
        [['is_page', 'blog'], ['Blog', 'Blog']],
        [['is_page', 'tour-packages'], ['Paket Tour', 'Tour Packages']],
        [['is_page', 'login'], ['Login', 'Login']],
        [['is_page', 'register'], ['Daftar', 'Register']],
        [['is_page', 'booking-detail'], ['Detail Booking', 'Booking Details']],
        [['is_page', 'checkout'], ['Pembayaran Booking', 'Booking Payment']],
        [['is_page', 'checkout-success'], ['Pembayaran Berhasil', 'Payment Successful']],
        [['is_page', 'dashboard'], ['Dashboard Member', 'Member Dashboard']],
        [['is_page', 'my-courses'], ['Kursus Saya', 'My Courses']],
        [['is_page', 'my-gear'], ['Peralatan Saya', 'My Gear']],
        [['is_page', 'my-travels'], ['Dive Saya', 'My Dives']],
        [['is_page', 'wishlist'], ['Gear Wishlist', 'Gear Wishlist']],
        [['is_page', 'reviews'], ['Dive Review Saya', 'My Dive Reviews']],
        [['is_page', 'travel-story'], ['Dive Story', 'Dive Story']],
        [['is_page', 'rewards'], ['Dive Rewards & Poin', 'Dive Rewards & Points']],
        [['is_page', 'membership'], ['Tier Member', 'Member Tier']],
        [['is_page', 'notifications'], ['Notifikasi', 'Notifications']],
        [['is_page', 'direct-checkout'], ['Checkout', 'Checkout']],
        [['is_page', 'settings'], ['Pengaturan Akun', 'Account Settings']],
    ];

    foreach ($title_checks as [$check, $titles]) {
        [$fn, $arg] = $check;
        if (function_exists($fn) && $fn($arg)) {
            [$id_title, $en_title] = $titles;
            return sprintf('%s – %s', contenly_tr($id_title, $en_title), get_bloginfo('name'));
        }
    }

    if (is_singular('tour')) {
        return sprintf('%s – %s', single_post_title('', false), get_bloginfo('name'));
    }

    return $title;
}
add_filter('pre_get_document_title', 'contenly_custom_document_title');

/**
 * Basic SEO meta / OG / schema for key public pages.
 */
function contenly_get_seo_context() {
    $default_image = get_site_icon_url(512) ?: get_template_directory_uri() . '/assets/images/hero-placeholder.jpg';
    $title = wp_get_document_title();
    $description = '';
    $schema = [];

    if (is_front_page() || is_home()) {
        $title = contenly_tr('Whale Dive Centre - Dive Beyond the Surface', 'Whale Dive Centre - Jakarta Diving Community & Academy');
        $description = contenly_tr(
            'Whale Dive Centre offers scuba courses, dive community support, quality gear guidance, and calm instructor-led training from Jakarta.',
            'Whale Dive Centre in Jakarta offers scuba diving courses, dive community programs, quality scuba equipment, and ocean-minded training for beginner to professional divers.'
        );
        $schema[] = [
            '@context' => 'https://schema.org',
            '@type' => 'SportsActivityLocation',
            'name' => get_bloginfo('name'),
            'url' => contenly_current_request_url(),
            'description' => $description,
        ];
        $schema[] = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => contenly_current_request_url(),
        ];
    } elseif (is_page_template('page-about.php') || is_page('about')) {
        $description = contenly_tr(
            'Get to know Whale Dive Centre, a Jakarta dive centre focused on safer scuba training, quality gear support, and an ocean-minded community.',
            'Get to know Whale Dive Centre, a Jakarta dive centre focused on safer scuba training, quality gear support, and an ocean-minded community.'
        );
    } elseif (is_page_template('page-contact.php') || is_page('contact')) {
        $description = contenly_tr(
            'Share your course, gear, or dive inquiry. The Whale Dive Centre crew will review it and guide you toward the right next step.',
            'Share your course, gear, or dive inquiry. The Whale Dive Centre crew will review it and guide you toward the right next step.'
        );
    } elseif (is_page_template('page-blog.php') || is_page('blog')) {
        $description = contenly_tr(
            'Read dive stories, course tips, gear notes, and ocean-minded insights from the Whale Dive Centre crew.',
            'Read dive stories, course tips, gear notes, and ocean-minded insights from the Whale Dive Centre crew.'
        );
    } elseif (is_page_template('page-tours.php') || is_page('tour-packages')) {
        $description = contenly_tr(
            'Explore Whale Dive Centre programs, course pathways, and dive support options for your next underwater step.',
            'Explore Whale Dive Centre programs, course pathways, and dive support options for your next underwater step.'
        );
    } elseif (is_page('login')) {
        $description = contenly_tr(
            'Log in to your Whale Dive Centre account to manage course progress, gear requests, certifications, and crew updates.',
            'Log in to your Whale Dive Centre account to manage course progress, gear requests, certifications, and crew updates.'
        );
    } elseif (is_page('register')) {
        $description = contenly_tr(
            'Create a Whale Dive Centre account to keep course planning, gear requests, certification notes, and dive updates in one place.',
            'Create a Whale Dive Centre account to keep course planning, gear requests, certification notes, and dive updates in one place.'
        );

    } elseif (is_page('courses') || is_post_type_archive('wm_course')) {
        $description = contenly_tr(
            'Jelajahi kursus selam PADI/SSI dari Open Water hingga Divemaster di Whale Dive Centre Jakarta.',
            'Explore PADI/SSI dive courses from Open Water to Divemaster at Whale Dive Centre Jakarta.'
        );
    } elseif (is_page('equipment') || is_post_type_archive('wm_equipment')) {
        $description = contenly_tr(
            'Beli peralatan selam berkualitas — masker, wetsuit, BCD, regulator, fin, dive computer — di Whale Dive Centre.',
            'Buy quality dive gear — masks, wetsuits, BCDs, regulators, fins, dive computers — at Whale Dive Centre.'
        );
    } elseif (is_page('member-login')) {
        $description = contenly_tr(
            'Masuk ke akun member Whale Dive Centre untuk kelola kursus, gear, dan sertifikasi selam Anda.',
            'Sign in to your Whale Dive Centre member account to manage courses, gear, and dive certifications.'
        );
    } elseif (is_page('member-register')) {
        $description = contenly_tr(
            'Buat akun member Whale Dive Centre untuk akses kursus selam, peralatan, dan dukungan crew.',
            'Create a Whale Dive Centre member account for access to dive courses, gear, and crew support.'
        );
    } elseif (is_page('direct-checkout')) {
        $description = contenly_tr(
            'Checkout dan bayar kursus selam atau peralatan diving langsung di Whale Dive Centre.',
            'Checkout and pay for dive courses or equipment directly at Whale Dive Centre.'
        );
    } elseif (is_page('my-courses')) {
        $description = contenly_tr(
            'Kelola kursus selam Anda — lihat status, daftar kursus baru, dan lacak progres sertifikasi.',
            'Manage your dive courses — view status, enroll in new courses, and track certification progress.'
        );
    } elseif (is_page('my-gear')) {
        $description = contenly_tr(
            'Beli peralatan selam dan kelola request gear Anda di member area Whale Dive Centre.',
            'Buy dive equipment and manage your gear requests in the Whale Dive Centre member area.'
        );
    } elseif (is_page('dashboard')) {
        $description = contenly_tr(
            'Ringkasan member Whale Dive Centre untuk request course, gear, dan bantuan crew.',
            'Your Whale Dive Centre member hub for course requests, gear requests, and crew support.'
        );
    } elseif (is_page('my-travels')) {
        $description = contenly_tr(
            'Lihat request course, gear, dan detail akun Anda di member area.',
            'Review your course requests, gear requests, and account details in the member area.'
        );
    } elseif (is_page('wishlist')) {
        $description = contenly_tr(
            'Save gear, course interests, and dive planning notes in your Whale Dive Centre member wishlist.',
            'Save gear, course interests, and dive planning notes in your Whale Dive Centre member wishlist.'
        );
    } elseif (is_page('reviews')) {
        $description = contenly_tr(
            'Manage your dive reviews and publication status from the Whale Dive Centre member dashboard.',
            'Manage your dive reviews and publication status from the Whale Dive Centre member dashboard.'
        );
    } elseif (is_page('travel-story')) {
        $description = contenly_tr(
            'Write and manage member dive stories to share through Whale Dive Centre.',
            'Write and manage member dive stories to share through Whale Dive Centre.'
        );
    } elseif (is_page('rewards')) {
        $description = contenly_tr(
            'Pantau poin rewards, benefit member, dan opsi voucher yang tersedia dari dashboard Whale Dive Centre.',
            'Track reward points, member benefits, and available voucher options from your Whale Dive Centre dashboard.'
        );
    } elseif (is_page('membership')) {
        $description = contenly_tr(
            'Cek tier membership aktif, progress spending, dan benefit yang sedang berlaku di akun Whale Dive Centre Anda.',
            'Check your active membership tier, spending progress, and current benefits in your Whale Dive Centre account.'
        );
    } elseif (is_page('notifications')) {
        $description = contenly_tr(
            'Manage course, gear, and member notification preferences the way you want.',
            'Manage course, gear, and member notification preferences the way you want.'
        );
    } elseif (is_page('settings')) {
        $description = contenly_tr(
            'Perbarui profil diver, kontak, sertifikasi, dan catatan ukuran gear Anda di satu halaman member Whale Dive Centre.',
            'Update your diver profile, contact details, certification level, and gear sizing notes from one Whale Dive Centre member page.'
        );
    } elseif (is_singular('tour')) {
        $description = wp_strip_all_tags(get_the_excerpt() ?: get_post_meta(get_the_ID(), 'location', true) ?: contenly_tr('Dive program from Whale Dive Centre.', 'Dive program from Whale Dive Centre.'));
        if (strlen($description) > 160) {
            $description = wp_trim_words($description, 24, '...');
        }
    }

    if (!$description) {
        $description = wp_strip_all_tags(get_bloginfo('description')) ?: contenly_tr(
            'Whale Dive Centre - scuba training, dive community, and gear support from Jakarta.',
            'Whale Dive Centre - scuba training, dive community, and gear support from Jakarta.'
        );
    }

    return [
        'title' => $title,
        'description' => $description,
        'image' => $default_image,
        'url' => contenly_current_request_url(),
        'schema' => $schema,
    ];
}

function contenly_output_basic_seo_meta() {
    if (is_admin()) {
        return;
    }

    $seo = contenly_get_seo_context();
    echo "\n" . '<meta name="description" content="' . esc_attr($seo['description']) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($seo['title']) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($seo['description']) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($seo['url']) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($seo['image']) . '">' . "\n";

    foreach ($seo['schema'] as $schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'contenly_output_basic_seo_meta', 1);

/**
 * Helpers for hiding known dummy/demo content on local/public pages.
 */
function contenly_get_dummy_tour_titles() {
    return [
        'bali adventure - 5 days',
        'paris romantic getaway - 7 days',
        'tokyo explorer - 6 days',
        'new york city tour - 5 days',
        'rome historical tour - 8 days',
        'phuket beach resort - 4 days',
    ];
}

function contenly_get_dummy_term_names() {
    return [
        'americas',
        'europe',
        'uncategorized',
    ];
}

function contenly_get_dummy_review_phrases() {
    return [
        'amazing phuket experience',
        'perfect vacation',
        'great tour overall',
        'best trip ever',
        'highly recommended',
        'wonderful experience',
        'exceeded expectations',
        'paradise found',
        'memorable holiday',
        'amazing experience',
        'nice trip',
    ];
}

function contenly_get_dummy_story_markers() {
    return [
        '3 hari di phuket',
        'island hopping sampai sunset',
    ];
}

function contenly_normalize_compare_text($value) {
    $value = wp_strip_all_tags((string) $value);
    $value = strtolower(trim($value));
    return preg_replace('/\s+/', ' ', $value);
}

function contenly_is_dummy_tour($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }

    $title = contenly_normalize_compare_text($post->post_title);
    return in_array($title, contenly_get_dummy_tour_titles(), true);
}

function contenly_is_dummy_term($term) {
    if (!$term || is_wp_error($term)) {
        return false;
    }

    $name = contenly_normalize_compare_text(is_object($term) ? $term->name : $term);
    return in_array($name, contenly_get_dummy_term_names(), true);
}

function contenly_is_dummy_review($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }

    $title = contenly_normalize_compare_text($post->post_title);
    $content = contenly_normalize_compare_text($post->post_content);
    $haystack = trim($title . ' ' . $content);

    if (in_array($title, ['mantap', 'ok', 'nice trip', 'amazing', 'keren', 'wonderfull'], true)) {
        return true;
    }

    if (in_array($content, ['mantap', 'ok', 'nice trip', 'keren'], true)) {
        return true;
    }

    foreach (contenly_get_dummy_review_phrases() as $phrase) {
        if (strpos($haystack, $phrase) !== false) {
            return true;
        }
    }

    return false;
}

function contenly_is_dummy_story($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }

    $slug = contenly_normalize_compare_text($post->post_name);
    if ($slug === 'test') {
        return true;
    }

    $haystack = contenly_normalize_compare_text($post->post_title . ' ' . $post->post_excerpt . ' ' . $post->post_content);
    foreach (contenly_get_dummy_story_markers() as $marker) {
        if (strpos($haystack, $marker) !== false) {
            return true;
        }
    }

    return false;
}

function contenly_filter_real_terms($terms) {
    if (!is_array($terms)) {
        return [];
    }

    return array_values(array_filter($terms, function($term) {
        return !contenly_is_dummy_term($term);
    }));
}

function contenly_filter_real_posts($posts, $type = 'tour') {
    if (!is_array($posts)) {
        return [];
    }

    return array_values(array_filter($posts, function($post) use ($type) {
        if ($type === 'review') {
            return !contenly_is_dummy_review($post);
        }

        return !contenly_is_dummy_tour($post);
    }));
}

function contenly_get_real_destination_terms() {
    $terms = get_terms([
        'taxonomy' => 'travel_category',
        'hide_empty' => false,
    ]);

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    $destinations = [];
    foreach (contenly_filter_real_terms($terms) as $term) {
        $tour_ids = get_posts([
            'post_type' => 'tour',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => [[
                'taxonomy' => 'travel_category',
                'field' => 'term_id',
                'terms' => $term->term_id,
            ]],
        ]);

        $real_tour_ids = array_values(array_filter($tour_ids, function($tour_id) {
            return !contenly_is_dummy_tour($tour_id);
        }));

        if (!empty($real_tour_ids)) {
            $term->real_count = count($real_tour_ids);
            $destinations[] = $term;
        }
    }

    usort($destinations, function($a, $b) {
        return ($b->real_count ?? 0) <=> ($a->real_count ?? 0);
    });

    return $destinations;
}

function contenly_get_member_tier_thresholds() {
    return [
        'silver' => 0,
        'gold' => 5000000,
        'platinum' => 15000000,
    ];
}

function contenly_get_member_tier_map() {
    return [
        'silver' => ['name' => 'Silver', 'icon' => '🥈', 'color' => '#94a3b8'],
        'gold' => ['name' => 'Gold', 'icon' => '🥇', 'color' => '#fbbf24'],
        'platinum' => ['name' => 'Platinum', 'icon' => '💎', 'color' => '#355F72'],
    ];
}

function contenly_get_user_total_spending($user_id) {
    $user_id = (int) $user_id;
    if ($user_id <= 0) {
        return 0;
    }

    $booking_posts = get_posts([
        'post_type' => 'tour_booking',
        'posts_per_page' => -1,
        'post_status' => 'any',
        'fields' => 'ids',
        'meta_query' => [
            ['key' => '_user_id', 'value' => $user_id],
            ['key' => '_booking_status', 'value' => contenly_paid_like_statuses(), 'compare' => 'IN'],
        ],
    ]);

    $total_spend = 0;
    foreach ($booking_posts as $booking_id) {
        $total_spend += (int) contenly_booking_total_amount($booking_id);
    }

    return $total_spend;
}

function contenly_get_tier_from_spending($total_spend) {
    $total_spend = (int) $total_spend;
    $thresholds = contenly_get_member_tier_thresholds();

    if ($total_spend >= $thresholds['platinum']) {
        return 'platinum';
    }
    if ($total_spend >= $thresholds['gold']) {
        return 'gold';
    }

    return 'silver';
}

function contenly_get_user_tier_data($user_id) {
    $total_spend = contenly_get_user_total_spending($user_id);
    $tier_key = contenly_get_tier_from_spending($total_spend);
    $map = contenly_get_member_tier_map();

    return [
        'tier' => $tier_key,
        'total_spend' => $total_spend,
        'info' => $map[$tier_key],
        'thresholds' => contenly_get_member_tier_thresholds(),
        'map' => $map,
    ];
}

function contenly_get_trip_style_presets() {
    return [
        'solo' => [
            'label' => contenly_tr('Solo / Me Time', 'Solo / Me Time'),
            'headline' => 'Trip ringkas buat yang mau jalan dengan ritme sendiri.',
            'desc' => 'Cocok buat first timer, short break, atau traveler yang pengen itinerary jelas tanpa ribet ngatur rame-rame.',
            'icon' => '🧭',
        ],
        'couple' => [
            'label' => contenly_tr('Couple / Honeymoon', 'Couple / Honeymoon'),
            'headline' => 'Lebih santai, lebih estetik, dan enak dinikmati berdua.',
            'desc' => 'Fokus ke pace yang nyaman, spot yang memorable, dan flow trip yang nggak bikin capek di jalan.',
            'icon' => '💙',
        ],
        'family' => [
            'label' => contenly_tr('Family Trip', 'Family Trip'),
            'headline' => 'Itinerary aman buat orang tua, anak, dan rombongan keluarga kecil.',
            'desc' => 'Durasi lebih masuk akal, ritme lebih nyaman, dan destinasi lebih gampang dinikmati semua umur.',
            'icon' => '👨‍👩‍👧‍👦',
        ],
        'friends' => [
            'label' => contenly_tr('Bareng Teman', 'Friends Trip'),
            'headline' => 'Pas buat short escape, seru-seruan, dan trip yang lebih hidup kalau berangkat rame.',
            'desc' => 'Cocok buat bestie trip, mini reunion, atau liburan bareng circle yang pengen itinerary fun tapi tetap rapi.',
            'icon' => '🌴',
        ],
    ];
}

function contenly_get_tour_travel_styles($post = null) {
    $post = get_post($post);
    if (!$post) {
        return [];
    }

    $styles = get_post_meta($post->ID, '_travel_styles', true);
    if (is_array($styles)) {
        $styles = array_map('sanitize_key', $styles);
    } elseif (is_string($styles) && $styles !== '') {
        $styles = array_map('sanitize_key', array_filter(array_map('trim', explode(',', $styles))));
    } else {
        $styles = [];
    }

    if (!empty($styles)) {
        return array_values(array_unique(array_filter($styles)));
    }

    $title = contenly_normalize_compare_text($post->post_title);
    $location = contenly_normalize_compare_text(get_post_meta($post->ID, 'location', true) ?: get_post_meta($post->ID, '_tour_location', true));
    $haystack = trim($title . ' ' . $location);
    $derived = [];

    if (preg_match('/singapore|seoul|tokyo|hanoi|sumba|city break|me time|solo/', $haystack)) {
        $derived[] = 'solo';
    }
    if (preg_match('/raja ampat|bali premium|romantic|honeymoon|couple|gili/', $haystack)) {
        $derived[] = 'couple';
    }
    if (preg_match('/jogja|yogyakarta|bali|kuala lumpur|singapore|family|bandung|lembang/', $haystack)) {
        $derived[] = 'family';
    }
    if (preg_match('/bangkok|pattaya|labuan bajo|bromo|belitung|hong kong|shenzhen|friends|teman/', $haystack)) {
        $derived[] = 'friends';
    }

    if (empty($derived)) {
        $derived[] = 'solo';
    }

    return array_values(array_unique($derived));
}

function contenly_tour_matches_style($post = null, $style = '') {
    $style = sanitize_key($style);
    if ($style === '' || $style === 'all') {
        return true;
    }

    return in_array($style, contenly_get_tour_travel_styles($post), true);
}

function contenly_is_domestic_tour($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }

    $title = contenly_normalize_compare_text($post->post_title);
    $location = contenly_normalize_compare_text(get_post_meta($post->ID, 'location', true) ?: get_post_meta($post->ID, '_tour_location', true));
    return (bool) preg_match('/indonesia|bali|jogja|yogyakarta|labuan bajo|raja ampat|bromo|lumajang|jakarta|bandung|lombok|komodo|belitung|sumba|lembang/', $title . ' ' . $location);
}

function contenly_is_international_tour($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }

    return !contenly_is_domestic_tour($post);
}

function contenly_is_diving_tour($post = null) {
    $post = get_post($post);
    if (!$post) {
        return false;
    }

    $title = contenly_normalize_compare_text($post->post_title);
    $location = contenly_normalize_compare_text(get_post_meta($post->ID, 'location', true) ?: get_post_meta($post->ID, '_tour_location', true));
    $haystack = trim($title . ' ' . $location);

    return (bool) preg_match('/diving|dive|liveaboard|sailing|phinisi|raja ampat|labuan bajo|komodo/', $haystack);
}

function contenly_get_diving_trip_mode($post = null) {
    $post = get_post($post);
    if (!$post) {
        return 'resort';
    }

    $title = contenly_normalize_compare_text($post->post_title);
    $location = contenly_normalize_compare_text(get_post_meta($post->ID, 'location', true) ?: get_post_meta($post->ID, '_tour_location', true));
    $haystack = trim($title . ' ' . $location);

    if (preg_match('/liveaboard|sailing|kapal|phinisi|labuan bajo|komodo/', $haystack)) {
        return 'liveaboard';
    }

    return 'resort';
}

function contenly_get_contact_details() {
    return [
        'phone_display' => '(021) 2274 0870',
        'phone_tel' => '+622****0870',
        'email' => 'hello@travelship.id',
        'whatsapp_display' => '',
        'whatsapp_number' => '',
        'whatsapp_text_default' => '',
        'office_1' => 'Jl. Tanah Kusir II No.3, RT.10/RW.9, Kby. Lama Sel., Kec. Kebayoran Lama, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12240, Indonesia',
        'office_2' => '',
        'hours' => 'Senin–Sabtu: 09:00–18:00',
    ];
}

function contenly_localize_business_hours($hours = '') {
    $hours = trim((string) $hours);
    if ($hours === '') {
        $hours = 'Senin–Sabtu: 09:00–18:00';
    }

    if (!contenly_is_english()) {
        return $hours;
    }

    $translations = [
        'Senin–Sabtu: 09:00–18:00' => 'Monday–Saturday: 09:00–18:00',
        'Senin - Sabtu: 09:00–18:00' => 'Monday–Saturday: 09:00–18:00',
        'Senin-Sabtu: 09:00–18:00' => 'Monday–Saturday: 09:00–18:00',
    ];

    return $translations[$hours] ?? $hours;
}

function contenly_get_whatsapp_link($message = '') {
    return home_url('/contact/#contact-form-start');
}

/**
 * Travel Story Featured Meta Box (Admin)
 */
function contenly_add_featured_story_metabox() {
    add_meta_box(
        'contenly_featured_story',
        'Travel Story Unggulan',
        'contenly_render_featured_story_metabox',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'contenly_add_featured_story_metabox');

function contenly_render_featured_story_metabox($post) {
    $is_featured = get_post_meta($post->ID, '_is_featured_travel_story', true) === '1';
    wp_nonce_field('contenly_featured_story_nonce', 'contenly_featured_story_nonce_field');
    echo '<label style="display:flex; gap:8px; align-items:center;">';
    echo '<input type="checkbox" name="contenly_is_featured_story" value="1" ' . checked($is_featured, true, false) . ' />';
    echo '<span>Jadikan story unggulan di homepage</span>';
    echo '</label>';
    echo '<p style="margin-top:8px;color:#64748b;font-size:12px;">Hanya satu postingan yang bisa jadi unggulan.</p>';
}

function contenly_save_featured_story_metabox($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['contenly_featured_story_nonce_field']) || !wp_verify_nonce($_POST['contenly_featured_story_nonce_field'], 'contenly_featured_story_nonce')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $set_featured = isset($_POST['contenly_is_featured_story']) && $_POST['contenly_is_featured_story'] === '1';

    if ($set_featured) {
        $all_featured = get_posts(contenly_all_language_post_args([
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'fields' => 'ids',
            'meta_query' => [[
                'key' => '_is_featured_travel_story',
                'value' => '1'
            ]]
        ]));
        foreach ($all_featured as $fid) {
            if ((int)$fid !== (int)$post_id) {
                delete_post_meta($fid, '_is_featured_travel_story');
            }
        }
        update_post_meta($post_id, '_is_featured_travel_story', '1');
    } else {
        delete_post_meta($post_id, '_is_featured_travel_story');
    }
}
add_action('save_post_post', 'contenly_save_featured_story_metabox');

/**
 * Ensure Travel Story page exists for member dashboard menu
 */
function contenly_ensure_travel_story_page() {
    $page = get_page_by_path('travel-story');
    if ($page) {
        if (get_page_template_slug($page->ID) !== 'page-travel-story.php') {
            update_post_meta($page->ID, '_wp_page_template', 'page-travel-story.php');
        }
        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Travel Story',
        'post_name'    => 'travel-story',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => 'Member Travel Story Dashboard',
    ]);

    if (!is_wp_error($page_id) && $page_id) {
        update_post_meta($page_id, '_wp_page_template', 'page-travel-story.php');
    }
}
add_action('after_switch_theme', 'contenly_ensure_travel_story_page');
add_action('init', 'contenly_ensure_travel_story_page');

/**
 * Ensure public Blog page exists
 */
function contenly_ensure_blog_page() {
    $page = get_page_by_path('blog');
    if ($page) {
        if (get_page_template_slug($page->ID) !== 'page-blog.php') {
            update_post_meta($page->ID, '_wp_page_template', 'page-blog.php');
        }
        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Blog',
        'post_name'    => 'blog',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => 'Public blog listing page',
    ]);

    if (!is_wp_error($page_id) && $page_id) {
        update_post_meta($page_id, '_wp_page_template', 'page-blog.php');
    }
}
add_action('after_switch_theme', 'contenly_ensure_blog_page');
add_action('init', 'contenly_ensure_blog_page');

/**
 * Ensure Booking Detail page exists
 */
function contenly_ensure_booking_detail_page() {
    $page = get_page_by_path('booking-detail');
    if ($page) {
        if (get_page_template_slug($page->ID) !== 'page-booking-detail.php') {
            update_post_meta($page->ID, '_wp_page_template', 'page-booking-detail.php');
        }
        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Booking Detail',
        'post_name'    => 'booking-detail',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => 'Booking detail page',
    ]);

    if (!is_wp_error($page_id) && $page_id) {
        update_post_meta($page_id, '_wp_page_template', 'page-booking-detail.php');
    }
}
add_action('after_switch_theme', 'contenly_ensure_booking_detail_page');
add_action('init', 'contenly_ensure_booking_detail_page');

/**
 * Replace Travel Story menu label/url with Blog for public navigation
 */
function contenly_replace_travel_story_menu($items, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
        return $items;
    }

    // Normalize Travel Story -> Blog and collect links by key
    $by_key = [];
    foreach ($items as $item) {
        $title = strtolower(trim(wp_strip_all_tags($item->title)));
        $url = $item->url;

        if ($title === 'travel story' || strpos($url, '/travel-story') !== false) {
            $item->title = 'Blog';
            $item->url = contenly_localized_url('/blog/');
            $title = 'blog';
        }

        if ($title === 'home' || rtrim($item->url, '/') === rtrim(contenly_localized_url('/'), '/')) $by_key['home'] = $item;
        if (in_array($title, ['about', 'about us'])) $by_key['about'] = $item;
        if (in_array($title, ['tour packages', 'tour package'])) $by_key['tour'] = $item;
        if ($title === 'blog' || strpos($item->url, '/blog') !== false) $by_key['blog'] = $item;
        if (in_array($title, ['contact', 'contact us']) || strpos($item->url, '/contact') !== false) $by_key['contact'] = $item;
    }

    // Create missing core items if needed
    $mk = function($title, $path, $id) {
        $o = new stdClass();
        $o->ID = -$id;
        $o->db_id = 0;
        $o->menu_item_parent = 0;
        $o->object_id = 0;
        $o->object = 'custom';
        $o->type = 'custom';
        $o->type_label = 'Custom Link';
        $o->title = $title;
        $o->url = contenly_localized_url($path);
        $o->target = '';
        $o->attr_title = '';
        $o->description = '';
        $o->classes = [];
        $o->xfn = '';
        $o->status = 'publish';
        $o->current = false;
        $o->current_item_parent = false;
        $o->current_item_ancestor = false;
        return $o;
    };

    if (!isset($by_key['home'])) $by_key['home'] = $mk('Home', '/', 1);
    if (!isset($by_key['about'])) $by_key['about'] = $mk('About Us', '/about/', 2);
    if (!isset($by_key['tour'])) $by_key['tour'] = $mk('Tour Packages', '/tour-packages/', 3);
    if (!isset($by_key['blog'])) $by_key['blog'] = $mk('Blog', '/blog/', 4);
    if (!isset($by_key['contact'])) $by_key['contact'] = $mk('Contact Us', '/contact/', 5);

    // Force order as requested
    return [
        $by_key['home'],
        $by_key['about'],
        $by_key['contact'],
        $by_key['tour'],
        $by_key['blog'],
    ];
}
add_filter('wp_nav_menu_objects', 'contenly_replace_travel_story_menu', 20, 2);

/**
 * Backward-compatible redirects for legacy/private leftovers.
 */
add_action('template_redirect', function () {
    $req = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
    $path = '/' . trim((string) $req, '/');
    if ('/' === $path || '' === trim((string) $req)) {
        return;
    }

    $lang = 0 === strpos($path, '/en/') ? 'en' : 'id';
    $normalized = $lang === 'en' ? '/' . ltrim(substr($path, 3), '/') : $path;
    $normalized = rtrim($normalized, '/');
    if ('' === $normalized) {
        $normalized = '/';
    }

    if (preg_match('#^/category/([^/]+)$#', $normalized, $matches)) {
        $target = contenly_localized_blog_category_url($matches[1], $lang);
        wp_redirect($target, 301);
        exit;
    }

    $redirect_map = [
        '/profile' => '/settings/',
        '/my-account' => '/dashboard/',
        '/my-bookings' => '/my-travels/',
        '/travel-dashboard' => '/dashboard/',
        '/daftar-travel' => '/register/',
        '/sample-page' => '/',
    ];

    if (!isset($redirect_map[$normalized])) {
        return;
    }

    $target = contenly_localized_url($redirect_map[$normalized], $lang);
    wp_redirect($target, 301);
    exit;
});


/**
 * AJAX: Update notification preferences
 */
function contenly_ajax_update_notifications() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Please login']);
    }
    $uid = get_current_user_id();
    update_user_meta($uid, '_notif_email', ($_POST['notif_email'] ?? '0') === '1' ? '1' : '0');
    update_user_meta($uid, '_notif_whatsapp', ($_POST['notif_whatsapp'] ?? '0') === '1' ? '1' : '0');
    update_user_meta($uid, '_notif_promo', ($_POST['notif_promo'] ?? '0') === '1' ? '1' : '0');
    wp_send_json_success(['message' => 'Notification preferences updated']);
}
add_action('wp_ajax_contenly_update_notifications', 'contenly_ajax_update_notifications');


/**
 * Canonical paid-like statuses for booking analytics/reporting.
 */
function contenly_paid_like_statuses() {
    return ['paid', 'confirmed', 'completed'];
}

/**
 * Canonical booking amount resolver.
 */
function contenly_booking_total_amount($booking_id) {
    $keys = ['_total_amount', '_total_price', '_price'];
    foreach ($keys as $k) {
        $v = get_post_meta($booking_id, $k, true);
        if ($v !== '' && $v !== null) return (float) $v;
    }
    return 0.0;
}

/**
 * Whale Dive phase 1 pretty URL fallbacks.
 * The host currently stores permalinks with /index.php/, so these two public pages
 * are routed explicitly until server-level rewrites are cleaned up.
 */
add_action('template_redirect', function () {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = preg_replace('#^index\.php/#', '', $path);
    $routes = array(
        'courses' => 'page-courses.php',
        'equipment' => 'page-equipment.php',
        'our-crew' => 'page-our-crew.php',
        'faq' => 'page-faq.php',
        'contact' => 'page-contact.php',
        'dashboard' => 'page-dashboard.php',
        'my-courses' => 'page-my-courses.php',
        'my-gear' => 'page-my-gear.php',
        'settings' => 'page-settings.php',
    );

    if (!isset($routes[$path])) {
        return;
    }

    $page = get_page_by_path($path);
    global $post, $wp_query;
    if ($page) {
        $post = $page;
        setup_postdata($post);
    } else {
        $post = (object) array(
            'ID' => 0,
            'post_author' => 0,
            'post_date' => current_time('mysql'),
            'post_date_gmt' => current_time('mysql', 1),
            'post_content' => '',
            'post_title' => ucwords(str_replace('-', ' ', $path)),
            'post_excerpt' => '',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_password' => '',
            'post_name' => $path,
            'to_ping' => '',
            'pinged' => '',
            'post_modified' => current_time('mysql'),
            'post_modified_gmt' => current_time('mysql', 1),
            'post_content_filtered' => '',
            'post_parent' => 0,
            'guid' => home_url('/' . $path . '/'),
            'menu_order' => 0,
            'post_type' => 'page',
            'post_mime_type' => '',
            'comment_count' => 0,
            'filter' => 'raw',
        );
        if ($wp_query) {
            $wp_query->post = $post;
            $wp_query->posts = array($post);
            $wp_query->queried_object = $post;
            $wp_query->queried_object_id = 0;
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
        }
    }

    status_header(200);
    nocache_headers();
    include get_stylesheet_directory() . '/' . $routes[$path];
    exit;
}, 0);


/**
 * Whale Dive favicon from existing logo asset.
 */
add_action('wp_head', function () {
    $icon = esc_url(get_stylesheet_directory_uri() . '/assets/brand/favicon-192.png');
    echo '<link rel="icon" href="' . $icon . '" type="image/jpeg">' . "\n";
    echo '<link rel="shortcut icon" href="' . $icon . '" type="image/jpeg">' . "\n";
}, 1);

add_filter('get_site_icon_url', function ($url) {
    return get_stylesheet_directory_uri() . '/assets/brand/favicon-192.png';
});


/**
 * Canonical public catalog URLs:
 * course  → /courses/{slug}/
 * equip   → /equipment/{slug}/
 * Legacy  → /course/ and /gear/ 301 to public paths.
 */
function wdc_catalog_public_permalink($permalink, $post) {
    if (!($post instanceof WP_Post)) {
        return $permalink;
    }
    if ($post->post_type === 'wm_course' && $post->post_name) {
        return home_url('/courses/' . $post->post_name . '/');
    }
    if ($post->post_type === 'wm_equipment' && $post->post_name) {
        return home_url('/equipment/' . $post->post_name . '/');
    }
    return $permalink;
}
add_filter('post_type_link', 'wdc_catalog_public_permalink', 15, 2);

add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', '/');
    $path = preg_replace('#^en/#', '', $path);
    if (preg_match('#^course/([^/]+)/?$#', $path, $m)) {
        wp_safe_redirect(home_url('/courses/' . $m[1] . '/'), 301);
        exit;
    }
    if (preg_match('#^gear/([^/]+)/?$#', $path, $m)) {
        wp_safe_redirect(home_url('/equipment/' . $m[1] . '/'), 301);
        exit;
    }
}, 0);

/**
 * Whale Dive course detail pretty routes.
 */
add_action('template_redirect', function () {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $course_slugs = array('open-water', 'advanced-open-water', 'rescue-diver', 'divemaster', 'instructor-course');

    if (strpos($path, 'courses/') !== 0) {
        return;
    }

    $slug = trim(substr($path, strlen('courses/')), '/');
    $course_post = get_page_by_path($slug, OBJECT, 'wm_course');
    if ($course_post) {
        global $post, $wp_query;
        $post = $course_post;
        setup_postdata($post);
        $wp_query->is_404 = false;
        $wp_query->is_single = true;
        $wp_query->is_singular = true;
        $wp_query->is_page = false;
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
        $wp_query->posts = array($course_post);
        $wp_query->post = $course_post;
        $wp_query->post_count = 1;
        $wp_query->found_posts = 1;
        $wp_query->current_post = -1;
        $wp_query->queried_object = $course_post;
        $wp_query->queried_object_id = $course_post->ID;
        status_header(200);
        nocache_headers();
        include get_stylesheet_directory() . '/single-wm_course.php';
        exit;
    }
    if (!in_array($slug, $course_slugs, true)) {
        return;
    }

    $GLOBALS['wd_course_slug'] = $slug;
    add_filter('pre_get_document_title', function () use ($slug) {
        $titles = array(
            'open-water' => 'Open Water Diver',
            'advanced-open-water' => 'Advanced Open Water',
            'rescue-diver' => 'Rescue Diver',
            'divemaster' => 'Divemaster',
            'instructor-course' => 'Instructor Course',
        );
        return ($titles[$slug] ?? 'Course Detail') . ' - Whale Dive Centre';
    });
    status_header(200);
    nocache_headers();
    include get_stylesheet_directory() . '/page-course-detail.php';
    exit;
}, 0);


/**
 * Whale Dive equipment detail pretty routes.
 * Prefer real product singles (wm_equipment) over legacy category pages.
 */
add_action('template_redirect', function () {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $gear_slugs = array('masks', 'wetsuits', 'bcd', 'regulators', 'fins', 'dive-computers');

    if (strpos($path, 'equipment/') !== 0) {
        return;
    }

    $slug = trim(substr($path, strlen('equipment/')), '/');
    if ($slug === '' || false !== strpos($slug, '/')) {
        return;
    }

    // Product single first: /equipment/{post_name}/
    $equipment_post = get_page_by_path($slug, OBJECT, 'wm_equipment');
    if ($equipment_post instanceof WP_Post) {
        global $post, $wp_query;
        $post = $equipment_post;
        setup_postdata($post);
        $wp_query->is_404 = false;
        $wp_query->is_single = true;
        $wp_query->is_singular = true;
        $wp_query->is_page = false;
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
        $wp_query->posts = array($equipment_post);
        $wp_query->post = $equipment_post;
        $wp_query->post_count = 1;
        $wp_query->found_posts = 1;
        $wp_query->current_post = -1;
        $wp_query->queried_object = $equipment_post;
        $wp_query->queried_object_id = $equipment_post->ID;
        status_header(200);
        nocache_headers();
        include get_stylesheet_directory() . '/single-wm_equipment.php';
        exit;
    }

    // Legacy category detail pages only.
    if (!in_array($slug, $gear_slugs, true)) {
        return;
    }

    $GLOBALS['wd_equipment_slug'] = $slug;
    global $wp_query;
    if ($wp_query instanceof WP_Query) {
        $wp_query->is_404 = false;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_single = false;
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
    }
    add_filter('pre_get_document_title', function () use ($slug) {
        $titles = array(
            'masks' => 'Masks',
            'wetsuits' => 'Wetsuits',
            'bcd' => 'BCD',
            'regulators' => 'Regulators',
            'fins' => 'Fins',
            'dive-computers' => 'Dive Computers',
        );
        return ($titles[$slug] ?? 'Equipment Detail') . ' - Whale Dive Centre';
    });
    status_header(200);
    nocache_headers();
    include get_stylesheet_directory() . '/page-equipment-detail.php';
    exit;
}, 0);

// Add to functions.php - New page routes for Blog, Gallery, Testimonials, Trips

// Blog archive route
add_action('init', function() {
    add_rewrite_rule('^blog/?$', 'index.php?pagename=blog', 'top');
    add_rewrite_rule('^blog/page/([0-9]+)/?$', 'index.php?pagename=blog&paged=$matches[1]', 'top');
});

// Testimonials route only (gallery/trips removed)
add_action('init', function() {
    add_rewrite_rule('^testimonials/?$', 'index.php?pagename=testimonials', 'top');
});

// Redirect removed public pages: gallery + trips
add_action('template_redirect', function () {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', '/');
    $path = preg_replace('#^en/#', '', $path);
    if (in_array($path, ['gallery', 'trips'], true)) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
}, 0);

// Template routing for remaining special pages
add_filter('template_include', function($template) {
    $pagename = get_query_var('pagename');
    if ($pagename === 'testimonials') {
        $new_template = locate_template(['whaledive-page-testimonials.php']);
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
});


// Redirect old pages to /about/
add_action('template_redirect', function() {
    if (is_page('contact') || is_page('our-crew') || is_page('faq')) {
        wp_redirect('/about/', 301);
        exit;
    }
});

// Redirect /login/ and /register/ to custom pages (before WP hijacks them)
add_action('init', function() {
    $uri = trim($_SERVER['REQUEST_URI'], '/');
    if ($uri === 'login') {
        wp_redirect(home_url('/member-login/'), 301);
        exit;
    }
    if ($uri === 'register') {
        wp_redirect(home_url('/member-register/'), 301);
        exit;
    }
}, 1);

// Override WP login/register URLs
add_filter('login_url', function($url, $redirect, $force_reauth) {
    return home_url('/member-login/');
}, 10, 3);
add_filter('register_url', function() {
    return home_url('/member-register/');
});

// Serve member templates even when the matching WP pages are not created yet.
function wdc_member_template_route_map() {
    return [
        'login' => 'page-login.php',
        'register' => 'page-register.php',
        'member-login' => 'page-login.php',
        'member-register' => 'page-register.php',
        'dashboard' => 'page-dashboard.php',
        'my-courses' => 'page-my-courses.php',
        'my-gear' => 'page-my-gear.php',
        'checkout' => 'page-checkout.php',
        'direct-checkout' => 'page-direct-checkout.php',
        'settings' => 'page-settings.php',
    ];
}

function wdc_current_clean_path() {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = preg_replace('#^index\.php/#', '', $path);

    // Normalize language-prefixed routes from multilingual plugins/localized URLs.
    if (preg_match('#^[a-z]{2}(?:-[a-z]{2})?/(.+)$#i', $path, $matches)) {
        $path = $matches[1];
    }

    return $path;
}

add_action('template_redirect', function () {
    $legacy_member_routes = [
        'my-travels' => '/my-courses/',
        'my-bookings' => '/my-courses/',
        'wishlist' => '/my-gear/',
        'membership' => '/dashboard/',
        'reviews' => '/dashboard/',
        'rewards' => '/dashboard/',
        'notifications' => '/dashboard/',
        'profile' => '/settings/',
    ];
    $path = wdc_current_clean_path();
    if (isset($legacy_member_routes[$path])) {
        wp_redirect(home_url($legacy_member_routes[$path]), 301);
        exit;
    }
}, 0);

add_filter('pre_handle_404', function($preempt, $wp_query) {
    $path = wdc_current_clean_path();
    if (isset(wdc_member_template_route_map()[$path])) {
        $source_page = get_page_by_path($path) ?: get_page_by_path('sample-page') ?: get_page_by_path('about');
        if ($source_page) {
            $wp_query->queried_object = $source_page;
            $wp_query->queried_object_id = $source_page->ID;
            $wp_query->post = $source_page;
            $wp_query->posts = [$source_page];
            $wp_query->post_count = 1;
        }
        $wp_query->is_404 = false;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        status_header(200);
        return true;
    }
    return $preempt;
}, 0, 2);

add_filter('template_include', function($template) {
    $path = wdc_current_clean_path();
    $map = wdc_member_template_route_map();
    if (isset($map[$path])) {
        $candidate = get_stylesheet_directory() . '/' . $map[$path];
        if (file_exists($candidate)) {
            global $wp_query, $post;
            $source_page = get_page_by_path($path) ?: get_page_by_path('sample-page') ?: get_page_by_path('about');
            if ($source_page) {
                $post = $source_page;
                setup_postdata($post);
            }
            if ($wp_query) {
                $wp_query->is_404 = false;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
            }
            status_header(200);
            return $candidate;
        }
    }
    return $template;
}, 0);

// Force our direct-checkout template at high priority (999) so it wins over
// any plugin (e.g. whale-membership) that overrides the checkout template.
add_filter('template_include', function($template) {
    if (isset($_GET['type'], $_GET['item']) && in_array(sanitize_key($_GET['type']), ['course', 'equipment'], true)) {
        $candidate = get_stylesheet_directory() . '/page-direct-checkout.php';
        if (file_exists($candidate)) {
            status_header(200);
            return $candidate;
        }
    }
    return $template;
}, 999);

add_filter('pre_get_document_title', function($title) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($path === 'member-login') {
        return 'Member Login - Whale Dive Centre';
    }
    if ($path === 'member-register') {
        return 'Member Register - Whale Dive Centre';
    }
    return $title;
});



/**
 * Member commerce helpers for direct checkout and admin fulfilment.
 */
function wdc_member_direct_order_meta_key($type) {
    return $type === 'equipment' ? '_wdc_gear_orders' : '_wdc_course_orders';
}

function wdc_member_request_meta_key($type) {
    return $type === 'equipment' ? '_wdc_gear_requests' : '_wdc_course_requests';
}


/**
 * Member-gated action URLs for course/equipment CTAs.
 * Public catalog shows price; Daftar/Beli requires login then request form.
 */
function wdc_member_action_url($type, $item_id = 0, $item_title = '', $extra = []) {
    $type = $type === 'equipment' ? 'equipment' : 'course';
    $item_id = absint($item_id);
    $item_title = is_string($item_title) ? $item_title : '';

    $base = $type === 'equipment'
        ? (function_exists('contenly_localized_url') ? contenly_localized_url('/my-gear/') : home_url('/my-gear/'))
        : (function_exists('contenly_localized_url') ? contenly_localized_url('/my-courses/') : home_url('/my-courses/'));

    $args = ['request' => '1'];
    if ($item_id) {
        $args['item_id'] = $item_id;
    }
    if ($item_title !== '') {
        $args['item'] = $item_title;
    }
    if (is_array($extra) && $extra) {
        $args = array_merge($args, $extra);
    }
    $target = add_query_arg($args, $base);

    if (is_user_logged_in()) {
        return $target;
    }

    $login = function_exists('contenly_localized_url') ? contenly_localized_url('/member-login/') : home_url('/member-login/');
    // Encode full target so nested query args survive HTML + login redirect.
    return $login . (strpos($login, '?') === false ? '?' : '&') . 'redirect_to=' . rawurlencode($target);
}


function wdc_member_status_options() {
    return ['Requested', 'Awaiting Payment', 'Payment Uploaded', 'Verified', 'Active', 'Completed', 'Cancelled'];
}

function wdc_send_member_commerce_email($user_id, $subject, $message) {
    $user = get_userdata($user_id);
    if (!$user || empty($user->user_email)) {
        return false;
    }
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    return wp_mail($user->user_email, $subject, wpautop($message), $headers);
}

function wdc_send_admin_commerce_email($subject, $message) {
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    $to = get_option('admin_email');
    if (function_exists('wdc_request_notify_recipients')) {
        $recipients = wdc_request_notify_recipients();
        if ($recipients) {
            $to = $recipients;
        }
    }
    return wp_mail($to, $subject, wpautop($message), $headers);
}

function wdc_find_equipment_post_by_title($title) {
    if (!$title || !post_type_exists('wm_equipment')) {
        return 0;
    }
    $post = get_page_by_title($title, OBJECT, 'wm_equipment');
    return $post ? (int) $post->ID : 0;
}

function wdc_get_equipment_post_id_from_record($record) {
    $post_id = absint($record['item_id'] ?? 0);
    if ($post_id && get_post_type($post_id) === 'wm_equipment') {
        return $post_id;
    }
    $item_title = $record['gear'] ?? $record['item'] ?? '';
    return wdc_find_equipment_post_by_title($item_title);
}

function wdc_order_consumes_gear_stock($status) {
    return in_array($status, ['Verified', 'Active'], true);
}

function wdc_maybe_adjust_gear_stock($record, $old_status, $new_status) {
    $old_consumes = wdc_order_consumes_gear_stock($old_status);
    $new_consumes = wdc_order_consumes_gear_stock($new_status);
    if ($old_consumes === $new_consumes) {
        return;
    }
    $post_id = wdc_get_equipment_post_id_from_record($record);
    if (!$post_id) {
        return;
    }
    $stock = get_post_meta($post_id, '_wm_stock', true);
    if ($stock === '' || !is_numeric($stock)) {
        return;
    }
    $delta = $new_consumes ? -1 : 1;
    update_post_meta($post_id, '_wm_stock', max(0, (int) $stock + $delta));
}

function wdc_equipment_stock_available($item_id) {
    $item_id = absint($item_id);
    if (!$item_id || get_post_type($item_id) !== 'wm_equipment') {
        return true;
    }
    $stock = get_post_meta($item_id, '_wm_stock', true);
    return $stock === '' || !is_numeric($stock) || (int) $stock > 0;
}

function wdc_member_admin_pending_count($type, $bucket) {
    $count = 0;
    foreach (wdc_collect_member_records($type, $bucket) as $record) {
        $status = $record['item']['status'] ?? 'Requested';
        if (in_array($status, ['Requested', 'Awaiting Payment', 'Payment Uploaded'], true)) {
            $count++;
        }
    }
    return $count;
}

function wdc_admin_menu_badge($count) {
    return $count > 0 ? ' <span class="awaiting-mod">' . esc_html($count) . '</span>' : '';
}

function wdc_ajax_save_direct_checkout() {
    check_ajax_referer('wdc_member_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Please login first.'], 401);
    }

    $type = sanitize_key(wp_unslash($_POST['direct_type'] ?? ''));
    if (!in_array($type, ['course', 'equipment'], true)) {
        wp_send_json_error(['message' => 'Invalid order type.'], 400);
    }

    $item = sanitize_text_field(wp_unslash($_POST['direct_item'] ?? ''));
    $item_id = absint($_POST['direct_item_id'] ?? 0);
    $expected_type = $type === 'course' ? 'wm_course' : 'wm_equipment';
    if ($item_id && get_post_type($item_id) !== $expected_type) {
        wp_send_json_error(['message' => 'Invalid catalog item.'], 400);
    }
    if ($type === 'equipment' && !wdc_equipment_stock_available($item_id)) {
        wp_send_json_error(['message' => 'This gear is out of stock. Please request availability help.'], 409);
    }
    $price = (float) sanitize_text_field(wp_unslash($_POST['direct_price'] ?? '0'));
    $notes = sanitize_textarea_field(wp_unslash($_POST['payment_notes'] ?? ''));
    $proof_url = '';
    if (!empty($_FILES['payment_proof'])) {
        $file = $_FILES['payment_proof'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'] ?? '', $allowed_types, true)) {
            wp_send_json_error(['message' => 'Invalid file type. Upload JPG, PNG, GIF, or WEBP.'], 400);
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            wp_send_json_error(['message' => 'File too large. Max 5MB.'], 400);
        }
        $upload_dir = wp_upload_dir();
        $proof_dir = trailingslashit($upload_dir['basedir']) . 'wdc-payment-proofs/';
        if (!file_exists($proof_dir)) {
            wp_mkdir_p($proof_dir);
        }
        $filename = $order_safe_name = time() . '-' . wp_generate_password(6, false, false) . '-' . sanitize_file_name($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $proof_dir . $filename)) {
            wp_send_json_error(['message' => 'Failed to upload payment proof.'], 500);
        }
        $proof_url = trailingslashit($upload_dir['baseurl']) . 'wdc-payment-proofs/' . $filename;
    }
    if (!$item) {
        wp_send_json_error(['message' => 'Missing item.'], 400);
    }

    $user_id = get_current_user_id();
    $meta_key = wdc_member_direct_order_meta_key($type);
    $orders = get_user_meta($user_id, $meta_key, true);
    $orders = is_array($orders) ? $orders : [];
    $order_id = 'WDC-' . strtoupper($type[0]) . '-' . current_time('YmdHis') . '-' . $user_id;

    array_unshift($orders, [
        'id' => $order_id,
        'item_id' => $item_id,
        'item' => $item,
        'price' => $price,
        'notes' => $notes,
        'payment_proof_url' => $proof_url,
        'status' => 'Payment Uploaded',
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ]);

    update_user_meta($user_id, $meta_key, array_slice($orders, 0, 25));

    wdc_send_member_commerce_email($user_id, 'Payment proof received - ' . $order_id, 'Thanks. We received your payment proof for <strong>' . esc_html($item) . '</strong>. The crew will verify it soon.');
    wdc_send_admin_commerce_email('New WDC direct order - ' . $order_id, 'A member uploaded payment proof for <strong>' . esc_html($item) . '</strong>.<br>Open WDC Members > Direct Orders to verify it.');

    wp_send_json_success(['order_id' => $order_id]);
}
add_action('wp_ajax_wdc_save_direct_checkout', 'wdc_ajax_save_direct_checkout');

function wdc_font_mode() {
    $mode = get_option('wdc_font_mode', 'current');
    return in_array($mode, ['current', 'brand'], true) ? $mode : 'current';
}

function wdc_is_brand_font_mode() {
    return wdc_font_mode() === 'brand';
}

function wdc_body_font_mode_class($classes) {
    $classes[] = wdc_is_brand_font_mode() ? 'wdc-brand-font-mode' : 'wdc-current-font-mode';
    return $classes;
}
add_filter('body_class', 'wdc_body_font_mode_class');
add_filter('admin_body_class', function($classes) {
    return trim($classes . ' ' . (wdc_is_brand_font_mode() ? 'wdc-brand-font-mode' : 'wdc-current-font-mode'));
});

function wdc_enqueue_brand_font_assets() {
    if (!wdc_is_brand_font_mode()) {
        return;
    }
    wp_enqueue_style('wdc-brand-open-sans', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap', [], null);
}
add_action('wp_enqueue_scripts', 'wdc_enqueue_brand_font_assets');
add_action('admin_enqueue_scripts', 'wdc_enqueue_brand_font_assets');

function wdc_render_font_mode_css() {
    if (!wdc_is_brand_font_mode()) {
        return;
    }
    ?>
    <style id="wdc-brand-font-mode-css">
        @font-face { font-family: 'iBrand'; src: url('<?php echo esc_url(get_template_directory_uri() . '/assets/fonts/ibrand.otf'); ?>') format('opentype'); font-weight: 400; font-style: normal; font-display: swap; }
        body.wdc-brand-font-mode, body.wdc-brand-font-mode input, body.wdc-brand-font-mode select, body.wdc-brand-font-mode textarea, body.wdc-brand-font-mode button { font-family: 'Open Sans', Arial, sans-serif !important; }
        body.wdc-brand-font-mode h1, body.wdc-brand-font-mode h2, body.wdc-brand-font-mode h3, body.wdc-brand-font-mode .wd-title, body.wdc-brand-font-mode .wd-brand span, body.wdc-brand-font-mode .page-title { font-family: 'iBrand', 'Open Sans', Arial, sans-serif !important; font-weight: 400; }
    </style>
    <?php
}
add_action('wp_head', 'wdc_render_font_mode_css', 99);
add_action('admin_head', 'wdc_render_font_mode_css', 99);

function wdc_handle_font_mode_update() {
    if (!current_user_can('manage_options')) {
        wp_die('Not allowed');
    }
    check_admin_referer('wdc_font_mode_update');
    $mode = sanitize_key(wp_unslash($_POST['wdc_font_mode'] ?? 'current'));
    update_option('wdc_font_mode', $mode === 'brand' ? 'brand' : 'current');
    wp_safe_redirect(add_query_arg(['page' => 'wdc-member-admin', 'font-updated' => '1'], admin_url('admin.php')));
    exit;
}
add_action('admin_post_wdc_update_font_mode', 'wdc_handle_font_mode_update');

function wdc_register_member_admin_menu() {
    add_menu_page('WDC Members', 'WDC Members', 'manage_options', 'wdc-member-admin', 'wdc_render_member_admin_dashboard', 'dashicons-groups', 30);
    add_submenu_page('wdc-member-admin', 'Course Requests', 'Course Requests' . wdc_admin_menu_badge(wdc_member_admin_pending_count('course', 'requests')), 'manage_options', 'wdc-course-requests', 'wdc_render_course_admin_page');
    add_submenu_page('wdc-member-admin', 'Gear Requests', 'Gear Requests' . wdc_admin_menu_badge(wdc_member_admin_pending_count('equipment', 'requests')), 'manage_options', 'wdc-gear-requests', 'wdc_render_gear_admin_page');
    $direct_pending = wdc_member_admin_pending_count('course', 'orders') + wdc_member_admin_pending_count('equipment', 'orders');
    add_submenu_page('wdc-member-admin', 'Direct Orders', 'Direct Orders' . wdc_admin_menu_badge($direct_pending), 'manage_options', 'wdc-direct-orders', 'wdc_render_direct_orders_admin_page');
    add_submenu_page('wdc-member-admin', 'Payment Settings', 'Payment Settings', 'manage_options', 'wdc-payment-settings', 'wdc_render_payment_settings_page');
}
add_action('admin_menu', 'wdc_register_member_admin_menu');

// Hide unnecessary menus — Comments (0 comments), Tools (only converter)
function wdc_cleanup_admin_menus() {
    remove_menu_page('edit-comments.php');  // Comments
    remove_menu_page('tools.php');          // Tools
}
add_action('admin_menu', 'wdc_cleanup_admin_menus', 999);

// Hide Whale Members menu — handled directly in plugin class-wm-admin.php
// Payment Settings page — reads/writes same options as whale-membership plugin
function wdc_render_payment_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Not allowed');
    }

    // Handle bank account updates
    if (isset($_POST['wdc_save_bank_accounts']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'] ?? '')), 'wdc_bank_accounts')) {
        $accounts = [];
        if (!empty($_POST['bank_name']) && is_array($_POST['bank_name'])) {
            foreach ($_POST['bank_name'] as $i => $name) {
                if (!empty($name)) {
                    $accounts[] = [
                        'bank' => sanitize_text_field($name),
                        'account_name' => sanitize_text_field($_POST['account_name'][$i] ?? ''),
                        'account_number' => sanitize_text_field($_POST['account_number'][$i] ?? ''),
                    ];
                }
            }
        }
        update_option('wm_bank_accounts', $accounts);
        echo '<div class="notice notice-success"><p>Bank accounts saved.</p></div>';
    }

    // Handle Midtrans settings
    if (isset($_POST['wdc_save_settings']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'] ?? '')), 'wdc_payment_settings')) {
        update_option('wm_midtrans_server_key', sanitize_text_field($_POST['wm_midtrans_server_key'] ?? ''));
        update_option('wm_midtrans_client_key', sanitize_text_field($_POST['wm_midtrans_client_key'] ?? ''));
        update_option('wm_midtrans_production', !empty($_POST['wm_midtrans_production']));
        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }

    echo '<div class="wrap">';
    echo '<h1>Payment Settings</h1>';

    // Midtrans Section
    echo '<h2 style="margin-top:1.5rem">Midtrans Payment Gateway</h2>';
    echo '<form method="post">';
    wp_nonce_field('wdc_payment_settings');
    echo '<table class="form-table"><tbody>';
    echo '<tr><th>Server Key</th><td><input type="text" name="wm_midtrans_server_key" value="' . esc_attr(get_option('wm_midtrans_server_key', '')) . '" class="regular-text"></td></tr>';
    echo '<tr><th>Client Key</th><td><input type="text" name="wm_midtrans_client_key" value="' . esc_attr(get_option('wm_midtrans_client_key', '')) . '" class="regular-text"></td></tr>';
    echo '<tr><th>Production Mode</th><td><label><input type="checkbox" name="wm_midtrans_production" value="1"' . checked(get_option('wm_midtrans_production'), true, false) . '> Enable production (live payments)</label></td></tr>';
    echo '</tbody></table>';
    echo '<p><button type="submit" name="wdc_save_settings" value="1" class="button button-primary">Save Settings</button></p>';
    echo '</form>';

    // Bank Accounts Section
    $bank_accounts = get_option('wm_bank_accounts', [
        ['bank' => 'BCA', 'account_name' => 'Whale Dive Centre', 'account_number' => '1234567890'],
        ['bank' => 'Mandiri', 'account_name' => 'Whale Dive Centre', 'account_number' => '9876543210'],
    ]);

    echo '<h2 style="margin-top:2rem">Bank Accounts (for Manual Payments)</h2>';
    echo '<form method="post">';
    wp_nonce_field('wdc_bank_accounts');
    echo '<table class="form-table"><tbody>';

    for ($i = 0; $i < 3; $i++) {
        $account = $bank_accounts[$i] ?? ['bank' => '', 'account_name' => '', 'account_number' => ''];
        echo '<tr><th>Bank ' . ($i + 1) . '</th><td>';
        echo '<input type="text" name="bank_name[]" value="' . esc_attr($account['bank']) . '" placeholder="Bank name (BCA, Mandiri...)" style="width:150px;margin-right:10px">';
        echo '<input type="text" name="account_number[]" value="' . esc_attr($account['account_number']) . '" placeholder="Account number" style="width:180px;margin-right:10px">';
        echo '<input type="text" name="account_name[]" value="' . esc_attr($account['account_name']) . '" placeholder="Account holder name" style="width:200px">';
        echo '</td></tr>';
    }

    echo '</tbody></table>';
    echo '<p><button type="submit" name="wdc_save_bank_accounts" value="1" class="button button-primary">Save Bank Accounts</button></p>';
    echo '</form>';
    echo '</div>';
}

function wdc_member_admin_handle_update() {
    if (!current_user_can('manage_options') || empty($_POST['wdc_member_admin_nonce'])) {
        return;
    }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_member_admin_nonce'])), 'wdc_member_admin_update')) {
        return;
    }

    $user_id = absint($_POST['user_id'] ?? 0);
    $type = sanitize_key(wp_unslash($_POST['record_type'] ?? ''));
    $bucket = sanitize_key(wp_unslash($_POST['bucket'] ?? 'requests'));
    $index = absint($_POST['record_index'] ?? 0);
    $status = sanitize_text_field(wp_unslash($_POST['status'] ?? ''));
    $admin_note = sanitize_textarea_field(wp_unslash($_POST['admin_note'] ?? ''));

    if (!in_array($status, wdc_member_status_options(), true)) {
        return;
    }

    if (!empty($_POST['bulk_apply']) && !empty($_POST['bulk_records']) && is_array($_POST['bulk_records'])) {
        foreach ($_POST['bulk_records'] as $packed) {
            $parts = explode('|', sanitize_text_field(wp_unslash($packed)));
            if (count($parts) !== 4) {
                continue;
            }
            [$bulk_user_id, $bulk_type, $bulk_bucket, $bulk_index] = $parts;
            $bulk_user_id = absint($bulk_user_id);
            $bulk_type = sanitize_key($bulk_type);
            $bulk_bucket = sanitize_key($bulk_bucket);
            $bulk_index = absint($bulk_index);
            if (!in_array($bulk_type, ['course', 'equipment'], true)) {
                continue;
            }
            $bulk_meta_key = $bulk_bucket === 'orders' ? wdc_member_direct_order_meta_key($bulk_type) : wdc_member_request_meta_key($bulk_type);
            $bulk_records = get_user_meta($bulk_user_id, $bulk_meta_key, true);
            if (!is_array($bulk_records) || !isset($bulk_records[$bulk_index])) {
                continue;
            }
            $old_status = $bulk_records[$bulk_index]['status'] ?? 'Requested';
            wdc_maybe_adjust_gear_stock($bulk_records[$bulk_index], $old_status, $status);
            $bulk_records[$bulk_index]['status'] = $status;
            $bulk_records[$bulk_index]['admin_note'] = $admin_note;
            $bulk_records[$bulk_index]['updated_at'] = current_time('mysql');
            update_user_meta($bulk_user_id, $bulk_meta_key, $bulk_records);
        }
        wp_safe_redirect(add_query_arg(['updated' => '1'], wp_get_referer() ?: admin_url('admin.php?page=wdc-member-admin')));
        exit;
    }

    if (!$user_id || !in_array($type, ['course', 'equipment'], true)) {
        return;
    }

    $meta_key = $bucket === 'orders' ? wdc_member_direct_order_meta_key($type) : wdc_member_request_meta_key($type);
    $records = get_user_meta($user_id, $meta_key, true);
    $records = is_array($records) ? $records : [];
    if (!isset($records[$index])) {
        return;
    }

    $old_status = $records[$index]['status'] ?? 'Requested';
    wdc_maybe_adjust_gear_stock($records[$index], $old_status, $status);

    $records[$index]['status'] = $status;
    $records[$index]['admin_note'] = $admin_note;
    $records[$index]['updated_at'] = current_time('mysql');
    update_user_meta($user_id, $meta_key, $records);

    if ($old_status !== $status) {
        $item_label = $records[$index]['course'] ?? $records[$index]['gear'] ?? $records[$index]['item'] ?? 'your WDC item';
        wdc_send_member_commerce_email($user_id, 'WDC status updated: ' . $status, 'Your status for <strong>' . esc_html($item_label) . '</strong> is now <strong>' . esc_html($status) . '</strong>.' . ($admin_note ? '<br><br>Note from crew: ' . esc_html($admin_note) : ''));
    }

    wp_safe_redirect(add_query_arg(['updated' => '1'], wp_get_referer() ?: admin_url('admin.php?page=wdc-member-admin')));
    exit;
}
add_action('admin_init', 'wdc_member_admin_handle_update');

function wdc_collect_member_records($type, $bucket) {
    $meta_key = $bucket === 'orders' ? wdc_member_direct_order_meta_key($type) : wdc_member_request_meta_key($type);
    $records = [];
    foreach (get_users(['fields' => ['ID', 'display_name', 'user_email']]) as $user) {
        $items = get_user_meta($user->ID, $meta_key, true);
        if (!is_array($items)) {
            continue;
        }
        foreach ($items as $index => $item) {
            $records[] = ['user' => $user, 'index' => $index, 'item' => $item, 'type' => $type, 'bucket' => $bucket];
        }
    }
    usort($records, function($a, $b) {
        return strcmp($b['item']['created_at'] ?? '', $a['item']['created_at'] ?? '');
    });
    return $records;
}

function wdc_render_low_stock_notice() {
    if (!post_type_exists('wm_equipment')) {
        return;
    }
    $low_stock = get_posts(['post_type' => 'wm_equipment', 'numberposts' => 8, 'post_status' => 'publish', 'meta_query' => [['key' => '_wm_stock', 'value' => 3, 'type' => 'NUMERIC', 'compare' => '<=']]]);
    if (!$low_stock) {
        return;
    }
    echo '<div class="notice notice-warning" style="margin:18px 0 0;"><p><strong>Low stock alert:</strong> ';
    $items = [];
    foreach ($low_stock as $post) {
        $items[] = '<a href="' . esc_url(get_edit_post_link($post->ID)) . '">' . esc_html($post->post_title) . '</a> (' . esc_html(get_post_meta($post->ID, '_wm_stock', true)) . ')';
    }
    echo wp_kses_post(implode(', ', $items));
    echo '</p></div>';
}

function wdc_render_member_admin_dashboard() {
    $course_requests = wdc_collect_member_records('course', 'requests');
    $gear_requests = wdc_collect_member_records('equipment', 'requests');
    $course_orders = wdc_collect_member_records('course', 'orders');
    $gear_orders = wdc_collect_member_records('equipment', 'orders');
    ?>
    <div class="wrap"><h1>WDC Member Admin</h1>
        <?php if (!empty($_GET['font-updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p>WDC font mode updated.</p></div>
        <?php endif; ?>
        <div style="background:#fff;border:1px solid #dcdcde;border-left:4px solid #004A98;border-radius:12px;padding:18px;margin:18px 0 20px;max-width:900px;">
            <h2 style="margin:0 0 8px;font-size:18px;">Brand Font Switch</h2>
            <p style="margin:0 0 14px;color:#64748b;">Switch website and member dashboard typography. Current keeps the existing font; Brand Guideline uses Open Sans body and attempts iBrand for headings when available.</p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                <?php wp_nonce_field('wdc_font_mode_update'); ?>
                <input type="hidden" name="action" value="wdc_update_font_mode">
                <label style="display:inline-flex;gap:6px;align-items:center;"><input type="radio" name="wdc_font_mode" value="current" <?php checked(wdc_font_mode(), 'current'); ?>> Keep current font</label>
                <label style="display:inline-flex;gap:6px;align-items:center;"><input type="radio" name="wdc_font_mode" value="brand" <?php checked(wdc_font_mode(), 'brand'); ?>> Brand Guideline font</label>
                <?php submit_button('Save Font Mode', 'primary', 'submit', false); ?>
            </form>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin:20px 0;">
            <?php foreach ([['Course Requests', count($course_requests)], ['Gear Requests', count($gear_requests)], ['Course Orders', count($course_orders)], ['Gear Orders', count($gear_orders)]] as $card) : ?>
            <div style="background:#fff;border:1px solid #dcdcde;border-radius:12px;padding:18px;"><strong><?php echo esc_html($card[0]); ?></strong><div style="font-size:32px;font-weight:800;margin-top:8px;"><?php echo esc_html($card[1]); ?></div></div>
            <?php endforeach; ?>
        </div>
        <?php wdc_render_low_stock_notice(); ?>
        <p>Use the submenu to verify payments, approve course access, update gear fulfilment, and leave notes visible to members.</p>
    </div>
    <?php
}

function wdc_status_badge_style($status) {
    $colors = [
        'Requested' => '#0b617c;background:#e8f8fc',
        'Awaiting Payment' => '#92400e;background:#fef3c7',
        'Payment Uploaded' => '#1d4ed8;background:#dbeafe',
        'Verified' => '#047857;background:#d1fae5',
        'Active' => '#166534;background:#dcfce7',
        'Completed' => '#334155;background:#e2e8f0',
        'Cancelled' => '#991b1b;background:#fee2e2',
    ];
    return $colors[$status] ?? '#334155;background:#e2e8f0';
}

function wdc_record_matches_admin_filters($record) {
    $status_filter = sanitize_text_field(wp_unslash($_GET['status_filter'] ?? ''));
    $search = strtolower(sanitize_text_field(wp_unslash($_GET['s'] ?? '')));
    $item = $record['item'];
    if ($status_filter && ($item['status'] ?? 'Requested') !== $status_filter) {
        return false;
    }
    if ($search) {
        $haystack = strtolower(implode(' ', [
            $record['user']->display_name,
            $record['user']->user_email,
            $item['course'] ?? '',
            $item['gear'] ?? '',
            $item['item'] ?? '',
            $item['id'] ?? '',
        ]));
        return strpos($haystack, $search) !== false;
    }
    return true;
}

function wdc_render_member_records_table($records, $title) {
    $records = array_values(array_filter($records, 'wdc_record_matches_admin_filters'));
    ?>
    <div class="wrap"><h1><?php echo esc_html($title); ?></h1>
    <?php if (!empty($_GET['updated'])) : ?><div class="notice notice-success"><p>Status updated.</p></div><?php endif; ?>
    <form method="get" style="display:flex;gap:10px;align-items:center;margin:16px 0;background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:12px;">
        <input type="hidden" name="page" value="<?php echo esc_attr(sanitize_key($_GET['page'] ?? 'wdc-member-admin')); ?>">
        <input type="search" name="s" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_GET['s'] ?? ''))); ?>" placeholder="Search member or item" style="min-width:260px;">
        <select name="status_filter"><option value="">All statuses</option><?php foreach (wdc_member_status_options() as $status) : ?><option value="<?php echo esc_attr($status); ?>" <?php selected(sanitize_text_field(wp_unslash($_GET['status_filter'] ?? '')), $status); ?>><?php echo esc_html($status); ?></option><?php endforeach; ?></select>
        <button class="button">Filter</button><a class="button" href="<?php echo esc_url(admin_url('admin.php?page=' . sanitize_key($_GET['page'] ?? 'wdc-member-admin'))); ?>">Reset</a>
    </form>
    <form id="wdc-bulk-admin" method="post" style="display:flex;gap:8px;align-items:center;margin:12px 0;">
        <?php wp_nonce_field('wdc_member_admin_update', 'wdc_member_admin_nonce'); ?>
        <input type="hidden" name="bulk_apply" value="1">
        <select name="status" required><option value="">Change status to...</option><?php foreach (wdc_member_status_options() as $status) : ?><option value="<?php echo esc_attr($status); ?>"><?php echo esc_html($status); ?></option><?php endforeach; ?></select>
        <input type="text" name="admin_note" placeholder="Optional bulk note" style="min-width:260px;">
        <button class="button button-primary">Apply to selected</button>
    </form>
    <table class="widefat striped" style="margin-top:16px;"><thead><tr><th><input type="checkbox" onclick="document.querySelectorAll('.wdc-bulk-record').forEach(function(cb){cb.checked=event.target.checked;});"></th><th>Date</th><th>Member</th><th>Item</th><th>Details</th><th>Status</th><th>Proof</th><th>Admin Note</th><th>Action</th></tr></thead><tbody>
    <?php if (!$records) : ?><tr><td colspan="9">No records yet.</td></tr><?php endif; ?>
    <?php foreach ($records as $record) : $item = $record['item']; $current_status = $item['status'] ?? 'Requested'; $item_label = $item['course'] ?? $item['gear'] ?? $item['item'] ?? 'Item'; $item_post_id = absint($item['item_id'] ?? 0); $item_link = $item_post_id ? get_edit_post_link($item_post_id) : ''; $row_form_id = 'wdc-row-admin-' . sanitize_key($record['user']->ID . '-' . $record['type'] . '-' . $record['bucket'] . '-' . $record['index']); ?>
        <form id="<?php echo esc_attr($row_form_id); ?>" method="post"></form>
        <tr>
            <td><input class="wdc-bulk-record" form="wdc-bulk-admin" type="checkbox" name="bulk_records[]" value="<?php echo esc_attr($record['user']->ID . '|' . $record['type'] . '|' . $record['bucket'] . '|' . $record['index']); ?>"></td>
            <td><?php echo esc_html($item['created_at'] ?? '-'); ?></td>
            <td><strong><?php echo esc_html($record['user']->display_name); ?></strong><br><small><?php echo esc_html($record['user']->user_email); ?></small></td>
            <td><strong><?php if ($item_link) : ?><a href="<?php echo esc_url($item_link); ?>"><?php echo esc_html($item_label); ?></a><?php else : ?><?php echo esc_html($item_label); ?><?php endif; ?></strong><br><small><?php echo esc_html($item['id'] ?? ($item['created_at'] ?? '')); ?></small></td>
            <td><?php echo esc_html($item['preferred_date'] ?? $item['request_type'] ?? (!empty($item['price']) ? 'Rp ' . number_format((float) $item['price'], 0, ',', '.') : 'Direct order')); ?><br><small><?php echo esc_html($item['message'] ?? $item['notes'] ?? $item['size_notes'] ?? ''); ?></small></td>
            <td><span style="display:inline-flex;padding:5px 9px;border-radius:999px;font-weight:700;<?php echo esc_attr(wdc_status_badge_style($current_status)); ?>"><?php echo esc_html($current_status); ?></span><br><select form="<?php echo esc_attr($row_form_id); ?>" name="status" style="margin-top:8px;"><?php foreach (wdc_member_status_options() as $status) : ?><option value="<?php echo esc_attr($status); ?>" <?php selected($current_status, $status); ?>><?php echo esc_html($status); ?></option><?php endforeach; ?></select></td>
            <td><?php if (!empty($item['payment_proof_url'])) : ?><a class="button" href="<?php echo esc_url($item['payment_proof_url']); ?>" target="_blank" rel="noopener">View Proof</a><?php else : ?><span style="color:#64748b;">No proof</span><?php endif; ?></td>
            <td><textarea form="<?php echo esc_attr($row_form_id); ?>" name="admin_note" rows="2" style="width:100%;"><?php echo esc_textarea($item['admin_note'] ?? ''); ?></textarea></td>
            <td><?php echo str_replace('<input ', '<input form="' . esc_attr($row_form_id) . '" ', wp_nonce_field('wdc_member_admin_update', 'wdc_member_admin_nonce', true, false)); ?><input form="<?php echo esc_attr($row_form_id); ?>" type="hidden" name="user_id" value="<?php echo esc_attr($record['user']->ID); ?>"><input form="<?php echo esc_attr($row_form_id); ?>" type="hidden" name="record_type" value="<?php echo esc_attr($record['type']); ?>"><input form="<?php echo esc_attr($row_form_id); ?>" type="hidden" name="bucket" value="<?php echo esc_attr($record['bucket']); ?>"><input form="<?php echo esc_attr($row_form_id); ?>" type="hidden" name="record_index" value="<?php echo esc_attr($record['index']); ?>"><button form="<?php echo esc_attr($row_form_id); ?>" class="button button-primary">Save</button><div style="margin-top:8px;display:flex;gap:4px;flex-wrap:wrap;"><button form="<?php echo esc_attr($row_form_id); ?>" class="button button-small" name="status" value="Verified">Verify</button><button form="<?php echo esc_attr($row_form_id); ?>" class="button button-small" name="status" value="Active">Activate</button><button form="<?php echo esc_attr($row_form_id); ?>" class="button button-small" name="status" value="Cancelled">Cancel</button></div></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <?php
}

function wdc_render_course_admin_page() { wdc_render_member_records_table(wdc_collect_member_records('course', 'requests'), 'Course Requests'); }
function wdc_render_gear_admin_page() { wdc_render_member_records_table(wdc_collect_member_records('equipment', 'requests'), 'Gear Requests'); }
function wdc_render_direct_orders_admin_page() {
    wdc_render_member_records_table(array_merge(wdc_collect_member_records('course', 'orders'), wdc_collect_member_records('equipment', 'orders')), 'Direct Course / Gear Orders');
}

/**
 * WDC editable content and catalog admin helpers.
 */
function wdc_content_defaults() {
    return [
        'hero_image_id' => 0,
        'hero_eyebrow_id' => 'Curated trip planner untuk domestik & internasional',
        'hero_eyebrow_en' => 'Curated trip planner for domestic and international journeys',
        'hero_title_id' => 'Liburan Rapi, Berangkat Pasti',
        'hero_title_en' => 'Plan Clearly, Travel Confidently',
        'hero_text_id' => 'Dari trip private sampai open trip, kami bantu pilih itinerary yang pas budget, nyaman, dan minim drama.',
        'hero_text_en' => 'From private trips to open departures, we help you choose itineraries that fit your budget, stay comfortable, and keep the journey hassle-free.',
    ];
}

function wdc_get_content_settings() {
    $saved = get_option('wdc_content_settings', []);
    return wp_parse_args(is_array($saved) ? $saved : [], wdc_content_defaults());
}

function wdc_admin_content_menu() {
    add_menu_page('WDC Content', 'WDC Content', 'manage_options', 'wdc-content-settings', 'wdc_render_content_settings_page', 'dashicons-edit-page', 26);
}
add_action('admin_menu', 'wdc_admin_content_menu');

function wdc_use_classic_editor_for_catalog($use_block_editor, $post_type) {
    if (in_array($post_type, ['wm_course', 'wm_equipment'], true)) {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'wdc_use_classic_editor_for_catalog', 10, 2);

function wdc_admin_assets($hook) {
    if ($hook === 'toplevel_page_wdc-content-settings') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'wdc_admin_assets');

function wdc_render_content_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Not allowed.');
    }

    if (isset($_POST['wdc_content_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_content_nonce'])), 'wdc_save_content_settings')) {
        $settings = [
            'hero_image_id' => absint($_POST['hero_image_id'] ?? 0),
            'hero_eyebrow_id' => sanitize_text_field(wp_unslash($_POST['hero_eyebrow_id'] ?? '')),
            'hero_eyebrow_en' => sanitize_text_field(wp_unslash($_POST['hero_eyebrow_en'] ?? '')),
            'hero_title_id' => sanitize_text_field(wp_unslash($_POST['hero_title_id'] ?? '')),
            'hero_title_en' => sanitize_text_field(wp_unslash($_POST['hero_title_en'] ?? '')),
            'hero_text_id' => sanitize_textarea_field(wp_unslash($_POST['hero_text_id'] ?? '')),
            'hero_text_en' => sanitize_textarea_field(wp_unslash($_POST['hero_text_en'] ?? '')),
        ];
        update_option('wdc_content_settings', wp_parse_args($settings, wdc_content_defaults()));
        echo '<div class="notice notice-success is-dismissible"><p>Homepage hero updated.</p></div>';
    }

    $settings = wdc_get_content_settings();
    $image_url = $settings['hero_image_id'] ? wp_get_attachment_image_url((int) $settings['hero_image_id'], 'medium_large') : '';
    ?>
    <div class="wrap wdc-content-admin">
        <h1>WDC Content</h1>
        <p>Edit public homepage content without touching theme code.</p>
        <form method="post">
            <?php wp_nonce_field('wdc_save_content_settings', 'wdc_content_nonce'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="hero_image_id">Hero Image</label></th>
                    <td>
                        <input type="hidden" id="hero_image_id" name="hero_image_id" value="<?php echo esc_attr($settings['hero_image_id']); ?>">
                        <div id="wdc-hero-preview" style="max-width:420px;margin-bottom:12px;<?php echo $image_url ? '' : 'display:none;'; ?>">
                            <img src="<?php echo esc_url($image_url); ?>" style="width:100%;height:auto;border-radius:12px;border:1px solid #dcdcde;" alt="Hero preview">
                        </div>
                        <button type="button" class="button button-secondary" id="wdc-select-hero">Choose Image</button>
                        <button type="button" class="button" id="wdc-remove-hero">Remove</button>
                        <p class="description">Recommended: wide image, 1800px+ width.</p>
                    </td>
                </tr>
                <tr><th scope="row">Eyebrow / Kicker</th><td><input class="regular-text" name="hero_eyebrow_id" value="<?php echo esc_attr($settings['hero_eyebrow_id']); ?>" placeholder="Indonesian"><br><br><input class="regular-text" name="hero_eyebrow_en" value="<?php echo esc_attr($settings['hero_eyebrow_en']); ?>" placeholder="English"></td></tr>
                <tr><th scope="row">Hero Title</th><td><input class="large-text" name="hero_title_id" value="<?php echo esc_attr($settings['hero_title_id']); ?>" placeholder="Indonesian"><br><br><input class="large-text" name="hero_title_en" value="<?php echo esc_attr($settings['hero_title_en']); ?>" placeholder="English"></td></tr>
                <tr><th scope="row">Hero Subtitle</th><td><textarea class="large-text" rows="3" name="hero_text_id" placeholder="Indonesian"><?php echo esc_textarea($settings['hero_text_id']); ?></textarea><br><br><textarea class="large-text" rows="3" name="hero_text_en" placeholder="English"><?php echo esc_textarea($settings['hero_text_en']); ?></textarea></td></tr>
            </table>
            <?php submit_button('Save Hero Content'); ?>
        </form>
    </div>
    <script>
    jQuery(function($){
        var frame;
        $('#wdc-select-hero').on('click', function(e){
            e.preventDefault();
            frame = wp.media({title:'Choose hero image', button:{text:'Use this image'}, multiple:false});
            frame.on('select', function(){
                var img = frame.state().get('selection').first().toJSON();
                $('#hero_image_id').val(img.id);
                $('#wdc-hero-preview').show().html('<img src="'+img.url+'" style="width:100%;height:auto;border-radius:12px;border:1px solid #dcdcde;" alt="Hero preview">');
            });
            frame.open();
        });
        $('#wdc-remove-hero').on('click', function(){
            $('#hero_image_id').val('0');
            $('#wdc-hero-preview').hide().empty();
        });
    });
    </script>
    <?php
}

function wdc_add_catalog_meta_boxes() {
    add_meta_box('wdc_course_details', 'Course Details', 'wdc_render_course_details_box', 'wm_course', 'normal', 'high');
    add_meta_box('wdc_equipment_details', 'Equipment Details', 'wdc_render_equipment_details_box', 'wm_equipment', 'normal', 'high');
}
add_action('add_meta_boxes', 'wdc_add_catalog_meta_boxes');

function wdc_meta_field($post_id, $key, $default = '') {
    $value = get_post_meta($post_id, $key, true);
    return ($value === '' || $value === null) ? $default : $value;
}

function wdc_admin_text_input($key, $label, $value, $type = 'text', $placeholder = '') {
    printf(
        '<p><label for="%1$s"><strong>%2$s</strong><br><input id="%1$s" type="%3$s" name="%1$s" value="%4$s" placeholder="%5$s" style="width:100%%"></label></p>',
        esc_attr($key),
        esc_html($label),
        esc_attr($type),
        esc_attr($value),
        esc_attr($placeholder)
    );
}

function wdc_admin_textarea($key, $label, $value, $placeholder = '', $rows = 3) {
    printf(
        '<p><label for="%1$s"><strong>%2$s</strong><br><textarea id="%1$s" name="%1$s" rows="%3$d" style="width:100%%" placeholder="%4$s">%5$s</textarea></label></p>',
        esc_attr($key),
        esc_html($label),
        (int) $rows,
        esc_attr($placeholder),
        esc_textarea($value)
    );
}

function wdc_render_course_details_box($post) {
    wp_nonce_field('wdc_save_course_details', 'wdc_course_details_nonce');
    $id = $post->ID;
    if (!has_post_thumbnail($id)) {
        echo '<div class="notice notice-warning inline"><p><strong>Featured image kosong.</strong> Single + catalog card akan pakai fallback theme image.</p></div>';
    }
    echo '<p class="description">Isi field di bawah. Layout single tetap rapi; section kosong otomatis disembunyikan.</p>';

    echo '<h3 style="margin:18px 0 8px">Pricing and Schedule</h3>';
    echo '<div class="wdc-admin-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;max-width:960px">';
    wdc_admin_text_input('_wm_price', 'Price (IDR)', wdc_meta_field($id, '_wm_price'), 'number', '5500000');
    wdc_admin_text_input('_wm_duration', 'Duration', wdc_meta_field($id, '_wm_duration'), 'text', '3-4 days');
    wdc_admin_text_input('_wm_max_students', 'Max Students', wdc_meta_field($id, '_wm_max_students'), 'number', '4');
    wdc_admin_text_input('_wm_prerequisites', 'Prerequisites', wdc_meta_field($id, '_wm_prerequisites'), 'text', 'Open Water certification');
    echo '</div>';
    wdc_admin_textarea('_wm_includes', "What's Included", wdc_meta_field($id, '_wm_includes'), 'Certification, instructor, pool session, rental gear...', 4);

    echo '<h3 style="margin:22px 0 8px">Single Page Highlights (max 3)</h3>';
    echo '<p class="description">Ganti hardcode section outcomes. Kosongkan semua untuk hide section.</p>';
    for ($i = 1; $i <= 3; $i++) {
        $title_ph = $i === 1 ? 'Yang kamu bangun' : ($i === 2 ? 'Cara kami mengajar' : 'Standar keselamatan');
        echo '<div style="margin:0 0 12px;padding:12px;border:1px solid #d9e2ec;border-radius:10px;background:#f8fbfd;max-width:960px">';
        echo '<strong style="display:block;margin-bottom:8px">Highlight ' . $i . '</strong>';
        echo '<div style="display:grid;grid-template-columns:1fr 2fr;gap:12px">';
        wdc_admin_text_input('_wdc_course_point_' . $i . '_title', 'Title', wdc_meta_field($id, '_wdc_course_point_' . $i . '_title'), 'text', $title_ph);
        wdc_admin_textarea('_wdc_course_point_' . $i . '_text', 'Text', wdc_meta_field($id, '_wdc_course_point_' . $i . '_text'), 'Isi singkat 1-2 kalimat', 2);
        echo '</div></div>';
    }

    echo '<h3 style="margin:22px 0 8px">CTA and Visibility</h3>';
    echo '<div class="wdc-admin-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;max-width:960px">';
    wdc_admin_text_input('_wdc_course_cta_label', 'CTA Label', wdc_meta_field($id, '_wdc_course_cta_label'), 'text', 'Daftar Kursus');
    $estimate = wdc_meta_field($id, '_wdc_price_estimate') === '1';
    echo '<p style="display:flex;align-items:flex-end"><label><input type="checkbox" name="_wdc_price_estimate" value="1" ' . checked($estimate, true, false) . '> Price is estimate / starting price</label></p>';
    echo '</div>';
    $visible = wdc_meta_field($id, '_wdc_catalog_visible') !== '0';
    echo '<p><label><input type="checkbox" name="_wdc_catalog_visible" value="1" ' . checked($visible, true, false) . '> Show in public/member course catalog</label></p>';
    echo '<p class="description">Featured Image = hero/card. Course Levels + Course Agencies panels = badge/filter. Title/Excerpt/Content = main single copy.</p>';
    echo '<p><a class="button" href="' . esc_url(get_permalink($id)) . '" target="_blank" rel="noopener">Preview single page</a></p>';
}

function wdc_render_equipment_details_box($post) {
    wp_nonce_field('wdc_save_equipment_details', 'wdc_equipment_details_nonce');
    $id = $post->ID;
    if (!has_post_thumbnail($id)) {
        echo '<div class="notice notice-warning inline"><p><strong>Featured image kosong.</strong> Single + catalog card akan pakai fallback theme image.</p></div>';
    }
    echo '<p class="description">Isi field di bawah. Section kosong di single otomatis disembunyikan.</p>';

    echo '<h3 style="margin:18px 0 8px">Pricing and Stock</h3>';
    echo '<div class="wdc-admin-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;max-width:960px">';
    wdc_admin_text_input('_wm_price', 'Price (IDR)', wdc_meta_field($id, '_wm_price'), 'number', '1250000');
    wdc_admin_text_input('_wm_stock', 'Stock', wdc_meta_field($id, '_wm_stock'), 'number', '8');
    wdc_admin_text_input('_wm_sizes', 'Sizes / Variants', wdc_meta_field($id, '_wm_sizes'), 'text', 'S, M, L / Clear, Black');
    wdc_admin_text_input('_wdc_equipment_fit', 'Fit / Usage Note', wdc_meta_field($id, '_wdc_equipment_fit'), 'text', 'Best for training and warm-water dives');
    echo '</div>';

    echo '<h3 style="margin:22px 0 8px">Single Page Service Points (max 3)</h3>';
    echo '<p class="description">Ganti hardcode service grid di single equipment.</p>';
    for ($i = 1; $i <= 3; $i++) {
        $title_ph = $i === 1 ? 'Panduan ukuran' : ($i === 2 ? 'Siap untuk pelatihan' : 'Cek ketersediaan');
        echo '<div style="margin:0 0 12px;padding:12px;border:1px solid #d9e2ec;border-radius:10px;background:#f8fbfd;max-width:960px">';
        echo '<strong style="display:block;margin-bottom:8px">Service Point ' . $i . '</strong>';
        echo '<div style="display:grid;grid-template-columns:1fr 2fr;gap:12px">';
        wdc_admin_text_input('_wdc_equip_point_' . $i . '_title', 'Title', wdc_meta_field($id, '_wdc_equip_point_' . $i . '_title'), 'text', $title_ph);
        wdc_admin_textarea('_wdc_equip_point_' . $i . '_text', 'Text', wdc_meta_field($id, '_wdc_equip_point_' . $i . '_text'), 'Isi singkat 1-2 kalimat', 2);
        echo '</div></div>';
    }

    echo '<h3 style="margin:22px 0 8px">CTA and Visibility</h3>';
    echo '<div class="wdc-admin-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;max-width:960px">';
    wdc_admin_text_input('_wdc_equip_cta_label', 'CTA Label', wdc_meta_field($id, '_wdc_equip_cta_label'), 'text', 'Ajukan Beli');
    $estimate = wdc_meta_field($id, '_wdc_price_estimate') === '1';
    echo '<p style="display:flex;align-items:flex-end"><label><input type="checkbox" name="_wdc_price_estimate" value="1" ' . checked($estimate, true, false) . '> Price is estimate / starting price</label></p>';
    echo '</div>';
    $visible = wdc_meta_field($id, '_wdc_catalog_visible') !== '0';
    echo '<p><label><input type="checkbox" name="_wdc_catalog_visible" value="1" ' . checked($visible, true, false) . '> Show in public/member equipment catalog</label></p>';
    echo '<p class="description">Featured Image = product photo. Equipment Categories + Brands panels = badge/filter.</p>';
    echo '<p><a class="button" href="' . esc_url(get_permalink($id)) . '" target="_blank" rel="noopener">Preview single page</a></p>';
}

function wdc_save_catalog_details($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if ($post->post_type === 'wm_course') {
        if (!isset($_POST['wdc_course_details_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_course_details_nonce'])), 'wdc_save_course_details')) {
            return;
        }
        $keys = [
            '_wm_price' => 'float',
            '_wm_duration' => 'text',
            '_wm_max_students' => 'int',
            '_wm_prerequisites' => 'text',
            '_wm_includes' => 'textarea',
            '_wdc_course_cta_label' => 'text',
            '_wdc_course_point_1_title' => 'text',
            '_wdc_course_point_1_text' => 'textarea',
            '_wdc_course_point_2_title' => 'text',
            '_wdc_course_point_2_text' => 'textarea',
            '_wdc_course_point_3_title' => 'text',
            '_wdc_course_point_3_text' => 'textarea',
        ];
    } elseif ($post->post_type === 'wm_equipment') {
        if (!isset($_POST['wdc_equipment_details_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_equipment_details_nonce'])), 'wdc_save_equipment_details')) {
            return;
        }
        $keys = [
            '_wm_price' => 'float',
            '_wm_stock' => 'int',
            '_wm_sizes' => 'text',
            '_wdc_equipment_fit' => 'text',
            '_wdc_equip_cta_label' => 'text',
            '_wdc_equip_point_1_title' => 'text',
            '_wdc_equip_point_1_text' => 'textarea',
            '_wdc_equip_point_2_title' => 'text',
            '_wdc_equip_point_2_text' => 'textarea',
            '_wdc_equip_point_3_title' => 'text',
            '_wdc_equip_point_3_text' => 'textarea',
        ];
    } else {
        return;
    }

    foreach ($keys as $key => $type) {
        $raw = wp_unslash($_POST[$key] ?? '');
        if ($type === 'float') {
            $value = $raw === '' ? '' : (float) $raw;
        } elseif ($type === 'int') {
            $value = $raw === '' ? '' : max(0, (int) $raw);
        } elseif ($type === 'textarea') {
            $value = sanitize_textarea_field($raw);
        } else {
            $value = sanitize_text_field($raw);
        }
        update_post_meta($post_id, $key, $value);
    }

    update_post_meta($post_id, '_wdc_catalog_visible', isset($_POST['_wdc_catalog_visible']) ? '1' : '0');
    update_post_meta($post_id, '_wdc_price_estimate', isset($_POST['_wdc_price_estimate']) ? '1' : '0');
}
add_action('save_post', 'wdc_save_catalog_details', 10, 2);

/**
 * Admin list columns for catalog CPTs.
 */
function wdc_catalog_admin_columns($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['wdc_price'] = 'Price';
            $new['wdc_meta'] = 'Meta';
            $new['wdc_visible'] = 'Catalog';
        }
    }
    return $new;
}
add_filter('manage_wm_course_posts_columns', 'wdc_catalog_admin_columns');
add_filter('manage_wm_equipment_posts_columns', 'wdc_catalog_admin_columns');

function wdc_catalog_admin_column_content($column, $post_id) {
    if ($column === 'wdc_price') {
        $price = get_post_meta($post_id, '_wm_price', true);
        if ($price === '' || $price === null) {
            echo '—';
        } else {
            echo 'Rp ' . esc_html(number_format((float) $price, 0, ',', '.'));
            if (get_post_meta($post_id, '_wdc_price_estimate', true) === '1') {
                echo '<br><small>estimate</small>';
            }
        }
        return;
    }
    if ($column === 'wdc_meta') {
        $type = get_post_type($post_id);
        if ($type === 'wm_course') {
            $bits = array_filter([
                get_post_meta($post_id, '_wm_duration', true),
                get_post_meta($post_id, '_wm_max_students', true) !== '' ? 'max ' . get_post_meta($post_id, '_wm_max_students', true) : '',
            ]);
            $tax = wp_get_post_terms($post_id, 'course_agency', ['fields' => 'names']);
            if (!is_wp_error($tax) && $tax) {
                $bits[] = $tax[0];
            }
            echo $bits ? esc_html(implode(' · ', $bits)) : '—';
        } else {
            $bits = array_filter([
                get_post_meta($post_id, '_wm_sizes', true),
                get_post_meta($post_id, '_wm_stock', true) !== '' ? 'stok ' . get_post_meta($post_id, '_wm_stock', true) : '',
            ]);
            echo $bits ? esc_html(implode(' · ', $bits)) : '—';
        }
        if (!has_post_thumbnail($post_id)) {
            echo '<br><small style="color:#b32d2e">no image</small>';
        }
        return;
    }
    if ($column === 'wdc_visible') {
        echo get_post_meta($post_id, '_wdc_catalog_visible', true) === '0' ? 'Hidden' : 'Visible';
    }
}
add_action('manage_wm_course_posts_custom_column', 'wdc_catalog_admin_column_content', 10, 2);
add_action('manage_wm_equipment_posts_custom_column', 'wdc_catalog_admin_column_content', 10, 2);

/**
 * Completed Courses — AJAX: Add
 */
function wdc_ajax_add_completed_course() {
    check_ajax_referer('wdc_member_nonce', 'nonce');

    $uid = isset($_POST['user_id']) ? (int) $_POST['user_id'] : get_current_user_id();
    if (!$uid) {
        wp_send_json_error('No user.');
    }

    // Only admin can add for others
    $current = get_current_user_id();
    if ($uid !== $current && !current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }

    $course_title = sanitize_text_field(wp_unslash($_POST['course_title'] ?? ''));
    if (!$course_title) {
        wp_send_json_error('Course title required.');
    }

    $completed = get_user_meta($uid, '_wdc_completed_courses', true);
    $completed = is_array($completed) ? $completed : [];

    $entry = [
        'course_id'      => (int) ($_POST['course_id'] ?? 0),
        'course_title'   => $course_title,
        'level'          => sanitize_text_field(wp_unslash($_POST['level'] ?? '')),
        'date_completed' => sanitize_text_field(wp_unslash($_POST['date_completed'] ?? '')),
        'cert_number'    => sanitize_text_field(wp_unslash($_POST['cert_number'] ?? '')),
        'notes'          => sanitize_textarea_field(wp_unslash($_POST['notes'] ?? '')),
        'added_by'       => ($uid === $current) ? 'user' : 'admin',
        'created_at'     => current_time('mysql'),
    ];

    array_unshift($completed, $entry);
    update_user_meta($uid, '_wdc_completed_courses', array_slice($completed, 0, 50));

    wp_send_json_success(['entry' => $entry, 'total' => count($completed)]);
}
add_action('wp_ajax_wdc_add_completed_course', 'wdc_ajax_add_completed_course');

/**
 * Completed Courses — AJAX: Remove
 */
function wdc_ajax_remove_completed_course() {
    check_ajax_referer('wdc_member_nonce', 'nonce');

    $uid = isset($_POST['user_id']) ? (int) $_POST['user_id'] : get_current_user_id();
    if (!$uid) {
        wp_send_json_error('No user.');
    }

    $current = get_current_user_id();
    if ($uid !== $current && !current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }

    $index = (int) ($_POST['index'] ?? -1);
    $completed = get_user_meta($uid, '_wdc_completed_courses', true);
    $completed = is_array($completed) ? $completed : [];

    if ($index < 0 || $index >= count($completed)) {
        wp_send_json_error('Invalid index.');
    }

    array_splice($completed, $index, 1);
    update_user_meta($uid, '_wdc_completed_courses', $completed);

    wp_send_json_success(['total' => count($completed)]);
}
add_action('wp_ajax_wdc_remove_completed_course', 'wdc_ajax_remove_completed_course');

/**
 * Admin: Completed Courses section on User Profile
 */
function wdc_admin_completed_courses_fields($user) {
    if (!current_user_can('manage_options')) {
        return;
    }
    $completed = get_user_meta($user->ID, '_wdc_completed_courses', true);
    $completed = is_array($completed) ? $completed : [];

    // Get all courses for dropdown
    $all_courses = get_posts(['post_type' => 'wm_course', 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC']);
    ?>
    <h2 style="font-size:16px;margin:20px 0 8px;">🎓 Completed Courses</h2>
    <p class="description">Kursus yang sudah pernah diikuti oleh member ini. Bisa ditambah/dihapus dari sini.</p>

    <?php if ($completed) : ?>
    <table class="widefat striped" style="max-width:800px;margin:12px 0;">
        <thead>
            <tr>
                <th>Kursus</th>
                <th>Level</th>
                <th>Tanggal</th>
                <th>No. Sertifikat</th>
                <th>Ditambah Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($completed as $i => $c) : ?>
            <tr>
                <td><strong><?php echo esc_html($c['course_title']); ?></strong>
                    <?php if (!empty($c['notes'])) : ?><br><small style="color:#666;"><?php echo esc_html($c['notes']); ?><?php endif; ?>
                </td>
                <td><?php echo esc_html($c['level'] ?: '-'); ?></td>
                <td><?php echo esc_html($c['date_completed'] ?: '-'); ?></td>
                <td><?php echo esc_html($c['cert_number'] ?: '-'); ?></td>
                <td><span style="padding:2px 8px;border-radius:4px;background:<?php echo ($c['added_by'] ?? '') === 'admin' ? '#dbeafe' : '#dcfce7'; ?>;font-size:12px;"><?php echo esc_html(($c['added_by'] ?? 'user') === 'admin' ? 'Admin' : 'User'); ?></span></td>
                <td>
                    <form method="post" style="display:inline;">
                        <?php wp_nonce_field('wdc_admin_remove_completed', 'wdc_admin_remove_nonce'); ?>
                        <input type="hidden" name="wdc_remove_index" value="<?php echo $i; ?>">
                        <input type="hidden" name="wdc_remove_uid" value="<?php echo $user->ID; ?>">
                        <button type="submit" class="button button-small" onclick="return confirm('Hapus kursus ini dari daftar?');">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;max-width:800px;margin:12px 0;">
        <strong>Tambah Kursus Selesai</strong>
        <table class="form-table" style="margin:8px 0 0;">
            <tr>
                <th><label>Kursus</label></th>
                <td>
                    <select name="wdc_new_completed_course_id" id="wdc-admin-completed-course-select" style="min-width:300px;">
                        <option value="">— Pilih kursus dari daftar —</option>
                        <?php foreach ($all_courses as $ac) :
                            $level_terms = wp_get_post_terms($ac->ID, 'course_level', ['fields' => 'names']);
                            $level = (!is_wp_error($level_terms) && $level_terms) ? $level_terms[0] : '';
                        ?>
                            <option value="<?php echo esc_attr($ac->ID); ?>" data-title="<?php echo esc_attr($ac->post_title); ?>" data-level="<?php echo esc_attr($level); ?>"><?php echo esc_html($ac->post_title); ?><?php if ($level) echo ' (' . esc_html($level) . ')'; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="wdc_new_completed_course_custom" placeholder="Atau ketik nama kursus manual..." style="min-width:300px;margin-top:6px;">
                </td>
            </tr>
            <tr>
                <th><label>Tanggal Selesai</label></th>
                <td><input type="date" name="wdc_new_completed_date" style="min-width:200px;"></td>
            </tr>
            <tr>
                <th><label>No. Sertifikat</label></th>
                <td><input type="text" name="wdc_new_completed_cert" placeholder="Opsional" style="min-width:200px;"></td>
            </tr>
            <tr>
                <th><label>Catatan</label></th>
                <td><textarea name="wdc_new_completed_notes" rows="2" placeholder="Opsional" style="min-width:300px;"></textarea></td>
            </tr>
        </table>
        <input type="hidden" name="wdc_new_completed_uid" value="<?php echo $user->ID; ?>">
        <p><button type="submit" name="wdc_admin_add_completed" value="1" class="button button-primary">Tambah Kursus Selesai</button></p>
    </div>
    <?php
}
add_action('show_user_profile', 'wdc_admin_completed_courses_fields');
add_action('edit_user_profile', 'wdc_admin_completed_courses_fields');

/**
 * Admin: Save completed course from user profile
 */
function wdc_admin_save_completed_courses($user_id) {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle remove
    if (isset($_POST['wdc_admin_remove_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_admin_remove_nonce'])), 'wdc_admin_remove_completed')) {
        $remove_uid = (int) ($_POST['wdc_remove_uid'] ?? 0);
        $remove_idx = (int) ($_POST['wdc_remove_index'] ?? -1);
        if ($remove_uid > 0 && $remove_idx >= 0) {
            $completed = get_user_meta($remove_uid, '_wdc_completed_courses', true);
            $completed = is_array($completed) ? $completed : [];
            if ($remove_idx < count($completed)) {
                array_splice($completed, $remove_idx, 1);
                update_user_meta($remove_uid, '_wdc_completed_courses', $completed);
            }
        }
    }

    // Handle add
    if (isset($_POST['wdc_admin_add_completed']) && $_POST['wdc_admin_add_completed'] === '1') {
        $target_uid = (int) ($_POST['wdc_new_completed_uid'] ?? $user_id);
        $course_id = (int) ($_POST['wdc_new_completed_course_id'] ?? 0);
        $custom_title = sanitize_text_field(wp_unslash($_POST['wdc_new_completed_course_custom'] ?? ''));

        $course_title = '';
        $level = '';
        if ($course_id) {
            $cp = get_post($course_id);
            $course_title = $cp ? $cp->post_title : '';
            $level_terms = wp_get_post_terms($course_id, 'course_level', ['fields' => 'names']);
            $level = (!is_wp_error($level_terms) && $level_terms) ? $level_terms[0] : '';
        } elseif ($custom_title) {
            $course_title = $custom_title;
        }

        if (!$course_title) {
            return;
        }

        $completed = get_user_meta($target_uid, '_wdc_completed_courses', true);
        $completed = is_array($completed) ? $completed : [];

        $entry = [
            'course_id'      => $course_id,
            'course_title'   => $course_title,
            'level'          => $level,
            'date_completed' => sanitize_text_field(wp_unslash($_POST['wdc_new_completed_date'] ?? '')),
            'cert_number'    => sanitize_text_field(wp_unslash($_POST['wdc_new_completed_cert'] ?? '')),
            'notes'          => sanitize_textarea_field(wp_unslash($_POST['wdc_new_completed_notes'] ?? '')),
            'added_by'       => 'admin',
            'created_at'     => current_time('mysql'),
        ];

        array_unshift($completed, $entry);
        update_user_meta($target_uid, '_wdc_completed_courses', array_slice($completed, 0, 50));
    }
}
add_action('personal_options_update', 'wdc_admin_save_completed_courses');
add_action('edit_user_profile_update', 'wdc_admin_save_completed_courses');
