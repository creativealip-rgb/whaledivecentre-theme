<?php
/**
 * Midtrans Payment Gateway Configuration
 * 
 * Setup Instructions:
 * 1. Get API keys from https://dashboard.midtrans.com
 * 2. Add to wp-config.php:
 *    define('MIDTRANS_SERVER_KEY', 'your-server-key');
 *    define('MIDTRANS_CLIENT_KEY', 'your-client-key');
 *    define('MIDTRANS_IS_PRODUCTION', false); // true for production
 * 3. Enable webhook notifications
 */

if (!defined('ABSPATH')) {
    exit;
}

class TMP_Midtrans_Gateway {
    
    private $server_key;
    private $client_key;
    private $is_production;
    private $api_url;
    
    public function __construct() {
        $this->server_key = defined('MIDTRANS_SERVER_KEY') ? MIDTRANS_SERVER_KEY : '';
        $this->client_key = defined('MIDTRANS_CLIENT_KEY') ? MIDTRANS_CLIENT_KEY : '';
        $this->is_production = defined('MIDTRANS_IS_PRODUCTION') ? MIDTRANS_IS_PRODUCTION : false;
        $this->api_url = $this->is_production 
            ? 'https://app.midtrans.com' 
            : 'https://app.sandbox.midtrans.com';
    }
    
    /**
     * Create Snap transaction
     */
    public function create_transaction($order_id, $gross_amount, $customer_details, $items = []) {
        if (empty($this->server_key)) {
            return ['error' => 'Midtrans server key not configured'];
        }
        
        $transaction = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => (int) $gross_amount,
            ],
            'customer_details' => $customer_details,
            'item_details' => $items,
            'enabled_payments' => [
                'credit_card',
                'gopay',
                'shopeepay',
                'qris',
                'bca_va',
                'bni_va',
                'permata_va',
                'mandiri_bill',
            ],
        ];
        
        // Call Midtrans Snap API
        $response = wp_remote_post($this->api_url . '/snap/v1/transactions', [
            'method' => 'POST',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->server_key . ':'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($transaction),
            'timeout' => 30,
        ]);
        
        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['token'])) {
            return [
                'success' => true,
                'snap_token' => $body['token'],
                'redirect_url' => $body['redirect_url'],
            ];
        }
        
        return ['error' => $body['status_message'] ?? 'Unknown error'];
    }
    
    /**
     * Get transaction status
     */
    public function get_transaction_status($order_id) {
        if (empty($this->server_key)) {
            return ['error' => 'Midtrans server key not configured'];
        }
        
        $response = wp_remote_get($this->api_url . '/snap/v1/transactions/' . $order_id, [
            'method' => 'GET',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->server_key . ':'),
                'Accept' => 'application/json',
            ],
        ]);
        
        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }
        
        return json_decode(wp_remote_retrieve_body($response), true);
    }
    
    /**
     * Verify webhook signature
     */
    public function verify_signature($signature, $order_id) {
        $hashed = hash('sha512', $order_id . $this->server_key);
        return hash_equals($hashed, $signature);
    }
    
    /**
     * Get client key for frontend
     */
    public function get_client_key() {
        return $this->client_key;
    }
    
    /**
     * Check if configured
     */
    public function is_configured() {
        return !empty($this->server_key) && !empty($this->client_key);
    }
}

/**
 * Initialize Midtrans Gateway
 */
function tmp_get_midtrans_gateway() {
    static $gateway = null;
    if (is_null($gateway)) {
        $gateway = new TMP_Midtrans_Gateway();
    }
    return $gateway;
}
