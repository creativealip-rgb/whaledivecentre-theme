<?php
/**
 * Plugin Integration - Contenly Theme
 * Integrates Travel Membership Pro plugin with Contenly theme design
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Override plugin templates with theme versions
 */
function contenly_override_plugin_templates($template, $template_name, $template_path) {
    // Check if template exists in theme
    $theme_template = get_stylesheet_directory() . '/plugin-templates/' . $template_name;
    
    if (file_exists($theme_template)) {
        return $theme_template;
    }
    
    return $template;
}
add_filter('locate_template', 'contenly_override_plugin_templates', 10, 3);

/**
 * Enqueue plugin styles with theme overrides
 */
function contenly_plugin_styles() {
    // Check if plugin is active
    if (class_exists('Travel_Membership_Pro')) {
        // Override plugin CSS with theme styles
        wp_add_inline_style('tmpb-public-css', '
            /* Tour Single Page Styling */
            .tmpb-tour-single { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
            .tmpb-tour-content-grid { display: grid; grid-template-columns: 1fr 400px; gap: 40px; }
            .tmpb-booking-card { position: sticky; top: 100px; }
            
            /* Booking Form Styling */
            .tmpb-booking-form { background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
            .tmpb-form-group { margin-bottom: 16px; }
            .tmpb-form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; }
            .tmpb-form-group input, .tmpb-form-group select, .tmpb-form-group textarea { 
                width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; 
            }
            .tmpb-btn-primary { 
                background: linear-gradient(135deg, #355F72, #539294, #E5A736); color: white; border: none; 
                padding: 14px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; width: 100%;
            }
            .tmpb-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(83, 146, 148, 0.4); }
            
            /* My Bookings Page */
            .tmpb-my-bookings { background: white; border-radius: 16px; padding: 24px; }
            .tmpb-booking-item { 
                border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 16px; 
                transition: all 0.3s; 
            }
            .tmpb-booking-item:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.12); transform: translateY(-2px); }
            
            /* Tour List */
            .tmpb-tour-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
            .tmpb-tour-card { 
                border-radius: 16px; overflow: hidden; background: white; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: all 0.3s;
            }
            .tmpb-tour-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.15); }
        ');
    }
}
add_action('wp_enqueue_scripts', 'contenly_plugin_styles', 20);

/**
 * Add custom templates for plugin pages
 */
function contenly_plugin_page_templates($template) {
    if (!class_exists('Travel_Membership_Pro')) {
        return $template;
    }
    
    // Check for tour single page
    if (is_singular('tour')) {
        $custom_template = get_stylesheet_directory() . '/single-tour.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    
    // Check for tour archive
    if (is_post_type_archive('tour')) {
        $custom_template = get_stylesheet_directory() . '/archive-tour.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter('single_template', 'contenly_plugin_page_templates');
add_filter('archive_template', 'contenly_plugin_page_templates');

/**
 * Ensure plugin AJAX URL is available
 */
function contenly_plugin_ajax_localize() {
    if (class_exists('Travel_Membership_Pro') && (is_singular('tour') || is_post_type_archive('tour') || is_page('my-bookings') || is_page('tour-packages') || is_page('tours') || is_page('wishlist') || is_page_template('page-tours.php'))) {
        wp_enqueue_script('jquery');
        wp_localize_script('jquery', 'contenlyBooking', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tmpb_booking_nonce'),
            'pluginUrl' => plugin_dir_url(dirname(__FILE__)) . '../travel-membership-plugin/',
            'i18n' => [
                'success' => 'Booking created successfully!',
                'error' => 'Booking failed. Please try again.',
                'loginRequired' => 'Please login to book this tour',
                'reviewSuccess' => 'Review submitted successfully!',
                'reviewError' => 'Failed to submit review',
                'wishlistAdded' => 'Added to wishlist',
                'wishlistRemoved' => 'Removed from wishlist',
            ],
        ]);
    }
}
add_action('wp_enqueue_scripts', 'contenly_plugin_ajax_localize', 20);

/**
 * Add plugin shortcodes to theme pages
 */
function contenly_add_plugin_shortcodes() {
    // Ensure plugin shortcodes are available
    if (class_exists('Travel_Membership_Pro')) {
        // Tour list shortcode
        add_shortcode('theme_tour_list', function($atts) {
            return do_shortcode('[tour_list ' . http_build_query($atts) . ']');
        });
        
        // Booking form shortcode
        add_shortcode('theme_booking_form', function($atts) {
            return do_shortcode('[booking_form ' . http_build_query($atts) . ']');
        });
        
        // My bookings shortcode
        add_shortcode('theme_my_bookings', function($atts) {
            return do_shortcode('[my_bookings]');
        });
    }
}
add_action('init', 'contenly_add_plugin_shortcodes');

/**
 * Modify tour archive to use theme design
 */
function contenly_tour_archive_content() {
    if (!class_exists('Travel_Membership_Pro')) {
        return;
    }
    
    get_header();
    ?>
    <main class="site-main" style="padding-top: 100px;">
        <section style="background: linear-gradient(135deg, #355F72 0%, #539294 62%, #E5A736 100%); padding: 60px 20px;">
            <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
                <h1 style="font-size: 42px; font-weight: 800; color: white; margin-bottom: 16px;">
                    🌍 Tour Packages
                </h1>
                <p style="font-size: 18px; color: rgba(255,255,255,0.9);">
                    Discover amazing destinations with our curated tour packages
                </p>
            </div>
        </section>
        
        <section style="padding: 60px 20px;">
            <div style="max-width: 1200px; margin: 0 auto;">
                <?php echo do_shortcode('[tour_list limit="-1"]'); ?>
            </div>
        </section>
    </main>
    <?php
    get_footer();
    exit;
}

/**
 * AJAX: Update User Profile
 */
function contenly_ajax_update_profile() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Please login']);
    }
    
    $user_id = get_current_user_id();
    $display_name = sanitize_text_field($_POST['display_name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    
    if (empty($display_name) || empty($email)) {
        wp_send_json_error(['message' => 'Name and email are required']);
    }
    
    // Update user data
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $display_name,
        'user_email' => $email,
    ]);
    
    // Update phone meta
    update_user_meta($user_id, 'phone_number', $phone);
    
    wp_send_json_success(['message' => 'Profile updated']);
}
add_action('wp_ajax_contenly_update_profile', 'contenly_ajax_update_profile');

