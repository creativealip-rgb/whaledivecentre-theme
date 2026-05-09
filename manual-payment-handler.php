<?php
/**
 * Manual Payment Handler
 * AJAX handlers for manual payment submission
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load membership plans class
require_once get_template_directory() . '/membership-plans.php';

/**
 * Register AJAX handlers
 */
add_action('wp_ajax_tmp_submit_manual_payment', 'tmp_handle_manual_payment');


/**
 * Handle manual payment submission
 */
function tmp_handle_manual_payment() {
    wp_send_json_error(['message' => 'Upgrade membership manual sudah dihapus. Tier sekarang naik otomatis dari total spending booking.']);

    // Verify nonce
    if (!wp_verify_nonce($_POST['payment_nonce'] ?? '', 'tmp_manual_payment')) {
        wp_send_json_error(['message' => 'Invalid security token']);
    }
    
    // Get data
    $order_id = sanitize_text_field($_POST['order_id'] ?? '');
    $tier = sanitize_text_field($_POST['tier'] ?? '');
    $amount = intval($_POST['amount'] ?? 0);
    $from_bank = sanitize_text_field($_POST['from_bank'] ?? '');
    $sender_name = sanitize_text_field($_POST['sender_name'] ?? '');
    $transfer_date = sanitize_text_field($_POST['transfer_date'] ?? '');
    $notes = sanitize_textarea_field($_POST['notes'] ?? '');
    
    if (empty($order_id) || empty($tier) || $amount <= 0) {
        wp_send_json_error(['message' => 'Invalid payment data']);
    }
    
    // Handle file upload
    $upload_dir = wp_upload_dir();
    $payment_proof_dir = $upload_dir['basedir'] . '/tmp-payment-proofs/';
    
    // Create directory if not exists
    if (!file_exists($payment_proof_dir)) {
        wp_mkdir_p($payment_proof_dir);
    }
    
    // Process uploaded file
    $proof_url = '';
    if (!empty($_FILES['payment_proof'])) {
        $file = $_FILES['payment_proof'];
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_types)) {
            wp_send_json_error(['message' => 'Invalid file type. Please upload JPG, PNG, or GIF.']);
        }
        
        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            wp_send_json_error(['message' => 'File size too large. Max 5MB.']);
        }
        
        // Generate unique filename
        $filename = time() . '-' . sanitize_file_name($file['name']);
        $filepath = $payment_proof_dir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $proof_url = $upload_dir['baseurl'] . '/tmp-payment-proofs/' . $filename;
        } else {
            wp_send_json_error(['message' => 'Failed to upload file']);
        }
    }
    
    wp_send_json_error(['message' => 'Upgrade membership manual sudah dihapus. Tier sekarang naik otomatis dari total spending booking.']);
}

/**
 * Send admin notification
 */
function tmp_send_admin_payment_notification($submission) {
    $admin_email = get_option('admin_email');
    $plans = TMP_Membership_Plans::get_plans();
    $plan = $plans[$submission['tier']] ?? [];
    
    $subject = '🔔 New Manual Payment Submission - ' . $submission['order_id'];
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <h2 style='color: #539294;'>🔔 New Payment Submission</h2>
        
        <p>A user has submitted proof of payment for membership upgrade.</p>
        
        <div style='background: #f0f9ff; border-left: 4px solid #539294; padding: 16px; margin: 20px 0;'>
            <h3 style='margin-top: 0;'>Payment Details:</h3>
            <ul>
                <li><strong>Order ID:</strong> {$submission['order_id']}</li>
                <li><strong>User:</strong> {$submission['sender_name']}</li>
                <li><strong>Tier:</strong> {$plan['name']}</li>
                <li><strong>Amount:</strong> " . number_format($submission['amount'], 0, ',', '.') . " IDR</li>
                <li><strong>From Bank:</strong> {$submission['from_bank']}</li>
                <li><strong>Transfer Date:</strong> {$submission['transfer_date']}</li>
            </ul>
        </div>
        
        <p style='margin-top: 20px;'>
            <a href='" . admin_url('users.php') . "' 
               style='background: linear-gradient(135deg, #539294, #539294); 
                      color: white; 
                      padding: 12px 32px; 
                      text-decoration: none; 
                      border-radius: 8px; 
                      display: inline-block;
                      font-weight: 600;'>
                Review Payment
            </a>
        </p>
        
        <p style='margin-top: 30px; font-size: 14px; color: #64748b;'>
            Please verify the payment and activate the membership.
        </p>
    </body>
    </html>
    ";
    
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    
    wp_mail($admin_email, $subject, $message, $headers);
}

/**
 * Send user confirmation
 */
function tmp_send_user_payment_confirmation($user_id, $submission) {
    $user = get_userdata($user_id);
    $plans = TMP_Membership_Plans::get_plans();
    $plan = $plans[$submission['tier']] ?? [];
    
    $subject = '✅ Payment Proof Received - ' . $submission['order_id'];
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <h2 style='color: #10b981;'>✅ Payment Received!</h2>
        
        <p>Thank you, {$user->display_name}! Your payment proof has been received.</p>
        
        <div style='background: #f0fdf4; border-left: 4px solid #10b981; padding: 16px; margin: 20px 0;'>
            <h3 style='margin-top: 0;'>Submission Details:</h3>
            <ul>
                <li><strong>Order ID:</strong> {$submission['order_id']}</li>
                <li><strong>Membership:</strong> {$plan['name']}</li>
                <li><strong>Amount:</strong> " . number_format($submission['amount'], 0, ',', '.') . " IDR</li>
                <li><strong>Status:</strong> ⏳ Pending Verification</li>
            </ul>
        </div>
        
        <div style='background: #fef3c7; padding: 16px; border-radius: 8px; margin: 20px 0;'>
            <h3 style='margin-top: 0; color: #92400e;'>⏱️ What's Next?</h3>
            <ol style='color: #78350f;'>
                <li>Our team will review your payment within <strong>24 hours</strong></li>
                <li>You'll receive an email when verified</li>
                <li>Your membership will be activated automatically</li>
            </ol>
        </div>
        
        <p style='margin-top: 30px;'>
            <a href='" . home_url('/my-account') . "' 
               style='background: linear-gradient(135deg, #539294, #539294); 
                      color: white; 
                      padding: 12px 32px; 
                      text-decoration: none; 
                      border-radius: 8px; 
                      display: inline-block;
                      font-weight: 600;'>
                Go to My Account
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


