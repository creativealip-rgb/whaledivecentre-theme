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
?>
<?php get_header(); ?>
<?php get_header(); ?>

  <!-- HERO -->
  <section class="wd-inner-hero wd-about-hero wdc-about-v2-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker"><?php echo esc_html(contenly_tr('Tentang Whale Dive Centre', 'About Whale Dive Centre')); ?></span>
          <h1><?php echo esc_html(contenly_tr('Kantor Pusat NAUI Indonesia untuk pelatihan selam yang aman, profesional, dan berkelas dunia.', 'NAUI Indonesia Headquarters for safe, professional, world-class dive training.')); ?></h1>
          <p><?php echo esc_html(contenly_tr('Didirikan pada 2008 di Jakarta, WDC berfokus pada pendidikan penyelam, keselamatan, eksplorasi bawah laut, dan pengembangan profesional diving Indonesia.', 'Founded in 2008 in Jakarta, WDC focuses on diver education, safety, underwater exploration, and professional development for Indonesia’s diving community.')); ?></p>
          <div class="wd-actions"><a class="wd-btn" href="#crew"><?php echo esc_html(contenly_tr('Kenali Tim', 'Meet the Team')); ?></a><a class="wd-btn alt" href="/courses/"><?php echo esc_html(contenly_tr('Lihat Kursus', 'View Courses')); ?></a></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT WDC -->
  <section class="wd-section white wdc-about-v2-intro">
    <div class="wd-shell">
      <div class="wd-about-split">
        <div>
          <span class="wd-kicker"><?php echo esc_html(contenly_tr('Sejak 2008', 'Since 2008')); ?></span>
          <h2 class="wd-title"><?php echo esc_html(contenly_tr('Standar internasional. Kepemimpinan lokal. Budaya keselamatan.', 'International standards. Local leadership. Safety culture.')); ?></h2>
          <p><?php echo esc_html(contenly_tr('Whale Dive Centre (WDC) adalah salah satu institusi penyelaman terkemuka di Indonesia yang berkantor pusat di Jakarta. WDC menghadirkan pelatihan rekreasional, profesional, dan teknis dengan standar internasional.', 'Whale Dive Centre (WDC) is one of Indonesia’s leading diving institutions headquartered in Jakarta. WDC delivers recreational, professional, and technical dive training with internationally recognized standards.')); ?></p>
          <p><?php echo esc_html(contenly_tr('Sebagai Kantor Pusat NAUI Indonesia serta pusat yang berafiliasi dengan NAUI, TDI, dan DAN, WDC membangun kompetensi, kepercayaan diri, dan kepemimpinan bawah air melalui instruktur berpengalaman dan pembelajaran berkelanjutan.', 'As the official NAUI Indonesia Headquarters and an affiliated center of NAUI, TDI, and DAN, WDC builds competence, confidence, and leadership underwater through experienced professionals and continuous learning.')); ?></p>
        </div>
        <div class="wd-about-stat-grid">
          <div><strong>2008</strong><span><?php echo esc_html(contenly_tr('Didirikan di Jakarta', 'Founded in Jakarta')); ?></span></div>
          <div><strong>NAUI</strong><span><?php echo esc_html(contenly_tr('Kantor Pusat Indonesia', 'Indonesia Headquarters')); ?></span></div>
          <div><strong>TDI</strong><span><?php echo esc_html(contenly_tr('Pengembangan technical diving', 'Technical diving development')); ?></span></div>
          <div><strong>DAN</strong><span><?php echo esc_html(contenly_tr('Budaya keselamatan & emergency awareness', 'Safety culture & emergency awareness')); ?></span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- CREW -->
  <section class="wd-section white wd-crew-proof" id="crew"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Leadership Team', 'Leadership Team')); ?></span><h2 class="wd-title"><?php echo esc_html(contenly_tr('Profesional berpengalaman yang membangun ekosistem diving Indonesia.', 'Experienced professionals advancing Indonesia’s diving ecosystem.')); ?></h2><div class="wd-profile-grid">
    <article class="wd-profile-card"><img src="<?php echo esc_url($theme_uri); ?>/assets/wdc-about-ebram-pool.jpg" alt="Ebram Harimurti scuba training"><div><h3>Ebram Harimurti</h3><b>NAUI Course Director, NAUI Rep. Indonesia, TDI Instructor, DAN Instructor Trainer</b><p><?php echo esc_html(contenly_tr('Penyelam profesional Indonesia sejak 1998 dengan pengalaman lebih dari dua dekade di diving, marine tourism, dan underwater operations. Ketua Umum IDCA serta Ketua Indonesia Divers Rescue Team (IDRT), berfokus pada profesionalisme, keselamatan, search and rescue, dan konservasi laut.', 'Indonesian professional diver active since 1998 with more than two decades of experience in diving, marine tourism, and underwater operations. President of IDCA and Chairman of Indonesia Divers Rescue Team (IDRT), focused on professionalism, safety, search and rescue, and marine conservation.')); ?></p></div></article>
    <article class="wd-profile-card"><img src="<?php echo esc_url($theme_uri); ?>/assets/wdc-about-mimi-pool.jpg" alt="Mimi Amilia scuba training"><div><h3>Mimi Amilia</h3><b>NAUI Instructor, DAN Instructor, TDI Diver</b><p><?php echo esc_html(contenly_tr('Penyelam profesional sejak 2012 yang aktif dalam penyelaman rekreasi, edukasi, konservasi laut, dan pengembangan komunitas. Ketua Umum KP3I, mendorong partisipasi, kompetensi, dan kepemimpinan perempuan dalam industri penyelaman nasional.', 'Professional diver active since 2012 across recreational diving, education, marine conservation, and community development. President of KP3I, promoting women’s participation, competence, and leadership in Indonesia’s diving industry.')); ?></p></div></article>
    <article class="wd-profile-card"><img src="<?php echo esc_url($theme_uri); ?>/assets/wdc-about-jovan.jpg" alt="Jovan Lesmana NAUI Instructor"><div><h3>Jovan Lesmana</h3><b>NAUI Instructor</b><p><?php echo esc_html(contenly_tr('Penyelam profesional Indonesia sejak 2010 dengan pengalaman penyelaman rekreasi, eksplorasi bawah laut, dan operasional diving. Aktif mempromosikan keselamatan, etika penyelaman, dan pelestarian ekosistem laut untuk generasi penyelam berikutnya.', 'Indonesian professional diver active since 2010 with experience in recreational diving, underwater exploration, and diving operations. Actively promotes safety, diving ethics, and marine ecosystem protection for future divers.')); ?></p></div></article>
  </div></div></section>

  <!-- HOW WE WORK -->
  <section class="wd-section wdc-about-values">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Nilai Kerja', 'Working Values')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Safety, integrity, continuous learning.', 'Safety, integrity, continuous learning.')); ?></h2>
      <div class="wd-about-crew-grid">
        <div class="wd-crew-card"><div class="wd-crew-icon">01</div><h3><?php echo esc_html(contenly_tr('Keselamatan', 'Safety')); ?></h3><span><?php echo esc_html(contenly_tr('Setiap training, trip, dan rekomendasi dipandu oleh kesiapan diver, kondisi, dan standar konservatif.', 'Every training, trip, and recommendation is guided by diver readiness, conditions, and conservative standards.')); ?></span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">02</div><h3><?php echo esc_html(contenly_tr('Integritas', 'Integrity')); ?></h3><span><?php echo esc_html(contenly_tr('Progress diver dibangun dengan evaluasi jujur, bukan sertifikasi terburu-buru.', 'Diver progress is built through honest evaluation, not rushed certification.')); ?></span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">03</div><h3><?php echo esc_html(contenly_tr('Pembelajaran berkelanjutan', 'Continuous learning')); ?></h3><span><?php echo esc_html(contenly_tr('WDC mendukung diver untuk terus naik level melalui edukasi, praktik, dan leadership.', 'WDC supports divers to keep improving through education, practice, and leadership.')); ?></span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">04</div><h3><?php echo esc_html(contenly_tr('Konservasi laut', 'Marine conservation')); ?></h3><span><?php echo esc_html(contenly_tr('Kami mendorong perilaku bawah air yang bertanggung jawab dan menghormati ekosistem laut.', 'We promote responsible underwater behavior and respect for marine ecosystems.')); ?></span></div>
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
          <div class="wd-contact-card"><strong>Phone</strong><a href="tel:+622****9068">(021) 27939068</a></div>
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