/**
 * AJAX: Change Password
 */
function contenly_ajax_change_password() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Please login']);
    }
    
    $user_id = get_current_user_id();
    $new_password = $_POST['new_password'] ?? '';
    
    if (strlen($new_password) < 6) {
        wp_send_json_error(['message' => 'Password must be at least 6 characters']);
    }
    
    wp_set_password($new_password, $user_id);
    
    wp_send_json_success(['message' => 'Password changed']);
}
add_action('wp_ajax_contenly_change_password', 'contenly_ajax_change_password');

/**
 * AJAX: Submit Review (for completed bookings)
 */
function contenly_ajax_submit_review() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Silakan login dulu / Please login first']);
    }

    $user_id = get_current_user_id();
    $booking_id = absint($_POST['booking_id'] ?? 0);
    $tour_id = absint($_POST['tour_id'] ?? 0);
    $existing_id = absint($_POST['existing_id'] ?? 0);
    $title = sanitize_text_field($_POST['title'] ?? '');
    $content = sanitize_textarea_field($_POST['content'] ?? '');
    $rating = absint($_POST['rating'] ?? 0);

    if (!$booking_id || !$tour_id) {
        wp_send_json_error(['message' => 'Data booking/tour tidak valid']);
    }

    if (empty($title)) {
        wp_send_json_error(['message' => 'Judul review wajib diisi']);
    }

    $content_length = mb_strlen(trim($content));
    if ($content_length < 10 || $content_length > 1000) {
        wp_send_json_error(['message' => 'Isi review harus 10-1000 karakter']);
    }

    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'Rating harus antara 1 sampai 5']);
    }

    $booking_user_id = absint(get_post_meta($booking_id, '_user_id', true));
    if ($booking_user_id !== $user_id) {
        wp_send_json_error(['message' => 'Akses ditolak: booking bukan milik kamu']);
    }

    $booking_status = get_post_meta($booking_id, '_booking_status', true);
    if ($booking_status !== 'completed') {
        wp_send_json_error(['message' => 'Review hanya bisa setelah trip selesai (completed)']);
    }

    $travel_date = get_post_meta($booking_id, '_travel_date', true);
    $travel_date_formatted = $travel_date ? date('Y-m-d', strtotime($travel_date)) : '';
    $tour_location = get_post_meta($tour_id, '_tour_location', true) ?: get_post_meta($tour_id, 'location', true);

    // Guard existing_id to prevent cross-user edits.
    if ($existing_id > 0) {
        $existing_owner = absint(get_post_meta($existing_id, '_user_id', true));
        $existing_booking = absint(get_post_meta($existing_id, '_review_booking_id', true));
        if ($existing_owner !== $user_id || $existing_booking !== $booking_id) {
            wp_send_json_error(['message' => 'Akses edit review tidak valid']);
        }
    }

    // One booking = one review per user.
    $owned_existing_review = get_posts([
        'post_type' => 'destination',
        'posts_per_page' => 1,
        'post_status' => ['publish', 'pending', 'draft'],
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => '_review_booking_id',
                'value' => $booking_id,
                'compare' => '=',
            ],
            [
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '=',
            ],
            [
                'key' => '_is_review',
                'value' => '1',
                'compare' => '=',
            ],
        ],
    ]);

    $target_review_id = $existing_id > 0 ? $existing_id : (!empty($owned_existing_review) ? absint($owned_existing_review[0]->ID) : 0);

    if ($target_review_id > 0) {
        $review_id = wp_update_post([
            'ID' => $target_review_id,
            'post_title' => $title,
            'post_content' => $content,
        ], true);
    } else {
        $review_id = wp_insert_post([
            'post_type' => 'destination',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
        ], true);
    }

    if (is_wp_error($review_id) || !$review_id) {
        wp_send_json_error(['message' => 'Gagal simpan review. Coba lagi ya.']);
    }

    update_post_meta($review_id, '_review_booking_id', $booking_id);
    update_post_meta($review_id, '_review_tour_id', $tour_id);
    update_post_meta($review_id, '_user_id', $user_id);
    update_post_meta($review_id, '_rating', $rating);
    update_post_meta($review_id, '_visit_date', $travel_date_formatted);
    update_post_meta($review_id, '_country', $tour_location ?: 'Tour Review');
    update_post_meta($review_id, '_is_review', '1');

    wp_send_json_success([
        'message' => $target_review_id > 0 ? 'Review berhasil diupdate ✅' : 'Review berhasil dikirim ✅',
        'review_id' => $review_id,
    ]);
}
add_action('wp_ajax_contenly_submit_review', 'contenly_ajax_submit_review');

