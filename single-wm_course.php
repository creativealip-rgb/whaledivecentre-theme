<?php
/**
 * Single Course — Whale Dive Centre
 */
while(have_posts()): the_post();
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
$course_image_map = array(
  'open-water-diver' => 'wdc-course-open-water-real.webp',
  'advanced-open-water' => 'wdc-course-advanced-open-water-real.webp',
  'rescue-diver' => 'wdc-course-rescue-diver-real.webp',
  'divemaster' => 'wdc-course-divemaster-real.webp',
  'instructor-course' => 'wdc-course-instructor-course-real.webp',
);
$course_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$course_image) {
  $course_image_file = $course_image_map[get_post_field('post_name', get_the_ID())] ?? 'wdc-home-hero-diving-clean3.webp';
  $course_image = $theme_uri . '/assets/' . $course_image_file;
}
endwhile; rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-courses whaledive-single-course'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>WHALE DIVE CENTRE</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-compact-hero wd-courses-hero wd-course-full-image-hero" style="--course-hero-image:url('<?php echo esc_url($course_image); ?>');background-image:linear-gradient(90deg,rgba(2,17,38,.9) 0%,rgba(2,17,38,.76) 36%,rgba(2,17,38,.42) 68%,rgba(2,17,38,.5) 100%),url('<?php echo esc_url($course_image); ?>')!important;background-size:cover!important;background-position:center 45%!important;">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/">Home</a> <span>/</span> <a href="/courses/">Courses</a> <span>/</span> <?php the_title(); ?></div>
        <?php if($agency_name): ?><span class="wd-kicker"><?php echo esc_html($agency_name); ?> Course</span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="wd-detail-meta">
          <?php if($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
          <?php if($duration): ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
          <?php if($price): ?><span class="wd-agency-badge">Rp <?php echo number_format((float)$price,0,',','.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="<?php echo esc_url(add_query_arg(['type' => 'course', 'item_id' => get_the_ID(), 'item' => get_the_title(), 'price' => $price], '/direct-checkout/')); ?>">Enroll Now</a>
          <a class="wd-btn alt" href="/courses/">All Courses</a>
        </div>
      </div>
      <aside class="wd-course-hero-card">
        <span>Course snapshot</span>
        <b><?php echo esc_html($level_name ?: 'Dive training'); ?></b>
        <ul>
          <?php if($duration): ?><li>Duration: <?php echo esc_html($duration); ?></li><?php endif; ?>
          <?php if($max_students): ?><li>Small group: max <?php echo esc_html($max_students); ?> divers</li><?php endif; ?>
          <?php if($prereqs): ?><li>Prerequisite: <?php echo esc_html($prereqs); ?></li><?php endif; ?>
        </ul>
      </aside>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): ?>
          <?php the_content(); ?>
        <?php else: ?>
          <span class="wd-kicker">About this course</span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <p>This course is designed to build your skills and confidence underwater. Contact the crew for detailed scheduling and availability.</p>
        <?php endif; ?>
        <?php endwhile; ?>
        <div class="wd-course-outcomes">
          <article><b>What you build</b><span>Calmer buoyancy, clearer buddy communication, safer planning habits, and more confidence underwater.</span></article>
          <article><b>How we teach</b><span>Briefing, demo, practice, feedback, and debrief cycles designed for real understanding.</span></article>
          <article><b>Safety standard</b><span>Conservative limits, equipment checks, and condition-aware decisions stay central throughout the course.</span></article>
        </div>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if($price): ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label">Course fee</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if($includes_text): ?>
          <h4>What&rsquo;s Included</h4>
          <p class="wd-sidebar-includes"><?php echo esc_html($includes_text); ?></p>
          <?php endif; ?>
          <a class="wd-btn" href="<?php echo esc_url(add_query_arg(['type' => 'course', 'item_id' => get_the_ID(), 'item' => get_the_title(), 'price' => $price], '/direct-checkout/')); ?>" style="width:100%;text-align:center;margin-top:16px">Enroll Now</a>
          <p class="wd-sidebar-note">Or <a href="/member-register/">create an account</a> to enroll from your dashboard.</p>
        </div>
      </aside>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker">Ready when you are</span><h2>Ask the crew for course availability.</h2><p>Send your target certification, dates, and group size.</p><a class="wd-btn alt" href="/contact/">Check Availability</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?></body></html>