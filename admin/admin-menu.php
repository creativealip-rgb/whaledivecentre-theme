<?php
/**
 * Member Admin Menu
 * Top-level menu for member management
 */

if (!defined('ABSPATH')) {
    exit;
}

class Contenly_Member_Admin {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'register_menu']);
    }
    
    /**
     * Register Member Menu
     */
    public function register_menu() {
        // Top-level menu
        add_menu_page(
            __('Member Management', 'contenly'),
            __('🎫 Member', 'contenly'),
            'manage_options',
            'contenly-member',
            [$this, 'render_dashboard'],
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
            [$this, 'render_dashboard']
        );
        
        // All Members
        add_submenu_page(
            'contenly-member',
            __('All Members', 'contenly'),
            __('👥 All Members', 'contenly'),
            'manage_options',
            'contenly-member-all',
            [$this, 'render_all_members']
        );
        
        // Tier Monitoring
        add_submenu_page(
            'contenly-member',
            __('Tier Monitoring', 'contenly'),
            __('💳 Tier Monitoring', 'contenly'),
            'manage_options',
            'contenly-member-payments',
            [$this, 'render_upgrade_payments']
        );
        
        // Spending Reports
        add_submenu_page(
            'contenly-member',
            __('Spending Reports', 'contenly'),
            __('📈 Spending Reports', 'contenly'),
            'manage_options',
            'contenly-member-reports',
            [$this, 'render_spending_reports']
        );
    }
    
    /**
     * Render Dashboard Page
     */
    public function render_dashboard() {
        include_once TRAVEL_MEMBERSHIP_PRO_PATH . '../themes/contenly-theme/admin/page-member-dashboard.php';
    }
    
    /**
     * Render All Members Page
     */
    public function render_all_members() {
        include_once TRAVEL_MEMBERSHIP_PRO_PATH . '../themes/contenly-theme/admin/page-member-all.php';
    }
    
    /**
     * Render Upgrade Payments Page
     */
    public function render_upgrade_payments() {
        include_once TRAVEL_MEMBERSHIP_PRO_PATH . '../themes/contenly-theme/admin/page-member-payments.php';
    }
    
    /**
     * Render Spending Reports Page
     */
    public function render_spending_reports() {
        include_once TRAVEL_MEMBERSHIP_PRO_PATH . '../themes/contenly-theme/admin/page-member-reports.php';
    }
}

new Contenly_Member_Admin();
