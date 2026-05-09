<?php
/**
 * Admin Settings for Manual Payment
 * Bank accounts configuration
 */

if (!defined('ABSPATH')) {
    exit;
}

class TMP_Payment_Settings {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_post_tmp_save_bank_accounts', [$this, 'save_bank_accounts']);
    }
    
    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'travel-membership',
            'Payment Settings',
            'Payment Settings',
            'manage_options',
            'tmp-payment-settings',
            [$this, 'render_settings_page']
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('tmp_payment_settings', 'tmp_bank_accounts');
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        $bank_accounts = get_option('tmp_bank_accounts', [
            [
                'bank' => 'BCA',
                'account_name' => 'PT TravelShip Indonesia',
                'account_number' => '',
            ],
        ]);
        
        ?>
        <div class="wrap">
            <h1 style="margin-bottom: 20px;">💳 Payment Settings</h1>
            
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 800px;">
                
                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                    <strong>📝 Instructions:</strong>
                    <p style="margin: 8px 0 0 0; color: #78350f;">
                        Add your bank account details here. Users will see these accounts on the checkout page and transfer manually.
                    </p>
                </div>
                
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="tmp_save_bank_accounts">
                    <?php wp_nonce_field('tmp_save_bank_accounts', 'bank_accounts_nonce'); ?>
                    
                    <div id="bank-accounts-container">
                        <?php foreach ($bank_accounts as $index => $bank) : ?>
                        <div class="bank-account-row" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #e5e7eb;">
                            <h3 style="margin-bottom: 12px; font-size: 16px;">Bank Account #<?php echo $index + 1; ?></h3>
                            
                            <div style="display: grid; gap: 12px;">
                                <div>
                                    <label style="display: block; margin-bottom: 4px; font-weight: 600;">Bank Name</label>
                                    <input type="text" name="bank_accounts[<?php echo $index; ?>][bank]" 
                                           value="<?php echo esc_attr($bank['bank']); ?>" 
                                           style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;"
                                           placeholder="e.g., BCA, Mandiri, BNI">
                                </div>
                                
                                <div>
                                    <label style="display: block; margin-bottom: 4px; font-weight: 600;">Account Name</label>
                                    <input type="text" name="bank_accounts[<?php echo $index; ?>][account_name]" 
                                           value="<?php echo esc_attr($bank['account_name']); ?>" 
                                           style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;"
                                           placeholder="e.g., PT TravelShip Indonesia">
                                </div>
                                
                                <div>
                                    <label style="display: block; margin-bottom: 4px; font-weight: 600;">Account Number</label>
                                    <input type="text" name="bank_accounts[<?php echo $index; ?>][account_number]" 
                                           value="<?php echo esc_attr($bank['account_number']); ?>" 
                                           style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;"
                                           placeholder="e.g., 1234567890">
                                </div>
                            </div>
                            
                            <?php if ($index > 0) : ?>
                            <button type="button" class="remove-bank-btn" 
                                    style="margin-top: 12px; background: #fee2e2; color: #dc2626; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 600;"
                                    onclick="this.closest('.bank-account-row').remove()">
                                🗑️ Remove
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" id="add-bank-btn" 
                            style="background: #f0f9ff; color: #0369a1; border: 2px solid #bae6fd; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600; margin-bottom: 24px;">
                        ➕ Add Another Bank Account
                    </button>
                    
                    <button type="submit" 
                            style="background: linear-gradient(135deg, #539294, #539294); color: white; border: none; padding: 12px 32px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                        💾 Save Bank Accounts
                    </button>
                </form>
                
            </div>
            
        </div>
        
        <script>
        // Add bank account row
        document.getElementById('add-bank-btn').addEventListener('click', function() {
            const container = document.getElementById('bank-accounts-container');
            const index = container.querySelectorAll('.bank-account-row').length;
            
            const row = document.createElement('div');
            row.className = 'bank-account-row';
            row.style.cssText = 'background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #e5e7eb;';
            row.innerHTML = `
                <h3 style="margin-bottom: 12px; font-size: 16px;">Bank Account #${index + 1}</h3>
                <div style="display: grid; gap: 12px;">
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: 600;">Bank Name</label>
                        <input type="text" name="bank_accounts[${index}][bank]" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;" placeholder="e.g., BCA, Mandiri, BNI">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: 600;">Account Name</label>
                        <input type="text" name="bank_accounts[${index}][account_name]" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;" placeholder="e.g., PT TravelShip Indonesia">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: 600;">Account Number</label>
                        <input type="text" name="bank_accounts[${index}][account_number]" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;" placeholder="e.g., 1234567890">
                    </div>
                </div>
                <button type="button" class="remove-bank-btn" style="margin-top: 12px; background: #fee2e2; color: #dc2626; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 600;" onclick="this.closest('.bank-account-row').remove()">🗑️ Remove</button>
            `;
            
            container.appendChild(row);
        });
        
        </script>
        <?php
    }
    
    /**
     * Save bank accounts
     */
    public function save_bank_accounts() {
        // Check nonce
        if (!wp_verify_nonce($_POST['bank_accounts_nonce'] ?? '', 'tmp_save_bank_accounts')) {
            wp_die('Invalid security token');
        }
        
        // Check permission
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Sanitize and save
        $bank_accounts = [];
        if (!empty($_POST['bank_accounts'])) {
            foreach ($_POST['bank_accounts'] as $bank) {
                $bank_accounts[] = [
                    'bank' => sanitize_text_field($bank['bank'] ?? ''),
                    'account_name' => sanitize_text_field($bank['account_name'] ?? ''),
                    'account_number' => sanitize_text_field($bank['account_number'] ?? ''),
                ];
            }
        }
        
        update_option('tmp_bank_accounts', $bank_accounts);
        
        // Redirect back with success message
        wp_redirect(admin_url('admin.php?page=tmp-payment-settings&updated=1'));
        exit;
    }
}

// Initialize
new TMP_Payment_Settings();
