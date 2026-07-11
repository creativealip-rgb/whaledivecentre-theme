<?php
/**
 * Single Course — Whale Dive Centre
 */
while (have_posts()) : the_post();
$price = get_post_meta(get_the_ID(), '_wm_price', true);
$duration = get_post_meta(get_the_ID(), '_wm_duration', true);
$max_students = get_post_meta(get_the_ID(), '_wm_max_students', true);
$prereqs = get_post_meta(get_the_ID(), '_wm_prerequisites', true);
$includes_text = get_post_meta(get_the_ID(), '_wm_includes', true);
$level = wp_get_post_terms(get_the_ID(), 'course_level');
$agency = wp_get_post_terms(get_the_ID(), 'course_agency');
$level_name = (!is_wp_error($level) && !empty($level)) ? $level[0]->name : '';
$agency_name = (!is_wp_error($agency) && !empty($agency)) ? $agency[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
$course_slug = get_post_field('post_name', get_the_ID());
$course_image_map = array(
  'open-water-scuba-diver' => 'wdc-course-open-water-real.webp',
  'open-water-diver' => 'wdc-course-open-water-real.webp',
  'advanced-open-water-diver' => 'wdc-course-advanced-open-water-real.webp',
  'advanced-open-water' => 'wdc-course-advanced-open-water-real.webp',
  'rescue-scuba-diver' => 'wdc-course-rescue-diver-real.webp',
  'rescue-diver' => 'wdc-course-rescue-diver-real.webp',
  'divemaster' => 'wdc-course-divemaster-real.webp',
  'instructor' => 'wdc-course-instructor-course-real.webp',
  'instructor-course' => 'wdc-course-instructor-course-real.webp',
  'enriched-air-nitrox' => 'wdc-course-nitrox.webp',
  'nitrox-diver' => 'wdc-course-nitrox.webp',
  'advanced-nitrox-diver' => 'wdc-course-adv-nitrox.webp',
  'deep-diver' => 'wdc-course-deep-diver-real-v2.jpg',
  'intro-to-tech' => 'wdc-course-intro-tech.webp',
  'decompression-procedures-diver' => 'wdc-course-decompression.webp',
  'trial-scuba' => 'wdc-course-discover-scuba.webp',
  'junior-scuba-diver' => 'wdc-course-open-water-real.webp',
);
$course_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$course_image) {
  $course_image_file = $course_image_map[$course_slug] ?? 'wdc-home-hero-diving-clean3.webp';
  $course_image = $theme_uri . '/assets/' . $course_image_file;
}
$course_excerpt = trim(wp_strip_all_tags(get_the_excerpt()));
$checkout_url = add_query_arg(
  array(
    'type' => 'course',
    'item_id' => get_the_ID(),
    'item' => get_the_title(),
    'price' => $price,
  ),
  home_url('/direct-checkout/')
);
endwhile;
rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-courses whaledive-single-course single single-wm_course'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-compact-hero wd-courses-hero wd-course-full-image-hero" style="--course-hero-image:url('<?php echo esc_url($course_image); ?>');background-image:linear-gradient(90deg,rgba(2,17,38,.9) 0%,rgba(2,17,38,.76) 36%,rgba(2,17,38,.42) 68%,rgba(2,17,38,.5) 100%),url('<?php echo esc_url($course_image); ?>')!important;background-size:cover!important;background-position:center 45%!important;">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb">
          <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(contenly_tr('Beranda', 'Home')); ?></a>
          <span>/</span>
          <a href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Kursus', 'Courses')); ?></a>
          <span>/</span>
          <?php the_title(); ?>
        </div>
        <?php if ($agency_name) : ?>
          <span class="wd-kicker"><?php echo esc_html($agency_name); ?> <?php echo esc_html(contenly_tr('Kursus', 'Course')); ?></span>
        <?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($course_excerpt) : ?><p><?php echo esc_html($course_excerpt); ?></p><?php endif; ?>
        <div class="wd-detail-meta">
          <?php if ($level_name) : ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
          <?php if ($duration) : ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
          <?php if ($price) : ?><span class="wd-agency-badge">Rp <?php echo number_format((float) $price, 0, ',', '.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="<?php echo esc_url($checkout_url); ?>"><?php echo esc_html(contenly_tr('Daftar Sekarang', 'Enroll Now')); ?></a>
          <a class="wd-btn alt" href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Semua Kursus', 'All Courses')); ?></a>
        </div>
      </div>
      <aside class="wd-course-hero-card">
        <span><?php echo esc_html(contenly_tr('Ringkasan kursus', 'Course snapshot')); ?></span>
        <b><?php echo esc_html($level_name ?: contenly_tr('Pelatihan selam', 'Dive training')); ?></b>
        <ul>
          <?php if ($duration) : ?><li><?php echo esc_html(contenly_tr('Durasi', 'Duration')); ?>: <?php echo esc_html($duration); ?></li><?php endif; ?>
          <?php if ($max_students) : ?><li><?php echo esc_html(contenly_tr('Grup kecil: maks', 'Small group: max')); ?> <?php echo esc_html($max_students); ?> <?php echo esc_html(contenly_tr('diver', 'divers')); ?></li><?php endif; ?>
          <?php if ($prereqs) : ?><li><?php echo esc_html(contenly_tr('Prasyarat', 'Prerequisite')); ?>: <?php echo esc_html($prereqs); ?></li><?php endif; ?>
        </ul>
      </aside>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while (have_posts()) : the_post(); ?>
          <?php
          $raw_content = get_the_content();
          $plain_content = trim(wp_strip_all_tags($raw_content));
          $is_duplicate = $plain_content !== '' && $course_excerpt !== '' && $plain_content === $course_excerpt;
          if ($plain_content !== '' && !$is_duplicate) :
            the_content();
          else :
          ?>
            <span class="wd-kicker"><?php echo esc_html(contenly_tr('Tentang kursus ini', 'About this course')); ?></span>
            <h2 class="wd-title"><?php the_title(); ?></h2>
            <p><?php echo esc_html(contenly_tr('Kursus ini dirancang untuk membangun skill dan kepercayaan diri di bawah air. Hubungi crew untuk jadwal dan ketersediaan detail.', 'This course is designed to build your skills and confidence underwater. Contact the crew for detailed scheduling and availability.')); ?></p>
          <?php endif; ?>
        <?php endwhile; ?>
        <div class="wd-course-outcomes">
          <article>
            <b><?php echo esc_html(contenly_tr('Yang kamu bangun', 'What you build')); ?></b>
            <span><?php echo esc_html(contenly_tr('Buoyancy lebih tenang, komunikasi buddy lebih jelas, kebiasaan planning lebih aman, dan kepercayaan diri di bawah air.', 'Calmer buoyancy, clearer buddy communication, safer planning habits, and more confidence underwater.')); ?></span>
          </article>
          <article>
            <b><?php echo esc_html(contenly_tr('Cara kami mengajar', 'How we teach')); ?></b>
            <span><?php echo esc_html(contenly_tr('Briefing, demo, latihan, feedback, dan debrief agar skill benar-benar dipahami.', 'Briefing, demo, practice, feedback, and debrief cycles designed for real understanding.')); ?></span>
          </article>
          <article>
            <b><?php echo esc_html(contenly_tr('Standar keselamatan', 'Safety standard')); ?></b>
            <span><?php echo esc_html(contenly_tr('Batas konservatif, cek peralatan, dan keputusan yang sadar kondisi selalu jadi pusat pelatihan.', 'Conservative limits, equipment checks, and condition-aware decisions stay central throughout the course.')); ?></span>
          </article>
        </div>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if ($price) : ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label"><?php echo esc_html(contenly_tr('Biaya kursus', 'Course fee')); ?></span>
            <span class="wd-price-amount">Rp <?php echo number_format((float) $price, 0, ',', '.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if ($includes_text) : ?>
          <h4><?php echo esc_html(contenly_tr('Sudah termasuk', "What's Included")); ?></h4>
          <p class="wd-sidebar-includes"><?php echo esc_html($includes_text); ?></p>
          <?php endif; ?>
          <a class="wd-btn" href="<?php echo esc_url($checkout_url); ?>" style="width:100%;text-align:center;margin-top:16px"><?php echo esc_html(contenly_tr('Daftar Sekarang', 'Enroll Now')); ?></a>
          <p class="wd-sidebar-note"><?php echo esc_html(contenly_tr('Atau', 'Or')); ?> <a href="<?php echo esc_url(home_url('/member-register/')); ?>"><?php echo esc_html(contenly_tr('buat akun', 'create an account')); ?></a> <?php echo esc_html(contenly_tr('untuk daftar dari dashboard.', 'to enroll from your dashboard.')); ?></p>
        </div>
      </aside>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Siap saat kamu siap', 'Ready when you are')); ?></span><h2><?php echo esc_html(contenly_tr('Tanya crew untuk jadwal kursus.', 'Ask the crew for course availability.')); ?></h2><p><?php echo esc_html(contenly_tr('Kirim sertifikasi target, tanggal, dan ukuran grup.', 'Send your target certification, dates, and group size.')); ?></p><a class="wd-btn alt" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html(contenly_tr('Cek Ketersediaan', 'Check Availability')); ?></a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker"><?php echo esc_html(contenly_tr('Siap dive?', 'Ready to dive?')); ?></span><h2>Whale Dive Centre</h2><p><?php echo esc_html(contenly_tr('Pelatihan selam, trip komunitas, dukungan peralatan, dan pengalaman peduli laut untuk petualangan bawah air yang lebih aman.', 'Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.')); ?></p><a class="wd-btn alt" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html(contenly_tr('Mulai Konsultasi', 'Start Inquiry')); ?></a></div><nav class="wd-footer-col"><h3><?php echo esc_html(contenly_tr('Jelajahi', 'Explore')); ?></h3><a href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Kursus Selam', 'Dive Courses')); ?></a><a href="<?php echo esc_url(home_url('/equipment/')); ?>"><?php echo esc_html(contenly_tr('Peralatan Selam', 'Scuba Equipment')); ?></a><a href="<?php echo esc_url(home_url('/conservation/')); ?>"><?php echo esc_html(contenly_tr('Konservasi', 'Conservation')); ?></a><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php echo esc_html(contenly_tr('Tentang Kami', 'About Us')); ?></a></nav><nav class="wd-footer-col"><h3><?php echo esc_html(contenly_tr('Kursus', 'Courses')); ?></h3><a href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Trial Scuba', 'Trial Scuba')); ?></a><a href="<?php echo esc_url(home_url('/courses/open-water-scuba-diver/')); ?>">Open Water Scuba Diver</a><a href="<?php echo esc_url(home_url('/courses/rescue-scuba-diver/')); ?>">Rescue Scuba Diver</a><a href="<?php echo esc_url(home_url('/courses/divemaster/')); ?>">Divemaster</a><a href="<?php echo esc_url(home_url('/courses/instructor/')); ?>"><?php echo esc_html(contenly_tr('Instruktur', 'Instructor')); ?></a><a href="<?php echo esc_url(home_url('/courses/intro-to-tech/')); ?>">Technical Diver</a><a href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Lihat Semua Kursus', 'View All Courses')); ?></a></nav><div class="wd-footer-col"><h3><?php echo esc_html(contenly_tr('Kontak', 'Contact')); ?></h3><p>Email: info@whaledivecentre.com</p><p><?php echo esc_html(contenly_tr('Telepon', 'Phone')); ?>: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id/" target="_blank" rel="noopener" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a><a href="https://www.facebook.com/whaledive.id/" target="_blank" rel="noopener" aria-label="Facebook"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a><a href="https://x.com/whaledivecentre" target="_blank" rel="noopener" aria-label="X (Twitter)"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. <?php echo esc_html(contenly_tr('Hak cipta dilindungi.', 'All rights reserved.')); ?></span><span>NAUI / TDI / DAN <?php echo esc_html(contenly_tr('jalur pelatihan', 'training pathways')); ?></span></div></div></footer>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?></body></html>
