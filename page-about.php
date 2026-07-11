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
<style id="wd-about-v2-extra">.whaledive-about .wd-sub{max-width:720px}.wd-contact-form small{display:block;margin-top:7px;color:#64748b;font-size:12px;line-height:1.45}.wd-form-privacy{margin:0;color:#64748b;font-size:13px;line-height:1.5}.wd-contact-notice{margin:18px 0 0;padding:14px 16px;border-radius:16px;font-weight:800;line-height:1.45}.wd-contact-notice.success{background:#e7f8ef;color:#05603a;border:1px solid rgba(5,96,58,.18)}.wd-contact-notice.error{background:#fff3e8;color:#9a3412;border:1px solid rgba(154,52,18,.18)}.wd-contact-hp{position:absolute;left:-9999px;opacity:0;pointer-events:none}.whaledive-about .wd-contact-grid{margin-top:30px!important}.wd-contact-card{display:flex;flex-direction:column;gap:8px}.wd-contact-card strong{color:#06384d;font-size:13px;letter-spacing:.08em;text-transform:uppercase}.wd-contact-card a{display:inline-flex;color:#0b617c;font-weight:800}.wdc-about-v2-hero{min-height:560px}
.wdc-about-v2-hero .wd-shell{position:relative;z-index:1}
.wdc-about-hero-copy{width:100%;max-width:100%;display:grid;gap:18px;align-content:center;min-height:420px}
.wdc-about-hero-copy .wd-kicker{width:max-content}
.wdc-about-hero-copy h1{max-width:100%!important;width:100%!important;font-size:clamp(36px,4.6vw,62px)!important;line-height:1.05!important;letter-spacing:-.035em!important;margin:0!important}
.wdc-about-hero-copy p{max-width:min(920px,100%)!important;width:100%!important;font-size:clamp(15px,1.35vw,19px)!important;line-height:1.7!important;color:rgba(255,255,255,.84)!important;margin:0!important}
.wdc-about-hero-copy .wd-actions{margin-top:8px;display:flex;flex-wrap:wrap;gap:12px}
@media(max-width:767.98px){
  .wdc-about-v2-hero{min-height:auto}
  .wdc-about-hero-copy{min-height:0;gap:14px}
  .wdc-about-hero-copy h1{font-size:clamp(30px,8.4vw,40px)!important}
  .wdc-about-hero-copy p{font-size:15px!important;max-width:100%!important}
  .wdc-about-hero-copy .wd-actions{display:grid!important;grid-template-columns:1fr!important}
  .wdc-about-hero-copy .wd-actions .wd-btn{width:100%!important}
}.wd-about-split{display:grid;grid-template-columns:1.2fr .8fr;gap:34px;align-items:start}.wd-about-split p{color:#587181;line-height:1.8;font-size:16px}.wd-about-stat-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.wd-about-stat-grid div{padding:22px;border-radius:26px;background:linear-gradient(180deg,#fff,#eef8fb);border:1px solid rgba(0,91,122,.1);box-shadow:0 16px 38px rgba(2,32,46,.07)}.wd-about-stat-grid strong{display:block;color:#06384d;font-size:30px;line-height:1;margin-bottom:10px}.wd-about-stat-grid span{color:#5b7180;line-height:1.5;font-weight:700}.wd-profile-grid{display:grid;grid-template-columns:1fr;gap:18px;margin-top:28px}.wd-profile-card{display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:center;padding:20px;border-radius:30px;background:linear-gradient(180deg,#fff,#eef8fb);border:1px solid rgba(0,91,122,.1);box-shadow:0 18px 44px rgba(2,32,46,.08)}.wd-profile-card img{width:220px;height:220px;aspect-ratio:1/1;object-fit:cover;border-radius:24px;background:#dff3f8}.wd-profile-card h3{margin:0 0 7px;color:#06384d;font-size:27px;line-height:1.15}.wd-profile-card b{display:block;margin-bottom:10px;color:#0b617c;line-height:1.4}.wd-profile-card p{margin:0;color:#587181;line-height:1.62}.wdc-about-values{background:#f5fbfd}@media(max-width:860px){.wd-about-split,.wd-profile-card{grid-template-columns:1fr}.wd-about-stat-grid{grid-template-columns:1fr}.wd-profile-card img{width:100%;height:auto;max-height:320px}}</style>

<style id="wd-about-polish-values-contact">.wdc-about-values{position:relative;background:radial-gradient(circle at 12% 0,rgba(76,200,237,.18),transparent 30%),linear-gradient(180deg,#f4fbff 0%,#eaf7fb 100%)}.wdc-about-values .wd-about-crew-grid{gap:20px;text-align:left!important}.wdc-about-values .wd-crew-card{position:relative;overflow:hidden;text-align:left!important;padding:26px 24px 24px!important;background:linear-gradient(180deg,#ffffff 0%,#f7fcff 100%)!important;border:1px solid rgba(76,200,237,.28)!important;box-shadow:0 18px 42px rgba(3,36,58,.09)!important;transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease}.wdc-about-values .wd-crew-card:hover{transform:translateY(-4px);box-shadow:0 24px 54px rgba(3,36,58,.14)!important;border-color:rgba(76,200,237,.48)!important}.wdc-about-values .wd-crew-card:before{content:"";position:absolute;inset:0 0 auto;height:4px;background:linear-gradient(90deg,#4CC8ED,#96DAEA,#004A98)}.wdc-about-values .wd-crew-icon{width:56px!important;height:56px!important;border-radius:999px!important;display:flex!important;align-items:center!important;justify-content:center!important;margin:0 0 20px!important;background:linear-gradient(135deg,#03172d,#0b617c)!important;box-shadow:0 12px 28px rgba(0,74,152,.22)!important;font-size:15px!important;line-height:1!important;letter-spacing:.01em!important}.wdc-about-values .wd-crew-card h3{margin-bottom:10px!important;color:#06384d!important}.wdc-about-values .wd-crew-card span{color:#516b7a!important;line-height:1.62!important}.whaledive-about #contact-form{background:linear-gradient(180deg,#ffffff 0%,#f3fbff 100%)}.whaledive-about .wd-contact-grid{align-items:stretch;gap:26px!important}.whaledive-about .wd-contact-cards{display:flex;flex-direction:column;gap:0;padding:26px!important;border-radius:30px!important;background:linear-gradient(180deg,#ffffff,#f7fcff)!important;border:1px solid rgba(76,200,237,.24)!important;box-shadow:0 18px 44px rgba(3,36,58,.09)!important}.whaledive-about .wd-contact-card{position:relative;padding:0 0 18px 42px!important;border-radius:0!important;background:transparent!important;border:0!important;box-shadow:none!important;min-height:0}.whaledive-about .wd-contact-card:not(:last-child){margin-bottom:18px!important;border-bottom:1px solid rgba(6,56,77,.08)!important}.whaledive-about .wd-contact-card:before{content:"";position:absolute;left:0;top:2px;width:22px;height:22px;border-radius:999px;background:linear-gradient(135deg,#4CC8ED,#0b617c);box-shadow:0 8px 18px rgba(76,200,237,.22)}.whaledive-about .wd-contact-card strong{font-size:12px!important;color:#06384d!important}.whaledive-about .wd-contact-card span,.whaledive-about .wd-contact-card a{color:#526b7a!important;line-height:1.55!important}.whaledive-about .wd-contact-card a{font-weight:900!important;color:#0b617c!important}.whaledive-about .wd-map-link{display:inline-flex!important;align-items:center!important;justify-content:center!important;width:max-content!important;margin-top:8px!important;padding:10px 14px!important;border-radius:999px!important;background:#e9f8fc!important;color:#064a63!important;border:1px solid rgba(76,200,237,.32)!important}.whaledive-about .wd-contact-form{padding:28px!important;border-radius:30px!important;background:#fff!important;border:1px solid rgba(0,91,122,.12)!important;box-shadow:0 22px 56px rgba(3,36,58,.12)!important}.whaledive-about .wd-contact-form:before{content:"Kirim pesan ke crew";display:block;margin:0 0 18px;color:#06384d;font-size:20px;font-weight:900;letter-spacing:-.02em}.whaledive-about .wd-contact-form label{gap:7px!important}.whaledive-about .wd-contact-form input,.whaledive-about .wd-contact-form select,.whaledive-about .wd-contact-form textarea{border-radius:16px!important;border:1px solid rgba(0,91,122,.16)!important;background:#f8fcff!important}.whaledive-about .wd-contact-form input:focus,.whaledive-about .wd-contact-form select:focus,.whaledive-about .wd-contact-form textarea:focus{background:#fff!important;border-color:#4CC8ED!important;box-shadow:0 0 0 4px rgba(76,200,237,.14)!important;outline:none!important}@media(max-width:860px){.whaledive-about .wd-contact-card{padding-left:54px!important}.whaledive-about .wd-contact-form{padding:22px!important}}</style>
</head>
<body <?php body_class('whaledive-inner whaledive-about'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <!-- HERO -->
  <section class="wd-inner-hero wd-about-hero wdc-about-v2-hero">
    <div class="wd-shell">
      <div class="wdc-about-hero-copy">
        <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_kicker', contenly_tr('Tentang Whale Dive Centre', 'About Whale Dive Centre')) : contenly_tr('Tentang Whale Dive Centre', 'About Whale Dive Centre')); ?></span>
        <h1><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_title', contenly_tr('Kantor Pusat NAUI Indonesia untuk pelatihan selam yang aman, profesional, dan berkelas dunia.', 'NAUI Indonesia Headquarters for safe, professional, world-class dive training.')) : contenly_tr('Kantor Pusat NAUI Indonesia untuk pelatihan selam yang aman, profesional, dan berkelas dunia.', 'NAUI Indonesia Headquarters for safe, professional, world-class dive training.')); ?></h1>
        <p><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_text', contenly_tr('Didirikan pada 2008 di Jakarta, WDC berfokus pada pendidikan penyelam, keselamatan, eksplorasi bawah laut, dan pengembangan profesional diving Indonesia.', 'Founded in 2008 in Jakarta, WDC focuses on diver education, safety, underwater exploration, and professional development for Indonesia’s diving community.')) : contenly_tr('Didirikan pada 2008 di Jakarta, WDC berfokus pada pendidikan penyelam, keselamatan, eksplorasi bawah laut, dan pengembangan profesional diving Indonesia.', 'Founded in 2008 in Jakarta, WDC focuses on diver education, safety, underwater exploration, and professional development for Indonesia’s diving community.')); ?></p>
        <div class="wd-actions"><a class="wd-btn" href="<?php echo esc_url(function_exists('wdc_site_url') ? wdc_site_url(wdc_site_get('about_cta1_url', '#crew')) : '#crew'); ?>"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_cta1_label', contenly_tr('Kenali Tim', 'Meet the Team')) : contenly_tr('Kenali Tim', 'Meet the Team')); ?></a><a class="wd-btn alt" href="<?php echo esc_url(function_exists('wdc_site_url') ? wdc_site_url(wdc_site_get('about_cta2_url', '/courses/')) : home_url('/courses/')); ?>"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_cta2_label', contenly_tr('Lihat Kursus', 'View Courses')) : contenly_tr('Lihat Kursus', 'View Courses')); ?></a></div>
      </div>
    </div>
  </section>

  <!-- ABOUT WDC -->
  <section class="wd-section white wdc-about-v2-intro">
    <div class="wd-shell">
      <div class="wd-about-split">
        <div>
                    <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_intro_kicker', contenly_tr('Sejak 2008', 'Since 2008')) : contenly_tr('Sejak 2008', 'Since 2008')); ?></span>
          <h2 class="wd-title"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_intro_title', contenly_tr('Standar internasional. Kepemimpinan lokal. Budaya keselamatan.', 'International standards. Local leadership. Safety culture.')) : contenly_tr('Standar internasional. Kepemimpinan lokal. Budaya keselamatan.', 'International standards. Local leadership. Safety culture.')); ?></h2>
          <p><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_intro_p1', contenly_tr('Whale Dive Centre (WDC) adalah salah satu institusi penyelaman terkemuka di Indonesia yang berkantor pusat di Jakarta. WDC menghadirkan pelatihan rekreasional, profesional, dan teknis dengan standar internasional.', 'Whale Dive Centre (WDC) is one of Indonesia’s leading diving institutions headquartered in Jakarta. WDC delivers recreational, professional, and technical dive training with internationally recognized standards.')) : contenly_tr('Whale Dive Centre (WDC) adalah salah satu institusi penyelaman terkemuka di Indonesia yang berkantor pusat di Jakarta. WDC menghadirkan pelatihan rekreasional, profesional, dan teknis dengan standar internasional.', 'Whale Dive Centre (WDC) is one of Indonesia’s leading diving institutions headquartered in Jakarta. WDC delivers recreational, professional, and technical dive training with internationally recognized standards.')); ?></p>
          <p><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('about_intro_p2', contenly_tr('Sebagai Kantor Pusat NAUI Indonesia serta pusat yang berafiliasi dengan NAUI, TDI, dan DAN, WDC membangun kompetensi, kepercayaan diri, dan kepemimpinan bawah air melalui instruktur berpengalaman dan pembelajaran berkelanjutan.', 'As the official NAUI Indonesia Headquarters and an affiliated center of NAUI, TDI, and DAN, WDC builds competence, confidence, and leadership underwater through experienced professionals and continuous learning.')) : contenly_tr('Sebagai Kantor Pusat NAUI Indonesia serta pusat yang berafiliasi dengan NAUI, TDI, dan DAN, WDC membangun kompetensi, kepercayaan diri, dan kepemimpinan bawah air melalui instruktur berpengalaman dan pembelajaran berkelanjutan.', 'As the official NAUI Indonesia Headquarters and an affiliated center of NAUI, TDI, and DAN, WDC builds competence, confidence, and leadership underwater through experienced professionals and continuous learning.')); ?></p>
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
  <?php
  $wdc_crew_kicker = function_exists('wdc_site_get') ? wdc_site_get('crew_kicker', contenly_tr('Leadership Team', 'Leadership Team')) : contenly_tr('Leadership Team', 'Leadership Team');
  $wdc_crew_title = function_exists('wdc_site_get') ? wdc_site_get('crew_title', contenly_tr('Profesional berpengalaman yang membangun ekosistem diving Indonesia.', 'Experienced professionals advancing Indonesia’s diving ecosystem.')) : contenly_tr('Profesional berpengalaman yang membangun ekosistem diving Indonesia.', 'Experienced professionals advancing Indonesia’s diving ecosystem.');
  $wdc_crew = function_exists('wdc_get_crew_profiles') ? wdc_get_crew_profiles() : [];
  ?>
  <section class="wd-section white wd-crew-proof" id="crew"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html($wdc_crew_kicker); ?></span><h2 class="wd-title"><?php echo esc_html($wdc_crew_title); ?></h2><div class="wd-profile-grid">
    <?php foreach ($wdc_crew as $member) :
      $img = $member['image'] ?? '';
      if (!$img) { continue; }
    ?>
    <article class="wd-profile-card"><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($member['alt'] ?? $member['name'] ?? 'Crew'); ?>"><div><h3><?php echo esc_html($member['name'] ?? ''); ?></h3><b><?php echo esc_html($member['role'] ?? ''); ?></b><p><?php echo esc_html($member['bio'] ?? ''); ?></p></div></article>
    <?php endforeach; ?>
  </div></div></section>

  <!-- HOW WE WORK -->
  <?php
  $wdc_values = [];
  for ($i = 1; $i <= 4; $i++) {
    $title = function_exists('wdc_site_get') ? wdc_site_get('value_' . $i . '_title') : '';
    $text = function_exists('wdc_site_get') ? wdc_site_get('value_' . $i . '_text') : '';
    if ($title === '' && $text === '') {
      continue;
    }
    $wdc_values[] = [
      'n' => str_pad((string) $i, 2, '0', STR_PAD_LEFT),
      'title' => $title,
      'text' => $text,
    ];
  }
  if (!$wdc_values) {
    $wdc_values = [
      ['n' => '01', 'title' => contenly_tr('Keselamatan', 'Safety'), 'text' => contenly_tr('Setiap training, trip, dan rekomendasi dipandu oleh kesiapan diver, kondisi, dan standar konservatif.', 'Every training, trip, and recommendation is guided by diver readiness, conditions, and conservative standards.')],
      ['n' => '02', 'title' => contenly_tr('Integritas', 'Integrity'), 'text' => contenly_tr('Progress diver dibangun dengan evaluasi jujur, bukan sertifikasi terburu-buru.', 'Diver progress is built through honest evaluation, not rushed certification.')],
      ['n' => '03', 'title' => contenly_tr('Pembelajaran berkelanjutan', 'Continuous learning'), 'text' => contenly_tr('WDC mendukung diver untuk terus naik level melalui edukasi, praktik, dan leadership.', 'WDC supports divers to keep improving through education, practice, and leadership.')],
      ['n' => '04', 'title' => contenly_tr('Konservasi laut', 'Marine conservation'), 'text' => contenly_tr('Kami mendorong perilaku bawah air yang bertanggung jawab dan menghormati ekosistem laut.', 'We promote responsible underwater behavior and respect for marine ecosystems.')],
    ];
  }
  ?>
  <section class="wd-section wdc-about-values">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('values_kicker', contenly_tr('Nilai Kerja', 'Working Values')) : contenly_tr('Nilai Kerja', 'Working Values')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(function_exists('wdc_site_get') ? wdc_site_get('values_title', contenly_tr('Safety, integrity, continuous learning.', 'Safety, integrity, continuous learning.')) : contenly_tr('Safety, integrity, continuous learning.', 'Safety, integrity, continuous learning.')); ?></h2>
      <div class="wd-about-crew-grid">
        <?php foreach ($wdc_values as $val) : ?>
        <div class="wd-crew-card"><div class="wd-crew-icon"><?php echo esc_html($val['n']); ?></div><h3><?php echo esc_html($val['title']); ?></h3><span><?php echo esc_html($val['text']); ?></span></div>
        <?php endforeach; ?>
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
<div class="wd-contact-card"><strong>Email</strong><a href="mailto:<?php echo esc_attr($wdc_email); ?>"><?php echo esc_html($wdc_email); ?></a></div>
          <div class="wd-contact-card"><strong>Phone</strong><a href="tel:<?php echo esc_attr($wdc_phone_tel ?: preg_replace('/\D+/', '', $wdc_phone)); ?>"><?php echo esc_html($wdc_phone); ?></a></div>
          <div class="wd-contact-card"><strong><?php echo contenly_tr('Jam Operasional', 'Business Hours'); ?></strong><span><?php echo esc_html($wdc_hours_note ?: contenly_tr('Senin - Sabtu, 09:00 - 18:00 WIB. Jadwal kursus dan perjalanan dikonfirmasi berdasarkan perjanjian.', 'Monday - Saturday, 09:00 - 18:00 WIB. Course and trip schedules are confirmed by appointment.')); ?></span></div>
          <div class="wd-contact-card"><strong><?php echo contenly_tr('Lokasi', 'Location'); ?></strong><span><?php echo esc_html($wdc_address); ?></span><a class="wd-map-link" href="<?php echo esc_url($wdc_map); ?>" target="_blank" rel="noopener"><?php echo contenly_tr('Buka di Google Maps', 'Open in Google Maps'); ?></a></div>
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
    <?php contenly_render_public_footer(); ?>
</main>
<?php wp_footer(); ?></body></html>
