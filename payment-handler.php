<?php
/**
 * Payment Handler
 * AJAX handlers and webhook for Midtrans
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('TMP_Payment_Handler')) {

class TMP_Payment_Handler {
    
    public function __construct() {
        // AJAX handlers
        add_action('wp_ajax_tmp_create_payment', [$this, 'create_payment']);
        
        // Webhook handler
        add_action('rest_api_init', [$this, 'register_webhook']);
    }
    
    /**
     * Create payment transaction
     */
    public function create_payment() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'tmp_payment_nonce')) {
            wp_send_json(['error' => 'Invalid nonce']);
        }
        
        // Get data
        $order_id = sanitize_text_field($_POST['order_id'] ?? '');
        $gross_amount = intval($_POST['gross_amount'] ?? 0);
        $first_name = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name = sanitize_text_field($_POST['last_name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $tier = sanitize_text_field($_POST['tier'] ?? '');
        
        if (empty($order_id) || $gross_amount <= 0 || empty($tier)) {
            wp_send_json(['error' => 'Invalid payment data']);
        }
        
        // Load Midtrans
        require_once get_template_directory() . '/midtrans-gateway.php';
        $gateway = tmp_get_midtrans_gateway();
        
        if (!$gateway->is_configured()) {
            wp_send_json(['error' => 'Payment gateway not configured']);
        }
        
        // Prepare customer details
        $customer_details = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
        ];
        
        // Prepare items
        $items = [
            [
                'id' => 'membership-' . $tier,
                'price' => $gross_amount,
                'quantity' => 1,
                'name' => ucfirst($tier) . ' Membership',
            ],
        ];
        
        // Create transaction
        $result = $gateway->create_transaction($order_id, $gross_amount, $customer_details, $items);
        
        if (isset($result['error'])) {
            wp_send_json(['error' => $result['error']]);
        }
        
        // Save order data
        $user_id = get_current_user_id();
        update_user_meta($user_id, '_tmp_last_order_id', $order_id);
        update_user_meta($user_id, '_tmp_last_order_tier', $tier);
        update_user_meta($user_id, '_tmp_last_order_amount', $gross_amount);
        update_user_meta($user_id, '_tmp_last_order_time', current_time('timestamp'));
        
        wp_send_json([
            'success' => true,
            'snap_token' => $result['snap_token'],
            'order_id' => $order_id,
        ]);
    }
    
    /**
     * Register webhook endpoint
     */
    public function register_webhook() {
        register_rest_route('tmp/v1', '/midtrans-webhook', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_webhook'],
            'permission_callback' => '__return_true',
        ]);
    }
    
    /**
     * Handle Midtrans webhook
     */
    public function handle_webhook($request) {
        $json = $request->get_json_params();
        
        if (empty($json)) {
            return new WP_REST_Response(['error' => 'Invalid JSON'], 400);
        }
        
        $order_id = $json['order_id'] ?? '';
        $transaction_status = $json['transaction_status'] ?? '';
        $fraud_status = $json['fraud_status'] ?? '';
        $gross_amount = $json['gross_amount'] ?? 0;
        $signature = $json['signature_key'] ?? '';
        
        // Verify signature
        require_once get_template_directory() . '/midtrans-gateway.php';
        $gateway = tmp_get_midtrans_gateway();
        
        if (!$gateway->verify_signature($signature, $order_id)) {
            return new WP_REST_Response(['error' => 'Invalid signature'], 401);
        }
        
        // Determine payment status
        $payment_status = '';
        if ($transaction_status === 'capture') {
            $payment_status = ($fraud_status === 'accept') ? 'success' : 'challenge';
        } elseif ($transaction_status === 'settlement') {
            $payment_status = 'success';
        } elseif ($transaction_status === 'pending') {
            $payment_status = 'pending';
        } elseif (in_array($transaction_status, ['deny', 'expire', 'cancel'])) {
            $payment_status = 'failed';
        } elseif ($transaction_status === 'refund') {
            $payment_status = 'refunded';
        }
        
        // Update order status
        $this->update_order_status($order_id, $payment_status, $json);
        
        return new WP_REST_Response(['status' => 'ok'], 200);
    }
    
    /**
     * Update order status and activate membership
     */
    private function update_order_status($order_id, $status, $data) {
        // Find user by order
        $users = get_users([
            'meta_key' => '_tmp_last_order_id',
            'meta_value' => $order_id,
        ]);
        
        if (empty($users)) {
            return;
        }
        
        $user = $users[0];
        $user_id = $user->ID;
        $tier = get_user_meta($user_id, '_tmp_last_order_tier', true);
        $amount = get_user_meta($user_id, '_tmp_last_order_amount', true);
        
        // Save transaction log
        $this->log_transaction($user_id, $order_id, $status, $data);
        
        // Activate membership on success
        if ($status === 'success') {
            $this->activate_membership($user_id, $tier, $amount);
        }
    }
    
    /**
     * Activate membership tier
     */
    private function activate_membership($user_id, $tier, $amount) {
        // Update tier
        update_user_meta($user_id, '_tmp_membership_tier', $tier);
        
        // Set expiry (1 month from now)
        $expiry = strtotime('+1 month');
        update_user_meta($user_id, '_tmp_membership_expires', $expiry);
        
        // Record payment
        $payment_id = 'PAY-' . $user_id . '-' . time();
        add_user_meta($user_id, '_tmp_payments', [
            'id' => $payment_id,
            'tier' => $tier,
            'amount' => $amount,
            'date' => current_time('timestamp'),
            'status' => 'success',
        ]);
        
        // Send confirmation email
        $this->send_upgrade_email($user_id, $tier);
    }
    
    /**
     * Log transaction
     */
    private function log_transaction($user_id, $order_id, $status, $data) {
        $log = [
            'user_id' => $user_id,
            'order_id' => $order_id,
            'status' => $status,
            'amount' => $data['gross_amount'] ?? 0,
            'payment_type' => $data['payment_type'] ?? '',
            'transaction_time' => $data['transaction_time'] ?? '',
            'timestamp' => current_time('timestamp'),
        ];
        
        // Save to user meta
        add_user_meta($user_id, '_tmp_transaction_logs', $log);
        
        // Save to file (for debugging)
        $log_file = wp_upload_dir()['basedir'] . '/tmp-payment-logs.txt';
        $log_line = date('Y-m-d H:i:s') . ' | User: ' . $user_id . ' | Order: ' . $order_id . ' | Status: ' . $status . ' | Amount: ' . $log['amount'] . PHP_EOL;
        file_put_contents($log_file, $log_line, FILE_APPEND);
    }
    
    /**
     * Send upgrade confirmation email
     */
    private function send_upgrade_email($user_id, $tier) {
        $user = get_userdata($user_id);
        $plans = TMP_Membership_Plans::get_plans();
        $plan = $plans[$tier];
        
        $subject = '🎉 Welcome to ' . $plan['name'] . ' Membership!';
        
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <h2 style='color: #539294;'>🎉 Congratulations, {$user->display_name}!</h2>
            
            <p>Your <strong>{$plan['name']}</strong> membership has been activated successfully!</p>
            
            <div style='background: #f0f9ff; border-left: 4px solid #539294; padding: 16px; margin: 20px 0;'>
                <h3 style='margin-top: 0;'>Your Benefits:</h3>
                <ul>
                    <li>{$plan['discount']}% discount on all tours</li>
                    <li>" . implode('</li><li>', array_slice($plan['benefits'], 1)) . "</li>
                </ul>
            </div>
            
            <p><strong>Membership Details:</strong></p>
            <ul>
                <li>Tier: {$plan['icon']} {$plan['name']}</li>
                <li>Activated: " . date_i18n(get_option('date_format')) . "</li>
                <li>Expires: " . date_i18n(get_option('date_format'), strtotime('+1 month')) . "</li>
            </ul>
            
            <p style='margin-top: 30px;'>
                <a href='" . home_url('/travel-dashboard') . "' 
                   style='background: linear-gradient(135deg, #539294, #539294); 
                          color: white; 
                          padding: 12px 32px; 
                          text-decoration: none; 
                          border-radius: 8px; 
                          display: inline-block;
                          font-weight: 600;'>
                    Go to Dashboard
                </a>
            </p>
            
            <p style='margin-top: 30px; font-size: 14px; color: #64748b;'>
                Thank you for choosing TravelShip! 🚀
            </p>
        </body>
        </html>
        ";
        
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        
        wp_mail($user->user_email, $subject, $message, $headers);
    }
} // End class

} // End class_exists check

// Initialize
if (class_exists('TMP_Payment_Handler')) {
    new TMP_Payment_Handler();
}