/**
 * AJAX: Delete Review (owner only)
 */
function contenly_ajax_delete_review() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Silakan login dulu']);
    }

    $user_id = get_current_user_id();
    $review_id = absint($_POST['review_id'] ?? 0);

    if (!$review_id) {
        wp_send_json_error(['message' => 'Review ID tidak valid']);
    }

    $review_owner = absint(get_post_meta($review_id, '_user_id', true));
    $is_review = get_post_meta($review_id, '_is_review', true);

    if ($review_owner !== $user_id || $is_review !== '1') {
        wp_send_json_error(['message' => 'Tidak punya akses hapus review ini']);
    }

    $deleted = wp_delete_post($review_id, true);
    if (!$deleted) {
        wp_send_json_error(['message' => 'Gagal menghapus review']);
    }

    wp_send_json_success(['message' => 'Review berhasil dihapus']);
}
add_action('wp_ajax_contenly_delete_review', 'contenly_ajax_delete_review');

/**
 * AJAX: Add Destination (Manual travel history)
 */
function contenly_ajax_add_destination() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Please login']);
    }
    
    $user_id = get_current_user_id();
    $title = sanitize_text_field($_POST['title'] ?? '');
    $visit_date = sanitize_text_field($_POST['visit_date'] ?? '');
    $description = sanitize_textarea_field($_POST['description'] ?? '');
    $country = sanitize_text_field($_POST['country'] ?? '');
    $rating = absint($_POST['rating'] ?? 0);
    
    if (empty($title) || empty($visit_date)) {
        wp_send_json_error(['message' => 'Title and visit date are required']);
    }
    
    // Create destination post
    $destination_id = wp_insert_post([
        'post_type' => 'destination',
        'post_title' => $title,
        'post_content' => $description,
        'post_status' => 'publish',
    ]);
    
    if (is_wp_error($destination_id)) {
        wp_send_json_error(['message' => 'Failed to create destination']);
    }
    
    // Save meta
    update_post_meta($destination_id, '_user_id', $user_id);
    update_post_meta($destination_id, '_visit_date', $visit_date);
    update_post_meta($destination_id, '_country', $country);
    update_post_meta($destination_id, '_rating', $rating);
    update_post_meta($destination_id, '_is_manual', '1'); // Flag for manual entry
    
    // Update user booking count (for membership)
    $count = get_user_meta($user_id, '_tmp_bookings_count', true) ?: 0;
    update_user_meta($user_id, '_tmp_bookings_count', $count + 1);
    
    wp_send_json_success(['message' => 'Destination added', 'destination_id' => $destination_id]);
}
add_action('wp_ajax_contenly_add_destination', 'contenly_ajax_add_destination');

/**
 * Override Destination Columns (Fix double columns)
 */
function contenly_fix_destination_columns($columns) {
    return [
        'cb' => '<input type="checkbox" />',
        'title' => __('Destination', 'contenly-theme'),
        'member' => __('Member', 'contenly-theme'),
        'country' => __('Country', 'contenly-theme'),
        'visit_date' => __('Visit Date', 'contenly-theme'),
        'rating' => __('Rating', 'contenly-theme'),
        'date' => __('Date Added', 'contenly-theme'),
    ];
}
add_filter('manage_destination_posts_columns', 'contenly_fix_destination_columns', 20);

