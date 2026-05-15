<?php
/**
 * Template Name: Whale Dive Courses
 */

// Get all courses from CPT
$all_courses = get_posts([
    'post_type'   => 'wm_course',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
]);

// Get filter terms
$levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
$theme_uri = get_stylesheet_directory_uri();
function wdc_course_image_url($title, $theme_uri) {
    $key = strtolower($title);
    if (strpos($key, 'discover') !== false) return $theme_uri . '/assets/wdc-course-discover-scuba-pexels.jpg';
    if (strpos($key, 'nitrox') !== false || strpos($key, 'enriched air') !== false) return $theme_uri . '/assets/wdc-course-nitrox-real-v2.jpg';
    if (strpos($key, 'deep') !== false) return $theme_uri . '/assets/wdc-course-deep-diver-real-v2.jpg';
    if (strpos($key, 'photography') !== false) return $theme_uri . '/assets/wdc-course-underwater-photography-real-v2.jpg';
    if (strpos($key, 'advanced') !== false) return $theme_uri . '/assets/wdc-course-advanced-open-water-real.png';
    if (strpos($key, 'rescue') !== false) return $theme_uri . '/assets/wdc-course-rescue-diver-real.png';
    if (strpos($key, 'divemaster') !== false) return $theme_uri . '/assets/wdc-course-divemaster-real.png';
    if (strpos($key, 'instructor') !== false || strpos($key, 'idc') !== false) return $theme_uri . '/assets/wdc-course-instructor-course.png';
    return $theme_uri . '/assets/wdc-course-open-water-real.png';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-courses-quickwins">.wd-course-badge{display:inline-flex;align-items:center;min-height:28px;padding:0 10px;border-radius:999px;background:#fff;color:#06384d;font-size:11px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.wd-course-helper{margin:34px auto 0;max-width:820px;padding:24px;border-radius:28px;background:linear-gradient(135deg,rgba(255,255,255,.14),rgba(255,255,255,.08));border:1px solid rgba(255,255,255,.18);color:#fff}.wd-course-helper h3{margin:0 0 8px;font-size:24px}.wd-course-helper p{margin:0 0 16px;color:rgba(255,255,255,.76)}.whaledive-courses .wd-mini-link{background:#fff!important;color:#06384d!important;border-color:#fff!important;font-weight:900}.whaledive-courses .wd-mini-link:focus-visible,.whaledive-courses .wd-mini-btn:focus-visible{outline:3px solid #4CC8ED;outline-offset:3px}.wd-course-photo{width:100%;aspect-ratio:16/10;object-fit:cover;border-radius:22px;margin:0 0 18px;box-shadow:0 16px 38px rgba(0,0,0,.18)}</style></head>
<body <?php body_class('whaledive-inner whaledive-courses'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero wd-courses-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker">Dive training pathway</span><h1>Courses built for calm, capable divers.</h1><p>From your first pool session to professional leadership, Whale Dive Centre keeps training small, practical, and safety-first.</p><div class="wd-actions"><a class="wd-btn" href="/contact/">Request Course Plan</a><a class="wd-btn alt" href="#course-pathway">View Pathway</a></div></div><aside class="wd-inner-card"><b>Good fit if you want</b><ul><li>Clear certification pathway</li><li>Small-group coaching</li><li>Gear guidance before class</li><li>Community after certification</li></ul></aside></div></section>

  <section id="course-pathway" class="wd-section wd-dark wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">Course pathway</span>
      <h2 class="wd-title">Choose your next underwater milestone</h2>
      <p class="wd-sub"><?php echo count($all_courses); ?> courses available — from first-time experiences to professional leadership.</p>
      <div class="wd-filter-bar" id="courseFilters">
        <button class="wd-chip active" data-filter="all">All Courses</button>
        <?php if (!empty($levels) && !is_wp_error($levels)): foreach ($levels as $level): ?>
          <button class="wd-chip" data-filter="level-<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <div class="wd-course-grid wd-page-grid" id="courseGrid">
        <?php foreach ($all_courses as $i => $course):
          $price = get_post_meta($course->ID, '_wm_price', true);
          $duration = get_post_meta($course->ID, '_wm_duration', true);
          $prereqs = get_post_meta($course->ID, '_wm_prerequisites', true);
          $includes_text = get_post_meta($course->ID, '_wm_includes', true);
          $max_students = get_post_meta($course->ID, '_wm_max_students', true);
          $level_terms = wp_get_post_terms($course->ID, 'course_level', ['fields' => 'all']);
          $agency_terms = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
          $level_slug = !empty($level_terms) ? $level_terms[0]->slug : '';
          $level_name = !empty($level_terms) ? $level_terms[0]->name : '';
          $agency_name = !empty($agency_terms) ? $agency_terms[0] : '';
          $excerpt = $course->post_excerpt ?: wp_trim_words($course->post_content, 18, '…');
          $permalink = get_permalink($course->ID);
          $course_image_url = wdc_course_image_url($course->post_title, $theme_uri);
        ?>
        <article class="wd-course-card wd-detail-card wd-course-decision-card" data-level="level-<?php echo esc_attr($level_slug); ?>">
          <?php if($course_image_url): ?><img class="wd-course-photo" src="<?php echo esc_url($course_image_url); ?>" alt="<?php echo esc_attr($course->post_title); ?>" loading="lazy"><?php endif; ?>
          <div class="wd-course-visual" aria-hidden="true">
            <span class="wd-course-route">Training step</span>
            <strong><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></strong>
            <i><?php echo esc_html($level_name ?: 'Dive Course'); ?></i>
          </div>
          <div class="wd-course-card-head">
            <div class="wd-course-no"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
            <?php if ($agency_name): ?><span class="wd-course-agency"><?php echo esc_html($agency_name); ?></span><?php endif; ?><?php if ($i === 0): ?><span class="wd-course-badge">Start Here</span><?php elseif (stripos($course->post_title, "open water") !== false): ?><span class="wd-course-badge">Most Popular</span><?php endif; ?>
          </div>
          <h3><?php echo esc_html($course->post_title); ?></h3>
          <div class="wd-course-meta wd-course-chips">
            <?php if ($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
            <?php if ($duration): ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
            <?php if ($max_students): ?><span>Max <?php echo esc_html($max_students); ?> divers</span><?php endif; ?>
          </div>
          <?php if ($excerpt): ?><p><?php echo esc_html($excerpt); ?></p><?php endif; ?>
          <div class="wd-course-fit">
            <b>Best for</b>
            <span><?php echo esc_html($level_name ? $level_name . ' divers ready for the next step.' : 'Divers who want a structured, instructor-led pathway.'); ?></span>
          </div>
          <?php if ($price): ?>
          <div class="wd-course-price">
            <span class="wd-price-label">Mulai dari</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price, 0, ',', '.'); ?></span>
          </div>
          <?php endif; ?>
          <dl class="wd-course-quickfacts">
            <?php if ($prereqs): ?><dt>Prerequisite</dt><dd><?php echo esc_html($prereqs); ?></dd><?php endif; ?>
            <?php if ($includes_text): ?><dt>Includes</dt><dd><?php echo esc_html($includes_text); ?></dd><?php endif; ?>
          </dl>
          <div class="wd-course-actions"><a class="wd-mini-btn" href="/contact/">Request Plan</a><a class="wd-mini-link" href="<?php echo esc_url($permalink); ?>">Lihat Detail</a></div>
        </article>
        <?php endforeach; ?>
      </div>
      <div class="wd-course-helper"><h3>Not sure where to start?</h3><p>Tell us your comfort level, target dates, and previous experience. The crew will recommend the safest next course.</p><a class="wd-btn alt" href="/contact/">Ask the Crew</a></div>
    </div>
  </section>

  <section class="wd-section white wd-course-standards"><div class="wd-shell"><span class="wd-kicker">Training standards</span><h2 class="wd-title">Every course is planned around readiness, not pressure.</h2><div class="wd-course-trust-grid"><article><b>Prerequisites checked</b><span>The crew confirms certification, comfort level, and schedule before recommending a path.</span></article><article><b>Small-group pacing</b><span>More time for questions, repeated skills, and calm debriefs after each session.</span></article><article><b>Gear guidance included</b><span>Fit, comfort, and setup support are part of the course planning process.</span></article></div></div></section>

  <section class="wd-section white"><div class="wd-shell wd-split"><div><span class="wd-kicker">How it works</span><h2 class="wd-title">Simple steps to start diving.</h2><p class="wd-sub left">Pick a course, share your dates, and the crew handles the rest — from gear to certification.</p></div><div class="wd-steps"><div><b>01</b><h3>Tell us your level</h3><p>Share certification, schedule, group size, and comfort level.</p></div><div><b>02</b><h3>Get your course plan</h3><p>The crew recommends schedule, prerequisites, and gear notes.</p></div><div><b>03</b><h3>Confirm and train</h3><p>Once confirmed, the course appears in your member dashboard.</p></div></div></div></section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Ready when you are</span><h2 class="wd-title">Ask the crew for course availability.</h2><p class="wd-sub">Send your target certification, dates, and group size. Whale Dive Centre will help map the right path.</p><a class="wd-btn alt" href="/contact/">Request Course Plan</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body></html>

