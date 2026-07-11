<?php
/**
 * Template Name: Checkout (Manual Payment)
 */

wp_safe_redirect(contenly_localized_url('/membership/'));
exit;

// Get pricing
if (!class_exists('TMP_Membership_Plans')) {
    require_once get_template_directory() . '/membership-plans.php';
}
$plans = TMP_Membership_Plans::get_plans();
$plan = isset($plans[$tier]) ? $plans[$tier] : ['name' => ucfirst($tier), 'price' => 0, 'price_display' => 'Rp 0', 'icon' => '🎒', 'discount' => 0, 'billing_period' => 'monthly', 'benefits' => []];
$user_id = get_current_user_id();
$user = wp_get_current_user();

// Get bank accounts from settings
$bank_accounts = get_option('tmp_bank_accounts', [
    [
        'bank' => 'BCA',
        'account_name' => 'PT TravelShip Indonesia',
        'account_number' => '1234567890',
    ],
    [
        'bank' => 'Mandiri',
        'account_name' => 'PT TravelShip Indonesia',
        'account_number' => '9876543210',
    ],
]);

// Create order ID
$order_id = 'MANUAL-' . $user_id . '-' . time();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8">
    ">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manual Payment - <?php echo $plan['name']; ?> Membership</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(180deg, #f1f5f9 0%, #ffffff 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .checkout-container { background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); max-width: 600px; width: 100%; overflow: hidden; }
        .checkout-header { background: linear-gradient(135deg, #539294, #539294); color: white; padding: 32px; text-align: center; }
        .checkout-header h1 { font-size: 28px; font-weight: 800; margin-bottom: 8px; }
        .checkout-header p { opacity: 0.9; font-size: 15px; }
        .checkout-body { padding: 32px; }
        .order-summary { background: #f8fafc; border-radius: 16px; padding: 24px; margin-bottom: 24px; }
        .order-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; }
        .order-row:last-child { margin-bottom: 0; padding-top: 12px; border-top: 2px solid #e2e8f0; font-weight: 700; font-size: 18px; color: #0f172a; }
        .bank-accounts { margin-bottom: 24px; }
        .bank-account { background: #f0f9ff; border: 2px solid #bae6fd; border-radius: 16px; padding: 20px; margin-bottom: 16px; }
        .bank-account:last-child { margin-bottom: 0; }
        .bank-name { font-size: 20px; font-weight: 700; color: #0369a1; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .bank-details { display: grid; gap: 8px; }
        .detail-row { display: flex; justify-content: space-between; font-size: 14px; }
        .detail-label { color: #64748b; }
        .detail-value { color: #0f172a; font-weight: 600; }
        .copy-btn { background: #0ea5e9; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .copy-btn:hover { background: #0284c7; }
        .instructions { background: #fef3c7; border: 2px solid #fcd34d; border-radius: 16px; padding: 20px; margin-bottom: 24px; }
        .instructions h3 { font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .instructions ol { padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.8; }
        .instructions li { margin-bottom: 8px; }
        .upload-section { background: #f0fdf4; border: 2px solid #86efac; border-radius: 16px; padding: 20px; margin-bottom: 24px; }
        .upload-section h3 { font-size: 16px; font-weight: 700; color: #166534; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .upload-form { display: grid; gap: 12px; }
        .form-group { display: grid; gap: 6px; }
        .form-group label { font-size: 14px; font-weight: 600; color: #0f172a; }
        .form-group input, .form-group textarea { padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #10b981; }
        .submit-btn { width: 100%; padding: 16px; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3); }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4); }
        .cancel-link { text-align: center; margin-top: 16px; }
        .cancel-link a { color: #64748b; text-decoration: none; font-size: 14px; }
        .cancel-link a:hover { color: #539294; }
        .alert { background: #DCE9E6; border-left: 4px solid #539294; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; color: #355F72; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>💳 Manual Payment</h1>
            <p><?php echo $plan['name']; ?> Membership</p>
        </div>
        
        <div class="checkout-body">
            <!-- Order Summary -->
            <div class="order-summary">
                <div class="order-row">
                    <span>Membership Tier</span>
                    <span><?php echo $plan['icon']; ?> <?php echo $plan['name']; ?></span>
                </div>
                <div class="order-row">
                    <span>Billing Period</span>
                    <span><?php echo $plan['billing_period'] ?? 'monthly'; ?></span>
                </div>
                <div class="order-row">
                    <span>Discount</span>
                    <span style="color: #10b981;"><?php echo $plan['discount'] ?? 0; ?>% OFF on all tours</span>
                </div>
                <div class="order-row">
                    <span>Total Amount</span>
                    <span><?php echo $plan['price_display']; ?></span>
                </div>
            </div>

            <!-- Alert -->
            <div class="alert">
                🔒 <strong>Secure Manual Payment:</strong> Transfer to one of the bank accounts below, then upload proof of payment.
            </div>
            
            <!-- Bank Accounts -->
            <div class="bank-accounts">
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">🏦 Bank Accounts</h3>
                
                <?php foreach ($bank_accounts as $bank) : ?>
                <div class="bank-account">
                    <div class="bank-name">
                        🏦 <?php echo esc_html($bank['bank']); ?>
                    </div>
                    <div class="bank-details">
                        <div class="detail-row">
                            <span class="detail-label">Account Name</span>
                            <span class="detail-value"><?php echo esc_html($bank['account_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Account Number</span>
                            <span class="detail-value">
                                <?php echo esc_html($bank['account_number']); ?>
                                <button class="copy-btn" onclick="copyText('<?php echo esc_js($bank['account_number']); ?>')">📋 Copy</button>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <h3>📝 How to Pay:</h3>
                <ol>
                    <li>Transfer <strong><?php echo $plan['price_display']; ?></strong> to one of the bank accounts above</li>
                    <li>Save the proof of payment (screenshot or photo)</li>
                    <li>Fill in the form below with your details</li>
                    <li>Upload proof of payment</li>
                    <li>Click "Submit Payment"</li>
                    <li>Admin will verify within 24 hours</li>
                    <li>You'll receive email confirmation when activated</li>
                </ol>
            </div>
            
            <!-- Upload Form -->
            <div class="upload-section">
                <h3>📤 Submit Payment Proof</h3>
                <form id="payment-form" class="upload-form" enctype="multipart/form-data" method="POST" action="">
                    <input type="hidden" name="action" value="tmp_submit_manual_payment">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="tier" value="<?php echo $tier; ?>">
                    <input type="hidden" name="amount" value="<?php echo $plan['price']; ?>">
                    <?php wp_nonce_field('tmp_manual_payment', 'payment_nonce'); ?>
                    
                    <div class="form-group">
                        <label>Transfer From (Bank Name)</label>
                        <input type="text" name="from_bank" required placeholder="e.g., BCA, Mandiri, BNI">
                    </div>
                    
                    <div class="form-group">
                        <label>Sender Name</label>
                        <input type="text" name="sender_name" value="<?php echo esc_attr($user->display_name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Transfer Date</label>
                        <input type="date" name="transfer_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload Proof of Payment</label>
                        <input type="file" name="payment_proof" accept="image/*" required>
                        <small style="color: #64748b; font-size: 13px;">JPG, PNG, or GIF (max 5MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Any additional information..."></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        ✅ Submit Payment Proof
                    </button>
                </form>
            </div>
            
            <!-- Cancel Link -->
            <div class="cancel-link">
                <a href="/membership">← Back to Membership</a>
            </div>
        </div>
    </div>
    
    <script>
    function copyText(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Account number copied: ' + text);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
    
    // Form submission
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('.submit-btn');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        
        fetch('/wp-admin/admin-ajax.php?action=tmp_submit_manual_payment', {
            method: 'POST',
            body: formData
        })
        .then(res => {
            // Check if response is HTML (error page)
            const contentType = res.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') === -1) {
                throw new Error('Server returned HTML instead of JSON. Check server logs.');
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = '/payment-pending?order_id=' + formData.get('order_id');
            } else {
                alert('Error: ' + (data.data?.message || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.textContent = '✅ Submit Payment Proof';
            }
        })
        .catch(err => {
            console.error('Submission error:', err);
            alert('Error: ' + err.message + '\n\nKalau masalah berlanjut, isi form kontak agar admin bisa cek.');
            submitBtn.disabled = false;
            submitBtn.textContent = '✅ Submit Payment Proof';
        });
        
        return false;
    });
    </script>
</body>
</html>