/**
 * Render Destination Column Content
 */
function contenly_render_destination_columns($column, $post_id) {
    switch ($column) {
        case 'member':
            $user_id = get_post_meta($post_id, '_user_id', true);
            if ($user_id) {
                $user = get_user_by('id', $user_id);
                if ($user) {
                    echo '<a href="' . admin_url('user-edit.php?user_id=' . $user_id) . '">';
                    echo esc_html($user->display_name);
                    echo '</a><br>';
                    echo '<small>' . esc_html($user->user_email) . '</small>';
                }
            } else {
                echo '<span style="color: #94a3b8;">—</span>';
            }
            break;
            
        case 'country':
            $country = get_post_meta($post_id, '_country', true);
            echo $country ? esc_html($country) : '<span style="color: #94a3b8;">—</span>';
            break;
            
        case 'visit_date':
            $visit_date = get_post_meta($post_id, '_visit_date', true);
            echo $visit_date ? date_i18n(get_option('date_format'), strtotime($visit_date)) : '<span style="color: #94a3b8;">—</span>';
            break;
            
        case 'rating':
            $rating = absint(get_post_meta($post_id, '_rating', true));
            if ($rating > 0) {
                echo '<span style="font-size: 16px;">' . str_repeat('⭐', $rating) . '</span>';
            } else {
                echo '<span style="color: #94a3b8;">—</span>';
            }
            break;
    }
}
add_action('manage_destination_posts_custom_column', 'contenly_render_destination_columns', 10, 2);

/**
 * Check if plugin is active and notify admin if not
 */
function contenly_check_plugin_activation() {
    if (is_admin() && !class_exists('Travel_Membership_Pro')) {
        add_action('admin_notices', function() {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong>⚠️ Travel Membership Pro Plugin Not Active</strong><br>
                    The Contenly theme requires the Travel Membership Pro plugin for tour booking functionality.<br>
                    Please activate the plugin or install it from <code>/wp-content/plugins/travel-membership-plugin/</code>
                </p>
            </div>
            <?php
        });
    }
}
add_action('admin_init', 'contenly_check_plugin_activation');


/**
 * AJAX: Toggle wishlist tour for current user
 */
function contenly_ajax_toggle_wishlist() {
    check_ajax_referer('tmpb_booking_nonce', 'nonce');
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Please login']);
    }
    $user_id = get_current_user_id();
    $tour_id = absint($_POST['tour_id'] ?? 0);
    if (!$tour_id || get_post_status($tour_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid tour']);
    }
    $wishlist = get_user_meta($user_id, '_member_wishlist', true);
    if (!is_array($wishlist)) $wishlist = [];
    $wishlist = array_values(array_unique(array_map('absint', $wishlist)));
    $idx = array_search($tour_id, $wishlist, true);
    if ($idx === false) {
        $wishlist[] = $tour_id;
        $in_wishlist = true;
    } else {
        unset($wishlist[$idx]);
        $wishlist = array_values($wishlist);
        $in_wishlist = false;
    }
    update_user_meta($user_id, '_member_wishlist', $wishlist);
    wp_send_json_success([
        'in_wishlist' => $in_wishlist,
        'count' => count($wishlist),
        'message' => $in_wishlist ? 'Added to wishlist' : 'Removed from wishlist',
    ]);
}
add_action('wp_ajax_contenly_toggle_wishlist', 'contenly_ajax_toggle_wishlist');

/**
 * AJAX: Login user from custom auth page (supports nopriv)
 */
function contenly_ajax_login_user() {
    check_ajax_referer('contenly_login_nonce', 'nonce');

    $email_or_username = sanitize_text_field($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect_to = esc_url_raw($_POST['redirect_to'] ?? '/dashboard');
    $redirect_to = wp_validate_redirect($redirect_to, '/dashboard');

    if (empty($email_or_username) || empty($password)) {
        wp_send_json_error(['message' => 'Email/username dan kata sandi wajib diisi.']);
    }

    $creds = [
        'user_login' => $email_or_username,
        'user_password' => $password,
        'remember' => false,
    ];

    $user = wp_signon($creds, is_ssl());
    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Email/username atau kata sandi salah. Coba lagi.']);
    }

    wp_send_json_success([
        'message' => 'Login berhasil',
        'redirect_url' => add_query_arg('login', 'success', $redirect_to),
    ]);
}
add_action('wp_ajax_nopriv_contenly_login_user', 'contenly_ajax_login_user');
add_action('wp_ajax_contenly_login_user', 'contenly_ajax_login_user');
