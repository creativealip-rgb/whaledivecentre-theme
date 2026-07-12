<?php
/**
 * Single Course — Whale Dive Centre (admin data-driven)
 */
while (have_posts()) : the_post();
$course_id = get_the_ID();
$price = get_post_meta($course_id, '_wm_price', true);
$duration = get_post_meta($course_id, '_wm_duration', true);
$max_students = get_post_meta($course_id, '_wm_max_students', true);
$prereqs = get_post_meta($course_id, '_wm_prerequisites', true);
$includes_text = get_post_meta($course_id, '_wm_includes', true);
$cta_label = trim((string) get_post_meta($course_id, '_wdc_course_cta_label', true));
if ($cta_label === '') {
  $cta_label = contenly_tr('Daftar Kursus', 'Enroll Course');
}
$level = wp_get_post_terms($course_id, 'course_level');
$agency = wp_get_post_terms($course_id, 'course_agency');
$level_name = (!is_wp_error($level) && !empty($level)) ? $level[0]->name : '';
$agency_name = (!is_wp_error($agency) && !empty($agency)) ? $agency[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
$course_slug = get_post_field('post_name', $course_id);
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
$course_image = get_the_post_thumbnail_url($course_id, 'full');
if (!$course_image) {
  $course_image_file = $course_image_map[$course_slug] ?? 'wdc-home-hero-diving-clean3.webp';
  $course_image = $theme_uri . '/assets/' . $course_image_file;
}
$course_excerpt = trim(wp_strip_all_tags(get_the_excerpt()));
$action_url = wdc_member_action_url('course', $course_id, get_the_title());

$highlights = [];
for ($i = 1; $i <= 3; $i++) {
  $title = trim((string) get_post_meta($course_id, '_wdc_course_point_' . $i . '_title', true));
  $text = trim((string) get_post_meta($course_id, '_wdc_course_point_' . $i . '_text', true));
  if ($title !== '' || $text !== '') {
    $highlights[] = ['title' => $title, 'text' => $text];
  }
}
// Backward-compatible defaults only when none set in admin.
if (!$highlights) {
  $highlights = [
    [
      'title' => contenly_tr('Yang kamu bangun', 'What you build'),
      'text' => contenly_tr('Buoyancy lebih tenang, komunikasi buddy lebih jelas, kebiasaan planning lebih aman, dan kepercayaan diri di bawah air.', 'Calmer buoyancy, clearer buddy communication, safer planning habits, and more confidence underwater.'),
    ],
    [
      'title' => contenly_tr('Cara kami mengajar', 'How we teach'),
      'text' => contenly_tr('Briefing, demo, latihan, feedback, dan debrief agar skill benar-benar dipahami.', 'Briefing, demo, practice, feedback, and debrief cycles designed for real understanding.'),
    ],
    [
      'title' => contenly_tr('Standar keselamatan', 'Safety standard'),
      'text' => contenly_tr('Batas konservatif, cek peralatan, dan keputusan yang sadar kondisi selalu jadi pusat pelatihan.', 'Conservative limits, equipment checks, and condition-aware decisions stay central throughout the course.'),
    ],
  ];
}
endwhile;
rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-courses whaledive-single-course single single-wm_course'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-compact-hero wd-courses-hero wd-course-full-image-hero" style="--course-hero-image:url('<?php echo esc_url($course_image); ?>');background-image:linear-gradient(100deg,rgba(2,17,38,.96) 0%,rgba(2,17,38,.9) 40%,rgba(2,17,38,.72) 68%,rgba(2,17,38,.58) 100%),url('<?php echo esc_url($course_image); ?>')!important;background-size:cover!important;background-position:center 45%!important;">
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
          <?php if ($price !== '' && $price !== null) : ?><span class="wd-agency-badge">Rp <?php echo number_format((float) $price, 0, ',', '.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="<?php echo esc_url($action_url); ?>"><?php echo esc_html($cta_label); ?></a>
          <a class="wd-btn alt" href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Semua Kursus', 'All Courses')); ?></a>
        </div>
      </div>
      <aside class="wd-course-hero-card">
        <span><?php echo esc_html(contenly_tr('Ringkasan kursus', 'Course snapshot')); ?></span>
        <b><?php echo esc_html($level_name ?: contenly_tr('Pelatihan selam', 'Dive training')); ?></b>
        <ul>
          <?php if ($duration) : ?><li><?php echo esc_html(contenly_tr('Durasi', 'Duration')); ?>: <?php echo esc_html($duration); ?></li><?php endif; ?>
          <?php if ($max_students !== '' && $max_students !== null) : ?><li><?php echo esc_html(contenly_tr('Grup kecil: maks', 'Small group: max')); ?> <?php echo esc_html($max_students); ?> <?php echo esc_html(contenly_tr('diver', 'divers')); ?></li><?php endif; ?>
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
            <?php if ($course_excerpt) : ?>
              <p><?php echo esc_html($course_excerpt); ?></p>
            <?php else : ?>
              <p><?php echo esc_html(contenly_tr('Detail kursus akan ditambahkan admin. Hubungi crew untuk jadwal dan ketersediaan.', 'Course details will be added by admin. Contact the crew for schedule and availability.')); ?></p>
            <?php endif; ?>
          <?php endif; ?>
        <?php endwhile; ?>

        <?php if ($highlights) : ?>
        <div class="wd-course-outcomes">
          <?php foreach ($highlights as $point) : ?>
            <article>
              <?php if (!empty($point['title'])) : ?><b><?php echo esc_html($point['title']); ?></b><?php endif; ?>
              <?php if (!empty($point['text'])) : ?><span><?php echo esc_html($point['text']); ?></span><?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if ($price !== '' && $price !== null) : ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label"><?php echo esc_html(contenly_tr('Harga mulai', 'Starting price')); ?></span>
            <span class="wd-price-amount">Rp <?php echo number_format((float) $price, 0, ',', '.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if ($includes_text) : ?>
          <h4><?php echo esc_html(contenly_tr('Sudah termasuk', "What's Included")); ?></h4>
          <p class="wd-sidebar-includes"><?php echo esc_html($includes_text); ?></p>
          <?php endif; ?>
          <a class="wd-btn" href="<?php echo esc_url($action_url); ?>" style="width:100%;text-align:center;margin-top:16px"><?php echo esc_html($cta_label); ?></a>
          <p class="wd-sidebar-note"><?php echo esc_html(contenly_tr('Login member dulu untuk mengajukan daftar. Crew konfirmasi jadwal setelah request masuk.', 'Member login required to request enrollment. Crew confirms schedule after the request lands.')); ?></p>
        </div>
      </aside>
    </div>
  </section>

  <?php
    $wdc_sc_kicker = function_exists('wdc_site_get') ? wdc_site_tr('course_single_cta_kicker', 'Siap saat kamu siap', 'Ready when you are') : contenly_tr('Siap saat kamu siap', 'Ready when you are');
    $wdc_sc_title = function_exists('wdc_site_get') ? wdc_site_tr('course_single_cta_title', 'Daftar lewat akun member.', 'Enroll through your member account.') : contenly_tr('Daftar lewat akun member.', 'Enroll through your member account.');
    $wdc_sc_text = function_exists('wdc_site_get') ? wdc_site_tr('course_single_cta_text', 'Ajukan kursus dari dashboard. Crew follow-up setelah request masuk.', 'Request the course from your dashboard. Crew follows up after the request lands.') : contenly_tr('Ajukan kursus dari dashboard. Crew follow-up setelah request masuk.', 'Request the course from your dashboard. Crew follows up after the request lands.');
  ?>
  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html($wdc_sc_kicker); ?></span><h2><?php echo esc_html($wdc_sc_title); ?></h2><p><?php echo esc_html($wdc_sc_text); ?></p><a class="wd-btn alt" href="<?php echo esc_url($action_url); ?>"><?php echo esc_html($cta_label); ?></a></div></section>
  <?php contenly_render_public_footer(); ?>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?></body></html>
