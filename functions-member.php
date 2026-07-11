<?php
/**
 * Member Admin Menu - Hook via Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only load in admin
if (!is_admin()) {
    return;
}

// TMPB menu is canonical source. Avoid duplicate/legacy menu registration from theme.
if (class_exists('Travel_Membership_Pro')) {
    return;
}

add_action('admin_menu', 'contenly_register_member_menu', 30);

/**
 * Register Member Menu
 */
function contenly_register_member_menu() {
    // Top-level menu
    add_menu_page(
        __('Member Management', 'contenly'),
        __('🎫 Member', 'contenly'),
        'manage_options',
        'contenly-member',
        'contenly_render_member_dashboard',
        'dashicons-groups',
        30
    );
    
    // Dashboard (default submenu)
    add_submenu_page(
        'contenly-member',
        __('Dashboard', 'contenly'),
        __('📊 Dashboard', 'contenly'),
        'manage_options',
        'contenly-member',
        'contenly_render_member_dashboard'
    );
    
    // All Members
    add_submenu_page(
        'contenly-member',
        __('All Members', 'contenly'),
        __('👥 All Members', 'contenly'),
        'manage_options',
        'contenly-member-all',
        'contenly_render_member_all'
    );
    
    // Tier Monitoring
    add_submenu_page(
        'contenly-member',
        __('Tier Monitoring', 'contenly'),
        __('💳 Tier Monitoring', 'contenly'),
        'manage_options',
        'contenly-member-payments',
        'contenly_render_member_payments'
    );
    
    // Spending Reports
    add_submenu_page(
        'contenly-member',
        __('Spending Reports', 'contenly'),
        __('📈 Spending Reports', 'contenly'),
        'manage_options',
        'contenly-member-reports',
        'contenly_render_member_reports'
    );
}

/**
 * Render Dashboard Page
 */
function contenly_render_member_dashboard() {
    $file = get_template_directory() . '/admin/page-member-dashboard.php';
    if (file_exists($file)) {
        include $file;
    } else {
        echo '<div class="wrap"><h1>Member Dashboard</h1><p>Dashboard file not found.</p></div>';
    }
}

/**
 * Render All Members Page
 */
function contenly_render_member_all() {
    $file = get_template_directory() . '/admin/page-member-all.php';
    if (file_exists($file)) {
        include $file;
    } else {
        echo '<div class="wrap"><h1>All Members</h1><p>Members file not found.</p></div>';
    }
}

/**
 * Render Tier Monitoring Page
 */
function contenly_render_member_payments() {
    $file = get_template_directory() . '/admin/page-member-payments.php';
    if (file_exists($file)) {
        include $file;
    } else {
        echo '<div class="wrap"><h1>Tier Monitoring</h1><p>Monitoring file not found.</p></div>';
    }
}

/**
 * Render Spending Reports Page
 */
function contenly_render_member_reports() {
    $file = get_template_directory() . '/admin/page-member-reports.php';
    if (file_exists($file)) {
        include $file;
    } else {
        echo '<div class="wrap"><h1>Spending Reports</h1><p>Reports file not found.</p></div>';
    }
}
