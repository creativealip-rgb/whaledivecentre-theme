<?php
/**
 * Template Name: About Page
 */
$theme_uri = get_stylesheet_directory_uri();

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
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-about-ux-pass">.wd-crew-proof{padding-top:56px!important}.wd-instructor-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-top:30px}.wd-instructor-grid article{padding:24px;border-radius:28px;background:linear-gradient(180deg,#fff,#eef8fb);border:1px solid rgba(0,91,122,.1);box-shadow:0 16px 38px rgba(2,32,46,.07)}.wd-instructor-grid div{width:74px;height:74px;border-radius:26px;display:grid;place-items:center;margin-bottom:18px;background:linear-gradient(135deg,#06384d,#08a7c7);color:#fff;font-size:28px;font-weight:900}.wd-instructor-grid h3{margin:0 0 8px;color:#06384d}.wd-instructor-grid b{display:block;margin-bottom:10px;color:#0b617c}.wd-instructor-grid span{color:#5b7180;line-height:1.65}.whaledive-about .wd-sub{max-width:720px}.wd-contact-form small{display:block;margin-top:7px;color:#64748b;font-size:12px;line-height:1.45;text-transform:none;letter-spacing:0}.wd-form-privacy{margin:0;color:#64748b;font-size:13px;line-height:1.5}.wd-contact-notice{margin:18px 0 0;padding:14px 16px;border-radius:16px;font-weight:800;line-height:1.45}.wd-contact-notice.success{background:#e7f8ef;color:#05603a;border:1px solid rgba(5,96,58,.18)}.wd-contact-notice.error{background:#fff3e8;color:#9a3412;border:1px solid rgba(154,52,18,.18)}.wd-contact-hp{position:absolute;left:-9999px;opacity:0;pointer-events:none}.whaledive-about .wd-contact-grid{margin-top:30px!important}.whaledive-about #contact-form .wd-sub{margin-bottom:0!important}.wd-contact-card{display:flex;flex-direction:column;gap:8px}.wd-contact-card strong{color:#06384d;font-size:13px;letter-spacing:.08em;text-transform:uppercase}.wd-contact-card span,.wd-contact-card a{line-height:1.55}.wd-contact-card a{display:inline-flex;color:#0b617c;font-weight:800}.wd-map-link{width:max-content;margin-top:4px!important;padding:9px 13px;border-radius:999px;background:#f3fbff;border:1px solid rgba(6,56,77,.14);text-decoration:none}.wd-menu a[data-nav="about"]{color:#06384d;background:rgba(8,167,199,.12)}@media(max-width:800px){.wd-instructor-grid{grid-template-columns:1fr}}</style></head>
<body <?php body_class('whaledive-inner whaledive-about'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <!-- HERO -->
  <section class="wd-inner-hero wd-about-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker"><?php echo esc_html(contenly_tr('Tentang Whale Dive Centre', 'About Whale Dive Centre')); ?></span>
          <h1><?php echo esc_html(contenly_tr('Pelatihan tenang. Kebiasaan lebih baik. Dive lebih aman.', 'Calm training. Better habits. Safer dives.')); ?></h1>
          <p><?php echo esc_html(contenly_tr('Pusat selam di Jakarta yang fokus pada pelatihan profesional, dukungan gear, dan komunitas peduli laut.', 'A Jakarta-based dive centre focused on professional scuba training, quality gear support, and an ocean-minded community.')); ?></p><div class="wd-actions"><a class="wd-btn" href="#contact-form"><?php echo esc_html(contenly_tr('Mulai Konsultasi', 'Start Inquiry')); ?></a><a class="wd-btn alt" href="/courses/"><?php echo esc_html(contenly_tr('Lihat Kursus', 'View Courses')); ?></a></div>
        </div>
      </div>
    </div>
  </section>

  <!-- TEAM STANDARDS -->
  <section class="wd-section white wd-team-standards">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Standar Pelatihan', 'Training Standards')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Standar yang dipandu crew untuk hari dive yang lebih aman', 'Crew-led standards for safer dive days')); ?></h2>
      <div class="wd-about-crew-grid">
        <div class="wd-crew-card"><div class="wd-crew-icon">01</div><h3><?php echo esc_html(contenly_tr('Progres dipandu instruktur', 'Instructor-led progression')); ?></h3><span><?php echo esc_html(contenly_tr('Kursus berjalan sesuai kenyamanan diver, bukan hanya kalender.', 'Courses move at the diver\'s real comfort level, not just the calendar.')); ?></span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">02</div><h3><?php echo esc_html(contenly_tr('Kesiapan peralatan', 'Equipment readiness')); ?></h3><span><?php echo esc_html(contenly_tr('Pemeriksaan pas, panduan setup, dan pertanyaan gear terjadi sebelum dive.', 'Fit checks, setup walkthroughs, and gear questions happen before the dive.')); ?></span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">03</div><h3><?php echo esc_html(contenly_tr('Perhatian kelompok kecil', 'Small-group attention')); ?></h3><span><?php echo esc_html(contenly_tr('Lebih banyak ruang untuk pertanyaan, pengulangan skill, dan debrief yang tenang.', 'More room for questions, repeated skills, and calm debriefs.')); ?></span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">04</div><h3><?php echo esc_html(contenly_tr('Perencanaan sesuai kondisi', 'Condition-aware planning')); ?></h3><span><?php echo esc_html(contenly_tr('Cuaca, arus, visibilitas, dan kesiapan diver menentukan setiap rekomendasi.', 'Weather, current, visibility, and diver readiness shape every recommendation.')); ?></span></div>
      </div>
    </div>
  </section>

  <section class="wd-section white wd-crew-proof"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Kenali Crew', 'Meet the Crew')); ?></span><h2 class="wd-title"><?php echo esc_html(contenly_tr('Bersertifikat, sabar, dan fokus pada progres yang tenang.', 'Certified, patient, and focused on calm progression.')); ?></h2><div class="wd-instructor-grid"><article><div>I</div><h3><?php echo esc_html(contenly_tr('Tim Instruktur', 'Instructor Team')); ?></h3><b><?php echo esc_html(contenly_tr('Jalur PADI / SSI', 'PADI / SSI pathway')); ?></b><span><?php echo esc_html(contenly_tr('Pacing kelompok kecil, kepercayaan diri pemula, dan pengulangan skill yang tenang.', 'Small-group pacing, beginner confidence, and calm skill repetition.')); ?></span></article><article><div>S</div><h3><?php echo esc_html(contenly_tr('Dukungan Keamanan', 'Safety Support')); ?></h3><b><?php echo esc_html(contenly_tr('Perencanaan dive sadar penyelamatan', 'Rescue-aware dive planning')); ?></b><span><?php echo esc_html(contenly_tr('Briefing, kesadaran buddy, pemeriksaan peralatan, dan kebiasaan debrief.', 'Briefings, buddy awareness, equipment checks, and debrief habits.')); ?></span></article><article><div>G</div><h3><?php echo esc_html(contenly_tr('Spesialis Gear', 'Gear Specialist')); ?></h3><b><?php echo esc_html(contenly_tr('Panduan pas dan setup', 'Fit and setup guidance')); ?></b><span><?php echo esc_html(contenly_tr('Pas masker, ukuran BCD, setup regulator, dan dasar dive computer.', 'Mask fit, BCD sizing, regulator setup, and dive computer basics.')); ?></span></article></div></div></section>

  <!-- HOW WE WORK -->
  <section class="wd-section wd-dark">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Cara Kami Bekerja', 'How We Work')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Briefing tenang. Kebiasaan lebih baik. Dive lebih aman.', 'Calm briefings. Better habits. Safer dives.')); ?></h2>
      <div class="wd-safety-grid"><article><b><?php echo esc_html(contenly_tr('Briefing sebelum dive', 'Pre-dive briefing')); ?></b><span><?php echo esc_html(contenly_tr('Tujuan, sinyal, batas, rencana buddy, dan rencana keluar ditinjau sebelum setiap sesi.', 'Objectives, signals, limits, buddy plan, and exit plan are reviewed before each session.')); ?></span></article><article><b><?php echo esc_html(contenly_tr('Pemeriksaan peralatan', 'Equipment checks')); ?></b><span><?php echo esc_html(contenly_tr('Pasokan udara, pemberat, pengaturan computer, dan kenyamanan diperiksa sebelum masuk air.', 'Fit, air delivery, weights, computer settings, and comfort are checked before entering the water.')); ?></span></article><article><b><?php echo esc_html(contenly_tr('Kesiapan darurat', 'Emergency readiness')); ?></b><span><?php echo esc_html(contenly_tr('Keputusan pelatihan mencakup batas konservatif, kesadaran P3K, dan keputusan tanpa terburu-buru.', 'Training decisions include conservative limits, first-aid awareness, and no-rush calls around conditions.')); ?></span></article></div><div class="wd-steps">
        <div class="wd-step"><span>01</span><h3><?php echo esc_html(contenly_tr('Sebelum dive', 'Before the dive')); ?></h3><p><?php echo esc_html(contenly_tr('Rencana jelas, pemeriksaan gear, kondisi situs, dan ekspektasi skill.', 'Clear plan, gear check, site conditions, and skill expectations.')); ?></p></div>
        <div class="wd-step"><span>02</span><h3><?php echo esc_html(contenly_tr('Saat dive', 'During the dive')); ?></h3><p><?php echo esc_html(contenly_tr('Kesadaran kelompok kecil, pacing santai, dan keputusan mengutamakan keamanan.', 'Small-group awareness, relaxed pacing, and safety-first decisions.')); ?></p></div>
        <div class="wd-step"><span>03</span><h3><?php echo esc_html(contenly_tr('Setelah dive', 'After the dive')); ?></h3><p><?php echo esc_html(contenly_tr('Debrief, coaching langkah selanjutnya, dan rekomendasi untuk pelatihan atau gear.', 'Debrief, next-step coaching, and recommendations for training or gear.')); ?></p></div>
      </div>
    </div>
  </section>

  <!-- GET IN TOUCH -->
  <section class="wd-section white" id="contact-form">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Hubungi Kami', 'Get in Touch')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Mulai percakapan', 'Start the conversation')); ?></h2>
      <?php if ($wd_contact_notice) : ?><div class="wd-contact-notice <?php echo esc_attr($wd_contact_notice_type); ?>" role="status"><?php echo esc_html($wd_contact_notice); ?></div><?php endif; ?>
      <div class="wd-contact-grid">
        <div class="wd-contact-cards">
