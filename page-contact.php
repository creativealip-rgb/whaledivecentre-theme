<?php
/**
 * Template Name: Contact Page
 * Style matched to About page contact form.
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
        $wd_contact_notice = function_exists('wdc_site_get') ? wdc_site_get('contact_success', contenly_tr('Terima kasih. Pesan Anda sudah terkirim dan crew akan membalas dalam 24 jam.', 'Thank you. Your inquiry has been sent and our crew will reply within 24 hours.')) : contenly_tr('Terima kasih. Pesan Anda sudah terkirim dan crew akan membalas dalam 24 jam.', 'Thank you. Your inquiry has been sent and our crew will reply within 24 hours.');
        $wd_contact_notice_type = 'success';
    } elseif ('mail-error' === $_GET['wd_contact']) {
        $wd_contact_notice = contenly_tr('Pesan belum berhasil dikirim oleh server email. Silakan hubungi kami via telepon jika urgent.', 'The mail server could not send your inquiry yet. Please call us if urgent.');
        $wd_contact_notice_type = 'error';
    }
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
<style id="wd-contact-match-about">
/* Shared contact form look — same as About */
.wd-contact-form small{display:block;margin-top:7px;color:#64748b;font-size:12px;line-height:1.45}
.wd-form-privacy{margin:0;color:#64748b;font-size:13px;line-height:1.5}
.wd-contact-notice{margin:18px 0 0;padding:14px 16px;border-radius:16px;font-weight:800;line-height:1.45}
.wd-contact-notice.success{background:#e7f8ef;color:#05603a;border:1px solid rgba(5,96,58,.18)}
.wd-contact-notice.error{background:#fff3e8;color:#9a3412;border:1px solid rgba(154,52,18,.18)}
.wd-contact-hp{position:absolute;left:-9999px;opacity:0;pointer-events:none}
.wd-contact-card{display:flex;flex-direction:column;gap:8px}
.wd-contact-card strong{color:#06384d;font-size:13px;letter-spacing:.08em;text-transform:uppercase}
.wd-contact-card a{display:inline-flex;color:#0b617c;font-weight:800}

body.whaledive-contact .wd-inner-hero{padding-top:150px!important;padding-bottom:58px!important;min-height:auto!important}
body.whaledive-contact .wd-inner-hero h1{max-width:18ch}
body.whaledive-contact .wd-inner-hero p{max-width:52ch;color:rgba(255,255,255,.9)}
body.whaledive-contact #contact-form{background:linear-gradient(180deg,#ffffff 0%,#f3fbff 100%)}
body.whaledive-contact .wd-contact-grid{margin-top:30px!important;align-items:stretch;gap:26px!important}
body.whaledive-contact .wd-contact-cards{display:flex;flex-direction:column;gap:0;padding:26px!important;border-radius:30px!important;background:linear-gradient(180deg,#ffffff,#f7fcff)!important;border:1px solid rgba(76,200,237,.24)!important;box-shadow:0 18px 44px rgba(3,36,58,.09)!important}
body.whaledive-contact .wd-contact-card{position:relative;padding:0 0 18px 42px!important;border-radius:0!important;background:transparent!important;border:0!important;box-shadow:none!important;min-height:0}
body.whaledive-contact .wd-contact-card:not(:last-child){margin-bottom:18px!important;border-bottom:1px solid rgba(6,56,77,.08)!important}
body.whaledive-contact .wd-contact-card:before{content:"";position:absolute;left:0;top:2px;width:22px;height:22px;border-radius:999px;background:linear-gradient(135deg,#4CC8ED,#0b617c);box-shadow:0 8px 18px rgba(76,200,237,.22)}
body.whaledive-contact .wd-contact-card strong{font-size:12px!important;color:#06384d!important}
body.whaledive-contact .wd-contact-card span,
body.whaledive-contact .wd-contact-card a{color:#526b7a!important;line-height:1.55!important}
body.whaledive-contact .wd-contact-card a{font-weight:900!important;color:#0b617c!important}
body.whaledive-contact .wd-map-link{display:inline-flex!important;align-items:center!important;justify-content:center!important;width:max-content!important;margin-top:8px!important;padding:10px 14px!important;border-radius:999px!important;background:#e9f8fc!important;color:#064a63!important;border:1px solid rgba(76,200,237,.32)!important;text-decoration:none!important}
body.whaledive-contact .wd-contact-form{padding:28px!important;border-radius:30px!important;background:#fff!important;border:1px solid rgba(0,91,122,.12)!important;box-shadow:0 22px 56px rgba(3,36,58,.12)!important;display:grid;gap:14px}
body.whaledive-contact .wd-contact-form:before{content:"<?php echo esc_js(contenly_tr('Kirim pesan ke crew', 'Send a message to the crew')); ?>";display:block;margin:0 0 18px;color:#06384d;font-size:20px;font-weight:900;letter-spacing:-.02em}
body.whaledive-contact .wd-contact-form label{display:grid;gap:7px!important;color:#04172d;font-weight:800;font-size:14px}
body.whaledive-contact .wd-contact-form input,
body.whaledive-contact .wd-contact-form select,
body.whaledive-contact .wd-contact-form textarea{width:100%;border-radius:16px!important;border:1px solid rgba(0,91,122,.16)!important;background:#f8fcff!important;padding:12px 14px;font:inherit;color:#0b1930;box-sizing:border-box}
body.whaledive-contact .wd-contact-form input:focus,
body.whaledive-contact .wd-contact-form select:focus,
body.whaledive-contact .wd-contact-form textarea:focus{background:#fff!important;border-color:#4CC8ED!important;box-shadow:0 0 0 4px rgba(76,200,237,.14)!important;outline:none!important}
body.whaledive-contact .wd-contact-form .wd-btn{min-height:48px;justify-content:center}
@media(max-width:860px){
  body.whaledive-contact .wd-contact-grid{grid-template-columns:1fr!important}
  body.whaledive-contact .wd-contact-card{padding-left:54px!important}
  body.whaledive-contact .wd-contact-form{padding:22px!important}
  body.whaledive-contact .wd-contact-form .wd-btn{width:100%}
}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-contact'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-inner-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('contact_kicker', contenly_tr('Hubungi Kami', 'Get in Touch')) : contenly_tr('Hubungi Kami', 'Get in Touch')); ?></span>
        <h1><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('contact_title', contenly_tr('Mulai percakapan dengan crew.', 'Start a conversation with the crew.')) : contenly_tr('Mulai percakapan dengan crew.', 'Start a conversation with the crew.')); ?></h1>
        <p><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('contact_text', contenly_tr('Tanya jadwal kursus, ketersediaan peralatan, atau jalur sertifikasi. Kami balas dalam 24 jam.', 'Ask about course schedules, equipment availability, or certification pathways. We reply within 24 hours.')) : contenly_tr('Tanya jadwal kursus, ketersediaan peralatan, atau jalur sertifikasi. Kami balas dalam 24 jam.', 'Ask about course schedules, equipment availability, or certification pathways. We reply within 24 hours.')); ?></p>
      </div>
    </div>
  </section>

  <section class="wd-section white" id="contact-form">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('contact_form_kicker', contenly_tr('Hubungi Kami', 'Get in Touch')) : contenly_tr('Hubungi Kami', 'Get in Touch')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('contact_form_title', contenly_tr('Mulai percakapan', 'Start the conversation')) : contenly_tr('Mulai percakapan', 'Start the conversation')); ?></h2>
      <?php if ($wd_contact_notice) : ?>
        <div class="wd-contact-notice <?php echo esc_attr($wd_contact_notice_type); ?>" role="status"><?php echo esc_html($wd_contact_notice); ?></div>
      <?php endif; ?>
      <div class="wd-contact-grid">
        <?php
        $wdc_email = function_exists('wdc_site_get') ? wdc_site_get('email') : 'info@whaledivecentre.com';
        $wdc_phone = function_exists('wdc_site_get') ? wdc_site_get('phone') : '(021) 27939068';
        $wdc_phone_tel = function_exists('wdc_site_get') ? wdc_site_get('phone_tel') : '+622127939068';
        $wdc_address = function_exists('wdc_site_get') ? wdc_site_get('address') : 'Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240';
        $wdc_hours_note = function_exists('wdc_site_get') ? wdc_site_get('contact_hours_note') : '';
        $wdc_map = function_exists('wdc_site_get') ? wdc_site_get('contact_map_url') : '';
        if ($wdc_map === '') {
          $wdc_map = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($wdc_address);
        }
        ?>
        <div class="wd-contact-cards">
          <?php if ($wdc_email) : ?><div class="wd-contact-card"><strong>Email</strong><a href="mailto:<?php echo esc_attr($wdc_email); ?>"><?php echo esc_html($wdc_email); ?></a></div><?php endif; ?>
          <?php if ($wdc_phone) : ?><div class="wd-contact-card"><strong><?php echo esc_html(contenly_tr('Telepon', 'Phone')); ?></strong><a href="tel:<?php echo esc_attr($wdc_phone_tel ?: preg_replace('/\D+/', '', $wdc_phone)); ?>"><?php echo esc_html($wdc_phone); ?></a></div><?php endif; ?>
          <?php if ($wdc_hours_note) : ?><div class="wd-contact-card"><strong><?php echo esc_html(contenly_tr('Jam Operasional', 'Business Hours')); ?></strong><span><?php echo esc_html($wdc_hours_note); ?></span></div><?php endif; ?>
          <?php if ($wdc_address) : ?>
          <div class="wd-contact-card">
            <strong><?php echo esc_html(contenly_tr('Lokasi', 'Location')); ?></strong>
            <span><?php echo esc_html($wdc_address); ?></span>
            <a class="wd-map-link" href="<?php echo esc_url($wdc_map); ?>" target="_blank" rel="noopener"><?php echo esc_html(contenly_tr('Buka di Google Maps', 'Open in Google Maps')); ?></a>
          </div>
          <?php endif; ?>
        </div>

        <form class="wd-contact-form" method="post">
          <?php wp_nonce_field('wd_contact_inquiry', 'wd_contact_nonce'); ?>
          <input type="hidden" name="wd_contact_submit" value="1">
          <label class="wd-contact-hp" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          <label><?php echo esc_html(contenly_tr('Nama Anda', 'Your Name')); ?><input type="text" name="your-name" placeholder="<?php echo esc_attr(contenly_tr('Nama Anda', 'Your name')); ?>" required></label>
          <label><?php echo esc_html(contenly_tr('Email', 'Email')); ?><input type="email" name="email" placeholder="you@example.com"><small><?php echo esc_html(contenly_tr('Gunakan email jika Anda lebih suka balasan tertulis.', 'Use email if you prefer a written reply.')); ?></small></label>
          <label><?php echo esc_html(contenly_tr('Nomor WhatsApp', 'WhatsApp Number')); ?><input type="tel" name="whatsapp" placeholder="+62..." required><small><?php echo esc_html(contenly_tr('Wajib agar crew bisa membalas dalam 24 jam.', 'Required so our crew can reply within 24 hours.')); ?></small></label>
          <label><?php echo esc_html(contenly_tr('Apa yang Anda butuhkan?', 'What do you need?')); ?>
            <select name="category">
              <option><?php echo esc_html(contenly_tr('Pertanyaan kursus', 'Course inquiry')); ?></option>
              <option><?php echo esc_html(contenly_tr('Ketersediaan peralatan', 'Equipment availability')); ?></option>
              <option><?php echo esc_html(contenly_tr('Pertanyaan umum', 'General question')); ?></option>
            </select>
          </label>
          <label><?php echo esc_html(contenly_tr('Pesan', 'Message')); ?><textarea name="message" rows="4" placeholder="<?php echo esc_attr(contenly_tr('Ceritakan yang Anda butuhkan...', 'Tell us what you need...')); ?>"></textarea></label>
          <p class="wd-form-privacy"><?php echo esc_html(contenly_tr('Kami hanya menggunakan detail kontak Anda untuk membalas pertanyaan ini.', 'We only use your contact details to reply to this inquiry.')); ?></p>
          <button type="submit" class="wd-btn"><?php echo esc_html(contenly_tr('Kirim Pesan', 'Send Inquiry')); ?></button>
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
