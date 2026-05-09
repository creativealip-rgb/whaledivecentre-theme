<?php
/**
 * Dashboard Header Include
 * Common header with sticky sidebar for all dashboard pages
 */

if (!is_user_logged_in()) {
    $requested_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : contenly_localized_url('/dashboard/');
    wp_redirect(add_query_arg('redirect_to', $requested_uri, contenly_localized_url('/login/')));
    exit;
}

$user_id = get_current_user_id();
$user = wp_get_current_user();
$tier_data_live = contenly_get_user_tier_data($user_id);
$current_tier = $tier_data_live['tier'];
$total_spend = (int) $tier_data_live['total_spend'];
$dynamic_points = 0;
$booking_posts = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'fields' => 'ids',
    'meta_query' => [[ 'key' => '_user_id', 'value' => $user_id ]],
]);

// Always use live booking count for sidebar badge (real-time).
$booking_count = count($booking_posts);

foreach ($booking_posts as $bid) {
    $st = get_post_meta($bid, '_booking_status', true);
    if (in_array($st, ['paid', 'confirmed', 'completed'], true)) {
        $dynamic_points += 100;
    }
}

$review_posts = get_posts([
    'post_type' => 'destination',
    'posts_per_page' => -1,
    'post_status' => ['publish', 'pending'],
    'fields' => 'ids',
    'meta_query' => [
        'relation' => 'AND',
        ['key' => '_user_id', 'value' => $user_id],
        ['key' => '_is_review', 'value' => '1'],
    ],
]);

$dynamic_points += (count($review_posts) * 25);

// Rewards sidebar should reflect live points, not stale user_meta snapshot.
$points = $dynamic_points;

// Tier data
$tier_data = contenly_get_member_tier_map();
$tier_info = $tier_data[$current_tier];

