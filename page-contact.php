<?php
/**
 * Template Name: Contact Page
 * Form + info cards matched to About page compact contact section.
 */
$wd_contact_notice = '';
$wd_contact_notice_type = '';

if ('POST' === ($_SERVER['REQUEST_METHOD'] ?? '') && isset($_POST['wd_contact_submit'])) {
    $nonce_ok = isset($_POST['wd_contact_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wd_contact_nonce'])), 'wd_contact_inquiry');
    $honeypot = trim((string) ($_POST['website'] ?? ''));
    $name = sanitize_text_field(wp_unslash($_POST['your-name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $whatsapp = sanitize_text_field(wp_unslash($_POST['whatsapp'] ?? ''));
    $category = sanitize_text_field(wp_unslash($_POST['category'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if ($nonce_ok && '' === $honeypot && $name && $whatsapp) {
        $recipient = function_exists('wdc_contact_inquiry_recipient') ? wdc_contact_inquiry_recipient() : (get_option('admin_email') ?: 'info@whaledivecentre.com');
        $subject = 'New Whale Dive Centre inquiry - ' . ($category ?: 'General');
        $body = "Name: {$name}\nEmail: " . ($email ?: '-') . "\nWhatsApp: {$whatsapp}\nCategory: " . ($category ?: '-') . "\n\nMessage:\n" . ($message ?: '-');
        $headers = ['Content-Type: text/plain; charset=UTF-8'];
        if ($email) {
            $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
        }
        $sent = wp_mail($recipient, $subject, $body, $headers);
        $target = add_query_arg('wd_contact', $sent ? 'sent' : 'mail-error', get_permalink());
        wp_safe_redirect($target . '#contact-form');
        exit;
    }

    $wd_contact_notice = contenly_tr('Mohon isi nama dan nomor WhatsApp dengan benar.', 'Please fill in your name and WhatsApp number correctly.');
    $wd_contact_notice_type = 'error';
}

if (isset($_GET['wd_contact'])) {
    if ('sent' === $_GET['wd_contact']) {
        $wd_contact_notice = function_exists('wdc_site_get') ? wdc_site_tr('contact_success', 'Terima kasih. Pesan Anda sudah terkirim dan crew akan membalas dalam 24 jam.', 'Thank you. Your inquiry has been sent and our crew will reply within 24 hours.') : contenly_tr('Terima kasih. Pesan Anda sudah terkirim dan crew akan membalas dalam 24 jam.', 'Thank you. Your inquiry has been sent and our crew will reply within 24 hours.');
        $wd_contact_notice_type = 'success';
    } elseif ('mail-error' === $_GET['wd_contact']) {
        $wd_contact_notice = contenly_tr('Pesan belum berhasil dikirim oleh server email. Silakan hubungi kami via telepon jika urgent.', 'The mail server could not send your inquiry yet. Please call us if urgent.');
        $wd_contact_notice_type = 'error';
    }
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
<style id="wd-contact-match-about-compact">
/* Shared notice/privacy */
.wd-contact-form small,
.wdc-contact-form small{display:block;margin-top:7px;color:#64748b;font-size:12px;line-height:1.45}
.wd-form-privacy{margin:0;color:#64748b;font-size:13px;line-height:1.5}
.wd-contact-notice{margin:12px 0 18px;padding:14px 16px;border-radius:16px;font-weight:800;line-height:1.45}
.wd-contact-notice.success{background:#e7f8ef;color:#05603a;border:1px solid rgba(5,96,58,.18)}
.wd-contact-notice.error{background:#fff3e8;color:#9a3412;border:1px solid rgba(154,52,18,.18)}
.wd-contact-hp{position:absolute;left:-9999px;opacity:0;pointer-events:none}

/* Hero */
body.whaledive-contact .wd-inner-hero{padding-top:150px!important;padding-bottom:58px!important;min-height:auto!important}
body.whaledive-contact .wd-inner-hero h1{max-width:18ch}
body.whaledive-contact .wd-inner-hero p{max-width:52ch;color:rgba(255,255,255,.9)}

/* Compact contact block = same as About */
body.whaledive-contact #contact-form.wdc-contact-compact{
  background:linear-gradient(180deg,#fff 0%,#f5fbff 100%)!important;
  padding-top:48px!important;
  padding-bottom:56px!important;
}
body.whaledive-contact .wdc-contact-head{max-width:640px;margin:0 0 22px}
body.whaledive-contact .wdc-contact-lead{margin:8px 0 0;color:#5b7180;font-size:15px;line-height:1.55;max-width:52ch}
body.whaledive-contact .wdc-contact-layout{
  display:grid;
  grid-template-columns:minmax(240px,.9fr) minmax(0,1.25fr);
  gap:18px;
  align-items:stretch;
  margin-top:8px;
}
body.whaledive-contact .wdc-contact-info{
  background:#fff;
  border:1px solid rgba(0,74,152,.10);
  border-radius:18px;
  box-shadow:0 10px 28px rgba(3,36,58,.06);
  padding:18px 18px 16px;
  display:flex;
  flex-direction:column;
}
body.whaledive-contact .wdc-contact-row{
  display:grid;
  gap:3px;
  padding:12px 0;
  border-bottom:1px solid rgba(0,74,152,.08);
}
body.whaledive-contact .wdc-contact-row:first-child{padding-top:2px}
body.whaledive-contact .wdc-contact-row:last-child{border-bottom:0;padding-bottom:2px}
body.whaledive-contact .wdc-contact-label{
  font-size:11px;
  font-weight:800;
  letter-spacing:.08em;
  text-transform:uppercase;
  color:#004A98;
}
body.whaledive-contact .wdc-contact-value{
  color:#0b1930;
  font-size:14px;
  font-weight:600;
  line-height:1.45;
  text-decoration:none;
  word-break:break-word;
}
body.whaledive-contact a.wdc-contact-value{color:#0b617c}
body.whaledive-contact .wdc-map-link{
  display:inline-flex;
  margin-top:6px;
  width:max-content;
  font-size:13px;
  font-weight:800;
  color:#004A98;
  text-decoration:none;
}
body.whaledive-contact .wdc-map-link:hover{color:#3B44AC}

/* Form card */
body.whaledive-contact .wdc-contact-form{
  background:#fff;
  border:1px solid rgba(0,74,152,.10);
  border-radius:18px;
  box-shadow:0 10px 28px rgba(3,36,58,.06);
  padding:18px!important;
  display:grid;
  gap:14px;
}
body.whaledive-contact .wdc-contact-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
body.whaledive-contact .wdc-field{display:grid;gap:6px;margin:0}
body.whaledive-contact .wdc-field>span{
  font-size:12px;
  font-weight:700;
  color:#334155;
  letter-spacing:.01em;
  text-transform:none;
}
body.whaledive-contact .wdc-field-full{grid-column:1/-1}
body.whaledive-contact .wdc-contact-form input,
body.whaledive-contact .wdc-contact-form select,
body.whaledive-contact .wdc-contact-form textarea{
  width:100%;
  min-height:40px;
  padding:9px 12px!important;
  border:1px solid rgba(0,74,152,.14)!important;
  border-radius:10px!important;
  font-size:14px!important;
  background:#fbfdff!important;
  box-shadow:none!important;
  box-sizing:border-box;
  color:#0b1930;
  font:inherit;
}
body.whaledive-contact .wdc-contact-form textarea{min-height:88px!important;resize:vertical}
body.whaledive-contact .wdc-contact-form input:focus,
body.whaledive-contact .wdc-contact-form select:focus,
body.whaledive-contact .wdc-contact-form textarea:focus{
  outline:none;
  border-color:#4CC8ED!important;
  background:#fff!important;
}
body.whaledive-contact .wdc-contact-form-foot{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  flex-wrap:wrap;
}
body.whaledive-contact .wdc-contact-form-foot .wd-form-privacy{
  margin:0!important;
  flex:1 1 180px;
  font-size:12px!important;
  line-height:1.4!important;
  color:#64748b!important;
}
body.whaledive-contact .wdc-contact-form-foot .wd-btn,
body.whaledive-contact .wdc-contact-form button[type=submit]{
  min-height:40px!important;
  padding:0 18px!important;
  border-radius:999px!important;
  font-size:14px!important;
  font-weight:800!important;
  background:#004A98!important;
  background-image:none!important;
  color:#FFFFFF!important;
  -webkit-text-fill-color:#FFFFFF!important;
  border:1px solid #004A98!important;
  box-shadow:0 12px 28px rgba(0,74,152,.24)!important;
}
body.whaledive-contact .wdc-contact-info :before,
body.whaledive-contact .wdc-contact-info :after,
body.whaledive-contact .wdc-contact-row:before,
body.whaledive-contact .wdc-contact-row:after{
  content:none!important;
  display:none!important;
  background:none!important;
  width:0!important;
  height:0!important;
}
/* hide old card system if any residual CSS remains */
body.whaledive-contact .wd-contact-cards,
body.whaledive-contact .wd-contact-card{display:none!important}

@media(max-width:900px){
  body.whaledive-contact .wdc-contact-layout{grid-template-columns:1fr}
}
@media(max-width:640px){
  body.whaledive-contact .wdc-contact-form-grid{grid-template-columns:1fr}
  body.whaledive-contact .wdc-contact-form-foot{align-items:stretch}
  body.whaledive-contact .wdc-contact-form-foot .wd-btn{width:100%}
  body.whaledive-contact #contact-form.wdc-contact-compact{padding-top:36px!important;padding-bottom:40px!important}
}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-contact'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-inner-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_tr('contact_kicker', 'Hubungi Kami', 'Get in Touch') : contenly_tr('Hubungi Kami', 'Get in Touch')); ?></span>
        <h1><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_tr('contact_title', 'Mulai percakapan dengan crew.', 'Start a conversation with the crew.') : contenly_tr('Mulai percakapan dengan crew.', 'Start a conversation with the crew.')); ?></h1>
        <p><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_tr('contact_text', 'Tanya jadwal kursus, ketersediaan peralatan, atau jalur sertifikasi. Kami balas dalam 24 jam.', 'Ask about course schedules, equipment availability, or certification pathways. We reply within 24 hours.') : contenly_tr('Tanya jadwal kursus, ketersediaan peralatan, atau jalur sertifikasi. Kami balas dalam 24 jam.', 'Ask about course schedules, equipment availability, or certification pathways. We reply within 24 hours.')); ?></p>
      </div>
    </div>
  </section>

  <?php
  $wdc_email = function_exists('wdc_site_get') ? wdc_site_get('email') : 'info@whaledivecentre.com';
  $wdc_phone = function_exists('wdc_site_get') ? wdc_site_get('phone') : '0821-2666-611';
  $wdc_phone_tel = function_exists('wdc_site_get') ? wdc_site_get('phone_tel') : '+628212666611';
  $wdc_address = function_exists('wdc_site_get') ? wdc_site_get('address') : 'Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240';
  $wdc_map = function_exists('wdc_site_get') ? wdc_site_get('contact_map_url') : '';
  if ($wdc_map === '') {
    $wdc_map = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($wdc_address);
  }
  // Compact bilingual hours only (match About).
  $wdc_hours_display = contenly_tr('Senin–Sabtu, 09:00–18:00 WIB', 'Mon–Sat, 09:00–18:00 WIB');
  ?>
  <section class="wd-section white wdc-contact-compact" id="contact-form">
    <div class="wd-shell">
      <div class="wdc-contact-head">
        <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_tr('contact_form_kicker', 'Hubungi Kami', 'Get in Touch') : contenly_tr('Hubungi Kami', 'Get in Touch')); ?></span>
        <h2 class="wd-title"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_tr('contact_form_title', 'Mulai percakapan', 'Start the conversation') : contenly_tr('Mulai percakapan', 'Start the conversation')); ?></h2>
        <p class="wdc-contact-lead"><?php echo esc_html(contenly_tr('Tanya kursus, gear, atau jadwal — crew balas via WhatsApp/email.', 'Ask about courses, gear, or schedule — crew replies via WhatsApp/email.')); ?></p>
      </div>
      <?php if ($wd_contact_notice) : ?>
        <div class="wd-contact-notice <?php echo esc_attr($wd_contact_notice_type); ?>" role="status"><?php echo esc_html($wd_contact_notice); ?></div>
      <?php endif; ?>

      <div class="wdc-contact-layout">
        <aside class="wdc-contact-info" aria-label="<?php echo esc_attr(contenly_tr('Kontak', 'Contact')); ?>">
          <?php if ($wdc_email) : ?>
          <div class="wdc-contact-row">
            <span class="wdc-contact-label">Email</span>
            <a class="wdc-contact-value" href="mailto:<?php echo esc_attr($wdc_email); ?>"><?php echo esc_html($wdc_email); ?></a>
          </div>
          <?php endif; ?>
          <?php if ($wdc_phone) : ?>
          <div class="wdc-contact-row">
            <span class="wdc-contact-label"><?php echo esc_html(contenly_tr('Telepon', 'Phone')); ?></span>
            <a class="wdc-contact-value" href="tel:<?php echo esc_attr($wdc_phone_tel ?: preg_replace('/\D+/', '', $wdc_phone)); ?>"><?php echo esc_html($wdc_phone); ?></a>
          </div>
          <?php endif; ?>
          <div class="wdc-contact-row">
            <span class="wdc-contact-label"><?php echo esc_html(contenly_tr('Jam', 'Hours')); ?></span>
            <span class="wdc-contact-value"><?php echo esc_html($wdc_hours_display); ?></span>
          </div>
          <?php if ($wdc_address) : ?>
          <div class="wdc-contact-row">
            <span class="wdc-contact-label"><?php echo esc_html(contenly_tr('Lokasi', 'Location')); ?></span>
            <span class="wdc-contact-value"><?php echo esc_html($wdc_address); ?></span>
            <a class="wdc-map-link" href="<?php echo esc_url($wdc_map); ?>" target="_blank" rel="noopener"><?php echo esc_html(contenly_tr('Google Maps', 'Google Maps')); ?> →</a>
          </div>
          <?php endif; ?>
        </aside>

        <form class="wdc-contact-form" method="post">
          <?php wp_nonce_field('wd_contact_inquiry', 'wd_contact_nonce'); ?>
          <input type="hidden" name="wd_contact_submit" value="1">
          <label class="wd-contact-hp" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          <div class="wdc-contact-form-grid">
            <label class="wdc-field">
              <span><?php echo esc_html(contenly_tr('Nama', 'Name')); ?></span>
              <input type="text" name="your-name" placeholder="<?php echo esc_attr(contenly_tr('Nama lengkap', 'Full name')); ?>" required>
            </label>
            <label class="wdc-field">
              <span>Email</span>
              <input type="email" name="email" placeholder="you@example.com">
            </label>
            <label class="wdc-field">
              <span>WhatsApp</span>
              <input type="tel" name="whatsapp" placeholder="+62..." required>
            </label>
            <label class="wdc-field">
              <span><?php echo esc_html(contenly_tr('Kebutuhan', 'Need')); ?></span>
              <select name="category">
                <option><?php echo esc_html(contenly_tr('Pertanyaan kursus', 'Course inquiry')); ?></option>
                <option><?php echo esc_html(contenly_tr('Ketersediaan peralatan', 'Equipment availability')); ?></option>
                <option><?php echo esc_html(contenly_tr('Pertanyaan umum', 'General question')); ?></option>
              </select>
            </label>
            <label class="wdc-field wdc-field-full">
              <span><?php echo esc_html(contenly_tr('Pesan', 'Message')); ?></span>
              <textarea name="message" rows="3" placeholder="<?php echo esc_attr(contenly_tr('Ceritakan singkat kebutuhan kamu...', 'Briefly tell us what you need...')); ?>"></textarea>
            </label>
          </div>
          <div class="wdc-contact-form-foot">
            <p class="wd-form-privacy"><?php echo esc_html(contenly_tr('Data kontak hanya untuk balas inquiry ini.', 'Contact details are only used to reply to this inquiry.')); ?></p>
            <button type="submit" class="wd-btn"><?php echo esc_html(contenly_tr('Kirim Pesan', 'Send Inquiry')); ?></button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <?php contenly_render_public_footer(); ?>
</main>
<script>
document.addEventListener('DOMContentLoaded', function(){
  var p = new URLSearchParams(window.location.search);
  var name = p.get('name');
  if(name){
    var nameInput = document.querySelector('input[name="your-name"]');
    if(nameInput) nameInput.value = name;
    var waInput = document.querySelector('input[name="whatsapp"]');
    if(waInput && p.get('whatsapp')) waInput.value = p.get('whatsapp');
    var emailInput = document.querySelector('input[name="email"]');
    if(emailInput && p.get('email')) emailInput.value = p.get('email');
    var msgInput = document.querySelector('textarea[name="message"]');
    if(msgInput){
      var msg = 'Course inquiry from homepage:\n';
      if(p.get('cert')) msg += 'Certification: ' + p.get('cert') + '\n';
      if(p.get('schedule')) msg += 'Schedule: ' + p.get('schedule') + '\n';
      if(p.get('group')) msg += 'Group: ' + p.get('group');
      msgInput.value = msg;
    }
    document.getElementById('contact-form')?.scrollIntoView({behavior:'smooth'});
  }
});
</script>
<?php wp_footer(); ?></body></html>