<div class="wd-contact-card"><strong>Email</strong><a href="mailto:info@whaledivecentre.com">info@whaledivecentre.com</a></div>
          <div class="wd-contact-card"><strong>Phone</strong><a href="tel:+622127939068">(021) 27939068</a></div>
          <div class="wd-contact-card"><strong><?php echo contenly_tr('Jam Operasional', 'Business Hours'); ?></strong><span><?php echo contenly_tr('Senin - Sabtu, 09:00 - 18:00 WIB. Jadwal kursus dan perjalanan dikonfirmasi berdasarkan perjanjian.', 'Monday - Saturday, 09:00 - 18:00 WIB. Course and trip schedules are confirmed by appointment.'); ?></span></div>
          <div class="wd-contact-card"><strong><?php echo contenly_tr('Lokasi', 'Location'); ?></strong><span>Jl. Tanah Kusir II No.3, RT.10/RW.9, Kebayoran Lama Selatan, Jakarta Selatan 12240</span><a class="wd-map-link" href="https://www.google.com/maps/search/?api=1&query=Jl.%20Tanah%20Kusir%20II%20No.3%20Jakarta%20Selatan" target="_blank" rel="noopener"><?php echo contenly_tr('Buka di Google Maps', 'Open in Google Maps'); ?></a></div>
        </div>
        <form class="wd-contact-form" method="post">
          <?php wp_nonce_field('wd_contact_inquiry', 'wd_contact_nonce'); ?>
          <input type="hidden" name="wd_contact_submit" value="1">
          <label class="wd-contact-hp" aria-hidden="true">Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          <label><?php echo contenly_tr('Nama Anda', 'Your Name'); ?><input type="text" name="your-name" placeholder="<?php echo contenly_tr('Nama Anda', 'Your name'); ?>" required></label>
          <label><?php echo contenly_tr('Email', 'Email'); ?><input type="email" name="email" placeholder="you@example.com"><small><?php echo contenly_tr('Gunakan email jika Anda lebih suka balasan tertulis.', 'Use email if you prefer a written reply.'); ?></small></label>
          <label><?php echo contenly_tr('Nomor WhatsApp', 'WhatsApp Number'); ?><input type="tel" name="whatsapp" placeholder="+62..." required><small><?php echo contenly_tr('Wajib agar crew bisa membalas dalam 24 jam.', 'Required so our crew can reply within 24 hours.'); ?></small></label>
          <label><?php echo contenly_tr('Apa yang Anda butuhkan?', 'What do you need?'); ?><select name="category"><option><?php echo contenly_tr('Pertanyaan kursus', 'Course inquiry'); ?></option><option><?php echo contenly_tr('Ketersediaan peralatan', 'Equipment availability'); ?></option><option><?php echo contenly_tr('Pertanyaan umum', 'General question'); ?></option></select></label>
          <label><?php echo contenly_tr('Pesan', 'Message'); ?><textarea name="message" rows="4" placeholder="<?php echo contenly_tr('Ceritakan yang Anda butuhkan...', 'Tell us what you need...'); ?>"></textarea></label>
          <p class="wd-form-privacy"><?php echo contenly_tr('Kami hanya menggunakan detail kontak Anda untuk membalas pertanyaan ini.', 'We only use your contact details to reply to this inquiry.'); ?></p><button type="submit" class="wd-btn"><?php echo esc_html(contenly_tr('Kirim Pesan', 'Send Inquiry')); ?></button>
        </form>
      </div>
    </div>
  </section>

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
    var msgInput = document.querySelector('textarea');
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
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script>
  <?php get_footer(); ?>