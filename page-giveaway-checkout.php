<?php
/**
 * Template Name: Giveaway Checkout
 * Payment page for giveaway shipping cost
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_user_logged_in()) {
    wp_redirect(add_query_arg('redirect_to', rawurlencode($_SERVER['REQUEST_URI'] ?? '/dashboard/'), contenly_localized_url('/login/')));
    exit;
}

$user_id = get_current_user_id();
$order_id = sanitize_text_field($_GET['order'] ?? '');

// Get giveaway order from user meta
$giveaway_order = get_user_meta($user_id, '_wdc_giveaway_order', true);

if (!$giveaway_order || !is_array($giveaway_order)) {
    wp_redirect(contenly_localized_url('/dashboard/'));
    exit;
}

// Verify order ID matches
if ($order_id && ($giveaway_order['order_id'] ?? '') !== $order_id) {
    wp_redirect(contenly_localized_url('/dashboard/'));
    exit;
}

// Already past payment stage? send back to dashboard progress
$gw_st = sanitize_key($giveaway_order['status'] ?? '');
if (in_array($gw_st, ['payment_uploaded', 'verified', 'shipped', 'delivered', 'paid'], true)) {
    wp_redirect(add_query_arg('giveaway', $gw_st, contenly_localized_url('/dashboard/')));
    exit;
}
if ($gw_st === 'cancelled') {
    wp_redirect(add_query_arg('giveaway', 'cancelled', contenly_localized_url('/dashboard/')));
    exit;
}

// Get item details
$all_items = function_exists('wdc_get_giveaway_items') ? wdc_get_giveaway_items() : [];
$selected_items = [];
foreach ($all_items as $item) {
    if (in_array($item['id'], $giveaway_order['items'] ?? [], true)) {
        $selected_items[] = $item;
    }
}

$total_weight = array_sum(array_column($selected_items, 'weight'));
$shipping_cost = intval($giveaway_order['shipping_cost'] ?? 0);
$courier = esc_html(strtoupper($giveaway_order['courier'] ?? ''));
$service = esc_html($giveaway_order['service'] ?? '');
$dest = esc_html($giveaway_order['destination'] ?? '');
$address = esc_html($giveaway_order['address'] ?? '');
$phone = esc_html($giveaway_order['phone'] ?? '');
$name = esc_html($giveaway_order['recipient_name'] ?? '');

$bank_accounts = get_option('wm_bank_accounts', []);
if (!is_array($bank_accounts) || empty($bank_accounts)) {
    $bank_accounts = get_option('tmp_bank_accounts', []);
}
if (!is_array($bank_accounts) || empty($bank_accounts)) {
    $bank_accounts = [[
        'bank' => 'BCA',
        'account_name' => 'Whale Dive Centre',
        'account_number' => 'Isi di WDC Members → Payment Settings',
    ]];
}

$icons = [
    'sticker-pack' => '🏷️',
    'lanyard' => '🪢',
    'keychain' => '🔑',
];
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
<style id="wdc-giveaway-checkout-layout">
.whaledive-giveaway-checkout{
  background:#f6fbfd;
  color:#0f172a;
  font-family:"Plus Jakarta Sans",system-ui,-apple-system,Segoe UI,Roboto,sans-serif;
}
.whaledive-giveaway-checkout .wdc-gco-wrap{
  max-width:1100px;
  margin:0 auto;
  padding:118px 22px 56px;
}
.whaledive-giveaway-checkout .wdc-gco-banner{
  display:flex;
  align-items:flex-start;
  gap:12px;
  padding:14px 16px;
  margin:0 0 18px;
  border-radius:14px;
  background:#fffbeb;
  border:1px solid #fde68a;
}
.whaledive-giveaway-checkout .wdc-gco-banner-icon{
  width:36px;height:36px;border-radius:10px;
  display:inline-flex;align-items:center;justify-content:center;
  background:#fef3c7;font-size:18px;flex:0 0 auto
}
.whaledive-giveaway-checkout .wdc-gco-banner h1{
  margin:0 0 4px;font-size:18px;line-height:1.25;font-weight:800;color:#0f172a
}
.whaledive-giveaway-checkout .wdc-gco-banner p{
  margin:0;font-size:13px;line-height:1.5;color:#92400e;font-weight:500
}
.whaledive-giveaway-checkout .wdc-gco-grid{
  display:grid;
  grid-template-columns:minmax(0,1.05fr) minmax(300px,.95fr);
  gap:18px;
  align-items:start
}
.whaledive-giveaway-checkout .wdc-gco-card{
  background:#fff;
  border:1px solid #e2e8f0;
  border-radius:16px;
  box-shadow:0 10px 28px rgba(15,23,42,.05);
  padding:18px
}
.whaledive-giveaway-checkout .wdc-gco-card h2{
  margin:0 0 14px;
  font-size:17px;
  font-weight:800;
  letter-spacing:-.01em;
  color:#0f172a
}
.whaledive-giveaway-checkout .wdc-gco-kicker{
  margin:0 0 8px;
  font-size:11px;
  font-weight:800;
  letter-spacing:.06em;
  text-transform:uppercase;
  color:#64748b
}
.whaledive-giveaway-checkout .wdc-gco-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px 12px;
  border-radius:12px;
  background:#f0fdf4;
  border:1px solid #dcfce7;
  margin:0 0 8px
}
.whaledive-giveaway-checkout .wdc-gco-item-ico{font-size:20px;line-height:1}
.whaledive-giveaway-checkout .wdc-gco-item strong{
  display:block;font-size:13px;font-weight:700;color:#0f172a;line-height:1.3
}
.whaledive-giveaway-checkout .wdc-gco-item span{
  display:block;font-size:12px;color:#64748b;margin-top:2px
}
.whaledive-giveaway-checkout .wdc-gco-badge{
  margin-left:auto;
  font-size:11px;font-weight:800;
  color:#166534;background:#dcfce7;
  border-radius:999px;padding:4px 8px
}
.whaledive-giveaway-checkout .wdc-gco-box{
  background:#f8fafc;
  border:1px solid #eef2f6;
  border-radius:12px;
  padding:12px 14px;
  font-size:13px;
  line-height:1.65;
  color:#334155
}
.whaledive-giveaway-checkout .wdc-gco-box strong{color:#0f172a}
.whaledive-giveaway-checkout .wdc-gco-section{margin-top:14px;padding-top:14px;border-top:1px solid #eef2f6}
.whaledive-giveaway-checkout .wdc-gco-quote img{
  width:100%;max-height:220px;object-fit:contain;
  border-radius:12px;border:1px solid #e5e7eb;background:#f8fafc
}
.whaledive-giveaway-checkout .wdc-gco-quote p{
  margin:8px 0 0;font-size:12px;color:#64748b
}
.whaledive-giveaway-checkout .wdc-gco-quote a{color:#004A98;font-weight:600;text-decoration:none}
.whaledive-giveaway-checkout .wdc-gco-row{
  display:flex;justify-content:space-between;align-items:center;gap:12px;
  margin:0 0 8px;font-size:13px
}
.whaledive-giveaway-checkout .wdc-gco-row span{color:#64748b}
.whaledive-giveaway-checkout .wdc-gco-row b{color:#0f172a;font-weight:700}
.whaledive-giveaway-checkout .wdc-gco-total{
  display:flex;justify-content:space-between;align-items:center;gap:12px;
  margin-top:10px;padding-top:12px;border-top:1px solid #e2e8f0
}
.whaledive-giveaway-checkout .wdc-gco-total span{
  font-size:14px;font-weight:800;color:#0f172a
}
.whaledive-giveaway-checkout .wdc-gco-total b{
  font-size:20px;font-weight:900;color:#004A98;letter-spacing:-.02em
}
.whaledive-giveaway-checkout .wdc-gco-paybox{
  background:#fffbeb;
  border:1px solid #fde68a;
  border-radius:12px;
  padding:12px 14px;
  margin:0 0 14px
}
.whaledive-giveaway-checkout .wdc-gco-paybox > p{
  margin:0 0 10px;font-size:13px;line-height:1.5;color:#92400e;font-weight:600
}
.whaledive-giveaway-checkout .wdc-gco-bank{
  background:#fff;
  border:1px solid #fde68a;
  border-radius:10px;
  padding:10px 12px;
  margin:0 0 8px;
  font-size:13px;line-height:1.55;color:#9a3412
}
.whaledive-giveaway-checkout .wdc-gco-bank strong{display:block;color:#0f172a;margin-bottom:2px}
.whaledive-giveaway-checkout .wdc-gco-amount{
  margin:8px 0 0;font-size:13px;line-height:1.5;color:#92400e
}
.whaledive-giveaway-checkout .wdc-gco-form{display:grid;gap:12px}
.whaledive-giveaway-checkout .wdc-gco-field{display:grid;gap:5px}
.whaledive-giveaway-checkout .wdc-gco-field label{
  font-size:12px;font-weight:700;color:#475569
}
.whaledive-giveaway-checkout .wdc-gco-field input,
.whaledive-giveaway-checkout .wdc-gco-field textarea{
  width:100%;
  border:1px solid #dbe4ea;
  border-radius:10px;
  padding:9px 11px;
  font-size:13px;
  color:#0f172a;
  background:#fff;
  box-sizing:border-box
}
.whaledive-giveaway-checkout .wdc-gco-field input{
  min-height:38px
}
.whaledive-giveaway-checkout .wdc-gco-field input:focus,
.whaledive-giveaway-checkout .wdc-gco-field textarea:focus{
  outline:none;border-color:#004A98;box-shadow:0 0 0 3px rgba(0,74,152,.12)
}
.whaledive-giveaway-checkout .wdc-gco-help{
  margin:0;font-size:12px;color:#64748b;line-height:1.4
}
.whaledive-giveaway-checkout .wdc-gco-error{
  display:none;background:#fee2e2;color:#991b1b;border-radius:10px;padding:10px 12px;font-size:13px;font-weight:600
}
.whaledive-giveaway-checkout .wdc-gco-submit{
  width:100%;
  min-height:40px;
  border:0;
  border-radius:999px;
  background:#004A98;
  color:#fff;
  font-size:13px;
  font-weight:800;
  cursor:pointer
}
.whaledive-giveaway-checkout .wdc-gco-submit:hover{background:#3B44AC}
.whaledive-giveaway-checkout .wdc-gco-submit:disabled{opacity:.7;cursor:not-allowed}
.whaledive-giveaway-checkout .wdc-gco-success{display:none;text-align:center;padding:18px 8px}
.whaledive-giveaway-checkout .wdc-gco-success h3{
  margin:0 0 6px;font-size:18px;font-weight:800;color:#0f172a
}
.whaledive-giveaway-checkout .wdc-gco-success p{
  margin:0 0 14px;font-size:13px;color:#64748b;line-height:1.5
}
.whaledive-giveaway-checkout .wdc-gco-success a{
  display:inline-flex;align-items:center;justify-content:center;
  min-height:38px;padding:0 16px;border-radius:999px;
  background:#004A98;color:#fff;text-decoration:none;font-size:13px;font-weight:800
}
.whaledive-giveaway-checkout .wdc-gco-back{
  margin-top:16px;text-align:center
}
.whaledive-giveaway-checkout .wdc-gco-back a{
  color:#64748b;text-decoration:none;font-size:13px;font-weight:600
}
.whaledive-giveaway-checkout .wdc-gco-back a:hover{color:#004A98}
@media(max-width:900px){
  .whaledive-giveaway-checkout .wdc-gco-wrap{padding:104px 16px 40px}
  .whaledive-giveaway-checkout .wdc-gco-grid{grid-template-columns:1fr}
}
@media(max-width:700px){
  .whaledive-giveaway-checkout .wdc-gco-field input,
  .whaledive-giveaway-checkout .wdc-gco-field textarea{font-size:16px;min-height:42px}
  .whaledive-giveaway-checkout .wdc-gco-card{padding:16px}
  .whaledive-giveaway-checkout .wdc-gco-banner h1{font-size:16px}
}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-giveaway-checkout'); ?>>
<?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <div class="wdc-gco-wrap">
    <div class="wdc-gco-banner">
      <div class="wdc-gco-banner-icon">🎁</div>
      <div>
        <h1><?php echo esc_html(contenly_tr('Giveaway Diklaim!', 'Giveaway Claimed!')); ?></h1>
        <p><?php echo esc_html(contenly_tr('Barangnya gratis. Transfer ongkir harus sama persis dengan nominal SS cek ongkir.', 'Items are free. Shipping transfer must exactly match the quote screenshot amount.')); ?></p>
      </div>
    </div>

    <div class="wdc-gco-grid">
      <!-- LEFT: summary -->
      <section class="wdc-gco-card">
        <h2><?php echo esc_html(contenly_tr('Ringkasan Pesanan', 'Order Summary')); ?></h2>

        <div class="wdc-gco-kicker"><?php echo esc_html(contenly_tr('Item Giveaway', 'Giveaway Items')); ?></div>
        <?php if ($selected_items) : ?>
          <?php foreach ($selected_items as $item) : ?>
          <div class="wdc-gco-item">
            <div class="wdc-gco-item-ico"><?php echo esc_html($icons[$item['id']] ?? '🎁'); ?></div>
            <div>
              <strong><?php echo esc_html($item['name']); ?></strong>
              <span><?php echo esc_html($item['desc'] ?? ''); ?><?php echo !empty($item['weight']) ? ' · ' . (int) $item['weight'] . 'g' : ''; ?></span>
            </div>
            <div class="wdc-gco-badge"><?php echo esc_html(contenly_tr('GRATIS', 'FREE')); ?></div>
          </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="wdc-gco-box"><?php echo esc_html(contenly_tr('Item giveaway tidak ditemukan.', 'Giveaway items not found.')); ?></div>
        <?php endif; ?>

        <div class="wdc-gco-section">
          <div class="wdc-gco-kicker"><?php echo esc_html(contenly_tr('Detail Pengiriman', 'Shipping Details')); ?></div>
          <div class="wdc-gco-box">
            <strong><?php echo $name; ?></strong><br>
            <?php echo $phone; ?><br>
            <?php echo $address; ?><br>
            <?php echo $dest; ?>
          </div>
        </div>

        <?php if (!empty($giveaway_order['quote_ss_url'])) : ?>
        <div class="wdc-gco-section wdc-gco-quote">
          <div class="wdc-gco-kicker"><?php echo esc_html(contenly_tr('SS Cek Ongkir', 'Shipping Quote Screenshot')); ?></div>
          <a href="<?php echo esc_url($giveaway_order['quote_ss_url']); ?>" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo esc_url($giveaway_order['quote_ss_url']); ?>" alt="Shipping quote">
          </a>
          <?php if (!empty($giveaway_order['quote_source'])) : ?>
          <p>Source: <a href="<?php echo esc_url($giveaway_order['quote_source']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($giveaway_order['quote_source']); ?></a></p>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="wdc-gco-section">
          <div class="wdc-gco-row">
            <span><?php echo esc_html(contenly_tr('Item Giveaway', 'Giveaway Items')); ?></span>
            <b><?php echo (int) count($selected_items); ?> item</b>
          </div>
          <div class="wdc-gco-row">
            <span><?php echo esc_html(contenly_tr('Berat Total', 'Total Weight')); ?></span>
            <b><?php echo (int) $total_weight; ?>g</b>
          </div>
          <div class="wdc-gco-row">
            <span><?php echo esc_html(contenly_tr('Kurir', 'Courier')); ?></span>
            <b><?php echo trim($courier . ' ' . $service); ?></b>
          </div>
          <div class="wdc-gco-total">
            <span><?php echo esc_html(contenly_tr('Total Ongkir', 'Total Shipping')); ?></span>
            <b>Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></b>
          </div>
        </div>
      </section>

      <!-- RIGHT: payment -->
      <aside class="wdc-gco-card">
        <h2><?php echo esc_html(contenly_tr('Instruksi Pembayaran', 'Payment Instructions')); ?></h2>

        <div class="wdc-gco-paybox">
          <p><?php echo esc_html(contenly_tr('Transfer bank (nominal harus sesuai ongkir SS):', 'Bank transfer (amount must match quote shipping):')); ?></p>
          <?php foreach ($bank_accounts as $bank) : ?>
          <div class="wdc-gco-bank">
            <strong><?php echo esc_html($bank['bank'] ?? 'Bank'); ?></strong>
            <?php echo esc_html(contenly_tr('Rekening', 'Account')); ?>: <?php echo esc_html($bank['account_number'] ?? '-'); ?><br>
            <?php echo esc_html(contenly_tr('Nama Rekening', 'Account Name')); ?>: <?php echo esc_html($bank['account_name'] ?? '-'); ?>
          </div>
          <?php endforeach; ?>
          <p class="wdc-gco-amount">
            <strong><?php echo esc_html(contenly_tr('Jumlah transfer:', 'Transfer amount:')); ?> Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></strong><br>
            <?php echo esc_html(contenly_tr('Kalau nominal beda, upload ditolak.', 'If the amount differs, the upload is rejected.')); ?>
          </p>
        </div>

        <form id="wdc-giveaway-payment-form" class="wdc-gco-form">
          <input type="hidden" name="order_id" value="<?php echo esc_attr($giveaway_order['order_id']); ?>">
          <input type="hidden" id="wdc-gw-expected-amount" value="<?php echo esc_attr($shipping_cost); ?>">

          <div class="wdc-gco-field">
            <label for="wdc-gw-paid-amount"><?php echo esc_html(contenly_tr('Nominal Transfer (Rp) *', 'Transfer Amount (Rp) *')); ?></label>
            <input type="text" name="paid_amount" id="wdc-gw-paid-amount" inputmode="numeric" required value="<?php echo esc_attr(number_format($shipping_cost, 0, ',', '.')); ?>">
            <p class="wdc-gco-help"><?php echo esc_html(contenly_tr('Isi sama dengan Total Ongkir di ringkasan.', 'Must match Total Shipping in the summary.')); ?></p>
          </div>

          <div class="wdc-gco-field">
            <label for="wdc-gw-payment-proof"><?php echo esc_html(contenly_tr('Upload Bukti Transfer *', 'Upload Transfer Proof *')); ?></label>
            <input id="wdc-gw-payment-proof" type="file" name="payment_proof" accept="image/*" required>
            <p class="wdc-gco-help"><?php echo esc_html(contenly_tr('JPG/PNG. Maks 5MB.', 'JPG/PNG. Max 5MB.')); ?></p>
          </div>

          <div class="wdc-gco-field">
            <label for="wdc-gw-notes"><?php echo esc_html(contenly_tr('Catatan (Opsional)', 'Notes (Optional)')); ?></label>
            <textarea id="wdc-gw-notes" name="notes" rows="2" placeholder="<?php echo esc_attr(contenly_tr('Jam transfer, dll...', 'Transfer time, etc...')); ?>"></textarea>
          </div>

          <div id="wdc-gw-pay-error" class="wdc-gco-error"></div>

          <button type="submit" id="wdc-gw-upload-btn" class="wdc-gco-submit">
            <?php echo esc_html(contenly_tr('Upload Bukti Transfer', 'Upload Transfer Proof')); ?>
          </button>
        </form>

        <div id="wdc-gw-payment-success" class="wdc-gco-success">
          <div style="font-size:42px;margin-bottom:8px;">✅</div>
          <h3><?php echo esc_html(contenly_tr('Bukti Transfer Diterima!', 'Transfer Proof Received!')); ?></h3>
          <p><?php echo esc_html(contenly_tr('Crew akan verifikasi dalam 24 jam. Giveaway dikirim setelah verifikasi.', 'Crew will verify within 24 hours. Giveaway ships after verification.')); ?></p>
          <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>">
            <?php echo esc_html(contenly_tr('Kembali ke Dashboard', 'Back to Dashboard')); ?>
          </a>
        </div>
      </aside>
    </div>

    <div class="wdc-gco-back">
      <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>">← <?php echo esc_html(contenly_tr('Kembali ke Dashboard', 'Back to Dashboard')); ?></a>
    </div>
  </div>

  <?php contenly_render_public_footer(); ?>
</main>

<script>
jQuery(document).ready(function($) {
    function onlyDigits(v){ return String(v || '').replace(/\D+/g, ''); }
    function showPayErr(msg){ $('#wdc-gw-pay-error').text(msg).show(); }
    function hidePayErr(){ $('#wdc-gw-pay-error').hide(); }

    $('#wdc-gw-paid-amount').on('input', function(){
        var d = onlyDigits(this.value);
        this.value = d ? Number(d).toLocaleString('id-ID') : '';
    });

    $('#wdc-giveaway-payment-form').on('submit', function(e) {
        e.preventDefault();

        var form = this;
        var btn = $('#wdc-gw-upload-btn');
        var originalText = btn.html();
        var expected = parseInt($('#wdc-gw-expected-amount').val(), 10) || 0;
        var paid = parseInt(onlyDigits($('#wdc-gw-paid-amount').val()), 10) || 0;

        hidePayErr();
        if (!expected || paid !== expected) {
            showPayErr('<?php echo esc_js(contenly_tr('Nominal transfer harus sama persis dengan ongkir: Rp ', 'Transfer amount must exactly match shipping: Rp ')); ?>' + expected.toLocaleString('id-ID'));
            return;
        }

        btn.prop('disabled', true).html('<?php echo esc_js(contenly_tr('Mengupload...', 'Uploading...')); ?>');

        var formData = new FormData(form);
        formData.set('paid_amount', String(paid));
        formData.append('action', 'wdc_upload_giveaway_payment');
        formData.append('nonce', (window.wdcGiveawayAjax && wdcGiveawayAjax.nonce) ? wdcGiveawayAjax.nonce : '');

        $.ajax({
            url: (window.wdcGiveawayAjax && wdcGiveawayAjax.ajaxurl) ? wdcGiveawayAjax.ajaxurl : '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res && res.success) {
                    $('#wdc-giveaway-payment-form').hide();
                    $('#wdc-gw-payment-success').show();
                } else {
                    showPayErr((res && res.data && res.data.message) ? res.data.message : '<?php echo esc_js(contenly_tr('Upload gagal.', 'Upload failed.')); ?>');
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                showPayErr('<?php echo esc_js(contenly_tr('Upload gagal. Coba lagi.', 'Upload failed. Try again.')); ?>');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
<?php wp_footer(); ?>
</body>
</html>
