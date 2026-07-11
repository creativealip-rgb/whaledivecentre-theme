<?php
/**
 * Template Name: Contact Page
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
        $recipient = get_option('admin_email') ?: 'info@whaledivecentre.com';
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
        $wd_contact_notice = contenly_tr('Terima kasih. Pesan Anda sudah terkirim dan crew akan membalas dalam 24 jam.', 'Thank you. Your inquiry has been sent and our crew will reply within 24 hours.');
        $wd_contact_notice_type = 'success';
    } elseif ('mail-error' === $_GET['wd_contact']) {
        $wd_contact_notice = contenly_tr('Pesan belum berhasil dikirim oleh server email. Silakan hubungi kami via telepon jika urgent.', 'The mail server could not send your inquiry yet. Please call us if urgent.');
        $wd_contact_notice_type = 'error';
    }
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
<style id="wd-contact-page-polish">
body.whaledive-contact .wd-contact-hero{padding:132px 0 56px;background:linear-gradient(135deg,#03172d 0%,#004A98 100%);color:#fff}
body.whaledive-contact .wd-contact-hero .wd-kicker{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.2);color:#fff}
body.whaledive-contact .wd-contact-hero h1{margin:14px 0 12px;font-size:clamp(34px,6vw,52px);line-height:1.05;color:#fff}
body.whaledive-contact .wd-contact-hero p{margin:0;max-width:680px;color:rgba(255,255,255,.88);line-height:1.7}
body.whaledive-contact .wd-contact-grid{display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);gap:24px;align-items:start}
body.whaledive-contact .wd-contact-cards{display:flex;flex-direction:column;gap:0;padding:24px;border-radius:28px;background:linear-gradient(180deg,#fff,#f7fcff);border:1px solid rgba(76,200,237,.24);box-shadow:0 18px 44px rgba(3,36,58,.09)}
body.whaledive-contact .wd-contact-card{display:flex;flex-direction:column;gap:6px;padding:16px 0;border-bottom:1px solid rgba(6,56,77,.08)}
body.whaledive-contact .wd-contact-card:last-child{border-bottom:0;padding-bottom:0}
body.whaledive-contact .wd-contact-card strong{color:#06384d;font-size:12px;letter-spacing:.08em;text-transform:uppercase}
body.whaledive-contact .wd-contact-card a{color:#0b617c;font-weight:800}
body.whaledive-contact .wd-contact-card span{color:#516b7a;line-height:1.6}
body.whaledive-contact .wd-contact-form{padding:26px;border-radius:28px;background:#fff;border:1px solid rgba(6,56,77,.1);box-shadow:0 18px 44px rgba(2,32,46,.08);display:grid;gap:14px}
body.whaledive-contact .wd-contact-form label{display:grid;gap:7px;color:#04172d;font-weight:800;font-size:14px}
body.whaledive-contact .wd-contact-form input,
body.whaledive-contact .wd-contact-form select,
body.whaledive-contact .wd-contact-form textarea{width:100%;border:1px solid rgba(6,56,77,.14);border-radius:14px;padding:12px 14px;font:inherit;color:#0b1930;background:#f8fcff}
body.whaledive-contact .wd-contact-form small,
body.whaledive-contact .wd-form-privacy{color:#64748b;font-size:12px;font-weight:600;line-height:1.45}
body.whaledive-contact .wd-contact-notice{margin:0 0 18px;padding:14px 16px;border-radius:16px;font-weight:800;line-height:1.45}
body.whaledive-contact .wd-contact-notice.success{background:#e7f8ef;color:#05603a;border:1px solid rgba(5,96,58,.18)}
body.whaledive-contact .wd-contact-notice.error{background:#fff3e8;color:#9a3412;border:1px solid rgba(154,52,18,.18)}
body.whaledive-contact .wd-contact-hp{position:absolute;left:-9999px;opacity:0;pointer-events:none}
body.whaledive-contact .wd-map-link{margin-top:4px}
@media(max-width:900px){
  body.whaledive-contact .wd-contact-grid{grid-template-columns:1fr}
}
@media(max-width:560px){
  body.whaledive-contact .wd-contact-hero{padding:110px 0 40px}
  body.whaledive-contact .wd-contact-form .wd-btn{width:100%}
}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-contact'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-contact-hero">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Hubungi Kami', 'Get in Touch')); ?></span>
      <h1><?php echo esc_html(contenly_tr('Mulai percakapan dengan crew.', 'Start a conversation with the crew.')); ?></h1>
      <p><?php echo esc_html(contenly_tr('Tanya jadwal kursus, ketersediaan peralatan, trip, atau konsultasi jalur sertifikasi. Kami balas dalam 24 jam.', 'Ask about course schedules, equipment availability, trips, or certification pathways. We reply within 24 hours.')); ?></p>
    </div>
  </section>

  <section class="wd-section white" id="contact-form">
    <div class="wd-shell">
      <?php if ($wd_contact_notice) : ?>
        <div class="wd-contact-notice <?php echo esc_attr($wd_contact_notice_type); ?>" role="status"><?php echo esc_html($wd_contact_notice); ?></div>
      <?php endif; ?>
      <div class="wd-contact-grid">
        <div class="wd-contact-cards">
          <div class="wd-contact-card"><strong>Email</strong><a href="mailto:info@whaledivecentre.com">info@whaledivecentre.com</a></div>
          <div class="wd-contact-card"><strong><?php echo esc_html(contenly_tr('Telepon', 'Phone')); ?></strong><a href="tel:+622****9068">(021) 27939068</a></div>
          <div class="wd-contact-card"><strong><?php echo esc_html(contenly_tr('Jam Operasional', 'Business Hours')); ?></strong><span><?php echo esc_html(contenly_tr('Senin - Sabtu, 09:00 - 18:00 WIB. Jadwal kursus dan trip dikonfirmasi berdasarkan perjanjian.', 'Monday - Saturday, 09:00 - 18:00 WIB. Course and trip schedules are confirmed by appointment.')); ?></span></div>
          <div class="wd-contact-card">
            <strong><?php echo esc_html(contenly_tr('Lokasi', 'Location')); ?></strong>
            <span>Jl. Tanah Kusir II No.3, RT.10/RW.9, Kebayoran Lama Selatan, Jakarta Selatan 12240</span>
            <a class="wd-map-link" href="https://www.google.com/maps/search/?api=1&query=Jl.%20Tanah%20Kusir%20II%20No.3%20Jakarta%20Selatan" target="_blank" rel="noopener"><?php echo esc_html(contenly_tr('Buka di Google Maps', 'Open in Google Maps')); ?></a>
          </div>
        </div>

        <form class="wd-contact-form" method="post">
          <?php wp_nonce_field('wd_contact_inquiry', 'wd_contact_nonce'); ?>
          <input type="hidden" name="wd_contact_submit" value="1">
          <label class="wd-contact-hp" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          <label><?php echo esc_html(contenly_tr('Nama Anda', 'Your Name')); ?><input type="text" name="your-name" placeholder="<?php echo esc_attr(contenly_tr('Nama Anda', 'Your name')); ?>" required></label>
          <label><?php echo esc_html(contenly_tr('Email', 'Email')); ?><input type="email" name="email" placeholder="you@example.com"><small><?php echo esc_html(contenly_tr('Opsional jika kamu lebih suka balasan tertulis.', 'Optional if you prefer a written reply.')); ?></small></label>
          <label><?php echo esc_html(contenly_tr('Nomor WhatsApp', 'WhatsApp Number')); ?><input type="tel" name="whatsapp" placeholder="+62..." required><small><?php echo esc_html(contenly_tr('Wajib agar crew bisa membalas dalam 24 jam.', 'Required so our crew can reply within 24 hours.')); ?></small></label>
          <label><?php echo esc_html(contenly_tr('Apa yang Anda butuhkan?', 'What do you need?')); ?>
            <select name="category">
              <option><?php echo esc_html(contenly_tr('Pertanyaan kursus', 'Course inquiry')); ?></option>
              <option><?php echo esc_html(contenly_tr('Ketersediaan peralatan', 'Equipment availability')); ?></option>
              <option><?php echo esc_html(contenly_tr('Dive trip', 'Dive trip')); ?></option>
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
<?php wp_footer(); ?></body></html>