// Get current page slug
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8">
    ">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* Hide WordPress Admin Bar */
        html { margin-top: 0 !important; }
        body { margin-top: 0 !important; }
        #wpadminbar { display: none !important; }
        
        /* Hide Theme Footer on Dashboard */
        #colophon.site-footer { display: none !important; }
        footer.site-footer { display: none !important; }
        .site-footer { display: none !important; }
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(180deg, #f1f5f9 0%, #ffffff 100%); min-height: 100vh; }
        .dashboard-wrapper { display: grid; grid-template-columns: 260px 1fr; gap: 0; max-width: 100%; margin: 0; }
        .dashboard-sidebar { background: white; padding: 24px 16px; height: 100vh; position: sticky; top: 0; border-right: 1px solid #f1f5f9; overflow-y: auto; }
        .dashboard-main { background: #f8fafc; padding: 32px 40px; min-height: 100vh; }
        .user-profile { text-align: center; padding: 24px 16px; border-bottom: 1px solid #f1f5f9; margin-bottom: 16px; }
        .user-avatar { width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #539294, #539294); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; font-weight: 700; margin: 0 auto 12px; }
        .user-name { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .user-email { font-size: 13px; color: #94a3b8; margin-bottom: 8px; }
        .user-tier { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 600; background: #fef3c7; color: #d97706; }
        .dashboard-menu { list-style: none; padding: 0; margin: 0; }
        .dashboard-menu li { margin-bottom: 4px; }
        .dashboard-menu a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #64748b; text-decoration: none; border-radius: 10px; transition: all 0.2s; font-weight: 500; font-size: 14px; }
        .dashboard-menu a:hover, .dashboard-menu a.active { background: #EEF5F4; color: #539294; }
        .dashboard-menu .menu-icon { width: 22px; height: 22px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; background: #e2e8f0; color: #334155; font-size: 10px; font-weight: 700; letter-spacing: .02em; flex: 0 0 22px; }
        .dashboard-menu .badge { margin-left: auto; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 600; }
        .dashboard-main { background: #f8fafc; padding: 32px; min-height: 100vh; }
        .dashboard-content { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .page-title { font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .page-subtitle { color: #64748b; margin-bottom: 32px; font-size: 14px; }

        /* Unified member page heading strip */
        .dashboard-main > div:has(> h1),
        .dashboard-main > section:has(> h1) {
            background: linear-gradient(135deg, #EEF5F4, #f8fbff) !important;
            border: 1px solid #DCE9E6 !important;
            border-radius: 14px !important;
            padding: 16px 18px !important;
            margin-bottom: 20px !important;
        }
        .dashboard-main > div:has(> h1) h1,
        .dashboard-main > section:has(> h1) h1 {
            margin: 0 0 6px !important;
            color: #0f172a !important;
            line-height: 1.2 !important;
        }
        .dashboard-main > div:has(> h1) p,
        .dashboard-main > section:has(> h1) p {
            margin: 0 !important;
            color: #475569 !important;
        }

        /* Fallback for pages that render heading directly (h1 + subtitle) */
        .dashboard-main > h1.page-title {
            margin: 0 !important;
            padding: 16px 18px 6px !important;
            background: linear-gradient(135deg, #EEF5F4, #f8fbff) !important;
            border: 1px solid #DCE9E6 !important;
            border-bottom: 0 !important;
            border-radius: 14px 14px 0 0 !important;
            color: #0f172a !important;
            line-height: 1.2 !important;
        }
        .dashboard-main > p.page-subtitle {
            margin: 0 0 20px !important;
            padding: 0 18px 14px !important;
            background: linear-gradient(135deg, #EEF5F4, #f8fbff) !important;
            border: 1px solid #DCE9E6 !important;
            border-top: 0 !important;
            border-radius: 0 0 14px 14px !important;
            color: #475569 !important;
        }
        
        /* Welcome Banner - Contenly Style */
        .welcome-banner { background: linear-gradient(135deg, #539294 0%, #539294 100%); border-radius: 16px; padding: 32px; color: white; margin-bottom: 32px; }
        .welcome-banner h1 { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .welcome-banner p { opacity: 0.9; font-size: 14px; margin-bottom: 20px; }
        .welcome-stats { display: flex; gap: 16px; }
        .welcome-stat { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 16px 24px; border-radius: 12px; text-align: center; }
        .welcome-stat-value { font-size: 24px; font-weight: 700; margin-bottom: 4px; }
        .welcome-stat-label { font-size: 12px; opacity: 0.9; }
        
        /* Mobile Menu Toggle - Contenly Style */
        .mobile-menu-toggle { display: none; position: fixed; top: 10px; left: 14px; z-index: 1000; background: transparent; border: 2px solid rgba(255,255,255,.5); border-radius: 8px; width: 40px; height: 40px; cursor: pointer; box-shadow: none; align-items: center; justify-content: center; flex-direction: column; gap: 4px; padding: 8px; transition: all .2s ease; }
        .mobile-menu-toggle:hover { background: transparent; border-color: rgba(255,255,255,.65); }
        .mobile-menu-toggle span { display: block; width: 20px; height: 2px; background: #539294; border-radius: 2px; transition: all 0.28s ease; margin: 0; }
        .mobile-menu-toggle.active { background: transparent; border-color: rgba(255,255,255,.65); }
        .mobile-menu-toggle.active span:nth-child(1) { transform: rotate(45deg) translate(4px, 4px); }
        .mobile-menu-toggle.active span:nth-child(2) { opacity: 0; }
        .mobile-menu-toggle.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
        .mobile-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 998; backdrop-filter: blur(2px); }
        .mobile-topbar { display: none; }
        
        @media (max-width: 768px) {
            .dashboard-wrapper { grid-template-columns: 1fr; padding: 0; width:100%; max-width:100vw; overflow-x:hidden; }
            .dashboard-sidebar { position: fixed; top: 0; left: -100%; width: 75%; max-width: 260px; height: 100vh; z-index: 1001; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); overflow-y: auto; background: white; box-shadow: 4px 0 24px rgba(0,0,0,0.12); }
            .dashboard-sidebar.active { left: 0; }
            .mobile-topbar { display:flex; position: fixed; top:0; left:0; right:0; z-index:1000; height:56px; background:#ffffff; border-bottom:1px solid #e2e8f0; align-items:center; justify-content:center; padding:0 14px; }
            .mobile-topbar .topbar-title { position:absolute; left:0; right:0; text-align:center; font-size:15px; font-weight:700; color:#0f172a; pointer-events:none; }
            .mobile-menu-toggle { display:flex; z-index:1002; border-color:#D8E8E8; background:#fff; }
            .mobile-topbar .mobile-menu-toggle { position:absolute !important; left:12px !important; top:50% !important; right:auto !important; transform:translateY(-50%) !important; margin:0 !important; }
            .mobile-overlay.active { display: block; }
            
            /* Mobile sidebar styling - MORE COMPACT */
            .user-profile { padding: 20px 16px 16px; border-bottom: 1px solid #f1f5f9; }
            .user-avatar { width: 56px; height: 56px; font-size: 24px; background: linear-gradient(135deg, #539294, #539294); margin-bottom: 8px; }
            .user-name { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
            .user-email { font-size: 12px; color: #94a3b8; margin-bottom: 8px; display: none; }
            .user-tier { display: inline-block; padding: 4px 10px; background: #fef3c7; color: #d97706; border-radius: 9999px; font-size: 11px; font-weight: 600; }
            
            .dashboard-home-btn { background: linear-gradient(135deg, #539294, #539294) !important; color: #ffffff !important; justify-content: center !important; margin: 12px 16px 16px !important; border-radius: 10px !important; font-weight: 700 !important; padding: 12px 16px !important; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important; font-size: 13px !important; text-shadow: 0 1px 2px rgba(0,0,0,0.25); }
            .dashboard-menu a.dashboard-home-btn { color:#ffffff !important; font-weight:700 !important; }
            .dashboard-menu a.dashboard-home-btn span:not(.menu-icon) { color:#ffffff !important; }
            
            .dashboard-menu { padding: 0 8px; }
            .dashboard-menu li { margin-bottom: 2px; }
            .dashboard-menu a { padding: 10px 12px !important; border-radius: 8px !important; font-weight: 500 !important; color: #475569 !important; font-size: 13px !important; }
            .dashboard-menu a:hover, .dashboard-menu a.active { background: #f0f9ff !important; color: #539294 !important; }
            .dashboard-menu .menu-icon { width: 20px; height: 20px; border-radius: 6px; display:inline-flex; align-items:center; justify-content:center; background:#e2e8f0; color:#334155; font-size:9px; font-weight:700; letter-spacing:.02em; flex:0 0 20px; }
            .dashboard-menu .badge { background: #fee2e2 !important; color: #dc2626 !important; padding: 2px 6px !important; border-radius: 9999px !important; font-size: 10px !important; font-weight: 600 !important; }
            .dashboard-home-btn, .dashboard-home-btn * { color: #ffffff !important; }
            .dashboard-home-btn .menu-icon { color: #355F72 !important; background: #eaf2ff !important; }
            
            .dashboard-main { border-radius: 0; padding: 72px 16px 16px; margin-top: 0; width:100%; max-width:100vw; overflow-x:hidden; }
            .dashboard-main > div:has(> h1), .dashboard-main > section:has(> h1) { padding: 14px 14px !important; margin-bottom: 16px !important; }
            .dashboard-main > div:has(> h1) h1, .dashboard-main > section:has(> h1) h1 { font-size: 22px !important; }
            .dashboard-main > h1.page-title { padding:14px 14px 6px !important; font-size:22px !important; }
            .dashboard-main > p.page-subtitle { padding:0 14px 12px !important; margin-bottom:16px !important; }
            html, body { margin-top: 0 !important; }
            #wpadminbar { display: none !important; }
            .page-title { font-size: 22px; margin-bottom: 8px; }
            .page-subtitle { color: #64748b; margin-bottom: 20px; font-size: 13px; }
            
            /* Stats cards mobile - 2x2 GRID */
            .dashboard-content > div[style*="display: grid"] { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; margin-bottom: 20px !important; }
            .dashboard-content > div[style*="display: grid"] > div { padding: 16px !important; border-radius: 12px !important; min-height: auto !important; }
            .dashboard-content > div[style*="display: grid"] > div > div:first-child { font-size: 24px !important; line-height: 1.2 !important; }
            .dashboard-content > div[style*="display: grid"] > div > div:last-child { font-size: 10px !important; text-transform: uppercase !important; }
            
            /* Welcome banner mobile */
            .welcome-banner { padding: 20px !important; margin-bottom: 20px !important; }
            .welcome-banner h1 { font-size: 18px !important; margin-bottom: 6px !important; }
            .welcome-banner p { font-size: 12px !important; margin-bottom: 12px !important; }
            .welcome-stats { gap: 8px !important; flex-wrap: wrap; }
            .welcome-stat { padding: 12px 16px !important; border-radius: 10px !important; }
            .welcome-stat-value { font-size: 20px !important; }
            .welcome-stat-label { font-size: 10px !important; }
            
            /* Tier progress mobile */
            .dashboard-content > div[style*="background: linear-gradient(135deg, #f0f9ff"] { padding: 16px !important; margin-bottom: 20px !important; }
            
            /* Recent bookings mobile - 1 column */
            .dashboard-content h2 { font-size: 18px !important; margin-bottom: 16px !important; }
        }
    </style>
</head>
<body <?php body_class(); ?>>

<div class="dashboard-wrapper">
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" onclick="toggleMobileMenu()"></div>

    <!-- Mobile Topbar -->
    <div class="mobile-topbar">
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <span class="topbar-title"><?php echo esc_html(contenly_tr('Dashboard Member', 'Member Dashboard')); ?></span>
    </div>
    
    <!-- Sticky Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="user-profile">
            <div class="user-avatar"><?php echo strtoupper(substr($user->display_name, 0, 1)); ?></div>
            <div class="user-name"><?php echo esc_html($user->display_name); ?></div>
            <div class="user-email"><?php echo esc_html($user->user_email); ?></div>
            <div class="user-tier">
                <?php echo esc_html($tier_info['icon'] . ' ' . $tier_info['name'] . ' ' . contenly_tr('Member', 'Member')); ?>
            </div>
        </div>
        
        <ul class="dashboard-menu">
            <li><a href="<?php echo esc_url(contenly_localized_url('/')); ?>" class="dashboard-home-btn"><span class="menu-icon">HM</span> <?php echo esc_html(contenly_tr('Kembali ke Homepage', 'Back to Homepage')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>" class="<?php echo is_page('dashboard') ? 'active' : ''; ?>"><span class="menu-icon">OV</span> <?php echo esc_html(contenly_tr('Overview', 'Overview')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/my-travels/')); ?>" class="<?php echo (is_page('my-travels') || is_page('my-bookings')) ? 'active' : ''; ?>"><span class="menu-icon">TR</span> <?php echo esc_html(contenly_tr('Perjalanan Saya', 'My Travels')); ?> <span class="badge"><?php echo esc_html($booking_count); ?></span></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/wishlist/')); ?>" class="<?php echo is_page('wishlist') ? 'active' : ''; ?>"><span class="menu-icon">WL</span> <?php echo esc_html(contenly_tr('Wishlist', 'Wishlist')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/reviews/')); ?>" class="<?php echo is_page('reviews') ? 'active' : ''; ?>"><span class="menu-icon">RV</span> <?php echo esc_html(contenly_tr('Review', 'Reviews')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/travel-story/')); ?>" class="<?php echo is_page('travel-story') ? 'active' : ''; ?>"><span class="menu-icon">TS</span> <?php echo esc_html(contenly_tr('Travel Story', 'Travel Story')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/rewards/')); ?>" class="<?php echo is_page('rewards') ? 'active' : ''; ?>"><span class="menu-icon">RW</span> <?php echo esc_html(contenly_tr('Rewards', 'Rewards')); ?> <span class="badge" style="background:#fef3c7; color:#d97706;"><?php echo esc_html($points); ?></span></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/membership/')); ?>" class="<?php echo is_page('membership') ? 'active' : ''; ?>"><span class="menu-icon">MB</span> <?php echo esc_html(contenly_tr('Tier Member', 'Member Tier')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/notifications/')); ?>" class="<?php echo is_page('notifications') ? 'active' : ''; ?>"><span class="menu-icon">NT</span> <?php echo esc_html(contenly_tr('Notifikasi', 'Notifications')); ?></a></li>
            <li><a href="<?php echo esc_url(contenly_localized_url('/settings/')); ?>" class="<?php echo (is_page('settings') || is_page('profile')) ? 'active' : ''; ?>"><span class="menu-icon">SE</span> <?php echo esc_html(contenly_tr('Pengaturan', 'Settings')); ?></a></li>
            <li><a href="<?php echo esc_url(wp_logout_url(contenly_localized_url('/'))); ?>" style="color: #dc2626; margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 12px;"><span class="menu-icon">LO</span> <?php echo esc_html(contenly_tr('Keluar', 'Logout')); ?></a></li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <?php if (isset($_GET['login']) && $_GET['login'] === 'success') : ?>
            <div style="margin:0 0 16px; padding:12px 14px; border-radius:10px; border:1px solid #86efac; background:#dcfce7; color:#166534; font-weight:600; font-size:14px;">
                <?php echo esc_html(contenly_tr('Login berhasil. Selamat datang kembali 👋', 'Login successful. Welcome back 👋')); ?>
            </div>
        <?php endif; ?>
